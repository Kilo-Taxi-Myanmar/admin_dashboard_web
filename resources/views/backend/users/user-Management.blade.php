@extends('layouts.app')

@section('content')
	<div class=" container">
		<div class="col-md-3">
			<form action="{{ route('users.search.role') }}" method="GET">
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
		<div class="table-responsive">
			<table class="table table-sm table-striped table-hover text-center">
				<thead class=" table-secondary" style="border-bottom:1px solid #ccc">
					<tr class="">
						<th>Driver ID</th>
						<th>Name</th>
						<th>Phone number</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
					@foreach ($users as $key => $user)
						<form action="{{ route('users.role.change', ['user' => $user]) }}" method="post">
							@csrf
							<tr class="">
								<td scope="row">{{ $user->driver_id }}</td>
								<td>
									<a class="text-dark text-decoration-none" href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a>
								</td>
								<td>{{ $user->phone }}</td>
								<td>
									<select class="form-select form-select-sm" name="role" aria-label="Default select example">
										@foreach ($roles as $role)
											<option value="{{ $role->name }}" @if ($user->roles->first()->name == $role->name) selected @endif>
												{{ $role->name }}
											</option>
										@endforeach
									</select>

								</td>
								<td>
									<!-- Button trigger modal -->
									<button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#User{{ $user->id }}Model"
										type="button">
										Change
									</button>

									<!-- Modal -->
									<div class="modal fade" id="User{{ $user->id }}Model" aria-labelledby="User{{ $user->id }}ModelLabel"
										aria-hidden="true" tabindex="-1">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-body py-5">
													Are you sure you want to change the role of this user?
												</div>
												<div class="modal-footer">
													<button class="btn btn-sm btn-danger" data-bs-dismiss="modal" type="button">Close</button>
													<button class="btn btn-sm btn-success" type="submit">Save changes</button>
												</div>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</form>
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
