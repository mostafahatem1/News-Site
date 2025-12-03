@extends('frontend.layouts.master')

@section('content')
<!-- Forgot Password Start -->
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <h3 class="mb-4">Forgot Password</h3>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-row">
                        <!-- Email Field -->
                        <div class="form-group col-md-12">
                            <label for="email">Email Address</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}"
                                   placeholder="Enter your email" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <button class="btn" type="submit">Send Password Reset Link</button>
                    </div>

                    <div class="m-3">
                        @if (Route::has('login'))
                        <a href="{{ route('frontend.login') }}">Back to Login</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Forgot Password End -->
@endsection
