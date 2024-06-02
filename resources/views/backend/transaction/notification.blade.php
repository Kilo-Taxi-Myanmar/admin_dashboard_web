@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-between mb-2 px-5">
			<div class="col text-start {{ $notificationsUnread>0?'text-danger':'' }}">
				<span class="me-2">Unread</span> : {{ $notificationsUnread }}
			</div>
			<div class="col text-end pe-5 me-5">
				<span class="me-5"><span class="me-2">Total</span> : {{ $notificationsCount }}</span>
			</div>
		</div>
		<div class="row px-5 m-0 pb-3">
            @foreach ($notifications as $key => $noti)
                <div class="d-flex flex-row justify-content-between m-0 px-2">
                    <div class="col-md-11 row alert border @if ($noti->status == 'unread') alert-primary @endif py-2 px-1 my-1 rounded justify-content-between text-center">
                        <div class="col">#{{ $noti->user->driver_id }}</div>
                        <div class="col text-start">{{ $noti->user->name }}</div>
                        <div class="col text-end">{{ $noti->amount.' Ks' }}</div>
                        <div class="col">{{ $noti->service }}</div>
                        <div class="col">{{ Carbon\Carbon::parse($noti->created_at)->diffForHumans() }}</div>
                        <div class="col">
                            <!-- Modal -->
                            <div class="modal fade" id="topUpNotiModal{{ $noti->id }}"
                                aria-labelledby="topUpNotiModal{{ $noti->id }}Label" aria-hidden="true" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header px-3 py-2">
                                            <h5 class="modal-title" id="topUpNotiModal{{ $noti->id }}Label">TopUp Notification</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group ">
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <h6 class=" text-muted">TopUp Username:</h6> <span class="">{{ $noti->user->name }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <h6 class=" text-muted">Service:</h6><span>{{ $noti->service }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <h6 class=" text-muted">Account Username</h6><span>{{ $noti->account_name }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <h6 class=" text-muted">Transfer Phone Number</h6><span>{{ $noti->phone }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <h6 class=" text-muted">Amount</h6><span>{{ $noti->amount }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-center">
                                                    <img class="img-fluid img-thumbnail rounded-top"
                                                        src="{{ asset('uploads/images/screenshots/' . $noti->screenshot) }}" alt="Top Up Screenshot"
                                                        width="100%">
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            @if ($noti->status == 'unread')
                                                <button class="btn btn-dark" data-bs-dismiss="modal" type="button">Close</button>
                                                <form action="{{ route('topup.done', $noti) }}" method="post">
                                                    @csrf
                                                    <button class="btn btn-primary" type="submit">Done</button>
                                                </form>
                                            @else
                                                <button class="btn btn-dark" data-bs-dismiss="modal" type="button">Close</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($noti->status == 'unread')
                                <button class="btn btn-primary px-2 py-0" data-bs-toggle="modal"
                                    data-bs-target="#topUpNotiModal{{ $noti->id }}" type="button">
                                    check
                                </button>
                            @else
                                <button class="btn btn-secondary px-2 py-0 " data-bs-toggle="modal"
                                    data-bs-target="#topUpNotiModal{{ $noti->id }}" type="button">
                                    Done
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-1 py-2 my-1 text-center">
                        <form class="d-inline" action="{{ route('notifiaction.destroy', ['notifiaction' => $noti]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-reset btn-clear " type="submit">
                                <i class="fa-regular fa-trash-can text-danger"></i>
                            </button>
                        </form>
                    </div>
                </div>
			@endforeach
		</div>
        <div class="row m-0 justify-content-between px-5 pb-3">
            <nav class="row m-0 py-2 px-5">
                <ul class="pagination pagination-sm justify-content-end p-0">
                    <li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" id="pre-page-link" href="{{ $notifications->previousPageUrl() }}" rel="prev"><</a>
                    </li>

                    @if ($notifications->lastPage() > 1 && $notifications->lastPage() < 10)
                            @for ($i = 1 ; $i <= $notifications->lastPage() ; $i++)
                                <li class="page-item {{ ($notifications->currentPage() == $i)? 'active':'' }} ">
                                    <a class="page-link" id="next-page-link" href="{{ $notifications->url($i) }}" rel="next">{{ $i }}</a>
                                </li>
                            @endfor
                    @elseif ($notifications->lastPage() >= 10)
                            @for ($i = 1 ; $i <= $notifications->lastPage() ; $i=$i+2)
                                <li class="page-item {{ ($notifications->currentPage() == $i)? 'active':'' }} ">
                                    <a class="page-link" id="next-page-link" href="{{ $notifications->url($i) }}" rel="next">{{ $i }}</a>
                                </li>
                            @endfor
                    @endif

                    <li class="page-item {{ $notifications->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" id="next-page-link" href="{{ $notifications->nextPageUrl() }}" rel="next">></a>
                    </li>
                </ul>
            </nav>
        </div>

	</div>
@endsection
@push('script')
	<script></script>
@endpush
