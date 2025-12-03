@extends('backend.layouts.master')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="contact-form">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <h3 class="mb-4">Edit User</h3>

                    <div class="form-row">
                        {{-- Profile Image --}}
                        <div class="form-group col-md-12">
                            <label for="image">Profile Image</label>
                            <div class="d-flex align-items-center mb-2">
                                <img id="image-preview"
                                    src="{{ asset('frontend/img/user/' . ($user->image ?? 'default.jpg')) }}"
                                    alt="Preview"
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
                                <option value="1" {{ $user->gender == '1' ? 'selected' : '' }}>Male</option>
                                <option value="0" {{ $user->gender == '0' ? 'selected' : '' }}>Female</option>

                            </select>
                            @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="form-control" placeholder="Full Name" required />
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username"
                                value="{{ old('username', $user->username) }}" class="form-control"
                                placeholder="Username" required />
                            @error('username')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="form-control" placeholder="Email" required />
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="form-control" placeholder="Phone" />
                            @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country', $user->country) }}"
                                class="form-control" placeholder="Country" />
                            @error('country')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}"
                                class="form-control" placeholder="City" />
                            @error('city')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="street">Street</label>
                            <input type="text" name="street" id="street" value="{{ old('street', $user->street) }}"
                                class="form-control" placeholder="Street" />
                            @error('street')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password fields optional for edit --}}
                        <div class="form-group col-md-6">
                            <label for="password">Password <small>(leave blank to keep current)</small></label>
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

                        <div class="form-group col-md-6">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary" type="submit">Update User</button>
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
    const imageInput = document.getElementById("image");
    const preview = document.getElementById("image-preview");
    const defaultSrc = "{{ asset('frontend/img/user/' . ($user->image ?? 'default.jpg')) }}";

    imageInput.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
        } else {
            preview.src = defaultSrc;
        }
    });

    document.querySelector('label[for="image"]').addEventListener('click', function() {
        imageInput.value = "";
        imageInput.click();
    });
</script>
@endsection
