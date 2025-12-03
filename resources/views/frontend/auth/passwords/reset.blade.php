@extends('frontend.layouts.master')

@section('content')
<!-- Reset Password Start -->
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <h3 class="mb-4">Reset Password</h3>

                    <div class="form-row">
                        <!-- Email -->
                        <div class="form-group col-md-12">
                            <label for="email">Email Address</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ $email ?? old('email') }}"
                                   placeholder="Email" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group col-md-12">
                            <label for="password">New Password</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="New Password" required autocomplete="new-password">
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group col-md-12">
                            <label for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" type="password"
                                   class="form-control"
                                   name="password_confirmation"
                                   placeholder="Confirm Password" required autocomplete="new-password">
                        </div>
                    </div>

                    <div>
                        <button class="btn" type="submit">Reset Password</button>
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
<!-- Reset Password End -->
@endsection
