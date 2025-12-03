@extends('backend.layouts.master')

@section('title', 'Show User')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">User Details</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ asset('frontend/img/user/'.$user->image) }}" alt="User Image" width="150" height="150"
                        style="object-fit:cover; border-radius:50%;">
                </div>
                <div class="col-md-9">
                    <table class="table">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{ $user->country }}</td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td>{{ $user->city }}</td>
                        </tr>
                        <tr>
                            <th>Street:</th>
                            <td>{{ $user->street }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ $user->gender }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($user->status == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Back to User List</a>
        </div>
    </div>
</div>
@endsection