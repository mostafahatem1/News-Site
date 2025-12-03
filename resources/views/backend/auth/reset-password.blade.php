@extends('backend.auth.layouts.master')
@section('title', ' Admin - Forgot Password')

@section('content')

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                {{-- Reset Password Form --}}
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Reset Password</h1>
                                </div>
                                <form method="POST" action="{{ route('admin.reset_password.submit') }}" class="user">
                        
                                    @csrf
                                    <input type="hidden" name="email"
                                        value="{{ isset($email) ? $email : old('email') }}">
                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <input type="password" name="password" id="password"
                                            class="form-control form-control-user"
                                            placeholder="New Password">
                                        @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control form-control-user"
                                            placeholder="Confirm New Password">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>
                                {{-- End Reset Password Form --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
