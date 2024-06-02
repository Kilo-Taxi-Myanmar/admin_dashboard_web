@extends('layouts.app')

@section('content')
	<div class=" container">
		<div class="row">
			<div class="col-md-3">
				<form action="{{ route('users.summary.search') }}" method="GET">
					@csrf
					<div class="input-group mb-3">
						<button class="btn btn-outline-secondary text-dark" type="submit" style="border:1px solid #ced4da">
                            <i class="fa-solid fa-magnifying-glass">
							</i>
						</button>
						<input class="form-control" name="key" type="text" placeholder="Search">
					</div>
				</form>
			</div>
			<div class="col-md-9">
				<form action="{{ route('users.summary') }}" method="GET">
					<div class="row justify-content-end">
						<div class="col-md-3">
							<select class="form-select" id="type" name="type" aria-label="Default select example">
								<option value="most-balance" selected>Most Balance</option>
								<option value="least-balance">Least Balance</option>
								<option value="maximum-travel">Maximum Travel</option>
								<option value="minimum-travel">Minimum Travel</option>
							</select>
						</div>
						<div class="col-md-3">
							<select class="form-select" id="date" name="date" aria-label="Default select example">
								<option value="today">Today</option>
								<option value="start-10">start-10</option>
								<option value="10-20">10-20</option>
								<option value="20-end">20-end</option>
								<option value="this-month">This Month</option>
								<option value="this-year">This Year</option>
							</select>
						</div>
						<div class="col-md-1">
							<input class="btn btn-primary" type="submit" value="Sort">
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-sm text-start">
				<thead class=" table-secondary" style="border-bottom:1px solid #ccc">
					<tr class="">
						<th>Driver ID</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Trip</th>
						<th>Balence</th>
					</tr>
				</thead>
				<tbody class="table-group-divider" style="border-top:10px solid #ffffff">
					@foreach ($users as $user)
						<tr @if ($user->balance < 1000) class="table-danger" @endif>
							<td>{{ $user->driver_id }}</td>
							<td>
								<a class="text-dark text-decoration-none" href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a>
							</td>
							<td>{{ $user->phone }}</td>
							<td>{{ $user->total_trips }}</td>
							<td>{{ $user->balance }}</td>
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
								<a class="page-link" id="pre-page-link" href="{{ $users->previousPageUrl() }}" rel="prev"><i class="fa-solid fa-chevron-left"></i></a>
							</li>

							@if ($users->lastPage() > 1)
								@for ($i = 1; $i <= $users->lastPage(); $i++)
									<li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }} ">
										<a class="page-link" id="link_{{ $i }}" href="{{ $users->url($i) }}"
											rel="next">{{ $i }}</a>
									</li>
								@endfor
							@endif

							<li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
								<a class="page-link" id="next-page-link" href="{{ $users->nextPageUrl() }}" rel="next"><i class="fa-solid fa-chevron-right"></i></a>
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
		const searchParams = new URLSearchParams(window.location.search);

		const type = searchParams.get('type');
		const date = searchParams.get('date');
        const token = searchParams.get('_token');
        const key = searchParams.get('key');
		const typeElement = document.getElementById('type');
		const dateElement = document.getElementById('date');

		for (let i = 0; i < typeElement.options.length; i++) {
			const option = typeElement.options[i];
			if (option.value === type) {
				option.selected = true;
				break;
			}
		}

		for (let i = 0; i < dateElement.options.length; i++) {
			const option = dateElement.options[i];
			if (option.value === date) {
				option.selected = true;
				break;
			}
		}
		const pre_page_link = document.querySelector('#pre-page-link');
		const next_page_link = document.querySelector('#next-page-link');

		const lastPage = {{ $users->lastPage() }};
		const mid_links = [];
		for (let i = 1; i <= lastPage; i++) {
			mid_links.push(document.querySelector(`#link_${i}`))
		}
		const Links = [pre_page_link, next_page_link, ...mid_links];

		Links.forEach(link => {
			let href = link.getAttribute('href');
			if (href !== '' && type != null && date != null) {
				let url = new URL(href);
				let params = new URLSearchParams(url.search);

				params.append("type", type);
				params.append("date", date);

				url.search = params.toString();
				link.setAttribute('href', url.href);
			}
		});

        Links.forEach(link => {
			let href = link.getAttribute('href');
			if (href !== '' && key != null) {
				let url = new URL(href);
				let params = new URLSearchParams(url.search);

				params.append("_token", token);
				params.append("key", key);

				url.search = params.toString();
				link.setAttribute('href', url.href);
			}
		});
	</script>
@endpush
