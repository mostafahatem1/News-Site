@extends('backend.layouts.master')

@section('title', 'Show Contact')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Contact Details</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name</label>
                        <div class="form-control-plaintext">{{ $contact->name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="form-control-plaintext">{{ $contact->email }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <div class="form-control-plaintext">{{ $contact->phone }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title</label>
                        <div class="form-control-plaintext">{{ $contact->title }}</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Body</label>
                <div class="form-control-plaintext">{{   $contact->body }}</div>
            </div>
            <div class="form-group">
                <label>IP Address</label>
                <div class="form-control-plaintext">{{ $contact->ip_address }}</div>
            </div>
            <a href="{{ route('admin.contact') }}" class="btn btn-secondary mt-2">Back</a>
        </div>
    </div>
</div>
@endsection
