@extends('layouts.app')

@section('content')
	<div class="container p-5">
		<div class="row">
			<div class="col-md-12">
				<form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-md-6">
							<div class=" mb-3">
								<label class="form-label" for="first_name">
									<span class="text-danger">*</span>{{ __('First Name') }}
								</label>
								<input class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" type="text"
									value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
								@error('first_name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class=" mb-3">
								<label class="form-label" for="email">
									<span class="text-danger">*</span>{{ __('Email Address') }}
								</label>
								<input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="text"
									value="{{ old('email') }}" required autocomplete="email" autofocus>
								@error('email')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class=" mb-3">
								<label class="form-label" for="phone">
									<span class="text-danger">*</span>{{ __('Phone Number') }}
								</label>
								<input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text"
									value="{{ old('phone') }}" required autocomplete="phone" autofocus>
								@error('phone')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							

						</div>
						<div class="col-md-6">
							<div class="row">
								<div class=" mb-3">
									<label class="form-label" for="last_name">
										<span class="text-danger">*</span>{{ __('Last Name') }}
									</label>
									<input class="form-control @error('last_name') is-invalid @enderror" id="last_name"
										name="last_name" type="text" value="{{ old('last_name') }}" required
										autocomplete="last_name" autofocus>
									@error('last_name')
										<div class="invalid-feedback">
											{{ $message }}
										</div>
									@enderror
								</div>
								
								<div class=" mb-3">
									<label class="form-label" for="password"><span class="text-danger">*</span>{{ __('Password') }}</label>
									<input class="form-control" id="password" name="password" type="password">
									@error('password')
										<div class="invalid-feedback">
											{{ $message }}
										</div>
									@enderror
								</div>
								<div class="mb-3">
									<label class="form-label" for="password-confirm"><span
											class="text-danger">*</span>{{ __('Confirm Password') }}</label>

									<input class="form-control" id="password-confirm" name="password_confirmation" type="password" required
										autocomplete="new-password">
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<label class="form-label" for="address">
								<span class="text-danger">*</span>{{ __('Address') }}
							</label>

							<textarea class=" form-control @error('address') is-invalid @enderror" id="address" name="address" required
							 autocomplete="address">{{ old('address') }}</textarea>

							@error('address')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror
						</div>
					</div>
					
					<div class="row mt-3">
						<div class="col-md-1 row m-0 mb-3 pe-lg-1">
							<button class="btn btn-danger " type="submit">Create</button>
						</div>
						<div class="col-md-1 row m-0 mb-3 pe-lg-1">
							<button class="btn btn-dark " type="reset">Cancel</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
@endsection
@push('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
		integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		
	</script>
@endpush
