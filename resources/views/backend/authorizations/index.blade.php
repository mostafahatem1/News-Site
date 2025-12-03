@extends('backend.layouts.master')

@section('title', 'Admin - Authorizations')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Role List</h1>

    {{-- You may add a filter for authorizations if needed --}}
    @include('backend.authorizations.filter')

    <button id="btnCreateRole" type="button" class="btn btn-success m-3" data-toggle="modal"
        data-target="#createRoleModal">
        Create Role
    </button>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">authorizations</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>user</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($authorizations as $index => $authorization)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $authorization->role }}</td>
                            <td>

                                @if (!empty($authorization->permissions))
                                @foreach ($authorization->permissions as $permission)
                                <span class="badge badge-info">
                                    {{ $permission }}
                                </span>
                                @endforeach
                                @else
                                <span class="text-muted">No Permissions</span>
                                @endif

                            </td>
                            <td>{{ $authorization->admin->count() }}</td>
                            <td>{{ $authorization->created_at ? $authorization->created_at->format('Y-m-d H:i') : '' }}
                            </td>
                            <td>

                                <a id="btnEditauthorization-{{ $authorization->id }}" data-toggle="modal"
                                    data-target="#editRoleModal-{{ $authorization->id }}" href="javascript:void(0);"
                                    class="btn btn-primary btn-sm">Edit</a>
                               <form action="{{ route('admin.authorizations.destroy', $authorization->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm delete-user-btn"
                                        data-user-id="{{ $authorization->id }}">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @include('backend.authorizations.edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-left mt-3">
                {{ $authorizations->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@include('backend.authorizations.create')
@endsection
