@extends('backend.layouts.master')

@section('title', 'Create Admin')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('admin.admin.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h3 class="mb-4">Create Admin</h3>

                    <div class="form-row">


                        <div class="form-group col-md-6">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Full Name" required />
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="form-control" placeholder="Username" required />
                            @error('username')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Email" required />
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password" required />
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirm Password" required />
                        </div>

                        <div class="form-group col-md-6">
                            <label for="authorization_id">Nmae Role</label>
                            <select name="role_id" id="authorization_id" class="form-control" required>
                                <option value="">Select Role </option>
                                @foreach($authorizations as $authorization)
                                <option value="{{ $authorization->id }}" {{ old('authorization_id')==$authorization->id
                                    ? 'selected' : '' }}>
                                    {{ $authorization->role }}
                                </option>
                                @endforeach
                            </select>
                            @error('authorization_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                    </div>

                    <div>
                        <button class="btn btn-success" type="submit">Create Admin</button>
                        <a href="{{ route('admin.admin.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
