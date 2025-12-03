@extends('frontend.layouts.master')
@section('title', 'Profile')

@section('breadcrumbs')
@parent
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<!-- Profile Start -->
<div class="dashboard container">
    <!-- Sidebar -->
    @include('frontend.dashboard._sidebar')

    <!-- Main Content -->
    <div class="main-content">
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
            <form action="{{ route('frontend.dashboard.post.store') }}" method="POST" enctype="multipart/form-data">
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

                        @endif
                        <div class="post-form p-3 border rounded">

                            <!-- Post Title -->
                            <input type="text" id="title" name="title" class="form-control mb-2"
                                placeholder="Post Title" />

                            <!-- Post Content -->
                            <textarea id="postContent" name="desc" class="form-control mb-2 summernote" rows="3"
                                placeholder="What's on your mind?"></textarea>

                            <!-- Image Upload -->
                            <input type="file" id="post-image" name="images[]" class="form-control mb-2"
                                accept="image/*" multiple />
                            <div class="tn-slider mb-2">
                                <div id="imagePreview" class="slick-slider"></div>
                            </div>

                            <!-- Category Dropdown -->
                            <select id="postCategory" name="category_id" class="form-select mb-2">
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <!-- Enable Comments Checkbox -->
                            <label class="form-check-label mb-2">
                                <input name="comment_able" type="checkbox" class="form-check-input" /> Enable Comments
                            </label><br>

                            <!-- Post Button -->
                            <button class="btn btn-primary post-btn">Post</button>
                        </div>
                </section>

            </form>
            <!-- Posts Section -->
            <section id="posts" class="posts-section">
                <h2>Recent Posts</h2>
                @forelse ($posts as $post)
                <div class="post-list">
                    <!-- Post Item -->
                    <div class="post-item mb-4 p-3 border rounded">
                        <div class="post-header d-flex align-items-center mb-2">
                            <img src="{{ asset('frontend/img/user/' . auth()->user()->image) }}" alt="User Image"
                                class="rounded-circle" style="width: 50px; height: 50px;" />
                            <div class="ms-3">
                                <h5 class="m-3" style="color: #ff6f61">{{ auth()->user()->name }}</h5>
                            </div>
                        </div>
                        <h4 class="post-title">{{ $post->title }}</h4>
                        <p class="">{!! chunk_split($post->desc,30) !!}</p>

                        <!-- Use unique ID for each carousel -->
                        <div id="newsCarousel_{{ $post->id }}" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($post->images as $key => $image)
                                <li data-target="#newsCarousel_{{ $post->id }}" data-slide-to="{{ $key }}"
                                    class="{{ $key === 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($post->images as $key => $image)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('frontend/img/' . $image->path) }}" class="d-block w-100"
                                        alt="Post Image" style="height: 400px; object-fit: cover" />
                                </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#newsCarousel_{{ $post->id }}" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#newsCarousel_{{ $post->id }}" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                        <div class="post-actions d-flex justify-content-between">
                            <div class="post-stats">
                                <!-- View Count -->
                                <span class="me-3">
                                    <i class="fas fa-eye"></i> {{ $post->num_of_views }}
                                </span>

                            </div>

                            <div class="d-flex align-items-center">
                                <a href="{{ route('frontend.dashboard.post.edit', $post->id) }}"
                                    class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <form id="delete-post-form-{{ $post->id }}"
                                    action="{{ route('frontend.dashboard.post.delete', $post->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2 delete-post"
                                        data-post-id="{{ $post->id }}">
                                        <i class="fas fa-trash trash"></i> Delete
                                    </button>
                                </form>

                                <button type="button" class="btn btn-sm btn-outline-secondary comment"
                                    post-id="{{ $post->id }}" id="comment-btn-{{ $post->id }}">
                                    <i class="fas fa-comment"></i>Show Comments
                                </button>

                                 <button type="button" class="btn btn-sm btn-outline-secondary "
                                    post-id="{{ $post->id }}" id="comment-btn-hide-{{ $post->id }}" style="display: none;">
                                    <i class="fas fa-comment"></i>Hide Comments
                                </button>
                            </div>
                        </div>

                        <!-- Display Comments -->
                        <div class="comments" id ="comments-{{ $post->id }}" style="display: none; margin-top: 20px;">
                            <div class="comment">
                                <img src="{{ asset('frontend/img/user/' . auth()->user()->image) }}" alt="User Image"
                                    class="comment-img" />
                                <div class="comment-content">
                                    <span class="username"></span>
                                    <p class="comment-text">first comment</p>
                                </div>
                            </div>
                            <!-- Add more comments here for demonstration -->
                        </div>
                    </div>

                    <!-- Add more posts here dynamically -->
                </div>
                @empty
                <div class="alert alert-info">
                    <strong>No posts found.</strong> Start by creating your first post!
                </div>
                @endforelse
            </section>
        </section>
    </div>

</div>
<!-- Profile End -->
@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-post');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-post-form-${postId}`).submit();
                        }
                    });
                });
            });
        });
</script>
<script>
    $(function() {

            $('.comment').on('click', function(e) {
                e.preventDefault();
                var postId = $(this).attr('post-id');
                $.ajax({
                    url: "{{ route('frontend.dashboard.post.comments', '') }}/" + postId,
                    type: 'GET',
                    success: function(response)
                    {
                        var commentsContainer = $('#comments-' + postId);
                        commentsContainer.empty(); // Clear previous comments
                        if (response.comments.length > 0) {
                            response.comments.forEach(function(comment) {
                                var commentHtml = `
                                    <div class="comment">
                                        <img src="{{ asset('frontend/img/user/') }}/${comment.user.image}" alt="User Image" class="comment-img" />
                                        <div class="comment-content">
                                            <span class="username">${comment.user.name}</span>
                                            <p class="comment-text">${comment.comment}</p>
                                        </div>
                                    </div>
                                `;
                                commentsContainer.append(commentHtml);
                            });
                        } else {
                            commentsContainer.html('<p  style="color: #ff6f61">No comments yet.</p>');
                        }
                        commentsContainer.show(); // Show the comments container
                        $('#comment-btn-'+postId).hide(); // Hide the form
                        $('#comment-btn-hide-'+postId).show(); // Show the hide button



                    },
                    error: function(xhr) {
                        console.error('Error fetching comments:', xhr);
                    }
                });
                $('#comment-btn-hide-'+postId).on('click', function(e) {
                    e.preventDefault();
                    $('#comments-' + postId).hide(); // Hide the comments container
                    $('#comment-btn-' + postId).show(); // Show the form
                    $('#comment-btn-hide-' + postId).hide(); // Hide the hide button
                });

            });



            $("#post-image").fileinput({
                theme: "fa5",
                maxFileCount: 5,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-post-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-post-form-${postId}`).submit();
                        }
                    });
                });
            });
        });
</script>
@endpush
