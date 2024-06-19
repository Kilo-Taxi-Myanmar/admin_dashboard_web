<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'SPD Kilo Taxt') }}</title>
    <link href="{{ asset('assets/logo/ktm.png') }}" rel="apple-touch-icon" sizes="180x180">
    <link type="image/png" href="{{ asset('assets/logo/ktm.png') }}" rel="icon" sizes="32x32">
    <link type="image/png" href="{{ asset('assets/logo/ktm.png') }}" rel="icon" sizes="16x16">
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
                <div class=" text-center w-100 d-none d-sm-block">
                    <img src="{{ asset('assets/logo/ktm.png') }}" alt="" width="15%" height="">
                </div>
                <div class=" text-center w-100 d-sm-none">
                    <img src="{{ asset('assets/logo/ktm.png') }}" alt="" width="50%" height="">
                </div>
				<div class="row justify-content-center mt-5">
					<ul class="nav justify-content-center">
						 <li class="mx-3">
						<a href="{{route('customer.app')}}" class="text-decoration-none btn btn-primary btn-sm d-none" download>KTM Customer</a>
					</li>
					<li class="mx-3">
						<a href="{{route('driver.app')}}" class="text-decoration-none btn btn-primary btn-sm " >Kilo Taxi Myanmar (version 2.0.0+24) </a>
					</li>
{{--					<li class="mx-3">--}}
{{--						<a href="https://drive.google.com/file/d/10yunlgfejY4mSy1xbg80vW29GlK5DhWH/view?usp=drive_link" class="text-decoration-none btn btn-primary btn-sm ">SPTS Customer</a>--}}
{{--					</li>--}}
{{--					<li class="mx-3">--}}
{{--						<a href="https://drive.google.com/file/d/1bdGtvzr9WAB0mGQfLPVYotBxhm9ZAfCC/view?usp=drive_link" class="text-decoration-none btn btn-primary btn-sm ">SPTS Driver</a>--}}
{{--					</li>--}}
					</ul>
				</div>
			</div>
		</div>
		<div style="margin-top: -2rem">
			<p class="text-center small fw-bold">Copyright &copy; 2024 {{env('APP_NAME')}}. All right reserved</p>
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
