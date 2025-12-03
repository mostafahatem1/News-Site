@extends('backend.layouts.master')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Site Settings</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_name">Site Name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name"
                                value="{{ old('site_name', $setting->site_name ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            @if(!empty($setting->logo))
                            <div class="mb-2">
                                <img id="logo-preview" src="{{ asset('frontend/img/' . $setting->logo) }}" alt="Logo"
                                    height="40" style="background:#f8f9fc;padding:4px;border-radius:6px;">
                            </div>
                            @else
                            <div class="mb-2">
                                <img id="logo-preview" src="" alt="Logo" height="40"
                                    style="display:none;background:#f8f9fc;padding:4px;border-radius:6px;">
                            </div>
                            @endif
                            <input type="file" class="form-control-file" id="logo" name="logo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="favicon">Favicon</label>
                            @if(!empty($setting->favicon))
                            <div class="mb-2">
                                <img id="favicon-preview" src="{{ asset($setting->favicon) }}" alt="Favicon" height="32"
                                    style="background:#f8f9fc;padding:4px;border-radius:6px;">
                            </div>
                            @else
                            <div class="mb-2">
                                <img id="favicon-preview" src="" alt="Favicon" height="32"
                                    style="display:none;background:#f8f9fc;padding:4px;border-radius:6px;">
                            </div>
                            @endif
                            <input type="file" class="form-control-file" id="favicon" name="favicon">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $setting->email ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $setting->phone ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="text" class="form-control" id="facebook" name="facebook"
                                value="{{ old('facebook', $setting->facebook ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="text" class="form-control" id="twitter" name="twitter"
                                value="{{ old('twitter', $setting->twitter ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="insagram">Instagram</label>
                            <input type="text" class="form-control" id="insagram" name="insagram"
                                value="{{ old('insagram', $setting->insagram ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="youtupe">YouTube</label>
                            <input type="text" class="form-control" id="youtupe" name="youtupe"
                                value="{{ old('youtupe', $setting->youtupe ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                value="{{ old('country', $setting->country ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="{{ old('city', $setting->city ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" id="street" name="street"
                                value="{{ old('street', $setting->street ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="small_desc">Small Description</label>
                    <textarea class="form-control" id="small_desc" name="small_desc"
                        rows="2">{{ old('small_desc', $setting->small_desc ?? '') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary px-5">Update</button>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    document.getElementById('logo').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const preview = document.getElementById('logo-preview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
});
document.getElementById('favicon').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const preview = document.getElementById('favicon-preview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
});
</script>
@endsection