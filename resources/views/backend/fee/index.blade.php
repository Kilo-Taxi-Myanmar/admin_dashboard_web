@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			@role('admin')
				<div class="col-md-10 p-3 mb-2">
					<button class="btn btn-primary" id="fee-add-form-btn" type="button" onclick="showForm()">
						Add Fee
					</button>
				</div>
				<div class="col-md-10 p-3 mb-2 border d-none" id="fee-add-form">
					<form action="{{ route('fee.store') }}" method="post">
						@csrf
						<div class="row">
							<div class=" col-md-5">
								<label for="fee-type">Fee Type</label>
								<input class="form-control @error('type') is-invalid @enderror" id="fee-type" name="type" type="text"
									value="{{ old('type') }}" required autocomplete="type" autofocus>
								@error('type')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class=" col-md-5">
								<label for="amount">Amount</label>
								<input class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" type="number"
									value="{{ old('amount') }}" required autocomplete="amount" autofocus>
								@error('amount')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class="col-md-2 d-flex justify-content-around align-items-end p-3 p-md-0">
								<button class=" btn btn-primary " type="submit">
									<i class="fa-solid fa-check"></i>
								</button>
								<button class=" btn btn-dark " type="button" onclick="hideForm()">
									<i class="fa-solid fa-xmark"></i>
								</button>
							</div>
						</div>
					</form>
				</div>
			@endrole
			<div class="col-md-10 table-responsive p-3">
				<table class="table table-striped">
					<thead>
						<tr class="">
							<th scope="col">Fee Type</th>
							<th scope="col">Amount</th>
							@role('admin')
								<th scope="col">Action</th>
							@endrole
						</tr>
					</thead>
					<tbody>
						@foreach ($fees as $fee)
							<tr id="fee-edit-tr{{ $fee->id }}">
								<td scope="row">{{ $fee->type }}</td>
								<td>{{ $fee->amount }}</td>
								@role('admin')
									<td>
										<span class="me-2">
											<button class=" btn-clear" onclick="show_edit_Form({{ $fee->id }})">
												<span class="me-2">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M7.33325 14H13.9999" stroke="#524E45" stroke-opacity="0.84" stroke-width="1.5"
															stroke-linecap="round" />
														<path fill-rule="evenodd" clip-rule="evenodd"
															d="M11.2563 3.21584C10.3437 2.41588 8.9494 2.4996 8.14147 3.40332C8.14147 3.40332 4.12591 7.89459 2.73345 9.45352C1.33917 11.0116 2.36121 13.164 2.36121 13.164C2.36121 13.164 4.66267 13.8857 6.03581 12.3495C7.40987 10.8132 11.4457 6.30012 11.4457 6.30012C12.2536 5.3964 12.1681 4.0158 11.2563 3.21584Z"
															stroke="#524E45" stroke-opacity="0.84" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M6.90698 4.86157L9.95038 7.5194" stroke="#524E45" stroke-opacity="0.84" stroke-width="1.5"
															stroke-linecap="round" stroke-linejoin="round" />
													</svg>
												</span>
											</button>
										</span>
										<span>
											<form class="d-inline" action="{{ route('fee.destroy', $fee) }}" method="POST">
												@csrf
												@method('DELETE')
												<button class="btn btn-reset btn-clear" type="submit">
													<i class="fa-regular fa-trash-can text-danger"></i>
												</button>
											</form>
										</span>
									</td>

								</tr>
								<tr class="d-none" id="fee-edit-form-{{ $fee->id }}">
									<form action="{{ route('fee.update', $fee) }}" method="post">
										@csrf
										@method('PUT')

										<td scope="row">
											<input class="form-control p-1 @error('type') is-invalid @enderror" id="fee-type" name="type"
												type="text" value="{{ old('type', $fee->type) }}" required autocomplete="type" autofocus>
											@error('type')
												<div class="invalid-feedback">
													{{ $message }}
												</div>
											@enderror
										</td>
										<td>
											<input class="form-control p-1 @error('amount') is-invalid @enderror" id="amount" name="amount"
												type="number" value="{{ old('amount', $fee->amount) }}" required autocomplete="amount" autofocus>
											@error('amount')
												<div class="invalid-feedback">
													{{ $message }}
												</div>
											@enderror
										</td>
										<td>
											<span class="me-2">
												<button class="btn-clear border-0 pt-1" type="submit">
													<svg width="20" height="20" viewBox="0 0 17 17" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path
															d="M15.0519 8.49999C15.0519 4.88137 12.1185 1.94791 8.49984 1.94791C4.88122 1.94791 1.94775 4.88137 1.94775 8.49999C1.94775 12.1186 4.88122 15.0521 8.49984 15.0521C12.1185 15.0521 15.0519 12.1186 15.0519 8.49999Z"
															stroke="#52CB08" stroke-opacity="0.73" stroke-width="1.5" />
														<path d="M6.375 8.50001L7.79167 9.91668L10.625 7.08334" stroke="#52CB08" stroke-opacity="0.73"
															stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
													</svg>
												</button>
											</span>
											<span class="">
												<button class=" btn-clear border-0 pt-1" type="button" onclick="hide_edit_Form({{ $fee->id }})">
													<svg width="20" height="20" viewBox="0 0 17 17" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path d="M9.91683 7.08334L7.0835 9.91668" stroke="#FF0606" stroke-opacity="0.55" stroke-width="1.5"
															stroke-linecap="round" />
														<path d="M9.91683 9.91668L7.0835 7.08334" stroke="#FF0606" stroke-opacity="0.55" stroke-width="1.5"
															stroke-linecap="round" />
														<path
															d="M15.0519 8.49999C15.0519 4.88137 12.1185 1.94791 8.49984 1.94791C4.88122 1.94791 1.94775 4.88137 1.94775 8.49999C1.94775 12.1186 4.88122 15.0521 8.49984 15.0521C12.1185 15.0521 15.0519 12.1186 15.0519 8.49999Z"
															stroke="#FF0606" stroke-opacity="0.55" stroke-width="1.5" />
													</svg>
												</button>
											</span>
										</td>

									</form>
								</tr>
							@endrole
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
@push('script')
	<script>
		function showForm() {
			document.querySelector('#fee-add-form').classList.remove('d-none');
			document.querySelector('#fee-add-form-btn').classList.add('d-none');
		}

		function hideForm() {
			document.querySelector('#fee-add-form').classList.add('d-none');
			document.querySelector('#fee-add-form-btn').classList.remove('d-none');
		}

		function show_edit_Form(id) {
			document.querySelector('#fee-edit-form-' + id).classList.remove('d-none');
			document.querySelector('#fee-edit-tr' + id).classList.add('d-none');
		}

		function hide_edit_Form(id) {
			document.querySelector('#fee-edit-form-' + id).classList.add('d-none');
			document.querySelector('#fee-edit-tr' + id).classList.remove('d-none');
		}
	</script>
@endpush
