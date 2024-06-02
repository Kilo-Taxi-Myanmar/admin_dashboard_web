@extends('layouts.app')
@push('style')
<style>
	.pending,.accepted,.canceled,.completed{
		text-align: center;
		border-radius: 10px;
		color: #fff;
	}
	.pending{
		background: #fc760894;

	}
	.accepted{
		background: #83e405;
	}
	.canceled{
		background: rgb(240, 40, 40);
	}
	.completed{
		background: #0fccee;
	}
</style>
@endpush

@section('content')
	<div class="container">
		<div class="col-md-3">
			<form action="{{ route('trips.search') }}" method="GET">
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
				<thead class="table-secondary align-top" style="border-bottom:1px solid #ccc">
					<tr class="text-center">
						<th>#</th>
						<th>Trip ID</th>
						<th>Driver Name</th>
						<th>Customer Name</th>
						<th>Distance (Km)</th>
						<th>Duration</th>					
						<th>Total Cost (Ks)</th>
						<th>Status</th>
					
						@role('admin')
							<th>Action</th>
						@endrole
					</tr>
				</thead>
				<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
					@foreach ($trips as $key => $trip)
					
						<tr class="text-center">
						
							<td>{{$loop->index +1}}</td>
							<td scope="row"><a class="text-dark text-decoration-none" href="{{route('trip.show',$trip->id)}}">
								ID-{{$trip->id }}
							</a></td>
							

							<td>
								<a class="text-dark text-decoration-none"
									href="{{ route('trip.show', $trip->id) }}">
									{{$trip->user->name}}
								</a>
							</td>
							<td>
								@if($trip->user_id === null)
								-
								@else
								<a class="text-dark text-decoration-none"
									href="{{ route('trip.show', $trip->id) }}">
									@foreach($users as $user)
									 	@if($user->id === $trip->user_id)
											{{$user->name}}
										@endif
									@endforeach
								</a>
								@endif
							</td>
							<td>{{ $trip->distance }} </td>
							<td>{{ $trip->duration }} </td>
							
							<td>{{ $trip->total_cost }}</td>
							<td>
								<div class="{{$trip->status}}">
									{{$trip->status}}
								</div>
							</td>
						
							
							{{-- <td>{{ Carbon\Carbon::parse($trip->created_at)->format('d-m-Y') }}</td> --}}
							@role('admin')
								<td>
									<span>
										<form class="d-inline" action="{{ route('trip.destroy', ['trip' => $trip]) }}" method="POST">
											@csrf
											@method('DELETE')
											<button class="btn btn-reset btn-clear" type="submit">
												<i class="fa-regular fa-trash-can text-danger"></i>
											</button>
										</form>
									</span>
								</td>
							@endrole
						</tr>
					@endforeach

				</tbody>
			</table>
			<div class="row m-0 justify-content-between">
				<div class="col-md-2 ps-0">
					<p class=" text-muted">Total: {{ $tripsCount }}</p>
				</div>

				<div class="col-md-2 pe-0">
					<nav class="row m-0">
						<ul class="pagination pagination-sm justify-content-end p-0">
							<li class="page-item {{ $trips->onFirstPage() ? 'disabled' : '' }}">
								<a class="page-link" id="pre-page-link" href="{{ $trips->previousPageUrl() }}" rel="prev"><</a>
							</li>
							@for ($i = 1 ; $i <= $trips->lastPage() ; $i++)
							<li class="page-item {{ ($trips->currentPage() == $i)? 'active':'' }} ">
								<a class="page-link" id="next-page-link" href="{{ $trips->url($i) }}" rel="next">{{ $i }}</a>
							</li>
						@endfor

							{{-- @if ($trips->lastPage() > 1 && $trips->lastPage() < 10)
                                    @for ($i = 1 ; $i <= $trips->lastPage() ; $i++)
                                        <li class="page-item {{ ($trips->currentPage() == $i)? 'active':'' }} ">
                                            <a class="page-link" id="next-page-link" href="{{ $trips->url($i) }}" rel="next">{{ $i }}</a>
                                        </li>
                                    @endfor
                            @elseif ($trips->lastPage() >= 1)
                                    @for ($i = 1 ; $i <= $trips->lastPage() ; $i=$i+2)
                                        <li class="page-item {{ ($trips->currentPage() == $i)? 'active':'' }} ">
                                            <a class="page-link" id="next-page-link" href="{{ $trips->url($i) }}" rel="next">{{ $i }}</a>
                                        </li>
                                        @if ($trips->currentPage()%2 != 0 && $i < $trips->currentPage() && ($i+2) > $trips->currentPage() )
                                            <li class="page-item active ">
                                                <a class="page-link" id="next-page-link" href="{{ $trips->url($trips->currentPage()) }}" rel="next">{{ $trips->currentPage() }}</a>
                                            </li>
                                        @endif
                                    @endfor
                            @endif --}}

							<li class="page-item {{ $trips->hasMorePages() ? '' : 'disabled' }}">
								<a class="page-link" id="next-page-link" href="{{ $trips->nextPageUrl() }}" rel="next">></a>
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
