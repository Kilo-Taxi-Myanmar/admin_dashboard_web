<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'kilo taxi') }}</title>
	<link href="{{ asset('assets/logo/ktm.png') }}" rel="apple-touch-icon" sizes="180x180">
	<link type="image/png" href="{{ asset('assets/logo/ktm.png') }}" rel="icon" sizes="32x32">
	<link type="image/png" href="{{ asset('assets/logo/ktm.png') }}" rel="icon" sizes="16x16">
	<link href="{{ asset('assets/icon/site.webmanifest') }}" rel="manifest">
	<style>
		#loader {
			background: rgb(255, 255, 255) url("https://admin.shwepadauktaxi.com/assets/loading/loading.gif") no-repeat center center !important;
			min-width: 100% !important;
			min-height: 100vh !important;
			position: fixed !important;
			z-index: 2000000 !important;
			scroll-behavior: none;
		}
	</style>
	<!-- Fonts -->
	<link href="//fonts.gstatic.com" rel="dns-prefetch">
	<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

	{{-- jquery --}}
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Scripts -->
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<script src="{{ mix('js/app.js') }}" defer></script>

	{{-- gogle map   --}}
	<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_KEY')}}"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_KEY')}}&libraries=geometry"></script>
	
	
	@stack('style')
</head>

<body>
	<div id="loader"></div>
	<div id="app">
		<div class="row m-0">
			@include('layouts.left-nav')
			@include('layouts.body')
		</div>
	</div>
	<script>
		let loader = document.getElementById("loader");
		window.addEventListener("load", function() {
			loader.style.display = "none";
			window.scrollTo(0, 0);
		}, );

		const socket = new WebSocket('ws://127.0.0.1:8000');

		socket.onopen = function(event) {
			console.log('WebSocket connection established.');
		};

		socket.onmessage = function(event) {
			const data = JSON.parse(event.data);
			if (data.event === 'locationUpdated') {
				// Handle location update
				console.log('Location update received:', data);
			}
		};

	</script>
	@stack('script')
</body>

</html>
