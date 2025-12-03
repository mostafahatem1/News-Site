@extends('backend.layouts.master')

@section('title', 'Create User')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h3 class="mb-4">Create User</h3>

                    <div class="form-row">
                        {{-- Profile Image --}}
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

                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="0">Male</option>
                                <option value="1">Female</option>
                            </select>
                            @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

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
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-success" type="submit">Create User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById("image").addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById("image-preview");
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
