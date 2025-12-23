@extends('frontend.layouts.master')
@section('title', 'Edit Post')

@section('breadcrumbs')
@parent
<li class="breadcrumb-item active">Edit Post</li>
@endsection

@section('content')
<!-- Profile Start -->
<div class="dashboard container">
    <div class="row">

    <!-- Sidebar -->
     @include('frontend.dashboard._sidebar')

    <!-- Main Content -->
    <div class="col-lg-9 col-md-8 main-content">
        <!-- Profile Section -->
        <section id="profile" class="content-section active">
            <h2>User Profile</h2>
            <div class="user-profile mb-3">
                <img src="{{ asset('frontend/img/user/' . auth()->user()->image) }}" alt="User Image"
                    class="profile-img rounded-circle" style="width: 100px; height: 100px;" />
                <span class="username">{{ auth()->user()->name }}</span>
            </div>
            <br>

            <!-- Add Post Section -->
            <form action="{{ route('frontend.dashboard.post.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <section id="add-post" class="add-post-section mb-5">
                    <h2>Add Post</h2>
                    @if (session()->has('errors'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach (session('errors')->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="post-form p-3 border rounded">

                        <!-- Post Title -->
                        <input type="text" id="title" name="title" class="form-control mb-2" placeholder="Post Title"
                            value="{{ old('title', $post->title ?? '') }}" />

                        <!-- Post id -->
                        <input type="hidden" id="post_id" name="post_id" class="form-control mb-2"
                            placeholder="post_id Title" value="{{ old('post_id', $post->id ?? '') }}" />


                        <!-- Post Content -->
                        <textarea id="postContent" name="desc" class="form-control mb-2 summernote" rows="3"
                            placeholder="What's on your mind?">{{ old('desc', $post->desc ?? '') }}</textarea>

                        <!-- Image Upload -->
                        <!-- Image Upload -->
                        <input type="file" id="post-image" name="images[]" class="form-control mb-2" accept="image/*"
                            multiple />
                        <div class="tn-slider mb-2">
                            <div id="imagePreview" class="slick-slider"></div>
                        </div>

                        <!-- Category Dropdown -->
                        <select id="postCategory" name="category_id" class="form-select mb-2">
                            <option value="" disabled>Select Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') ==
                                $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>

                        <!-- Enable Comments Checkbox -->
                        <label class="form-check-label mb-2">
                            <input name="comment_able" type="checkbox" class="form-check-input" {{ old('comment_able',
                                $post->comment_able ?? false) ? 'checked' : '' }} /> Enable Comments
                        </label><br>
                        <div class="post-stats d-flex justify-content-between align-items-center mb-2">
                            <!-- View Count -->
                            <span class="ms-3">
                                <i class="fas fa-comment"></i> {{ $post->comments->count() }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-eye"></i> {{ $post->num_of_views }}
                            </span>
                        </div>

                        <!-- Post Button -->
                        <button class="btn btn-primary post-btn">Upadte</button>
                    </div>
                </section>
            </form>

        </section>
    </div>

    </div>

</div>
<!-- Profile End -->
@endsection

@push('scripts')

<script>
    $(function() {

              $("#post-image").fileinput({
                theme: "fa5",
                maxFileCount: 5,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                initialPreview: [
                    @if(isset($post) && $post->images)
                        @foreach($post->images as $img)
                        "{{ asset('frontend/img/' . $img->path) }}",
                    @endforeach
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                        @if(isset($post) && $post->images)
                        @foreach($post->images as $img)
                    {

                        width: "120px",
                        url: "{{ route('frontend.dashboard.post.remove_image', ['image_id' => $img->id, 'post_id' => $post->id, '_token' => csrf_token()]) }}",
                        key: {{ $img->id }}
                    },
                    @endforeach
                    @endif
                ]
            }).on('filesorted', function (event, params) {
                console.log(params.previewId, params.oldIndex, params.newIndex, params.stack);
            });

            $(document).ready(function() {
                $('.summernote').summernote({
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            });
        });
</script>
@endpush
