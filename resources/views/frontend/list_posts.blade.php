@extends('frontend.layouts.master')
@section('title', 'Search Results for ' . $search)

@section('breadcrumb')
    @parent
     <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
    <li class="breadcrumb-item active">Posts</li>
@endsection

@section('content')
    <div class="search-wrap">
        <div class="container">
            <h2 class="search-title">Search Results</h2>
            <p class="search-description">You searched for: <strong>{{ $search }}</strong></p>
            <p class="search-count">results found (<span style="color: brown">{{ $count }}</span>)</p>
        </div>

    </div>

    <!-- Main News Start-->
    <div class="main-news">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        @forelse ($posts as $post)
                            <div class="col-md-4">
                                <div class="mn-img">
                                    <img class="img-fluid uniform-img"
                                        src="{{ asset('frontend/img/' . $post->images->first()->path) }}" />
                                    <div class="mn-title">
                                        <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    No posts available.
                                </div>
                            </div>
                        @endforelse

                        <div class="col-md-12">
                            <div class="pagination">
                               {{ $posts->appends(request()->query())->links() }}

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main News End-->
@endsection
