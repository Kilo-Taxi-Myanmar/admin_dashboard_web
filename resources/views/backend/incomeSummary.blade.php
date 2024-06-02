@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center ">
			<div class="col-md-10 shadow table-responsive p-5 mb-3">
                <div class="d-flex flex-row align-items-center justify-content-center text-white gap-2 mb-3">
					<div class="col bg-primary shadow-sm rounded p-3 text-center">
						<span class="small">Total Income</span>
						<h3>{{ $totalIncome }}<small class="ms-2">Ks</small></h3>
					</div>
					<div class="col bg-success shadow-sm rounded p-3 text-center">
						<span class="small">Total Users</span>
						<h3>{{ $totalUsers }}<small class="ms-1"><i class="fa-solid fa-users"></i></small></h3>
					</div>
					<div class="col bg-info shadow-sm rounded p-3 text-center">
						<span class="small">Total Trips</span>
						<h3>{{ $totalTrips }}<small class="ms-1"><i class="fa-solid fa-location-dot"></i></small></h3>
					</div>
				</div>
				<div class="row justify-content-between align-items-center">
					<div class="col-md-3 ">
						<h5 class="m-0">Income Reoprt</h5>
					</div>
					<div class="col-md-3 ">
						<div class="input-group mb-3 justify-content-end">
							<div class="btn-group">
								<button class="btn btn-secondary dropdown-toggle" id="triggerId" data-bs-toggle="dropdown" type="button"
									aria-haspopup="true" aria-expanded="false">
									@if (url()->current() === route('income.summary', 'date'))
										By Date
									@elseif (url()->current() === route('income.summary', 'month'))
										By Month
									@else
										By Year
									@endif
								</button>
								<div class="dropdown-menu dropdown-menu-start" aria-labelledby="triggerId">
									<a class="dropdown-item" href="{{ route('income.summary', 'date') }}">By Date</a>
									<a class="dropdown-item" href="{{ route('income.summary', 'month') }}">By Month</a>
									<a class="dropdown-item" href="{{ route('income.summary', 'year') }}">By Year</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-sm table-striped table-hover align-middle text-center">
					<thead class=" table-secondary" style="border-bottom:1px solid #ccc">
						<tr class="">
                            <th class=" px-5 text-end">Date</th>
							<th class=" px-5 text-end">Topup</th>
							<th class=" px-5 text-end">Commission</th>
						</tr>
					</thead>
					<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
						@foreach ($incomes as $income)
							<tr class="">
                                <td scope="row" class=" px-5 text-end">{{ $income['date'] }}</td>
								<td scope="row" class=" px-5 text-end">{{ $income['topup'] }}</td>
								<td scope="row" class=" px-5 text-end">{{ $income['commission'] }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
            <div class="col-md-10 m-0 ">
				<div class="row justify-content-end my-3">
                    <div class="col-md-2 pe-0">
                        <nav class="row m-0">
                            <ul class="pagination pagination-sm justify-content-end p-0">
                                <li class="page-item {{ $incomes->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" id="pre-page-link" href="{{ $incomes->previousPageUrl() }}" rel="prev"><</a>
                                </li>

                                @if ($incomes->lastPage() > 1)
                                        @for ($i = 1 ; $i <= $incomes->lastPage() ; $i++)
                                            <li class="page-item {{ ($incomes->currentPage() == $i)? 'active':'' }} ">
                                                <a class="page-link" id="next-page-link" href="{{ $incomes->url($i) }}" rel="next">{{ $i }}</a>
                                            </li>
                                        @endfor
                                @endif

                                <li class="page-item {{ $incomes->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" id="next-page-link" href="{{ $incomes->nextPageUrl() }}" rel="next">></a>
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
	<script></script>
@endpush
