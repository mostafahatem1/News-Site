@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Category List</h1>

    {{-- You may add a filter for categories if needed --}}
    @include('backend.categories.filter')

    <button id="btnCreateCategory" type="button" class="btn btn-success m-3" data-toggle="modal"
        data-target="#createCategoryModal">
        Create Category
    </button>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Posts Count</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->posts_count ?? 0 }}</td>
                            <td>
                                @if ($category->status == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                                <a href="{{ route('admin.categories.status', $category->id) }}"
                                    class="btn btn-sm btn-warning ml-2" title="Toggle Status">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </td>
                            <td>{{ $category->created_at ? $category->created_at->format('Y-m-d H:i') : '' }}</td>
                            <td>
                                <a href="{{ route('admin.categories.show', $category->id) }}"
                                    class="btn btn-info btn-sm">Show</a>
                                <a id="btnEditCategory-{{ $category->id }}" data-toggle="modal"
                                    data-target="#editCategoryModal-{{ $category->id }}" href="javascript:void(0);"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm delete-user-btn"
                                        data-user-id="{{ $category->id }}">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @include('backend.categories.edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-left mt-3">
                {{ $categories->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@include('backend.categories.create')
@endsection