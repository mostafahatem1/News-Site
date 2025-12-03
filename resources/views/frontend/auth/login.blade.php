@extends('frontend.layouts.master')
@section('content')
<!-- Login Start -->
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <h3 class="mb-4">Login</h3>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="email">Email Address</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}"
                                placeholder="Email" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label for="password">Password</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" placeholder="Password" required autocomplete="current-password">
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn" type="submit">Login</button>
                    </div>

                    <div class="mt-3">
                        @if (Route::has('password.request'))
                        <a href="{{ route('frontend.forgot.password') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>

                    <div class="m-3">
                        @if (Route::has('register'))
                        <a href="{{ route('frontend.register') }}">Don't have an account? Register</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Login End -->
@endsection

