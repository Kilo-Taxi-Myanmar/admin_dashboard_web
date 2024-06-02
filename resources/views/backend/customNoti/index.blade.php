@extends('layouts.app')

@section('content')
	<div class="container p-5">
		<div class="row justify-content-center align-items-stretch">
			<div class="col-lg-4 mb-3 ">
				<div class="card mb-3 h-100">
					<div class="card-body">
						<form method="POST" action="{{ route('notification.send') }}">
							@csrf
							<div class="mb-2">
								<label class="col-md-6 col-form-label" for="title">{{ __('Notification Title') }}</label>
								<input class="form-control @error('title') is-invalid @enderror" id="title" name="title" type="title"
									required placeholder="Title">

								@error('title')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>

							<div class="mb-2">
								<label class="col-md-6 col-form-label" for="body">{{ __('Description') }}</label>

								<div class="input-group">
									<textarea class="form-control @error('body') is-invalid @enderror" id="" name="body" cols="30"
									 rows="10"></textarea>
								</div>
								@error('body')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>

							<div class="row mb-0">
								<div class="col-mb-12 p-3 d-flex justify-content-end">
									<button class="btn btn-primary" type="submit">
										<i class="fa-solid fa-paper-plane me-2"></i>{{ __('Send') }}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-8 ">
				<div class="card mb-3 h-100">
					<div class="card-body mb-3">
						<span class=""><i class="fa-solid fa-bell me-2"></i>Notifications History</span>
						<div class="table-responsive">
							<table class="table align-middle">
								<thead>
									<tr>
										<td>Title</td>
										<td>Description</td>
										<td>Date</td>
									</tr>
								</thead>
								<tbody>
									@foreach ($notifications as $noti)
										<tr>
											<td>{{ $noti->title }}</td>
											<td>{{ $noti->body }}</td>
											<td>{{ Carbon\Carbon::parse($noti->created_at)->format('d-m-Y') }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-body position-relative">
						<nav class="row m-0 position-absolute bottom-0 end-0 me-3">
							<ul class="pagination pagination-sm justify-content-end p-0 small">
								<li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
									<a class="page-link" href="{{ $notifications->previousPageUrl() }}" rel="prev">&laquo;</a>
								</li>

								<li class="page-item active">
									<a class="page-link"
										href="{{ $notifications->url($notifications->currentPage()) }}">{{ $notifications->currentPage() }}</a>
								</li>

								<li class="page-item {{ $notifications->hasMorePages() ? '' : 'disabled' }}">
									<a class="page-link" href="{{ $notifications->nextPageUrl() }}" rel="next">&raquo;</a>
								</li>
							</ul>
						</nav>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('script')
@endpush
