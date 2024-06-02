@extends('layouts.app')

@section('content')
	<div class="container p-5">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<form method="POST" action="{{ route('admin.update', $user) }}" enctype="multipart/form-data">
					@csrf
					<div class=" mb-3">
						<label class="form-label" for="name">
							<span class="text-danger">*</span>{{ __('Username') }}
						</label>
						<input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
							value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
						@error('name')
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
							value="{{ old('email', $user->email) }}" required autocomplete="email" autofocus>
						@error('email')
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

						<input class="form-control" id="password-confirm" name="password_confirmation" type="password"
							autocomplete="new-password">
						@error('password')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="row justify-content-end">
						<div class="col-3 row m-0 pe-1">
							<button class="btn btn-primary " type="submit">Save</button>
						</div>
						<div class="col-3 row m-0 ps-1">
							<a class="btn btn-dark " href="{{ route('users.index') }}">Cancel</a>
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
		// Front NRC Image Preview
		$("#front_nrc").change(function() {
			var reader = new FileReader();
			reader.onload = function() {
				$("#front_nrc-preview").attr("src", reader.result).show();
				$("#front_nrc-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Front NRC Image Cancel
		$("#front_nrc-cancel-btn").click(function() {
			$("#front_nrc").val("");
			$("#front_nrc-preview").hide();
			$("#front_nrc-cancel-btn").hide();
		});

		// Back NRC Image Preview
		$("#back_nrc").change(function() {
			var reader = new FileReader();
			reader.onload = function() {
				$("#back_nrc-preview").attr("src", reader.result).show();
				$("#back_nrc-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Back NRC Image Cancel
		$("#back_nrc-cancel-btn").click(function() {
			$("#back_nrc").val("");
			$("#back_nrc-preview").hide();
			$("#back_nrc-cancel-btn").hide();
		});

		// Front Licence Image Preview
		$("#front_license").change(function() {
			var reader = new FileReader();
			reader.onload = function() {
				$("#front_license-preview").attr("src", reader.result).show();
				$("#front_license-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Front Licence Image Cancel
		$("#front_license-cancel-btn").click(function() {
			$("#front_license").val("");
			$("#front_license-preview").hide();
			$("#front_license-cancel-btn").hide();
		});

		// Back Licence Image Preview
		$("#back_license").change(function() {
			var reader = new FileReader();
			reader.onload = function() {
				$("#back_license-preview").attr("src", reader.result).show();
				$("#back_license-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Back Licence Image Cancel
		$("#back_license-cancel-btn").click(function() {
			$("#back_license").val("");
			$("#back_license-preview").hide();
			$("#back_license-cancel-btn").hide();
		});

		// profile Image Preview
		$("#profile_image").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#profile_image-preview").attr("src", reader.result).show();
				$("#profile_image-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		})
		// profile Image Cancel
		$("#profile_image-cancel-btn").click(function() {
			$("#profile_image").val("");
			$("#profile_image-preview").hide();
			$("#profile_image-cancel-btn").hide();
		});

		$("#vehicle_image").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#vehicle_image-preview").attr("src", reader.result).show();
				$("#vehicle_image-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		})
		// Vehicle Image Cancel
		$("#vehicle_image-cancel-btn").click(function() {
			$("#vehicle_image").val("");
			$("#vehicle_image-preview").hide();
			$("#vehicle_image-cancel-btn").hide();
		});
	</script>
@endpush
