<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'SPD Kilo Taxt') }}</title>
	<link href="{{ asset('assets/icon/apple-touch-icon.png') }}" rel="apple-touch-icon" sizes="180x180">
	<link type="image/png" href="{{ asset('assets/icon/favicon-32x32.png') }}" rel="icon" sizes="32x32">
	<link type="image/png" href="{{ asset('assets/icon/favicon-16x16.png') }}" rel="icon" sizes="16x16">
	<link href="{{ asset('assets/icon/site.webmanifest') }}" rel="manifest">
	<!-- Fonts -->
	<link href="//fonts.gstatic.com" rel="dns-prefetch">
	<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
	<!-- Scripts -->

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
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body>
	<div id="app">
		<div class="row justify-content-center align-items-center vh-100 m-0">
			<div class="">
				<div class=" text-center w-100">
					<img src="{{ asset('assets/logo/logo.png') }}" alt="" width="15%" height="">
				</div>
				<div class="row justify-content-center">
					<div class="col-md-4">
						<a class="form-control btn btn-primary" href="{{ route('login') }}" role="button">Login</a>
					</div>
				</div>
			</div>
		</div>
		<div style="margin-top: -2rem">
			<p class="text-center small fw-bold">Copyright &copy; 2023 Shwe Padauk. All right reserved</p>
		</div>
	</div>
	<script>
		let loader = document.getElementById("loader");
		window.addEventListener("load", function() {
			setTimeout(() => {
				loader.style.display = "none";
				window.scrollTo(0, 0);
			}, 200);
		}, );
	</script>
</body>

</html>
