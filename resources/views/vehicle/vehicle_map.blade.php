@extends('layouts.app')
@section('wrapper')
 <style>


#map {
    position: relative; /* Ensure proper positioning */
    z-index: 0; /* Lower stack order */
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
                                    <svg width="26" height="26" fill="currentColor" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                        <path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/>
                                    </svg>
                                    {{ __(' Find Vehicle Location') }}
                                </h3>
                        
                                <form method="GET" action="{{ route('vehicle_loc') }}" style="display: flex; align-items: center;">
                                    <div class="form-group mb-0 mr-2">
                                        <label for="vehicle_id">Select Vehicle</label>
                                        <select id="vehicle_id" name="vehicle_id" class="form-control">
                                            <option value="">All Vehicles</option>
                                            @foreach($activeVehicles as $id => $vehicle_no)
                                                <option value="{{ $id }}" {{ $id == request('vehicle_id') ? 'selected' : '' }}>
                                                    {{ $vehicle_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mt-3 mx-2">Filter</button>
                                </form>
                            </div>
                        </div>
                        <div id="map" style="height: 500px;"></div>

                        <script>
                            // --------------
                            var map = L.map('map').setView([20.5937, 78.9629], 5); // Initial map view (set to a default location)

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(map);

                            @foreach($vehicleLocations as $location)
                                
                                    var marker = L.marker([{{ $location['latitude'] }}, {{ $location['longitude'] }}]).addTo(map);
                                    marker.bindPopup("<b>Vehicle: {{ $location['vehicleName'] }}</b><br>Latitude: {{ $location['latitude'] }}<br>Longitude: {{ $location['longitude'] }}<br>Direction: {{ $location['direction'] }}<br>Speed: {{ $location['speed'] }}").openPopup();  
                            @endforeach
                        </script>
                    
                               
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
