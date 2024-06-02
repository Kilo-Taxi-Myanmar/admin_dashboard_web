@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-md-12 table-responsive p-3">
			<table class="table table-striped text-center">
				<thead>
					<tr id="">
						<th scope="col">Fee Type</th>
						<th scope="col">Amount</th>
						@role('admin')
							<th scope="col">Action</th>
						@endrole
					</tr>
				</thead>
				<tbody>
					<tr class="" id="Initial-fee-tr">
						<td>Waiting Fee</td>
						<td>{{ $waitingFee }}</td>
						@role('admin')
							<td>
								<span class="me-2">
									<button class=" btn-clear" onclick="showForm()">
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
							</td>
						@endrole
					</tr>
					@role('admin')
						<tr class="d-none" id="Initial-fee-form">
							<form action="{{ route('system.update.waitingFee') }}" method="post">
								@csrf
								<td>Waiting Fee</td>
								<td><input class="form-control-plaintext p-1" name="waitingFee" type="text" value="{{ $waitingFee }}"></td>
								<td>

									<button class="btn-clear border-0 pe-1" type="submit">
										<svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path
												d="M15.0519 8.49999C15.0519 4.88137 12.1185 1.94791 8.49984 1.94791C4.88122 1.94791 1.94775 4.88137 1.94775 8.49999C1.94775 12.1186 4.88122 15.0521 8.49984 15.0521C12.1185 15.0521 15.0519 12.1186 15.0519 8.49999Z"
												stroke="#52CB08" stroke-opacity="0.73" stroke-width="1.5" />
											<path d="M6.375 8.50001L7.79167 9.91668L10.625 7.08334" stroke="#52CB08" stroke-opacity="0.73"
												stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
										</svg>
									</button>

									<button class="btn-clear border-0" type="button" onclick="hideForm()">
										<svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.91683 7.08334L7.0835 9.91668" stroke="#FF0606" stroke-opacity="0.55" stroke-width="1.5"
												stroke-linecap="round" />
											<path d="M9.91683 9.91668L7.0835 7.08334" stroke="#FF0606" stroke-opacity="0.55" stroke-width="1.5"
												stroke-linecap="round" />
											<path
												d="M15.0519 8.49999C15.0519 4.88137 12.1185 1.94791 8.49984 1.94791C4.88122 1.94791 1.94775 4.88137 1.94775 8.49999C1.94775 12.1186 4.88122 15.0521 8.49984 15.0521C12.1185 15.0521 15.0519 12.1186 15.0519 8.49999Z"
												stroke="#FF0606" stroke-opacity="0.55" stroke-width="1.5" />
										</svg>
									</button>

								</td>
							</form>
						</tr>
					@endrole
				</tbody>
			</table>
		</div>
	</div>
@endsection
@push('script')
	<script>
		function showForm() {
			document.querySelector('#Initial-fee-form').classList.remove('d-none');
			document.querySelector('#Initial-fee-tr').classList.add('d-none');
		}

		function hideForm() {
			document.querySelector('#Initial-fee-form').classList.add('d-none');
			document.querySelector('#Initial-fee-tr').classList.remove('d-none');
		}
	</script>
@endpush
