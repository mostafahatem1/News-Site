@extends('frontend.layouts.master')
@section('title', 'Settings')

@section('breadcrumbs')
@parent
<li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<!-- Settings Start -->
<div class="dashboard container">
    <!-- Sidebar -->
    @include('frontend.dashboard._sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Settings Section -->
        <section id="settings">
            <h2>Settings</h2>
            <form method="POST" action="{{ route('frontend.dashboard.setting.update') }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="form-row">
                    {{-- Profile Image --}}
                    <div class="form-group col-md-12">
                        <label for="image">Profile Image</label>
                        <div class="d-flex align-items-center mb-2">
                            <img id="image-preview"
                                src="{{ asset('frontend/img/user/' . (auth()->user()->image ?? 'default.jpg')) }}"
                                alt="Preview"
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
                            <option value="0" {{ old('gender', auth()->user()->gender) == '0' ? 'selected' : '' }}>Male
                            </option>
                            <option value="1" {{ old('gender', auth()->user()->gender) == '1' ? 'selected' : ''
                                }}>Female</option>
                        </select>
                        @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                            class="form-control" placeholder="Full Name" />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username"
                            value="{{ old('username', auth()->user()->username) }}" class="form-control"
                            placeholder="Username" />
                        @error('username')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                            class="form-control" placeholder="Email" />
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}"
                            class="form-control" placeholder="Phone" />
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="country">Country</label>
                        <input type="text" name="country" id="country"
                            value="{{ old('country', auth()->user()->country) }}" class="form-control"
                            placeholder="Country" />
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city', auth()->user()->city) }}"
                            class="form-control" placeholder="City" />
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="street">Street</label>
                        <input type="text" name="street" id="street" value="{{ old('street', auth()->user()->street) }}"
                            class="form-control" placeholder="Street" />
                        @error('street')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="form-control"
                            placeholder="Current Password" />
                        @error('current_password')
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
                    <button class="btn" type="submit">Save Changes</button>
                </div>
            </form>
        </section>
    </div>
</div>
<!-- Settings End -->
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
