<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New District') }}
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
        .vertex-label {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
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
                        <form method="POST" action="{{ route('kecamatan.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">District Name</label>
                                <input type="text" name="NamaKecamatan" value="{{ old('NamaKecamatan') }}" required 
                                    class="w-full border rounded px-3 py-2">
                                @error('NamaKecamatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Region</label>
                                <select name="Wilayah" required class="w-full border rounded px-3 py-2">
                                    <option value="">Select region...</option>
                                    <option value="Surabaya Barat" {{ old('Wilayah') == 'Surabaya Barat' ? 'selected' : '' }}>Surabaya Barat</option>
                                    <option value="Surabaya Timur" {{ old('Wilayah') == 'Surabaya Timur' ? 'selected' : '' }}>Surabaya Timur</option>
                                    <option value="Surabaya Utara" {{ old('Wilayah') == 'Surabaya Utara' ? 'selected' : '' }}>Surabaya Utara</option>
                                    <option value="Surabaya Selatan" {{ old('Wilayah') == 'Surabaya Selatan' ? 'selected' : '' }}>Surabaya Selatan</option>
                                    <option value="Surabaya Tengah" {{ old('Wilayah') == 'Surabaya Tengah' ? 'selected' : '' }}>Surabaya Tengah</option>
                                </select>
                                @error('Wilayah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Polygon Coordinates</label>
                                <textarea name="PolygonJSON" id="polygonJSON" rows="5" required 
                                    class="w-full border rounded px-3 py-2 font-mono text-xs bg-gray-50" readonly>{{ old('PolygonJSON') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Click on map to draw polygon</p>
                                @error('PolygonJSON')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 p-3 bg-blue-50 rounded border border-blue-200">
                                <p class="text-sm font-medium text-blue-800 mb-1">Drawing Instructions:</p>
                                <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                                    <li><strong>Click on map</strong> to add vertices (minimum 3 points)</li>
                                    <li><strong>Click vertex</strong> to enable drag mode, then drag to adjust</li>
                                    <li><strong>Right-click vertex</strong> to delete it (min 3 vertices required)</li>
                                    <li><strong>Double-click dragging marker</strong> to finish adjustment</li>
                                    <li>Polygon auto-closes when you click near the first point</li>
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
                                <a href="{{ route('kecamatan.index') }}" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
                                <button type="button" onclick="clearPolygon()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Clear</button>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create District</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Map Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Draw District Boundary</h3>
                        <div id="map" class="w-full h-[600px] rounded border"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let map, polygon, vertices = [];
                const CLOSE_THRESHOLD = 0.0005;
                
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

                // Handle map clicks
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    if (vertices.length >= 3) {
                        const firstVertex = vertices[0];
                        const distance = Math.sqrt(
                            Math.pow(lat - firstVertex[0], 2) + 
                            Math.pow(lng - firstVertex[1], 2)
                        );

                        if (distance < CLOSE_THRESHOLD) {
                            alert('Polygon closed! You can now adjust vertices or create the district.');
                            return;
                        }
                    }

                    vertices.push([lat, lng]);
                    saveToHistory();
                    updatePolygon();
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

                function updatePolygon() {
                    if (polygon) {
                        map.removeLayer(polygon);
                    }

                    map.eachLayer(function(layer) {
                        if (layer instanceof L.CircleMarker || (layer instanceof L.Marker && layer.options.draggable)) {
                            map.removeLayer(layer);
                        }
                    });

                    if (vertices.length > 0) {
                        polygon = L.polygon(vertices, {
                            color: 'blue',
                            weight: 2,
                            fillColor: 'lightblue',
                            fillOpacity: 0.3
                        }).addTo(map);

                        vertices.forEach((vertex, index) => {
                            const marker = L.circleMarker(vertex, {
                                radius: 8,
                                color: 'red',
                                fillColor: 'red',
                                fillOpacity: 1,
                                weight: 2
                            }).addTo(map);

                            const label = `V${index + 1}`;
                            marker.bindTooltip(label, {
                                permanent: true,
                                direction: 'top',
                                className: 'vertex-label',
                                offset: [0, -10]
                            });

                            marker.on('contextmenu', function(e) {
                                L.DomEvent.preventDefault(e);
                                if (vertices.length <= 3) {
                                    alert('Cannot delete - polygon needs at least 3 vertices');
                                    return;
                                }
                                if (confirm(`Delete vertex ${index + 1}?`)) {
                                    vertices.splice(index, 1);
                                    saveToHistory();
                                    updatePolygon();
                                    updateJSON();
                                }
                            });

                            marker.bindPopup(`<b>Vertex ${index + 1}</b><br>Right-click to delete<br>Click to make draggable`);

                            marker.on('click', function() {
                                enableMarkerDrag(marker, index);
                            });
                        });

                        if (vertices.length > 2) {
                            map.fitBounds(polygon.getBounds());
                        }
                    }
                }

                function enableMarkerDrag(marker, index) {
                    const vertex = vertices[index];
                    map.removeLayer(marker);
                    
                    const dragMarker = L.marker(vertex, {
                        draggable: true,
                        icon: L.divIcon({
                            className: 'drag-marker',
                            html: `<div style="background: orange; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        })
                    }).addTo(map);

                    dragMarker.bindTooltip(`Dragging V${index + 1}`, {
                        permanent: true,
                        direction: 'top'
                    });

                    dragMarker.on('dragend', function(e) {
                        const newPos = e.target.getLatLng();
                        vertices[index] = [newPos.lat, newPos.lng];
                        saveToHistory();
                        updatePolygon();
                        updateJSON();
                    });

                    dragMarker.on('dblclick', function() {
                        updatePolygon();
                    });
                }

                function updateJSON() {
                    if (vertices.length > 0) {
                        const coordinates = vertices.map(vertex => [vertex[1], vertex[0]]);
                        document.getElementById('polygonJSON').value = JSON.stringify(coordinates);
                    } else {
                        document.getElementById('polygonJSON').value = '';
                    }
                }

                function clearPolygon() {
                    vertices = [];
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

                function saveToHistory() {
                    if (historyIndex < history.length - 1) {
                        history = history.slice(0, historyIndex + 1);
                    }
                    
                    const state = JSON.parse(JSON.stringify(vertices));
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
                        vertices = JSON.parse(JSON.stringify(history[historyIndex]));
                        updatePolygon();
                        updateJSON();
                        updateUndoRedoButtons();
                    }
                }

                function redo() {
                    if (historyIndex < history.length - 1) {
                        historyIndex++;
                        vertices = JSON.parse(JSON.stringify(history[historyIndex]));
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

                window.clearPolygon = clearPolygon;
                window.undo = undo;
                window.redo = redo;
                
                saveToHistory();
            }, 100);
        });
    </script>
</x-app-layout>
