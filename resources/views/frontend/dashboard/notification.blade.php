@extends('frontend.layouts.master')
@section('title', 'Notifications')

@section('breadcrumbs')
@parent
<li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<!-- Dashboard Start-->
<div class="dashboard container">
    <div class="row">
    <!-- Sidebar -->
    @include('frontend.dashboard._sidebar')

    <!-- Main Content -->
    <div class="col-lg-9 col-md-8 main-content">
        <div class="container">
            <h2 class="mb-4">Notifications</h2>
            @if(auth()->user()->notifications->count())
            <form method="POST" action="{{ route('frontend.dashboard.notification.delete_all') }}"
                id="delete-all-notifications-form">
                @csrf
                <button type="button" class="btn btn-danger mb-3 delete-all-notifications-btn">Delete All</button>
            </form>
            @endif
            @forelse (auth()->user()->notifications as $notification)
            <div
                class="notification alert {{ $notification->read_at ? 'alert-secondary' : 'alert-info' }} d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ $notification['data']['link'] }}?notify={{ $notification->id }}" class="text-dark"
                        style="text-decoration:none;">
                        <strong>{{ $notification['data']['commented_by'] }}</strong>
                        <span class="ml-2">{{ Str::limit($notification['data']['post_title'], 50) }}</span>
                    </a>
                    <br>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                        @if (!$notification->read_at)
                        <span class="badge badge-primary ml-1">New</span>
                        @endif
                    </small>
                </div>
                <form method="POST" action="{{ route('frontend.dashboard.notification.delete', $notification->id) }}"
                    id="delete-notification-form-{{ $notification->id }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm ml-2 delete-notification-btn" type="button"
                        data-notification-id="{{ $notification->id }}">Delete</button>
                </form>
            </div>
            @empty
            <div class="alert alert-light text-center">No notifications found.</div>
            @endforelse
        </div>
    </div>
    </div>
</div>
<!-- Dashboard End-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-notification-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.dataset.notificationId;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-notification-form-${notificationId}`).submit();
                    }
                });
            });
        });

        const deleteAllBtn = document.querySelector('.delete-all-notifications-btn');
        if(deleteAllBtn) {
            deleteAllBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete all notifications!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete all!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-all-notifications-form').submit();
                    }
                });
            });
        }
    });


</script>
@endpush
