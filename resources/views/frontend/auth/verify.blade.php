@extends('frontend.layouts.master')

@section('content')
<!-- Verify Email Start -->
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <h3 class="mb-4">Verify Your Email Address</h3>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                <p>{{ __('If you did not receive the email') }},</p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ __('Click here to request another') }}
                    </button>
                </form>

                <div class="m-3">
                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link">Logout</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Verify Email End -->
@endsection
