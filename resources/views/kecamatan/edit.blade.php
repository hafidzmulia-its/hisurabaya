<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kecamatan') }}
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
            border: none;
            font-weight: bold;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
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
                        <form method="POST" action="{{ route('kecamatan.update', $kecamatan) }}" id="kecamatanForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Kecamatan Name</label>
                                <input type="text" name="NamaKecamatan" value="{{ old('NamaKecamatan', $kecamatan->NamaKecamatan) }}" required 
                                    class="w-full border rounded px-3 py-2">
                                @error('NamaKecamatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Region</label>
                                <select name="Wilayah" required class="w-full border rounded px-3 py-2">
                                    <option value="">Select region...</option>
                                    <option value="Surabaya Barat" {{ old('Wilayah', $kecamatan->Wilayah) == 'Surabaya Barat' ? 'selected' : '' }}>Surabaya Barat</option>
                                    <option value="Surabaya Timur" {{ old('Wilayah', $kecamatan->Wilayah) == 'Surabaya Timur' ? 'selected' : '' }}>Surabaya Timur</option>
                                    <option value="Surabaya Utara" {{ old('Wilayah', $kecamatan->Wilayah) == 'Surabaya Utara' ? 'selected' : '' }}>Surabaya Utara</option>
                                    <option value="Surabaya Selatan" {{ old('Wilayah', $kecamatan->Wilayah) == 'Surabaya Selatan' ? 'selected' : '' }}>Surabaya Selatan</option>
                                    <option value="Surabaya Tengah" {{ old('Wilayah', $kecamatan->Wilayah) == 'Surabaya Tengah' ? 'selected' : '' }}>Surabaya Tengah</option>
                                </select>
                                @error('Wilayah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Polygon Coordinates</label>
                                <textarea name="PolygonJSON" id="polygonJSON" rows="3" 
                                    class="w-full border rounded px-3 py-2 font-mono text-xs bg-gray-50" 
                                    readonly>{{ old('PolygonJSON', json_encode($kecamatan->PolygonJSON)) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Click on map to redraw polygon boundary</p>
                                @error('PolygonJSON')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 p-3 bg-blue-50 rounded border border-blue-200">
                                <p class="text-sm font-medium text-blue-800 mb-1">Drawing Instructions:</p>
                                <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                                    <li>Click on map to add polygon vertices</li>
                                    <li><strong>Click vertex</strong> to make it draggable (orange circle)</li>
                                    <li><strong>Right-click vertex</strong> to delete it (min 3 points required)</li>
                                    <li><strong>Double-click</strong> dragging marker to finish adjustment</li>
                                    <li>Click near the first point to auto-close the polygon</li>
                                    <li>Click "Clear" to start over</li>
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
                                <a href="{{ route('kecamatan.index') }}" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
                                <button type="button" onclick="clearPolygon()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Clear</button>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Kecamatan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Map Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Edit Polygon Boundary</h3>
                        <div id="map" class="w-full h-[600px] rounded border"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let map, polygon, polygonPoints = [];
                const CLOSE_THRESHOLD = 0.0005;
                
                // Undo/Redo system
                let history = [];
                let historyIndex = -1;
                const MAX_HISTORY = 50;

                // Initialize map
                map = L.map('map').setView([-7.2575, 112.7521], 13);
                setTimeout(() => map.invalidateSize(), 100);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Load existing polygon
        @if($kecamatan->PolygonJSON)
            try {
                let existingCoords = {!! json_encode($kecamatan->PolygonJSON) !!};
                if (typeof existingCoords === 'string') {
                    existingCoords = JSON.parse(existingCoords);
                }
                if (Array.isArray(existingCoords) && existingCoords.length > 0) {
                    polygonPoints = existingCoords.map(coord => [coord[1], coord[0]]);
                    saveToHistory(); // Save initial state
                    updatePolygon();
                    if (polygon) {
                        map.fitBounds(polygon.getBounds());
                    }
                }
            } catch(e) {
                console.error('Failed to load polygon:', e);
            }
        @endif

        // Handle map clicks
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Check if we should close the polygon
            if (polygonPoints.length >= 3) {
                const firstPoint = polygonPoints[0];
                const distance = Math.sqrt(
                    Math.pow(lat - firstPoint[0], 2) + 
                    Math.pow(lng - firstPoint[1], 2)
                );

                if (distance < CLOSE_THRESHOLD) {
                    polygonPoints.push([...firstPoint]);
                    updatePolygon();
                    updateJSON();
                    return;
                }
            }

            polygonPoints.push([lat, lng]);
            saveToHistory();
            updatePolygon();
            updateJSON();
        });

        // Keyboard shortcuts for undo/redo
        document.addEventListener('keydown', function(e) {
            // Ctrl+Z or Cmd+Z for undo
            if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                undo();
            }
            // Ctrl+Shift+Z or Cmd+Shift+Z for redo
            else if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'z') {
                e.preventDefault();
                redo();
            }
        });

        function updatePolygon() {
            // Remove existing polygon and markers
            if (polygon) {
                map.removeLayer(polygon);
            }
            map.eachLayer(function(layer) {
                if (layer instanceof L.CircleMarker || (layer instanceof L.Marker && layer.options.draggable)) {
                    map.removeLayer(layer);
                }
            });

            if (polygonPoints.length > 0) {
                // Draw polygon
                polygon = L.polygon(polygonPoints, {
                    color: 'blue',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.2
                }).addTo(map);

                // Add markers for vertices with labels
                polygonPoints.forEach((point, index) => {
                    // Skip the closing point if polygon is closed
                    if (index === polygonPoints.length - 1 && polygonPoints.length > 3) {
                        const firstPoint = polygonPoints[0];
                        if (point[0] === firstPoint[0] && point[1] === firstPoint[1]) {
                            return; // Skip duplicate closing point
                        }
                    }

                    const isFirst = index === 0;
                    const markerColor = isFirst ? 'green' : 'orange';
                    
                    const marker = L.circleMarker(point, {
                        radius: 8,
                        color: markerColor,
                        fillColor: markerColor,
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(map);

                    // Add label
                    const label = isFirst ? 'START' : `Vertex ${index}`;
                    marker.bindTooltip(label, {
                        permanent: true,
                        direction: 'top',
                        className: 'waypoint-label',
                        offset: [0, -10]
                    });

                    // Right-click to delete (min 3 points)
                    if (polygonPoints.length > 3) {
                        marker.on('contextmenu', function(e) {
                            L.DomEvent.preventDefault(e);
                            if (confirm(`Delete Vertex ${index}?`)) {
                                polygonPoints.splice(index, 1);
                                updatePolygon();
                                updateJSON();
                            }
                        });
                        marker.bindPopup(`<b>${label}</b><br>Right-click to delete<br>Click to make draggable`);
                    } else {
                        marker.bindPopup(`<b>${label}</b><br>Need at least 3 vertices`);
                    }

                    // Click to enable dragging
                    marker.on('click', function() {
                        enableVertexDrag(marker, index);
                    });
                });
            }
        }

        function enableVertexDrag(marker, index) {
            // Create a new draggable marker at the same position
            const point = polygonPoints[index];
            
            // Remove the old marker
            map.removeLayer(marker);
            
            // Create draggable marker
            const dragMarker = L.marker(point, {
                draggable: true,
                icon: L.divIcon({
                    className: 'drag-marker',
                    html: `<div style="background: orange; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                    iconSize: [16, 16],
                    iconAnchor: [8, 8]
                })
            }).addTo(map);

            dragMarker.bindTooltip(`Dragging Vertex ${index}`, {
                permanent: true,
                direction: 'top'
            });

            dragMarker.on('dragend', function(e) {
                const newPos = e.target.getLatLng();
                polygonPoints[index] = [newPos.lat, newPos.lng];
                saveToHistory();
                updatePolygon();
                updateJSON();
            });

            // Double-click to finish dragging
            dragMarker.on('dblclick', function() {
                updatePolygon();
            });
        }

        function updateJSON() {
            const coordinates = polygonPoints.map(point => [point[1], point[0]]);
            document.getElementById('polygonJSON').value = JSON.stringify(coordinates);
        }

        function clearPolygon() {
            polygonPoints = [];
            saveToHistory();
            if (polygon) {
                map.removeLayer(polygon);
                polygon = null;
            }
            map.eachLayer(function(layer) {
                if (layer instanceof L.CircleMarker || (layer instanceof L.Marker && layer.options.draggable)) {
                    map.removeLayer(layer);
                }
            });
                document.getElementById('polygonJSON').value = '';
            }

            // Undo/Redo functions
            function saveToHistory() {
                // Remove any future history if we're not at the end
                if (historyIndex < history.length - 1) {
                    history = history.slice(0, historyIndex + 1);
                }
                
                // Deep copy the current state
                const state = JSON.parse(JSON.stringify(polygonPoints));
                history.push(state);
                
                // Limit history size
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
                    polygonPoints = JSON.parse(JSON.stringify(history[historyIndex]));
                    updatePolygon();
                    updateJSON();
                    updateUndoRedoButtons();
                }
            }

            function redo() {
                if (historyIndex < history.length - 1) {
                    historyIndex++;
                    polygonPoints = JSON.parse(JSON.stringify(history[historyIndex]));
                    updatePolygon();
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

            // Make functions global
            window.clearPolygon = clearPolygon;
            window.undo = undo;
            window.redo = redo;
            }, 100);
        });
    </script>
</x-app-layout>
