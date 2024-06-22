@extends('layouts.app')

@section('content')
	<div class=" container">
		<div class="col-md-3">
			<form action="{{ route('users.search') }}" method="GET">
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
			<table class="table table-striped table-hover">
				<thead class=" table-secondary" style="border-bottom:1px solid #ccc">
					<tr class="">
						<th>#</th>
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
							<td>{{$loop->index + 1 }}</td>

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
								<a class=" text-decoration-none" href="{{ route('users.edit', $user) }}">
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
								</a>
								@role('admin')
									<span>
										<form class="d-inline" action="{{ route('users.destroy', $user->id) }}" method="POST">
											@csrf
											@method('DELETE')
											<button class="btn btn-reset btn-clear" type="submit">
												<i class="fa-regular fa-trash-can text-danger"></i>
											</button>
										</form>
									</span>
								@endcan
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

							@if ($users->lastPage() >= 1)
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
	<script>



        $(document).ready(function (){


        $.ajax({
            url: '/api/all-driver-lists',
            type: "GET",
            success: function (response) {
                console.log(response);

            },
            error: function (response) {
                console.log("Error : ", response);
            }
        });






        })

    </script>
@endpush
