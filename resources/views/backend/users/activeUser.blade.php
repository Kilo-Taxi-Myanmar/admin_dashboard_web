@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-md-3">
			<form action="{{ route('users.search.active') }}" method="GET">
				@csrf
				<div class="input-group mb-3">
					<button class="btn btn-outline-secondary text-dark" type="submit" style="border:1px solid #ced4da"><i
							class="fa-solid fa-magnifying-glass">
						</i>
					</button>
					<input class="form-control" name="key" type="text" placeholder="Search">
				</div>
			</form>
		</div>
		<div class="table-responsive small">
			<table class="table table-striped table-hover align-middle">
				<thead class=" table-secondary" style="border-bottom:1px solid #ccc">
					<tr class="">
						<th>No</th>
						<th>Driver ID</th>
						<th>Driver Name</th>
						<th>Vehicle number</th>
						<th>Phone number</th>
						<th>Address</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
					@foreach ($users as $key => $user)
						<tr class="">
							<td scope="row">{{ $key + 1 }}</td>
							<td>{{ $user->driver_id }}</td>
							<td>
								<a class="text-dark text-decoration-none" href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a>
							</td>
							<td>{{ $user->vehicle ? $user->vehicle->vehicle_plate_no : '' }}</td>
							<td>{{ $user->phone }}</td>
							<td>{{ $user->address }}</td>
							<td>
								@if ($user->status == 'active')
									<span class="text-success">Active</span>
								@else
									<span class="text-danger">Pending</span>
								@endif
							</td>
							<td>
								<span>
									<form class="d-inline" action="{{ route('users.turn-pending', $user->id) }}" method="POST">
										@csrf
										@method('PUT')
										<button class="btn btn-sm btn-danger p-1" type="submit">
											Pending
										</button>
									</form>
								</span>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
			<div class="row m-0 justify-content-between">
				<div class="col-md-2 ps-0">
					<p class=" text-muted">Total: {{ $usersCount }}</p>
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
