<!DOCTYPE html>
<html>
<head>
    <title>Leaflet Geocoder with Route Planning</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        body { display: flex; margin: 0; }
        #sidebar {
            /* width: 300px; */
            padding: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        #map {  
            /* margin-left: 30%; */
            width: 100%; /* Ensure it takes full width */
            height: 500px;}
        .btn-maps { width: 15%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; border-radius: 5px; }
        #drawRoute { width: 10%; padding: 10px; background-color: #007bff; color: #fff; border: none; cursor: pointer; border-radius: 5px; }
        /* button:hover { background-color: #0056b3; } */
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <h3>Route Planner</h3>
        <label for="fromLocation">From Location:</label>
        <input type="text" class="btn-maps" id="fromLocation" placeholder="Search From Location">
        
        <label for="toLocation">To Location:</label>
        <input type="text" class="btn-maps" id="toLocation" placeholder="Search To Location">
        <label for="fromcoord">From Coord:</label>
        <input type="text" class="btn-maps" id="fromLatLng" value="">
        <label for="tocoord">To Coord:</label>
        <input type="text" class="btn-maps" id="toLatLng" value="">
        
        <div id="drawRoute" class="btn-maps draw-route">Draw Route</div>
        {{-- <button id="livelocation">Live</button> --}}

    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
    async function fetchSuggestions(query) {
        let response = await fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&addressdetails=1&limit=5`);
        console.log(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&addressdetails=1&limit=5`);
        if (response.ok) {
            return await response.json();
        } else {
            throw new Error('Error fetching suggestions');
        }
    }

    function addSuggestions(inputElement, dropdownId) {
    let dropdown = document.createElement('div');
    dropdown.id = dropdownId;
    dropdown.style.position = 'absolute';
    dropdown.style.zIndex = 1050; // Ensure it appears above other modal elements
    dropdown.style.background = '#fff';
    dropdown.style.border = '1px solid #ccc';
    dropdown.style.maxHeight = '150px';
    dropdown.style.overflowY = 'auto';

    // Append dropdown to the modal
    inputElement.closest('.modal').appendChild(dropdown);

    inputElement.addEventListener('input', async () => {
        let query = inputElement.value.trim();
        if (query.length > 2) {
            try {
                let results = await fetchSuggestions(query);
                dropdown.innerHTML = '';
                results.forEach(result => {
                    let item = document.createElement('div');
                    item.style.padding = '8px';
                    item.style.cursor = 'pointer';
                    item.textContent = result.display_name;

                    item.addEventListener('click', () => {
                        inputElement.value = result.display_name;
                        dropdown.innerHTML = '';
                        let coords = { lat: parseFloat(result.lat), lng: parseFloat(result.lon) };
                        if (inputElement.id === 'fromLocation') {
                            fromMarker.setLatLng(coords);
                            document.getElementById('fromLatLng').value = result.lat + ", " + result.lon;         
                            updateRoute();
                        } else if (inputElement.id === 'toLocation') {
                            toMarker.setLatLng(coords);
                            document.getElementById('toLatLng').value=result.lat+", "+result.lon; 
                            updateRoute();
                        }
                    });

                    dropdown.appendChild(item);
                });

                let rect = inputElement.getBoundingClientRect();
                let modalRect = inputElement.closest('.modal').getBoundingClientRect();

                dropdown.style.left = `${rect.left - modalRect.left}px`;
                dropdown.style.top = `${rect.bottom - modalRect.top}px`;
                dropdown.style.width = `${rect.width}px`;
            } catch (error) {
                console.error('Error fetching suggestions:', error);
            }
        } else {
            dropdown.innerHTML = '';
        }
    });

    document.addEventListener('click', (event) => {
        if (!dropdown.contains(event.target) && event.target !== inputElement) {
            dropdown.innerHTML = '';
        }
    });
}


    // Attach suggestion dropdowns to input fields
    addSuggestions(document.getElementById('fromLocation'), 'fromSuggestions');
    addSuggestions(document.getElementById('toLocation'), 'toSuggestions');


        // Custom icons
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
    async function mapinitialize(){
       // Initialize map
        let map = L.map('map').setView([31.5204, 74.3587], 6);
        // Create the OpenStreetMap tile layer
        let osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        });

        // Create the Stamen Toner tile layer
        let tonerLayer = L.tileLayer('https://tiles.stadiamaps.com/tiles/stamen_toner_background/{z}/{x}/{y}{r}.png?api_key=87bfb7ed-6d2e-498b-b400-013c60f8e593');
        let terrainLayer = L.tileLayer('https://tiles.stadiamaps.com/tiles/stamen_terrain/{z}/{x}/{y}{r}.png?api_key=87bfb7ed-6d2e-498b-b400-013c60f8e593');
        let smoothDark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png?api_key=87bfb7ed-6d2e-498b-b400-013c60f8e593');
        let sateliteView = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}.jpg?api_key=87bfb7ed-6d2e-498b-b400-013c60f8e593');




        // Add the OpenStreetMap tile layer by default
        osmLayer.addTo(map);

        // Initialize Routing Control
        let routingControl = L.Routing.control({
            waypoints: [],
            routeWhileDragging: true,
            showAlternatives: true,
            draggableWaypoints: false,
            addWaypoints: false,
            // router: L.Routing.osrmv1({
            //     serviceUrl: 'https://router.project-osrm.org/route/v1'
            // }),
            createMarker: () => null, // Suppress default markers
        }).addTo(map);

        // Add the layer control to the map
        L.control.layers({
            'OpenStreetMap': osmLayer,
            'Satelite View': sateliteView,
            'Smooth Dark': smoothDark,
            'Stamen Toner': tonerLayer,
            'Terrain Layer': terrainLayer,
            
            
        }).addTo(map);
        // Initialize markers
  // Initialize markers with default positions
        let fromMarker = L.marker([31.5204, 74.3587], { icon: fromIcon, draggable: true }).addTo(map);
        let toMarker = L.marker([32.0836, 72.6748], { icon: toIcon, draggable: true }).addTo(map);

        // Function to update marker positions to current location
        function setMarkersToCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const currentLat = position.coords.latitude;
                        const currentLng = position.coords.longitude;

                        // Update the 'fromMarker' to the current location
                        fromMarker.setLatLng([currentLat, currentLng]);

                        // Optionally, update 'toMarker' to a nearby location
                        const nearbyLat = currentLat + 0.01; // Adjust latitude slightly for demonstration
                        const nearbyLng = currentLng + 0.01; // Adjust longitude slightly for demonstration
                        toMarker.setLatLng([nearbyLat, nearbyLng]);

                        // Center the map on the current location
                        map.setView([currentLat, currentLng], 14);
                    },
                    (error) => {
                        console.error("Error fetching location:", error.message);
                    }
                );
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }
        setMarkersToCurrentLocation();

        // Add event listener to the "Live" button
        // document.getElementById("livelocation").addEventListener("click", () => {
        //     setMarkersToCurrentLocation();
        // });
        
        // Update inputs when markers are dragged
        fromMarker.on('dragend', function() {
            let latlng = fromMarker.getLatLng();
            document.getElementById('fromLatLng').value = `${latlng.lat}, ${latlng.lng}`;
           updateRoute();
        });

        toMarker.on('dragend', function() {
            let latlng = toMarker.getLatLng();
            document.getElementById('toLatLng').value = `${latlng.lat}, ${latlng.lng}`;
           updateRoute();
        });

        // Geocoder for address input
        let geocoder = L.Control.Geocoder.nominatim();

        async function geocodeLocation(query) {
            return new Promise((resolve, reject) => {
                geocoder.geocode(query, (results) => {
                    if (results.length > 0) {
                        resolve(results[0].center);
                    }
                    //  else {
                    //     reject('Location not found');
                    // }
                });
            });
        }
        globalThis.geocodeLocation = geocodeLocation;
        globalThis.fromMarker = fromMarker;
        globalThis.toMarker = toMarker;
        globalThis.routingControl = routingControl;
        globalThis.map = map;

        // Handle "Draw Route" button click
        document.getElementById('drawRoute').addEventListener('click', async () => {
            let fromLocation = document.getElementById('fromLocation').value;
            let toLocation = document.getElementById('toLocation').value;
            let fromCoords = document.getElementById('fromLatLng').value;
            let toCoords = document.getElementById('toLatLng').value;

            if ((!fromLocation || !toLocation) && (!fromCoords||!toCoords)) {
                alert('Please fill both From and To locations.');
                return;
            }

            try {
                // let fromCoords = await geocodeLocation(fromLocation);
                // let toCoords = await geocodeLocation(toLocation);
                let fromCoords = document.getElementById('fromLatLng').value;
                let toCoords = document.getElementById('toLatLng').value;

                if (fromCoords && toCoords) {
                    let [fromLat, fromLng] = fromCoords.split(' ').map(coord => parseFloat(coord.trim()));
                    let [toLat, toLng] = toCoords.split(' ').map(coord => parseFloat(coord.trim()));
                    var row_id = document.getElementById('selected_row').value;
                    // let fromCoordslanglet = fromCoords.lat+' '+ fromCoords.lng;
                    // let toCoordslanglet = toCoords.lat+' '+ toCoords.lng;
                    let fromSelect = document.querySelector(`#ratelist_id${row_id}`);
                    let toSelect = document.querySelector(`#to_rate_list_id${row_id}`);
                
                    // Add hidden inputs for lat and lng or set them as data attributes
                    // let fromOption = fromSelect.appendChild(new Option(fromCoordslanglet, fromCoordslanglet,false, true));
                    // let toOption = toSelect.appendChild(new Option(toCoordslanglet, toCoordslanglet,false,true));
                    // to fetch time between two routes:
                    routingControl.on('routesfound', function (e) {
                        let routes = e.routes;
                        let travelTimeInSeconds = routes[0].summary.totalTime; // Get time in seconds
                        let travelTimeInMinutes = Math.round(travelTimeInSeconds / 60); // Convert to minutes
                        console.log(`Travel Time: ${travelTimeInMinutes} minutes`);
                        document.querySelector(`#estimated_time_${row_id}`).value = travelTimeInMinutes;
                        
                        // You can use travelTimeInMinutes here in your code
                    });
                    routingControl.setWaypoints([
                        L.latLng(fromLat, fromLng),
                        L.latLng(toLat, toLng)
                    ]);
                    console.log()
                    fromMarker.setLatLng([fromLat, fromLng]);
                    toMarker.setLatLng([toLat, toLng]);

                    map.fitBounds(routingControl.getBounds());
                }
            }
             catch (error) {
                console.error(error);
                // alert('Locations not found');
            }
        });

        async function updateRoute() {
            
            let fromLocation = document.getElementById('fromLatLng').value;
            let toLocation = document.getElementById('toLatLng').value;
            var row_id = document.getElementById('selected_row').value;
            console.log('update route'+row_id);
            if (!fromLocation || !toLocation) return;

            try {
                console.log('before '+fromLocation);
          
                // let fromCoords =  geocodeLocation(fromLocation);
                console.log('after'+fromLocation);
          
                // let toCoords =  geocodeLocation(toLocation);

                
                //   let fromCoordslanglet = fromCoords.lat+' '+ fromCoords.lng;
                //   let toCoordslanglet = toCoords.lat+' '+ toCoords.lng;
                // Update the 'from' and 'to' fields with latitude and longitude values
                // let fromSelect = document.querySelector(`#ratelist_id${row_id}`).value;
                // let toSelect = document.querySelector(`#to_rate_list_id${row_id}`).value;
                // if(fromSelect&&toSelect){
                //     let [fromLat, fromLng] = fromCoordslanglet.split(' ').map(Number);
                //     let [toLat, toLng] = toCoordslanglet.split(' ').map(Number);
                //     fromMarker.setLatLng([fromLat,fromLng]);
                //     toMarker.setLatLng([toLat,toLng]);
                //     routingControl.setWaypoints([
                //         L.latLng(fromLat, fromLng),
                //         L.latLng(toLat, toLng)
                //     ]);
                // }
                // else{
                   
                // }
                routingControl.on('routesfound', function (e) {
                        let routes = e.routes;
                        let travelTimeInSeconds = routes[0].summary.totalTime; // Get time in seconds
                        let travelTimeInMinutes = Math.round(travelTimeInSeconds / 60); // Convert to minutes
                        console.log(`Travel Time: ${travelTimeInMinutes} minutes`);
                        document.querySelector(`#estimated_time_${row_id}`).value = travelTimeInMinutes;

                        // You can use travelTimeInMinutes here in your code
                });
                routingControl.setWaypoints([
                        L.latLng(fromLocation.split(',')[0], fromLocation.split(',')[1]),
                        L.latLng(toLocation.split(',')[0], toLocation.split(',')[1])
                ]);
                

                
                // Add hidden inputs for lat and lng or set them as data attributes
                // let fromOption = fromSelect.appendChild(new Option(fromCoordslanglet, fromCoordslanglet,false, true));
                // let toOption = toSelect.appendChild(new Option(toCoordslanglet, toCoordslanglet,false,true));
                // let fromOption = fromSelect.querySelector(`option[value="${fromCoordslanglet}"]`);
                // let toOption = toSelect.querySelector(`option[value="${toCoordslanglet}"]`);
                // console.log('FROM OPTION:'+fromOption.value);
                // console.log('TO OPTION:'+toOption.value);
                
               

            } catch (error) {
                console.error(error);
            }
        }
        globalThis.updateRoute = updateRoute;

    }
    </script>
</body>
</html>
