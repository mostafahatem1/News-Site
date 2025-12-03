@extends('frontend.layouts.master')

@section('title', 'Contact Us')

@section('breadcrumb')
@parent
 <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
<li class="breadcrumb-item "><a href="{{ route('frontend.contact.show') }}">Contact Us</a></li>
@endsection

@section('content')
    <!-- Contact Start -->
    <div class="contact">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="contact-form">

              <form method="POST" action="{{ route('frontend.contact.store') }}">
                @csrf

                <h3 class="mb-4">Contact Us</h3>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <input
                      type="text"
                      name="name"
                      value="{{ old('name') }}"
                      class="form-control"
                      placeholder="Your Name"
                    />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="form-group col-md-4">
                    <input
                      type="email"
                        name="email"
                        value="{{ old('email') }}"
                      class="form-control"
                      placeholder="Your Email"
                    />
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="form-group col-md-4">
                    <input
                      type="tel"
                      name="phone"
                      value="{{ old('phone') }}"
                      class="form-control"
                      placeholder="Your Phone"
                    />
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="form-group">
                  <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    class="form-control"
                    placeholder="Title"
                  />
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                  <textarea
                    name="body"
                    class="form-control"
                    rows="5"
                    placeholder="Message"
                  ></textarea>
                    @error('body')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                  <button class="btn" type="submit">Send Message</button>
                </div>
              </form>
            </div>
          </div>
          <div class="col-md-4">
            <div class="contact-info">
              <h3>Get in Touch</h3>
              <p class="mb-4">
                The contact form is currently inactive. Get a functional and
                working contact form with Ajax & PHP in a few minutes. Just copy
                and paste the files, add a little code and you're done.
                <a href="https://htmlcodex.com/contact-form">Download Now</a>.
              </p>
              <h4><i class="fa fa-map-marker"></i>{{ $setting->country }}, {{ $setting->city }}, {{ $setting->street }}</h4>
              <h4><i class="fa fa-envelope"></i>{{ $setting->email }}</h4>
              <h4><i class="fa fa-phone"></i>+{{ $setting->phone }}</h4>
              <div class="social">
                  <a href="{{ $setting->twitter }}" title="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="{{ $setting->facebook }}" title="facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $setting->instagram }}" title="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $setting->youtube }}" title="youtube"><i class="fab fa-youtube"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Contact End -->

@endsection
