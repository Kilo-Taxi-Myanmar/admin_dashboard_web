@extends('layouts.app')

@section('content')
	<div class=" container">
		<div class="col-md-3">
			<form action="{{ route('topup.search') }}" method="GET">
				@csrf
				<div class="input-group mb-3">
					<button class="btn btn-outline-light" type="submit" style="border:1px solid #ced4da"><i
							class="fa-solid fa-magnifying-glass">
						</i>
					</button>
					<input class="form-control" name="key" type="text" placeholder="Search">
				</div>
			</form>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<thead class=" table-secondary" style="border-bottom:1px solid #ccc">
					<tr class="">
						<th>Driver_id</th>
						<th>Driver Name</th>
						<th>Phone number</th>
						<th>Balence</th>
						<th class=" text-center">Top Up</th>
					</tr>
				</thead>
				<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
					@foreach ($users as $key => $user)
						<tr @if ($user->balance < 1000) class="text-danger" @endif >
							<td scope="row">{{ $user->driver_id }}</td>
							<td>{{ $user->name }}</td>
							<td>{{ $user->phone }}</td>
							<td>{{ $user->balance . ' ks' }}</td>
							<td style=" min-width:100px">
								<form action="{{ route('topup.add') }}" method="POST">
									@csrf
									<div class="row">
										<div class="col-md-8">
											<input class="form-control col-md-6 col-12 p-0" id="amount" name="amount" type="text" placeholder="">
											<input name="user_id" type="hidden" value="{{ $user->id }}">
										</div>
										<div class="col-md-4 d-flex justify-content-center gap-2">
											<span>
												<button class=" btn-clear border-0" type="reset">
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
											</span>
											<span>
												<button class="btn-clear border-0" type="submit">
													<svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path
															d="M15.0519 8.49999C15.0519 4.88137 12.1185 1.94791 8.49984 1.94791C4.88122 1.94791 1.94775 4.88137 1.94775 8.49999C1.94775 12.1186 4.88122 15.0521 8.49984 15.0521C12.1185 15.0521 15.0519 12.1186 15.0519 8.49999Z"
															stroke="#52CB08" stroke-opacity="0.73" stroke-width="1.5" />
														<path d="M6.375 8.50001L7.79167 9.91668L10.625 7.08334" stroke="#52CB08" stroke-opacity="0.73"
															stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
													</svg>
												</button>
											</span>
										</div>
									</div>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div class="row m-0 justify-content-between">
				<div class="col-md-2 ps-0">
					<p class=" text-muted">Total: {{ $userCount }}</p>
				</div>

				<div class="col-md-2 pe-0">
					<nav class="row m-0">
                        <ul class="pagination pagination-sm justify-content-end p-0">
                            <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
								<a class="page-link" id="pre-page-link" href="{{ $users->previousPageUrl() }}" rel="prev"><</a>
							</li>

							@if ($users->lastPage() > 1)
                                    @for ($i = 1 ; $i <= $users->lastPage() ; $i++)
                                        <li class="page-item {{ ($users->currentPage() == $i)? 'active':'' }} ">
                                            <a class="page-link" id="next-page-link" href="{{ $users->url($i) }}" rel="next">{{ $i }}</a>
                                        </li>
                                    @endfor
                            @endif

							<li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
								<a class="page-link" id="next-page-link" href="{{ $users->nextPageUrl() }}" rel="next">></a>
							</li>
                        </ul>
					</nav>
				</div>

			</div>

		</div>

	</div>
@endsection
@push('script')
	<script></script>
@endpush
