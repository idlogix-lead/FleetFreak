@extends('layouts.app')
@section('wrapper')

<style>
    #map {
        height: 500px;
        position: relative;
        z-index: 0;
    }
    /* Loading spinner */
    #loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="pb-4">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">
                                <svg width="26" height="26" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="..." />
                                </svg>
                                {{ __('Find Vehicle Location') }}
                            </h3>

                            <div style="display: flex; align-items: center;">
                                <div class="form-group mb-0 mr-2">
                                    <label for="vehicle_id">Select Vehicle</label>
                                    <select id="vehicle_id" name="vehicle_id" class="form-control">
                                        <option value="">All Vehicles</option>
                                        @foreach($activeVehicles as $id => $vehicle_no)
                                            <option value="{{ $id }}" {{ $id == $vehicleId ? 'selected' : '' }}>
                                                {{ $vehicle_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="map"></div>
                    <div id="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <script>
                        // Initialize map
                        var map = L.map('map').setView([0, 0], 2);

                        // Add OpenStreetMap tiles
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);
                        let fromIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                            shadowSize: [41, 41],
                        });

                        let toIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                            shadowSize: [41, 41],
                        });

                        // Initialize routing control
                        var routingControl = L.Routing.control({
                            waypoints: [],
                            routeWhileDragging: false,
                            show: false,
                            createMarker: () => null,
                            draggableWaypoints: false,
                        }).addTo(map);

                        // Show or hide loading indicator
                        function toggleLoading(state) {
                            document.getElementById('loading').style.display = state ? 'block' : 'none';
                        }
                        let intervalId;
                        let startMarker; // Reference for start marker
                        let endMarker; // Reference for end marker
                        // Fetch route history for a specific vehicle
                        function fetchRouteData(vehicleId) {
                            toggleLoading(true); // Show loading spinner

                            ffsQuiet($.ajax(
                            {
                            url: `/vehicle/route-history`,
                            type: 'GET',
                            data: {
                                vehicle_id: vehicleId
                            },
                            success: function(data) {
                                toggleLoading(false); // Hide loading spinner

                                // Sort route data by 'created_at' to ensure chronological order
                                if (data.length > 0) {
                                    data.sort(function(a, b) {
                                        return new Date(a.created_at) - new Date(b.created_at);
                                    });

                                    // Map sorted data to latLng
                                    var routeData = data.map(function(point) {
                                        return L.latLng(point.latitude, point.longitude);
                                    });

                                    // Set waypoints for the route control
                                    routingControl.setWaypoints(routeData);
                                    // routingControl.createMarker=null;
                                    map.fitBounds(routeData); // Adjust map view to fit the route

                                    // Remove existing markers (if needed)
                                    if (startMarker) map.removeLayer(startMarker);
                                    if (endMarker) map.removeLayer(endMarker);

                                    // Add markers at the start and end points
                                    startMarker = L.marker(routeData[0], { title: "Start Point", icon: fromIcon, draggable: false }).addTo(map);
                                    endMarker = L.marker(routeData[routeData.length - 1], { title: "End Point", icon: toIcon, draggable: false }).addTo(map);
                                    map.createPane('dotsPane');
                                    map.getPane('dotsPane').style.zIndex = 650;
                                    for (let i = 1; i < routeData.length - 1; i++) { // Skip start and end points
                                        L.circle(routeData[i], {
                                            radius: 5, // Small radius
                                            color: 'black',
                                            fillColor: 'black',
                                            fillOpacity: 1,
                                            pane: 'dotsPane',
                                        }).bindTooltip(`Point ${i}`, { // Set the tooltip text
                                        permanent: false, // Tooltip only shows on hover
                                        direction: 'top' // Position the tooltip above the dot
                                    }).addTo(map);
                                    }
                                } else {
                                    routingControl.setWaypoints([]);
                                    alert("No route data available for this vehicle.");
                                }
                            },
                            error: function(xhr, status, error) {
                                toggleLoading(false); // Hide loading spinner
                                console.error("Error fetching route data:", error);
                                alert("Failed to fetch route data.");
                            }
                        }));
                        }

                        // Handle vehicle selection
                        document.getElementById('vehicle_id').addEventListener('change', function () {
                            var vehicleId = this.value;
                            if (intervalId) {
                                clearInterval(intervalId);
                            }
                            if (vehicleId) {
                                console.log(vehicleId);
                                fetchRouteData(vehicleId);
                                // Set interval to refresh data every 30 seconds (adjust as needed)
                                intervalId = setInterval(() => fetchRouteData(vehicleId), 10000);
                            } else {
                                routingControl.setWaypoints([]);
                                map.setView([0, 0], 2); // Reset map view if "All Vehicles" is selected
                            }
                        });

                        // Auto-fetch data if a vehicle is preselected
                        @if($vehicleId)
                            fetchRouteData({{ $vehicleId }});
                            intervalId = setInterval(() => fetchRouteData({{ $vehicleId }}), 10000);
                        @endif
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
