@extends('backend.layouts.master')

@section('title', 'Show Category')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Category Details</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table w-50">
                <tr>
                    <th>Name:</th>
                    <td>{{ $category->name }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        @if($category->status == 1)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
            </table>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mt-3">Back to Category List</a>
        </div>
    </div>
</div>
@endsection