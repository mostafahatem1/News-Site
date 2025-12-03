@extends('frontend.layouts.master')

@section('content')
<!-- Register Start -->
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    <h3 class="mb-4">Register</h3>

                    <div class="form-row">
                        {{-- Profile Image --}}
                        {{-- Image Upload with Stylish Preview --}}
                        <div class="form-group col-md-12">
                            <label for="image">Profile Image</label>

                            <div class="d-flex align-items-center mb-2">
                                <img id="image-preview" src="{{ asset('frontend/img/user/default.jpg') }}" alt="Preview"
                                    style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; object-position: top; border: 2px solid #ddd; margin-right: 15px;">

                                <label for="image" class="btn btn-secondary mb-0">Choose Image</label>
                            </div>

                            <input type="file" name="image" id="image" class="d-none" accept="image/*" />
                            @error('image')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- Gender --}}
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="0" {{ old('gender')=='0' ? 'selected' : '' }}>Male</option>
                                <option value="1" {{ old('gender')=='1' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Full Name" />
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="form-control" placeholder="Username" />
                            @error('username')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Email" />
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-control"
                                placeholder="Phone" />
                            @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}"
                                class="form-control" placeholder="Country" />
                            @error('country')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="form-control"
                                placeholder="City" />
                            @error('city')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="street">Street</label>
                            <input type="text" name="street" id="street" value="{{ old('street') }}"
                                class="form-control" placeholder="Street" />
                            @error('street')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password" />
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirm Password" />
                        </div>
                    </div>

                    <div>
                        <button class="btn" type="submit">Register</button>
                    </div>

                    <div class="m-3">
                        @if (Route::has('login'))
                        <a href="{{ route('frontend.login') }}">Already have an account? Login</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Register End -->
@endsection

@push('scripts')
<script>
    document.getElementById("image").addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById("image-preview");
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endpush
