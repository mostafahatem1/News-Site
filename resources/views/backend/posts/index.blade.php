@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Post List</h1>

    @include('backend.posts.filter')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $index => $post)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $post->title }}</td>
                            <td>
                                @if($post->status == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                                <a href="{{ route('admin.posts.status', $post->id) }}"
                                    class="btn btn-sm btn-warning ml-2" title="Toggle Status">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </td>
                            <td>{{ $post->user->name ?? '-' }}</td>
                            <td>{{ $post->category->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.posts.show', [$post->id,'page' => request()->page]) }}"
                                    class="btn btn-info btn-sm">Show</a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm delete-post-btn"
                                        data-post-id="{{ $post->id }}">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-left mt-3">
                {{ $posts->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection
