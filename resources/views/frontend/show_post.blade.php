@extends('frontend.layouts.master')
@section('title', $single_post->title)

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('frontend.post.show', $single_post->slug) }}">{{ $single_post->title
        }}</a></li>
@endsection

@section('content')
<!-- Single News Start-->
<div class="single-news">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Carousel -->
                <div id="newsCarousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#newsCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#newsCarousel" data-slide-to="1"></li>

                    </ol>
                    <div class="carousel-inner">
                        @foreach ($single_post->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('frontend/img/' . $image->path) }}" class="carousel-img-small" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5>{{ $single_post->title }}</h5>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a class="carousel-control-prev" href="#newsCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#newsCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <div class="sn-content">
                    {!! chunk_split($single_post->desc,30) !!}
                </div>

                <!-- Comment Section -->
                <div class="comment-section">
                    <!-- Comment Input -->
                    <form id="commentForm">
                        @csrf

                        <div class="comment-input">
                            @auth
                            <input type="hidden" name="post_id" value="{{ $single_post->id }}" />
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
                            <input type="text" name="comment" placeholder="Add a comment..." id="commentBox" />
                            <button type="submit">Post</button>
                            @endauth

                        </div>
                    </form>
                    <div class="alert alert-success" id="success-alert" style="display: none;">

                    </div>
                    <div class="alert alert-danger" id="error-alert" style="display: none;">

                    </div>

                    <!-- Display Comments -->
                    <div class="comments">
                        @foreach ($single_post->comments as $comment)
                        <div class="comment">
                            <img src="{{ asset('frontend/img/user/' . $comment->user->image) }}" alt="User Image"
                                class="comment-img" />
                            <div class="comment-content">
                                <span class="username">{{ $comment->user->name }}</span>
                                <p class="comment-text">{{ $comment->comment }}</p>
                            </div>
                        </div>
                        @endforeach
                        <!-- Add more comments here for demonstration -->
                    </div>
                    @if($single_post->comments->count() > 0)
                    <!-- Show More Button -->
                    <button id="showMoreBtn" class="btn btn-primary">Show more</button>
                    <button id="showLessBtn" style="display: none;" class="btn btn-primary">Show less</button>
                    @endif
                </div>

                <!-- Related News -->
                <div class="sn-related">
                    <h2>Related News</h2>
                    <div class="row sn-slider">
                        @foreach ($posts_belonging_to_category as $post)
                        <div class="col-md-4">
                            <div class="sn-img">
                                <img style="width: 100%; height: 100px; object-fit: cover;"
                                    src="{{ asset('frontend/img/' . $post->images->first()->path) }}" />
                                <div class="sn-title">
                                    <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
    <div class="sidebar-widget">
        <h2 class="sw-title">In This Category</h2>
        <div class="news-list">
            @foreach ($posts_belonging_to_category as $post)
            <div class="nl-item">
                <div class="nl-img">
                    <img src="{{ asset('frontend/img/' . $post->images->first()->path) }}"
                         style="width: 80px; height: 80px; object-fit: cover; display: block;" />
                </div>
                <div class="nl-title">
                    <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="sidebar-widget">
        <div class="tab-news">
            <ul class="nav nav-pills nav-justified">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#latest">Latest</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#popular">Popular</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="latest" class="container tab-pane active">
                    @foreach ($latest_posts as $post)
                    <div class="tn-news">
                        <div class="tn-img">
                            <img src="{{ asset('frontend/img/' . $post->images->first()->path) }}"
                                 style="width: 70px; height: 70px; object-fit: cover; display: block;" />
                        </div>
                        <div class="tn-title">
                            <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="popular" class="container tab-pane fade">
                    @foreach ($popular_posts as $post)
                    <div class="tn-news">
                        <div class="tn-img">
                            <img src="{{ asset('frontend/img/' . $post->images->first()->path) }}"
                                 style="width: 70px; height: 70px; object-fit: cover; display: block;" />
                        </div>
                        <div class="tn-title">
                            <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}
                                <span style="color: blue; display:block"><i class="fas fa-comment"></i>
                                    ({{ $post->comments_count }})
                                </span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar-widget">
        <h2 class="sw-title">News Category</h2>
        <div class="category">
            <ul>
                @foreach ($categories as $category)
                <li><a href="">{{ $category->name }}</a><span>({{ $category->posts->count() }})</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>
<!-- Single News End-->
@endsection

@push('scripts')
<script>
    $(document).on('submit', '#commentForm', function(e) {

            e.preventDefault();

            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "{{ route('frontend.post.comment.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {

                   $('.comments').prepend(`
                    <div class="comment">
                        <img src="{{ asset('frontend/img/user/') }}/${data.comment.user.image}" alt="User Image" class="comment-img" />
                        <div class="comment-content">
                            <span class="username">${data.comment.user.name}</span>
                            <p class="comment-text">${data.comment.comment}</p>
                        </div>
                    </div>
                `);
                     $('#error-alert').hide();
                     $('#success-alert').text(data.message).show();


                     // Hide the success alert after 3 seconds
                     setTimeout(function() {
                          $('#success-alert').fadeOut('slow');
                     }, 3000);
                 // Reset the form after successful submission
                   $('#commentForm')[0].reset();
                },
                error: function(data) {
                    var response = $.parseJSON(data.responseText);
                    $('#error-alert').text(response.message).show();

                }
            })

        })

        $(document).on('click', '#showMoreBtn', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('frontend.post.all.comment', $single_post->slug) }}",
                type: "GET",
                success: function(data) {
                    $('.comments').empty();
                    $.each(data.comments, function(index, comment) {
                        $('.comments').append(`
                    <div class="comment">
                        <img src="{{ asset('frontend/img/user/') }}/${comment.user.image}" alt="User Image" class="comment-img" />
                        <div class="comment-content">
                            <span class="username">${comment.user.name}</span>
                            <p class="comment-text">${comment.comment}</p>
                        </div>
                    </div>
                `);
                    });
                    $('#showMoreBtn').hide();
                    $('#showLessBtn').show();
                },
                error: function(xhr) {
                    if (xhr.status === 401 || xhr.responseJSON?.message === "Unauthenticated.") {
                        window.location.href = '{{ route("frontend.login") }}';
                        return;
                    }
                    console.log(xhr);
                }
            })
        })

        $(document).on('click', '#showLessBtn', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('frontend.post.less.comment', $single_post->slug) }}",
                type: "GET",
                success: function(data) {
                    $('.comments').empty();
                    $.each(data.comments, function(index, comment) {
                        $('.comments').append(`
                    <div class="comment">
                        <img src="{{ asset('frontend/img/user/') }}/${comment.user.image}" alt="User Image" class="comment-img" />
                        <div class="comment-content">
                            <span class="username">${comment.user.name}</span>
                            <p class="comment-text">${comment.comment}</p>
                        </div>
                    </div>
                `);
                    });
                    $('#showMoreBtn').show();
                    $('#showLessBtn').hide();
                },
                error: function(error) {
                    console.log(error);
                }
            })
        })
</script>
@endpush
