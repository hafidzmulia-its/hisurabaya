<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Route') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            width: 100%;
            height: 600px;
            position: relative;
            z-index: 0;
        }
        .waypoint-label {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        .hotel-label {
            background: rgba(255, 255, 255, 0.95);
            color: #1e40af;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #3b82f6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .drag-marker {
            cursor: move;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6">
                <!-- Form Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('jalan.update', $jalan) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Route Name</label>
                                <input type="text" name="NamaJalan" value="{{ old('NamaJalan', $jalan->NamaJalan) }}" required 
                                    class="w-full border rounded px-3 py-2">
                                @error('NamaJalan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Start Point</label>
                                    <select name="StartPointID" id="startPoint" required class="w-full border rounded px-3 py-2">
                                        <option value="">Select hotel...</option>
                                        @php
                                            $currentStart = $hotels->firstWhere('PointID', old('StartPointID', $jalan->StartPointID));
                                            $otherHotels = $hotels->where('PointID', '!=', old('StartPointID', $jalan->StartPointID));
                                        @endphp
                                        @if($currentStart)
                                            <option value="{{ $currentStart->PointID }}"
                                                data-lat="{{ $currentStart->Latitude }}" 
                                                data-lng="{{ $currentStart->Longitude }}" selected>
                                                ⭐ {{ $currentStart->NamaObjek }} (Current)
                                            </option>
                                            <option disabled>──────────</option>
                                        @endif
                                        @foreach($otherHotels as $hotel)
                                            <option value="{{ $hotel->PointID }}"
                                                data-lat="{{ $hotel->Latitude }}" 
                                                data-lng="{{ $hotel->Longitude }}">
                                                {{ $hotel->NamaObjek }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('StartPointID')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">End Point</label>
                                    <select name="EndPointID" id="endPoint" required class="w-full border rounded px-3 py-2">
                                        <option value="">Select hotel...</option>
                                        @php
                                            $currentEnd = $hotels->firstWhere('PointID', old('EndPointID', $jalan->EndPointID));
                                            $otherHotelsEnd = $hotels->where('PointID', '!=', old('EndPointID', $jalan->EndPointID));
                                        @endphp
                                        @if($currentEnd)
                                            <option value="{{ $currentEnd->PointID }}"
                                                data-lat="{{ $currentEnd->Latitude }}" 
                                                data-lng="{{ $currentEnd->Longitude }}" selected>
                                                ⭐ {{ $currentEnd->NamaObjek }} (Current)
                                            </option>
                                            <option disabled>──────────</option>
                                        @endif
                                        @foreach($otherHotelsEnd as $hotel)
                                            <option value="{{ $hotel->PointID }}"
                                                data-lat="{{ $hotel->Latitude }}" 
                                                data-lng="{{ $hotel->Longitude }}">
                                                {{ $hotel->NamaObjek }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('EndPointID')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Distance (KM)</label>
                                <input type="number" step="0.01" name="DistanceKM" id="distanceKM" value="{{ old('DistanceKM', $jalan->DistanceKM) }}" required 
                                    class="w-full border rounded px-3 py-2" readonly>
                                <p class="text-xs text-gray-500 mt-1">Auto-calculated from route</p>
                                @error('DistanceKM')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Route Coordinates</label>
                                <textarea name="KoordinatJSON" id="koordinatJSON" rows="3" required 
                                    class="w-full border rounded px-3 py-2 font-mono text-xs bg-gray-50" readonly>{{ old('KoordinatJSON', json_encode($jalan->KoordinatJSON)) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Click on map to edit route</p>
                                @error('KoordinatJSON')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 p-3 bg-blue-50 rounded border border-blue-200">
                                <p class="text-sm font-medium text-blue-800 mb-1">Editing Instructions:</p>
                                <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                                    <li><strong>Click blue line</strong> to insert a waypoint at that position</li>
                                    <li><strong>Click waypoint</strong> to enable drag mode, then drag to adjust</li>
                                    <li><strong>Right-click waypoint</strong> to delete it (any point, including END)</li>
                                    <li><strong>Double-click dragging marker</strong> to finish adjustment</li>
                                    <li>Hotel names are shown directly on map for easy reference</li>
                                    <li>Use <strong>Ctrl+Z</strong> to undo, <strong>Ctrl+Shift+Z</strong> to redo</li>
                                </ul>
                            </div>

                            <div class="flex justify-between items-center gap-2 mb-4">
                                <div class="flex gap-2">
                                    <button type="button" onclick="undo()" id="undoBtn" class="bg-gray-400 text-white px-3 py-2 rounded hover:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled title="Undo (Ctrl+Z)">
                                        ↶ Undo
                                    </button>
                                    <button type="button" onclick="redo()" id="redoBtn" class="bg-gray-400 text-white px-3 py-2 rounded hover:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled title="Redo (Ctrl+Shift+Z)">
                                        ↷ Redo
                                    </button>
                                </div>
                                <div class="text-xs text-gray-500">
                                    Ctrl+Z: Undo | Ctrl+Shift+Z: Redo
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                <a href="{{ route('jalan.index') }}" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
                                <button type="button" onclick="clearRoute()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Clear</button>
                                <button type="button" onclick="finishRoute()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Finish Route</button>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Route</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Map Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Edit Route on Map</h3>
                        <div id="map" class="w-full h-[600px] rounded border"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let map, polyline, routePoints = [], hotelMarkers = {};
                let isDrawing = false;
                
                // Undo/Redo system
                let history = [];
                let historyIndex = -1;
                const MAX_HISTORY = 50;

                // Initialize map
                map = L.map('map').setView([-7.2575, 112.7521], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                setTimeout(() => map.invalidateSize(), 100);

                // Add hotel markers with permanent labels
                const hotels = @json($hotels);
                hotels.forEach(hotel => {
                    if (hotel.Latitude && hotel.Longitude) {
                        const marker = L.marker([hotel.Latitude, hotel.Longitude])
                            .addTo(map);
                        
                        marker.bindTooltip(hotel.NamaObjek, {
                            permanent: true,
                            direction: 'top',
                            className: 'hotel-label',
                            offset: [0, -35]
                        });
                        
                        marker.bindPopup(`<b>${hotel.NamaObjek}</b><br>Click to view details`);
                        hotelMarkers[hotel.PointID] = marker;
                    }
                });

                // Load existing route
                @if($jalan->KoordinatJSON)
                    try {
                        let existingCoords = {!! json_encode($jalan->KoordinatJSON) !!};
                        if (typeof existingCoords === 'string') {
                            existingCoords = JSON.parse(existingCoords);
                        }
                        if (Array.isArray(existingCoords) && existingCoords.length > 0) {
                            routePoints = existingCoords.map(coord => [coord[1], coord[0]]);
                            saveToHistory();
                            updatePolyline();
                            if (polyline) {
                                map.fitBounds(polyline.getBounds());
                            }
                        }
                    } catch(e) {
                        console.error('Failed to load route:', e);
                    }
                @endif

                // Handle start/end point selection
                document.getElementById('startPoint').addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    if (option.value) {
                        const lat = parseFloat(option.dataset.lat);
                        const lng = parseFloat(option.dataset.lng);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            routePoints = [[lat, lng]];
                            isDrawing = true;
                            saveToHistory();
                            updatePolyline();
                            updateJSON();
                            map.setView([lat, lng], 14);
                        }
                    }
                });

                document.getElementById('endPoint').addEventListener('change', updateJSON);

                // Handle map clicks for waypoints
                map.on('click', function(e) {
                    if (!isDrawing && routePoints.length === 0) {
                        alert('Please select start point first');
                        return;
                    }

                    isDrawing = true;
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    routePoints.push([lat, lng]);
                    saveToHistory();
                    updatePolyline();
                    updateJSON();
                });

                // Keyboard shortcuts for undo/redo
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                        e.preventDefault();
                        undo();
                    }
                    else if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'z') {
                        e.preventDefault();
                        redo();
                    }
                });

                function updatePolyline() {
                    if (polyline) {
                        map.removeLayer(polyline);
                    }

                    map.eachLayer(function(layer) {
                        if (layer instanceof L.CircleMarker || (layer instanceof L.Marker && layer.options.draggable)) {
                            map.removeLayer(layer);
                        }
                    });

                    if (routePoints.length > 0) {
                        polyline = L.polyline(routePoints, {
                            color: 'blue',
                            weight: 4,
                            opacity: 0.7
                        }).addTo(map);

                        polyline.on('click', function(e) {
                            const clickedPoint = [e.latlng.lat, e.latlng.lng];
                            let minDistance = Infinity;
                            let insertIndex = 1;
                            
                            for (let i = 0; i < routePoints.length - 1; i++) {
                                const distance = getDistanceToSegment(
                                    clickedPoint,
                                    routePoints[i],
                                    routePoints[i + 1]
                                );
                                if (distance < minDistance) {
                                    minDistance = distance;
                                    insertIndex = i + 1;
                                }
                            }
                            
                            routePoints.splice(insertIndex, 0, clickedPoint);
                            saveToHistory();
                            updatePolyline();
                            updateJSON();
                        });

                        routePoints.forEach((point, index) => {
                            const isStart = index === 0;
                            const isEnd = index === routePoints.length - 1;
                            const markerColor = isStart ? 'green' : (isEnd ? 'red' : 'orange');
                            
                            const marker = L.circleMarker(point, {
                                radius: 8,
                                color: markerColor,
                                fillColor: markerColor,
                                fillOpacity: 1,
                                draggable: false,
                                weight: 2
                            }).addTo(map);

                            const label = isStart ? 'START' : (isEnd ? 'END' : `Point ${index}`);
                            marker.bindTooltip(label, {
                                permanent: true,
                                direction: 'top',
                                className: 'waypoint-label',
                                offset: [0, -10]
                            });

                            marker.on('contextmenu', function(e) {
                                L.DomEvent.preventDefault(e);
                                if (routePoints.length <= 2) {
                                    alert('Cannot delete - route needs at least 2 points');
                                    return;
                                }
                                if (confirm(`Delete ${label}?${isEnd ? ' (Point ' + (index-1) + ' will become new END)' : ''}`)) {
                                    routePoints.splice(index, 1);
                                    saveToHistory();
                                    updatePolyline();
                                    updateJSON();
                                }
                            });
                            
                            const popupText = isStart ? `<b>${label}</b><br>Right-click to delete<br>(Need min 2 points)` :
                                            isEnd ? `<b>${label}</b><br>Right-click to delete<br>Point ${index-1} becomes new END` :
                                            `<b>Point ${index}</b><br>Right-click to delete<br>Click to make draggable`;
                            marker.bindPopup(popupText);

                            marker.on('click', function() {
                                enableMarkerDrag(marker, index);
                            });
                        });
                    }
                }

                function enableMarkerDrag(marker, index) {
                    const point = routePoints[index];
                    map.removeLayer(marker);
                    
                    const dragMarker = L.marker(point, {
                        draggable: true,
                        icon: L.divIcon({
                            className: 'drag-marker',
                            html: `<div style="background: orange; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        })
                    }).addTo(map);

                    dragMarker.bindTooltip(`Dragging Point ${index}`, {
                        permanent: true,
                        direction: 'top'
                    });

                    dragMarker.on('dragend', function(e) {
                        const newPos = e.target.getLatLng();
                        routePoints[index] = [newPos.lat, newPos.lng];
                        saveToHistory();
                        updatePolyline();
                        updateJSON();
                    });

                    dragMarker.on('dblclick', function() {
                        updatePolyline();
                    });
                }

                function updateJSON() {
                    const coordinates = routePoints.map(point => [point[1], point[0]]);
                    document.getElementById('koordinatJSON').value = JSON.stringify(coordinates);

                    if (routePoints.length > 1) {
                        let distance = 0;
                        for (let i = 1; i < routePoints.length; i++) {
                            distance += calculateDistance(
                                routePoints[i-1][0], routePoints[i-1][1],
                                routePoints[i][0], routePoints[i][1]
                            );
                        }
                        document.getElementById('distanceKM').value = distance.toFixed(2);
                    }
                }

                function calculateDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371;
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                              Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    return R * c;
                }

                function getDistanceToSegment(point, segmentStart, segmentEnd) {
                    const [px, py] = point;
                    const [x1, y1] = segmentStart;
                    const [x2, y2] = segmentEnd;
                    
                    const dx = x2 - x1;
                    const dy = y2 - y1;
                    const lengthSquared = dx * dx + dy * dy;
                    
                    if (lengthSquared === 0) {
                        return Math.sqrt((px - x1) ** 2 + (py - y1) ** 2);
                    }
                    
                    let t = ((px - x1) * dx + (py - y1) * dy) / lengthSquared;
                    t = Math.max(0, Math.min(1, t));
                    
                    const projX = x1 + t * dx;
                    const projY = y1 + t * dy;
                    
                    return Math.sqrt((px - projX) ** 2 + (py - projY) ** 2);
                }

                function finishRoute() {
                    const endSelect = document.getElementById('endPoint');
                    const option = endSelect.options[endSelect.selectedIndex];
                    
                    if (!option.value) {
                        alert('Please select end point');
                        return;
                    }

                    const lat = parseFloat(option.dataset.lat);
                    const lng = parseFloat(option.dataset.lng);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        routePoints.push([lat, lng]);
                        saveToHistory();
                        updatePolyline();
                        updateJSON();
                        isDrawing = false;
                        alert('Route completed! You can now update the route.');
                    }
                }

                function clearRoute() {
                    routePoints = [];
                    isDrawing = false;
                    saveToHistory();
                    if (polyline) {
                        map.removeLayer(polyline);
                        polyline = null;
                    }
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.CircleMarker || (layer instanceof L.Marker && layer.options.draggable)) {
                            map.removeLayer(layer);
                        }
                    });
                    document.getElementById('koordinatJSON').value = '';
                    document.getElementById('distanceKM').value = '';
                }

                function saveToHistory() {
                    if (historyIndex < history.length - 1) {
                        history = history.slice(0, historyIndex + 1);
                    }
                    
                    const state = JSON.parse(JSON.stringify(routePoints));
                    history.push(state);
                    
                    if (history.length > MAX_HISTORY) {
                        history.shift();
                    } else {
                        historyIndex++;
                    }
                    
                    updateUndoRedoButtons();
                }

                function undo() {
                    if (historyIndex > 0) {
                        historyIndex--;
                        routePoints = JSON.parse(JSON.stringify(history[historyIndex]));
                        updatePolyline();
                        updateJSON();
                        updateUndoRedoButtons();
                    }
                }

                function redo() {
                    if (historyIndex < history.length - 1) {
                        historyIndex++;
                        routePoints = JSON.parse(JSON.stringify(history[historyIndex]));
                        updatePolyline();
                        updateJSON();
                        updateUndoRedoButtons();
                    }
                }

                function updateUndoRedoButtons() {
                    const undoBtn = document.getElementById('undoBtn');
                    const redoBtn = document.getElementById('redoBtn');
                    
                    if (undoBtn) {
                        undoBtn.disabled = historyIndex <= 0;
                    }
                    if (redoBtn) {
                        redoBtn.disabled = historyIndex >= history.length - 1;
                    }
                }

                window.clearRoute = clearRoute;
                window.finishRoute = finishRoute;
                window.undo = undo;
                window.redo = redo;
            }, 100);
        });
    </script>
</x-app-layout>
