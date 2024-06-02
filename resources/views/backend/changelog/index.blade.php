@extends('layouts.app')

@section('content')
	<div class=" container-lg">
		<div class="row justify-content-center">
			<div class="col-lg-10 ">
				<div class="row justify-content-between">
					<div class="col-md-6 d-flex justify-content-start align-items-center">
						<button class="btn btn-primary" data-bs-toggle="collapse" href="#log_add" role="button" aria-expanded="false"
							aria-controls="log_add">Add Log</button>
					</div>
					<div class="col-md-6 d-flex justify-content-end align-items-center">
						<div class=" btn-group">
							<a class="btn btn-dark mb-3" href="{{ $app_link }}">
								<small>Download App</small>
							</a>
							<a class="btn btn-outline-dark mb-3" data-bs-toggle="collapse" href="#app_link_update" role="button"
								aria-expanded="false" aria-controls="app_link_update" style="--bs-btn-border-width: 2px;"><i
									class="fa-solid fa-pen-to-square"></i></a>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="collapse" id="app_link_update">
						<div class="card card-body">
							<form action="{{ route('appLink.update') }}" method="POST">
								@csrf
								@method('PUT')
								<div class="row justify-content-center align-items-center">
									<div class="col-10">
										<input class="form-control" name="app_link" type="text" aria-label="app_link" placeholder="app link...">
									</div>
									<div class="col-2">
										<button class=" btn btn-success">Update</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="collapse" id="log_add">
						<div class="card card-body">
							<form action="{{ route('changeLog.add') }}" method="POST">

								@csrf

								<div class="row">
									<div class="col-8 mb-3">
										<textarea class="form-control" id="exampleFormControlTextarea1" name="changes" placeholder="changes..." rows="3"></textarea>
									</div>
									<div class="col-4 mb-3">
										<div class="col mb-2">
											<input class="form-control" name="version" type="text" placeholder="version">
										</div>
										<div class="col">
											<button class="form-control btn btn-primary" type="submit">Add</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-10 table-responsive table-bordered">
				<table class="table align-middle text-center">
					<thead>
						<tr>
							<th scope="col">Version</th>
							<th scope="col">Changes</th>
							<th scope="col">Changed_at</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($changes as $change)
							<tr>
								<td>{{ $change->version }}</td>
								<td>{{ $change->changes }}</td>
								<td>{{ $change->created_at }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endsection
	@push('script')
		<script></script>
	@endpush
