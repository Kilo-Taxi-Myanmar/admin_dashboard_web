@extends('layouts.app')

@section('content')
	<div class=" container">
		<div class="col-md-3">
			<form action="{{ route('topup.history.search') }}" method="GET">
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
						<th>No</th>
						<th>Driver Name</th>
						<th>Vehicle number</th>
						<th>Phone number</th>
						<th>Top UP</th>
					</tr>
				</thead>
				<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
					@foreach ($transactions as $key => $transaction)
						<tr class="">
							<td scope="row">{{ $transaction->user->driver_id }}</td>
							<td>{{ $transaction->user->name }}</td>
							<td>{{ $transaction->user->vehicle ? $transaction->user->vehicle->vehicle_plate_no : '' }}</td>
							<td>{{ $transaction->user->phone }}</td>
							<td>{{ $transaction->amount }}</td>
						</tr>
					@endforeach

				</tbody>
			</table>
			<div class="row m-0 justify-content-between">
				<div class="col-md-2 ps-0">
					<p class=" text-muted">Total: {{ $transactionCount }}</p>
				</div>

				<div class="col-md-2 pe-0">
					<nav class="row m-0">
						<ul class="pagination pagination-sm justify-content-end p-0">
							<li class="page-item {{ $transactions->onFirstPage() ? 'disabled' : '' }}">
								<a class="page-link" id="pre-page-link" href="{{ $transactions->previousPageUrl() }}" rel="prev"><</a>
							</li>

							@if ($transactions->lastPage() > 1)
                                    @for ($i = 1 ; $i <= $transactions->lastPage() ; $i++)
                                        <li class="page-item {{ ($transactions->currentPage() == $i)? 'active':'' }} ">
                                            <a class="page-link" id="next-page-link" href="{{ $transactions->url($i) }}" rel="next">{{ $i }}</a>
                                        </li>
                                    @endfor
                            @endif

							<li class="page-item {{ $transactions->hasMorePages() ? '' : 'disabled' }}">
								<a class="page-link" id="next-page-link" href="{{ $transactions->nextPageUrl() }}" rel="next">></a>
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
