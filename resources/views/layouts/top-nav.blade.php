<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-3">
	<div class="container">
		<button class="btn btn-outline-dark me-3 d-sm-none" id="sidebar_btn"><i class="fa-solid fa-bars"></i></button>
		<a class="navbar-brand" href="{{ url('/dashboard') }}">
			{{ __('Dashboard') }}
		</a>

		<!-- Right Side Of Navbar -->
		<ul class="navbar-nav flex-row ms-auto">
			<!-- Authentication Links -->
			{{-- <li class="mx-3">
				<a href="{{route('customer.app')}}" class="text-decoration-none btn btn-primary btn-sm ">customer.app</a>
			</li>
			<li class="mx-3">
				<a href="{{route('driver.app')}}" class="text-decoration-none btn btn-primary btn-sm ">driver.app</a>
			</li> --}}
			<li class="nav-item d-flex  align-items-center pe-3" id="noti">
				<a class="text-black text-decoration-none" href="{{ route('topup.notification') }}">
					<i class="fa-regular fa-bell"></i>
				</a>
				<a id="noti-api" data-url="{{ route('api.notification') }}">
				</a>
				<a id="noti-pg" data-url="{{ route('topup.notification') }}">
				</a>
			</li>
		</ul>

	</div>
</nav>
