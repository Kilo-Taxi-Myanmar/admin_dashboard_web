<div class="col-12 col-sm-2 position-relative p-0 bg-secondary min-vh-100 overflow-hidden d-none d-sm-block"
	id="sidebar" style="transition: width 2s;transition-timing-function: ease-in-out;">
	<div class="d-sm-none position-absolute p-1" style="right: 0;">
		<button class="btn fs-1 fw-bold" id="sidebar_btn2">
			<i class="fa-solid fa-xmark"></i>
		</button>
	</div>
	<div class=" container m-auto px-4 py-4 text-center ">
		<a class="navbar-brand d-none d-sm-inline " href="{{ route('dashboard') }}">
			<img src="{{ asset('assets/logo/ktm.png') }}" alt="SPTS" width="75%" height="auto">
		</a>
		<a class="navbar-brand d-sm-none " href="{{ route('dashboard') }}">
			<img src="{{ asset('assets/logo/ktm.png') }}" alt="" width="45%" height="auto">
		</a>
	</div>
	<div class="">
		<ul class="nav nav-pills flex-column align-content-sm-stretch justify-content-center">
			<li class="nav-item">
				<a class="nav-link @if (url()->current() === route('dashboard')) active @endif " href="{{ route('dashboard') }}"
					aria-current="page">
					<span class="pe-3">
						@if (url()->current() === route('dashboard'))
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
									d="M2 13.25H9.5C10.1875 13.25 10.75 12.6875 10.75 12V2C10.75 1.3125 10.1875 0.75 9.5 0.75H2C1.3125 0.75 0.75 1.3125 0.75 2V12C0.75 12.6875 1.3125 13.25 2 13.25ZM2 23.25H9.5C10.1875 23.25 10.75 22.6875 10.75 22V17C10.75 16.3125 10.1875 15.75 9.5 15.75H2C1.3125 15.75 0.75 16.3125 0.75 17V22C0.75 22.6875 1.3125 23.25 2 23.25ZM14.5 23.25H22C22.6875 23.25 23.25 22.6875 23.25 22V12C23.25 11.3125 22.6875 10.75 22 10.75H14.5C13.8125 10.75 13.25 11.3125 13.25 12V22C13.25 22.6875 13.8125 23.25 14.5 23.25ZM13.25 2V7C13.25 7.6875 13.8125 8.25 14.5 8.25H22C22.6875 8.25 23.25 7.6875 23.25 7V2C23.25 1.3125 22.6875 0.75 22 0.75H14.5C13.8125 0.75 13.25 1.3125 13.25 2Z"
									fill="#fff" />
							</svg>
						@else
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
									d="M2 13.25H9.5C10.1875 13.25 10.75 12.6875 10.75 12V2C10.75 1.3125 10.1875 0.75 9.5 0.75H2C1.3125 0.75 0.75 1.3125 0.75 2V12C0.75 12.6875 1.3125 13.25 2 13.25ZM2 23.25H9.5C10.1875 23.25 10.75 22.6875 10.75 22V17C10.75 16.3125 10.1875 15.75 9.5 15.75H2C1.3125 15.75 0.75 16.3125 0.75 17V22C0.75 22.6875 1.3125 23.25 2 23.25ZM14.5 23.25H22C22.6875 23.25 23.25 22.6875 23.25 22V12C23.25 11.3125 22.6875 10.75 22 10.75H14.5C13.8125 10.75 13.25 11.3125 13.25 12V22C13.25 22.6875 13.8125 23.25 14.5 23.25ZM13.25 2V7C13.25 7.6875 13.8125 8.25 14.5 8.25H22C22.6875 8.25 23.25 7.6875 23.25 7V2C23.25 1.3125 22.6875 0.75 22 0.75H14.5C13.8125 0.75 13.25 1.3125 13.25 2Z"
									fill="#524E45" />
							</svg>
						@endif
					</span>
					<span class="d-inline d-sm-none d-xl-inline">Dashboard</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="collapse" href="#driver-management-nav-dropdown" role="button"
					aria-expanded="false" aria-controls="driver-management-nav-dropdown">
					<span class="pe-3">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M23.2483 11.5448C23.1564 5.36371 18.041 0.577171 12 0.581882C10.4969 0.582278 9.00915 0.883847 7.62451 1.46879C6.23988 2.05373 4.98644 2.91019 3.93818 3.98762C2.88993 5.06504 2.06811 6.34158 1.52124 7.7419C0.974367 9.14221 0.713521 10.6379 0.754102 12.1407C0.916608 18.5008 6.21808 23.1884 12.0188 23.08C18.4155 23.0588 23.3566 18.0603 23.2483 11.5448ZM4.64248 7.60858C6.04144 5.28362 8.06217 3.81374 10.7659 3.45805C14.4211 2.97515 17.2449 4.3885 19.2775 7.44604C19.3889 7.57219 19.4504 7.73471 19.4504 7.90303C19.4504 8.07135 19.3889 8.23387 19.2775 8.36001C18.7829 8.99366 18.2953 9.62967 17.7984 10.261C17.8526 10.1927 17.5087 9.58491 17.464 9.49776C17.0688 8.72137 16.5403 8.02051 15.9025 7.4272C15.0918 6.68663 14.0779 6.20586 12.9915 6.04683C11.7026 5.84314 10.3823 6.04307 9.21149 6.61924C8.38695 7.03317 7.67317 7.63805 7.12953 8.38356C6.82084 8.8115 6.55276 9.26732 6.32877 9.74509C6.30993 9.78514 6.29344 9.82282 6.27696 9.86287C6.26047 9.90292 6.2275 9.97359 6.20395 10.0278L6.03202 10.1031C5.56099 9.43415 5.05228 8.77459 4.59302 8.08912C4.567 8.00936 4.55919 7.92479 4.5702 7.84162C4.58121 7.75845 4.61073 7.67882 4.65661 7.60858H4.64248ZM3.58265 11.5801C3.66431 11.62 3.74302 11.6656 3.81817 11.7167C4.79085 12.5317 5.92368 13.2643 6.52661 14.4068C7.31794 15.9026 8.13047 17.3842 8.94065 18.8824L8.98069 18.9578C9.13613 19.2452 9.29627 19.5325 9.47526 19.8552C6.49599 19.6409 2.92792 14.5976 3.59678 11.5801H3.58265ZM11.098 17.0639C10.4715 15.978 9.86387 14.8826 9.22798 13.8014C7.91144 11.566 9.29627 9.20331 11.3311 8.81464C11.9175 8.68152 12.5299 8.72201 13.0937 8.93115C13.6575 9.1403 14.1481 9.50906 14.5059 9.99243C15.3349 11.0218 15.5163 12.2161 14.9063 13.3868C14.0278 15.064 13.0339 16.6846 12.0801 18.3335C12.0302 18.3912 11.975 18.444 11.9152 18.4914C11.6326 17.9826 11.3594 17.535 11.098 17.0639ZM14.4093 19.9212C15.0405 18.8164 15.6364 17.8012 16.1945 16.7859C16.5478 16.1358 16.8799 15.4904 17.1978 14.8261C17.3813 14.4227 17.6105 14.0418 17.8808 13.6907C18.1982 13.3496 18.5467 13.0389 18.9218 12.7626C19.374 12.3763 19.838 12.0041 20.2972 11.6248C20.3363 11.6003 20.3772 11.5791 20.4197 11.5612C20.9096 14.7083 18.2506 19.0096 14.4093 19.9283V19.9212Z"
								fill="#524E45" />
							<path
								d="M13.453 12.3079C13.4585 12.5047 13.4239 12.7006 13.3513 12.8837C13.2787 13.0668 13.1696 13.2332 13.0307 13.3727C12.8918 13.5123 12.7259 13.6222 12.5432 13.6957C12.3605 13.7691 12.1647 13.8047 11.9678 13.8001C11.6724 13.8015 11.3832 13.7152 11.1369 13.5521C10.8905 13.389 10.6982 13.1564 10.5841 12.8838C10.4701 12.6113 10.4395 12.311 10.4963 12.0211C10.5531 11.7312 10.6947 11.4646 10.9031 11.2552C11.1115 11.0458 11.3774 10.903 11.667 10.8448C11.9567 10.7867 12.2571 10.8158 12.5302 10.9285C12.8033 11.0413 13.0368 11.2325 13.2011 11.4781C13.3653 11.7236 13.453 12.0124 13.453 12.3079Z"
								fill="#524E45" />
						</svg>
					</span>
					<span class="d-inline d-sm-none d-lg-inline">
						Driver
					</span>
					<span class="d-inline d-sm-none d-lg-inline float-end"><i class="fa-solid fa-chevron-down text-end"></i></span>
				</a>
				<div class="collapse" id="driver-management-nav-dropdown">
					<ul class="nav nav-pills flex-column">
						<li class="nav-item">
							<a class="nav-link  @if (url()->current() === route('users.index')) active @endif " href="{{ route('users.index') }}"
								href="{{ route('users.index') }}">All Drivers</a>
						</li>
						<li class="nav-item">
							<a class="nav-link  @if (url()->current() === route('users.create')) active @endif " href="{{ route('users.create') }}"
								href="{{ route('users.create') }}">Add Driver</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() === route('users.pending')) active @endif " href="{{ route('users.pending') }}">Pending
								Driver</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() === route('users.active')) active @endif " href="{{ route('users.active') }}">Active
								Driver</a>
						</li>
						@role('admin')
							<li class="nav-item">
								<a class="nav-link @if (url()->current() === route('users.role')) active @endif " href="{{ route('users.role') }}">Manage
									Role
								</a>
							</li>
						@endrole
                            <li class="nav-item">
								<a class="nav-link @if (url()->current() === route('users.summary')) active @endif " href="{{ route('users.summary') }}">Summary
								</a>
							</li>
					</ul>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="collapse" href="#customer-management-nav-dropdown" role="button"
					aria-expanded="false" aria-controls="customer-management-nav-dropdown">
					<span class="pe-3">
						<i class="fa-solid fa-users"></i>
					</span>
					<span class="d-inline d-sm-none d-lg-inline">
						Customer
					</span>
					<span class="d-inline d-sm-none d-lg-inline float-end"><i class="fa-solid fa-chevron-down text-end"></i></span>
				</a>
				<div class="collapse" id="customer-management-nav-dropdown">
					<ul class="nav nav-pills flex-column">
						<li class="nav-item">
							<a class="nav-link  @if (url()->current() === route('customers.index')) active @endif " href="{{ route('customers.index') }}"
								href="{{ route('users.index') }}">All Customer</a>
						</li>
						<li class="nav-item">
							<a class="nav-link  @if (url()->current() === route('customers.create')) active @endif " href="{{ route('customers.create') }}"
								href="{{ route('users.create') }}">Add Customer</a>
						</li>
						
						
					</ul>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="collapse" href="#wallet-nav-dropdown" role="button" aria-expanded="false"
					aria-controls="wallet-nav-dropdown">
					<span class="pe-3">
						<svg width="22" height="17" viewBox="0 0 29 23" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M3.71043 2.70249C3.20795 2.70249 2.52953 3.19565 2.52953 4.13842V19.1159C2.52953 20.0587 3.20795 20.5518 3.71043 20.5518H24.5964C25.0989 20.5518 25.7773 20.0587 25.7773 19.1159V4.13842C25.7773 3.19565 25.0989 2.70249 24.5964 2.70249H3.71043ZM0.25 4.13842C0.25 2.32391 1.64958 0.581848 3.71043 0.581848H24.5964C26.6573 0.581848 28.0569 2.32391 28.0569 4.13842V19.1159C28.0569 20.9304 26.6573 22.6725 24.5964 22.6725H3.71043C1.64958 22.6725 0.25 20.9304 0.25 19.1159V4.13842Z"
								fill="#524E45" />
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M24.6606 9.60117H18.3974C17.8179 9.60117 17.3482 10.009 17.3482 10.5121V14.3926C17.3482 14.8957 17.8179 15.3035 18.3974 15.3035H24.6606C25.24 15.3035 25.7097 14.8957 25.7097 14.3926V10.5121C25.7097 10.009 25.24 9.60117 24.6606 9.60117ZM18.3974 7.77934C16.6591 7.77934 15.25 9.00283 15.25 10.5121V14.3926C15.25 15.9019 16.6591 17.1254 18.3974 17.1254H24.6606C26.3988 17.1254 27.8079 15.9019 27.8079 14.3926V10.5121C27.8079 9.00283 26.3988 7.77934 24.6606 7.77934H18.3974Z"
								fill="#524E45" />
							<path
								d="M21.1974 12.0275C21.1974 12.4968 20.8171 12.8772 20.3478 12.8772C19.8786 12.8772 19.4982 12.4968 19.4982 12.0275C19.4982 11.5583 19.8786 11.1779 20.3478 11.1779C20.8171 11.1779 21.1974 11.5583 21.1974 12.0275Z"
								fill="#524E45" />
						</svg>
					</span>

					<span class="d-inline d-sm-none d-lg-inline">Wallet</span>
					<span class="d-inline d-sm-none d-lg-inline float-end"><i class="fa-solid fa-chevron-down"></i></span>

				</a>
				<div class="collapse" id="wallet-nav-dropdown">
					<ul class="nav nav-pills flex-column">
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('topup.index')) active @endif" href="{{ route('topup.index') }}">Top
								Up</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('topup.history')) active @endif" href="{{ route('topup.history') }}">Top Up
								History</a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="collapse" href="#fees-nav-dropdown" role="button" aria-expanded="false"
					aria-controls="fees-nav-dropdown">
					<span class="pe-3" style="margin-left: -3px">
						<svg width="27" height="27" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M16.5138 11C13.475 11 11.0138 13.4612 11.0138 16.5C11.0138 19.5387 13.475 22 16.5138 22C19.5525 22 22.0138 19.5387 22.0138 16.5C22.0138 13.4612 19.5525 11 16.5138 11ZM16.5138 19.25C15.0013 19.25 13.7638 18.0125 13.7638 16.5C13.7638 14.9875 15.0013 13.75 16.5138 13.75C18.0263 13.75 19.2638 14.9875 19.2638 16.5C19.2638 18.0125 18.0263 19.25 16.5138 19.25Z"
								fill="#524E45" />
							<path
								d="M28.8475 15.1387H28.82C28.6825 13.8737 28.3525 12.6638 27.8575 11.5362H27.885C28.545 11.1375 28.765 10.2987 28.3937 9.63874C28.0087 8.97874 27.17 8.75875 26.51 9.13H26.4825C25.74 8.14 24.8462 7.26001 23.8425 6.51751L23.87 6.47626C24.255 5.81626 24.0212 4.9775 23.3612 4.5925C22.7012 4.2075 21.8625 4.44126 21.4775 5.10126V5.12874C20.3362 4.63374 19.1262 4.30375 17.8612 4.16625V4.125C17.8612 3.36875 17.2425 2.75 16.4862 2.75C15.73 2.75 15.1112 3.36875 15.1112 4.125V4.17999C13.8462 4.31749 12.6363 4.66125 11.5225 5.15625L11.495 5.10126C11.11 4.44126 10.2712 4.22125 9.61124 4.5925C8.95124 4.9775 8.73124 5.81626 9.10249 6.47626L9.14374 6.54499C8.13999 7.28749 7.26 8.1675 6.53125 9.17125L6.46252 9.13C5.80252 8.745 4.96373 8.97874 4.57873 9.63874C4.19373 10.2987 4.42752 11.1375 5.08752 11.5225L5.15625 11.5638C4.675 12.6775 4.345 13.8875 4.2075 15.1387H4.125C3.36875 15.1387 2.75 15.7575 2.75 16.5137C2.75 17.27 3.36875 17.8887 4.125 17.8887H4.2075C4.345 19.14 4.67501 20.35 5.17001 21.4638L5.08752 21.505C4.42752 21.89 4.20748 22.7287 4.57873 23.3887C4.83998 23.8287 5.29377 24.0762 5.77502 24.0762C6.00877 24.0762 6.24252 24.0213 6.46252 23.8975L6.54501 23.8425C7.27376 24.8325 8.15375 25.7125 9.1575 26.455L9.11626 26.5375C8.73126 27.1975 8.965 28.0362 9.625 28.4212C9.845 28.545 10.0787 28.6 10.3125 28.6C10.7937 28.6 11.2475 28.3525 11.5087 27.9125L11.55 27.8438C12.6637 28.3388 13.86 28.6687 15.125 28.8062V28.8887C15.125 29.645 15.7437 30.2637 16.5 30.2637C17.2562 30.2637 17.875 29.645 17.875 28.8887V28.82C19.14 28.6825 20.3362 28.3525 21.4637 27.8713L21.4913 27.9262C21.7525 28.3662 22.2062 28.6137 22.6875 28.6137C22.9212 28.6137 23.155 28.5587 23.375 28.435C24.035 28.05 24.255 27.2112 23.8837 26.5512L23.8563 26.4963C24.86 25.7538 25.74 24.8738 26.4825 23.87L26.5375 23.8975C26.7575 24.0213 26.9912 24.0762 27.225 24.0762C27.7062 24.0762 28.16 23.8287 28.4212 23.3887C28.8062 22.7287 28.5725 21.89 27.9125 21.505H27.8712C28.3662 20.3638 28.6963 19.1537 28.8475 17.8887H28.8888C29.645 17.8887 30.2638 17.27 30.2638 16.5137C30.2638 15.7575 29.645 15.1387 28.8888 15.1387H28.8475ZM16.5138 26.1387C11.2063 26.1387 6.875 21.8213 6.875 16.5C6.875 11.1788 11.1925 6.86126 16.5138 6.86126C21.835 6.86126 26.1525 11.1788 26.1525 16.5C26.1525 21.8213 21.835 26.1387 16.5138 26.1387Z"
								fill="#524E45" />
						</svg>
					</span>

					<span class="d-inline d-sm-none d-lg-inline">Fees</span>
					<span class="d-inline d-sm-none d-lg-inline float-end"><i class="fa-solid fa-chevron-down"></i></span>
				</a>
				<div class="collapse" id="fees-nav-dropdown">
					<ul class="nav nav-pills flex-column">

						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('system.initialFee')) active @endif" href="{{ route('system.initialFee') }}"
								href="#">Initial fee</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('system.normalFee')) active @endif" href="{{ route('system.normalFee') }}"
								href="#">Per Kilo Fee</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('system.waitingFee')) active @endif" href="{{ route('system.waitingFee') }}"
								href="#">Waiting fee</a>
						</li>
                        <li class="nav-item">
							<a class="nav-link @if (url()->current() == route('system.CommissionFee')) active @endif"
								href="{{ route('system.CommissionFee') }}">Commission Fee</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('system.OrderCommissionFee')) active @endif"
								href="{{ route('system.OrderCommissionFee') }}"> % Commission Fee</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('fee.index')) active @endif" href="{{ route('fee.index') }}">
								Extra fees</a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="collapse" href="#trips-nav-dropdown" role="button" aria-expanded="false"
					aria-controls="trips-nav-dropdown">
					<span class="pe-3" style="margin-left: -3px">
						<i class="fa-solid fa-route"></i>
					</span>
					<span class="d-inline d-sm-none d-lg-inline">Trip</span>
					<span class="d-inline d-sm-none d-lg-inline float-end"><i class="fa-solid fa-chevron-down"></i></span>
				</a>
				<div class="collapse" id="trips-nav-dropdown">
					<ul class="nav nav-pills flex-column">
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('trip.index')) active @endif" href="{{ route('trip.index') }}">All
								Trip</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('trips.accepted')) active @endif" href="{{ route('trips.accepted') }}">Accepted Trips
								</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('trips.completed')) active @endif" href="{{ route('trips.completed') }}">Completed Trips
								</a>
						</li>
						<li class="nav-item">
							<a class="nav-link @if (url()->current() == route('trips.canceled')) active @endif" href="{{ route('trips.canceled') }}">Canceled Trips
								</a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('notification.index') }}">
					<span class="pe-3" style="margin-left: -3px">
						<i class="fa-solid fa-paper-plane"></i>
					</span>
					<span class="d-inline d-sm-none d-lg-inline">Notification</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('map.index') }}">
					<span class="pe-3" style="margin-left: -3px">
						<i class="fas fa-map-marked-alt"></i>
					</span>
					<span class="d-inline d-sm-none d-lg-inline">Map</span>
				</a>
			</li>
		</ul>
	</div>

	<div class=" position-absolute ms-3 mb-3 bottom-0">
		<ul class="nav nav-pills align-content-sm-stretch justify-content-center">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="navbarDropdown" data-bs-toggle="dropdown" href="#" role="button"
					aria-haspopup="true" aria-expanded="false" v-pre>
					<i class="fa-solid fa-sliders me-3"></i>{{ Auth::user()->name }}
				</a>
				<ul class="dropdown-menu">
					<li>
						<a class="dropdown-item" href="{{ route('admin.profile') }}">
							{{ __('Password Reset') }}
						</a>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('changeLog') }}">
							{{ __('Change Log') }}
						</a>
					</li>
					<li><a class="dropdown-item" href="{{ route('logout') }}"
							onclick="event.preventDefault();document.getElementById('logout-form').submit();">
							{{ __('Logout') }}
						</a>
						<form class="d-inline d-sm-none" id="logout-form" action="{{ route('logout') }}" method="POST">
							@csrf
						</form>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>

@push('script')
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"
		integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function() {
			$('.nav-link').on('click', function() {
				// Hide all other collapsed divs
				$('.collapse').collapse('hide');
			});
		});
		$(document).ready(function() {
			// Check if any of the nav links have the 'active' class
			if ($('#driver-management-nav-dropdown .nav-link').hasClass('active')) {
				// Show the collapse
				$('#driver-management-nav-dropdown').collapse('show');
			}
			if ($('#customer-management-nav-dropdown .nav-link').hasClass('active')) {
				// Show the collapse
				$('#customer-management-nav-dropdown').collapse('show');
			}
			if ($('#wallet-nav-dropdown .nav-link').hasClass('active')) {
				// Show the collapse
				$('#wallet-nav-dropdown').collapse('show');
			}
			if ($('#fees-nav-dropdown .nav-link').hasClass('active')) {
				// Show the collapse
				$('#fees-nav-dropdown').collapse('show');
			}
			if ($('#trips-nav-dropdown .nav-link').hasClass('active')) {
				// Show the collapse
				$('#trips-nav-dropdown').collapse('show');
			}
		});
	</script>
@endpush
