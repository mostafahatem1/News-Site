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
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Enter Verification Code?</h1>
                                </div>
                                <form method="POST" action="{{ route('admin.verify-code') }}" class="user">
                                    @csrf
                                    <input type="hidden" name="email"
                                        value="{{ isset($email) ? $email : old('email') }}">
                                    <div class="form-group">
                                        <input type="text" name="token" value="{{ old('token') }}"
                                            class="form-control form-control-user"
                                            placeholder="Enter Verification Code...">
                                        @error('token')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <button type="submit"class="btn btn-primary btn-user btn-block">
                                        Verification Code
                                    </button>
                                </form>
                                <hr>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
