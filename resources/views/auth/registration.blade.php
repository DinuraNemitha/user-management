
@extends('layout')

@section('content')


<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Register</div>
                  <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                      <form action="{{ route('register.post') }}" method="POST" onload="initAutocomplete()">
                          @csrf
                          <div class="form-group row">
                              <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                              <div class="col-md-6">
                                  <input type="text" id="name" class="form-control" name="name" value = "{{old('name')}}"autofocus>
                                  @if ($errors->has('name'))
                                      <span class="text-danger">{{ $errors->first('name') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="address" class="col-md-4 col-form-label text-md-right">City Location</label>
                              <div class="col-md-6">
                                  <input type="text" id="address" class="form-control" name="address" value = "{{old('address')}}"autofocus  onchange="initAutocomplete()">
                                  @if ($errors->has('address'))
                                      <span class="text-danger">{{ $errors->first('address') }}</span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="latitude" class="col-md-4 col-form-label text-md-right">Latitude</label>
                              <div class="col-md-6">
                              <input type="text" id="latitude" name="latitude">   
                                @if ($errors->has('latitude'))
                                      <span class="text-danger">{{ $errors->first('latitude') }}</span>
                                @endif    
                                                        
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="longitude" class="col-md-4 col-form-label text-md-right">Longitude</label>
                              <div class="col-md-6">
                              <input type="text" id="longitude" name="longitude">     
                                 @if ($errors->has('longitude'))
                                      <span class="text-danger">{{ $errors->first('longitude') }}</span>
                                  @endif                            
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" class="form-control" name="email" value = "{{old('email')}}"autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>
                         
                            <div class="form-group row">
                              <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number</label>
                              <div class="col-md-6">
                                  <input type="text" id="phone_number" class="form-control" name="phone_number" value = "{{old('phone_number')}}"autofocus>
                                  @if ($errors->has('phone_number'))
                                      <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password" class="form-control" name="password">
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div>


  
                          <div class="form-group row">
                              <div class="col-md-6 offset-md-4">
                                  <div class="checkbox">
                                      <label>
                                          <input type="checkbox" name="remember"> Remember Me
                                      </label>
                                  </div>
                              </div>
                          </div>
  
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  Register
                              </button>
                          </div>
                          <br>
                          <div id="map" style="width:500px;height:250px;float:left; margin: 5px 0;"></div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection
<<script async defer
    src="{{ env('GOOGLE_KEY')}};">

    
</script>
<script>
var marker;
var position;
var address;
var map;
var geocoder;
var marker_icon = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
function initMap(icon_status = 1) {
    if (icon_status == 1) {
        var marker_icon = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
    }
    map = new google.maps.Map(document.getElementById('map'), {
        disableDefaultUI: true,
        fullscreenControl: true,
        streetViewControl: true,
        zoom: 12,
        center: new google.maps.LatLng(6.9271, 79.8612),
    });
    geocoder = new google.maps.Geocoder();

    // document.getElementById('submit').addEventListener('click', function() {
    geocodeAddress(geocoder, map, marker_icon);
    // });
}
function setCooridnateDisplays(lat, lng) {
    $('#latitudes').val(lat);
    $('#longitudes').val(lng);

    if (!isNaN(lat)) {
        lat = lat.toFixed(3);
    }
    if (!isNaN(lng)) {
        lng = lng.toFixed(3);
    }

    $('#map_lat_span').text(lat);
    $('#map_lng_span').text(lng);
}
function geocodeAddress(geocoder, resultsMap, marker_icon) {
    address = document.getElementById('address').value;
    var latitiude = document.getElementById('latitude').value;
    var longitude = document.getElementById('longitude').value;
    
    if (latitiude > 0) {
        //var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};

        var latlng = {lat: parseFloat(latitiude), lng: parseFloat(longitude)};
        geocoder.geocode({'location': latlng}, function (results, status) {
            if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                //position = results[0].geometry.location;
                position = results[0].geometry.location;
                setCooridnateDisplays(position.lat(), position.lng());
                marker = new google.maps.Marker({
                    map: resultsMap,
                    position: position,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    icon: {
                        url: marker_icon
                    }

                });
                // $('#reset').removeAttr('disabled');
                google.maps.event.addListener(marker, 'drag', function (evt) {
                    setCooridnateDisplays(evt.latLng.lat(), evt.latLng.lng());
                });
            }
            else if (status === 'ZERO_RESULTS') {
                marker = new google.maps.Marker({
                    map: resultsMap,
                    position: {lat: 6.9271, lng: 79.8612},
                    draggable: false,
                    animation: google.maps.Animation.DROP,
                    visible: true,

                });
                pass_null_value();
                reset_location();
                marker.setVisible(true);
                google.maps.event.addListener(marker, 'drag', function (evt) {
                    setCooridnateDisplays(evt.latLng.lat(), evt.latLng.lng());
                });
            }

            else {
                marker = new google.maps.Marker({
                    map: resultsMap,
                    position: {lat: 6.9271, lng: 79.8612},
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    visible: true,

                });
                //$('#reset').attr('disabled','disabled');
                google.maps.event.addListener(marker, 'drag', function (evt) {
                    setCooridnateDisplays(evt.latLng.lat(), evt.latLng.lng());
                });
                //alert('Geocode was not successful for the following reason: ' + status);
            }

        });
    }
    else {
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                //position = results[0].geometry.location;
                position = results[0].geometry.location;
                setCooridnateDisplays(position.lat(), position.lng());
                marker = new google.maps.Marker({
                    map: resultsMap,
                    position: position,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    visible: true,
                    icon: {
                        url: marker_icon
                    }

                });
                // $('#reset').removeAttr('disabled');
                google.maps.event.addListener(marker, 'drag', function (evt) {
                    setCooridnateDisplays(evt.latLng.lat(), evt.latLng.lng());
                });

            } else {
                marker = new google.maps.Marker({
                    map: resultsMap,
                    position: {lat: 6.9271, lng: 79.8612},
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    visible: true,

                });
                //$('#reset').attr('disabled','disabled');
                google.maps.event.addListener(marker, 'drag', function (evt) {
                    setCooridnateDisplays(evt.latLng.lat(), evt.latLng.lng());
                });
                //alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }


}
function setCooridnateDisplays(lat, lng) {
    $('#latitude').val(lat);
    $('#longitude').val(lng);

    if (!isNaN(lat)) {
        lat = lat.toFixed(3);
    }
    if (!isNaN(lng)) {
        lng = lng.toFixed(3);
    }

    $('#latitude').text(lat);
    $('#longitude').text(lng);
}
</script>


<script >

    function initAutocomplete() {
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        });
        geocodeAddress(geocoder, map, marker_icon);
    }

    // initAutocomplete();

</script>

