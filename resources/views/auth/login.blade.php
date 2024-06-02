<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Shwe Padauk Taxi Service') }}</title>
	<link href="{{ asset('assets/logo/ktm.png') }}" rel="apple-touch-icon" sizes="180x180">
	<link type="image/png" href="{{ asset('assets/logo/ktm.png') }}" rel="icon" sizes="32x32">
	<link type="image/png" href="{{ asset('assets/logo/ktm.png') }}" rel="icon" sizes="16x16">
	<link href="{{ asset('assets/icon/site.webmanifest') }}" rel="manifest">
	<!-- Fonts -->
	<link href="//fonts.gstatic.com" rel="dns-prefetch">
	<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
	<!-- Scripts -->
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
				<div class="row justify-content-center">
					<div class="col-md-4">

						<form method="POST" action="{{ route('login') }}">
							@csrf

							<div class="mb-2">
								<label class="col-md-6 col-form-label" for="email">{{ __('Email Address') }}</label>
								<input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email"
									value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">

								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>

							<div class="mb-2">
								<label class="col-md-6 col-form-label" for="password">{{ __('Password') }}</label>

								<div class="input-group">
									<input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
										type="password" required autocomplete="current-password" placeholder="Enter your password">
									<button class="btn btn-secondary" id="show-hide" type="button" onclick="showPassword()">
										<i class="fa-solid fa-eye"></i>
									</button>
								</div>
								@error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>

							<div class="row mb-0">
								<div class="col-mb-12 p-3">
									<button class="btn btn-primary form-control btn-lg" type="submit">
										{{ __('LOGIN') }}
									</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>

		</div>
		<div style="margin-top: -2rem">
			<p class="text-center small fw-bold">Copyright &copy; 2024 Kilo Taxi Myanmar. All right reserved</p>
		</div>
	</div>
	<script>
		function showPassword() {
			const passwordInput = document.getElementById("password");
			const showHideButton = document.getElementById("show-hide");

			if (passwordInput.type === "password") {
				passwordInput.type = "text";
				showHideButton.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
			} else {
				passwordInput.type = "password";
				showHideButton.innerHTML = '<i class="fa-solid fa-eye"></i>';
			}
		}
	</script>
</body>

</html>
