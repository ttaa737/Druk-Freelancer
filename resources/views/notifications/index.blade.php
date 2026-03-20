@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fa fa-bell me-2"></i>Notifications</h5>
    @if($notifications->where('read_at', null)->count())
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        <button class="btn btn-sm btn-outline-secondary">Mark all as read</button>
    </form>
    @endif
</div>
@forelse($notifications as $notif)
<div class="card mb-2 {{ is_null($notif->read_at) ? 'border-primary border-start border-3' : '' }}">
    <div class="card-body py-3 px-4">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-{{ is_null($notif->read_at) ? 'primary' : 'light' }} text-{{ is_null($notif->read_at) ? 'white' : 'secondary' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                    <i class="fa fa-{{ $notif->data['icon'] ?? 'bell' }} small"></i>
                </div>
                <div>
                    <p class="mb-0 small {{ is_null($notif->read_at) ? 'fw-semibold' : '' }}">{{ $notif->data['message'] ?? 'Notification' }}</p>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                @if(is_null($notif->read_at))
                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">@csrf <button class="btn btn-sm btn-outline-primary" title="Mark read"><i class="fa fa-check"></i></button></form>
                @endif
                @if(isset($notif->data['url']))
                <a href="{{ $notif->data['url'] }}" class="btn btn-sm btn-outline-secondary">View</a>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa fa-times"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <i class="fa fa-bell-slash fa-3x mb-3 opacity-25"></i>
    <p>You're all caught up!</p>
</div>
@endforelse
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
