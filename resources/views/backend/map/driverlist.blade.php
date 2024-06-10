@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">            
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        {{-- <h5 class="font-weight-bold">{{ $pageTitle }}</h5> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div id="map" style="height: 600px;"></div>
                    {{-- <div id="maplegend" class="d-none">
                        
                        <div>
                            <img src="{{ asset('images/online.png') }}" /> {{ __('message.online') }}
                        </div>
                        <div>
                            <img src="{{ asset('images/ontrip.png') }}" /> {{ __('message.in_service') }}
                        </div>
                        <div>
                            <img src="{{ asset('images/offline.png') }}" /> {{ __('message.offline') }}
                        </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
	$(function(){
		let map;
		var marker = undefined;
		var locations = [];
		var taxiicon = ""
		
		function initialize() {
			var myLatlng = new google.maps.LatLng(20.947940, 72.955786);
			var myOptions = {
				zoom: 1.5,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map = new google.maps.Map(document.getElementById('map'), myOptions);
			const legend = document.getElementById("maplegend");

			map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend)
		}

		function changeMarkerPositions(locations)
		{

			
			var infowindow = new google.maps.InfoWindow();
			var markers = {};
			if(locations.length > 0 )
			{
				for(i = 0 ; i < locations.length ; i++) {
					// console.log("new "+locations[i].lat, locations[i].lng);
				
					if(markers[locations[i].id] ){
						markers[locations[i].id].setMap(null); // set markers setMap to null to remove it from map
						delete markers[locations[i].id]; // delete marker instance from markers object
					}
					
					if( locations[i].active == 'active' && locations[i].available == 0) {
						taxicon = "{{ asset('assets/icon/ontrip.png') }}";
					} else if( locations[i].active == 'active' && locations[i].available == 1) {
						taxicon = "{{ asset('assets/icon/online.png') }}";
					} else {
						taxicon = "{{ asset('assets/icon/offline.png') }}";
					}
					marker = new google.maps.Marker({
						position:  new google.maps.LatLng( parseFloat(locations[i].lat)  + (Math.random() -.5) / 1500, parseFloat(locations[i].lng) + (Math.random() -.5) / 1500 ),
						map: map,
						icon: taxicon,
						title: locations[i].display_name,
						driver_id: locations[i].id
					});
					
				
				}
			}
		}


		if(window.google || window.google.maps) {
			let users = @json($users);

			
			initialize();
			changeMarkerPositions(users);
			$('#maplegend').removeClass('d-none')
			// console.log('1.initial');
		}
	});
</script>
@endpush

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Driver Location</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHWnJ99eF2wvCJdFgIRIkpBYyEuZwazFM"></script>
    <script>
       let users = @json($users);
	//    console.log(users)

	function initMap() {
            var latitude =17.88703400;
            var longitude = 115.18541700;
            var driverLocation = { lat: latitude, lng: longitude };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: driverLocation
            });

            var marker = new google.maps.Marker({
                position: driverLocation,
                map: map,
                title: 'Driver Location'
            });
        }

        window.onload = function() {
            initMap();
        };


    </script>
</head>
<body>
    <div id="map" style="height: 500px; width: 100%;"></div>

  

</body>
</html> --}}
