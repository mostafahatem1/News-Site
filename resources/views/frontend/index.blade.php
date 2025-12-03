@extends('frontend.layouts.master')

@section('title', 'Home')
@section('breadcrumb')
@parent
 <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
@endsection

@section('content')

<!-- Top News Start-->
<div class="top-news">
    <div class="container">
        <div class="row">
            <!-- Main Hero (Left) -->
            <div class="col-md-6 tn-left">
                <div class="row tn-slider">
                    @forelse ($latest_posts as $post)
                    <div class="col-12">
                        <div class="hero-card">
                            @if($post->images->isNotEmpty())
                                <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}">
                            @else
                                <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default">
                            @endif
                            <div class="hero-overlay">
                                <span class="badge">Latest</span>
                                <a href="{{ route('frontend.post.show', $post->slug) }}">
                                    <h3>{{ Str::limit($post->title, 80) }}</h3>
                                </a>
                                <small>
                                    <i class="far fa-calendar-alt"></i> {{ $post->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No latest posts available.</div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Sub Hero Grid (Right) -->
            <div class="col-md-6 tn-right">
                <div class="row">
                    @foreach ($posts->take(4) as $post)
                    <div class="col-md-6 mb-4">
                        <div class="news-card">
                            <div class="card-img">
                                <span class="card-badge">{{ $post->category->name ?? 'News' }}</span>
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default" class="w-100 h-100" style="object-fit: cover;">
                                @endif
                            </div>
                            <div class="card-content">
                                <a href="{{ route('frontend.post.show', $post->slug) }}" class="card-title">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                                <div class="card-meta">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $post->created_at->format('M d') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Top News End-->

<!-- Category News Start-->
<div class="cat-news">
    <div class="container">
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-6">
                <h2>{{ $category->name }}</h2>
                <div class="row cn-slider">
                    @foreach ($category->posts as $post)
                    <div class="col-md-6">
                        <div class="news-card">
                            <div class="card-img">
                                <span class="card-badge">{{ $category->name }}</span>
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default" style="width: 100%; height: 200px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="card-content">
                                <a href="{{ route('frontend.post.show', $post->slug) }}" class="card-title">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                                <div class="card-meta">
                                    <span><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Category News End-->

<!-- Tab News Start-->
<div class="tab-news">
    <div class="container">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <ul class="nav nav-pills nav-justified">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#featured">Oldest News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#popular">Popular News</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Oldest News Tab -->
                    <div id="featured" class="container tab-pane active">
                        @foreach ($oldest_post as $post)
                        <div class="tn-news">
                            <div class="tn-img">
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}"/>
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default"/>
                                @endif
                            </div>
                            <div class="tn-title">
                                <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Popular News Tab -->
                    <div id="popular" class="container tab-pane fade">
                        @foreach ($popular_posts as $post)
                        <div class="tn-news">
                            <div class="tn-img">
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}"/>
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default"/>
                                @endif
                            </div>
                            <div class="tn-title">
                                <a href="{{ route('frontend.post.show', $post->slug) }}">
                                    {{ $post->title }}
                                    <span style="color: #ff6f61; display:block; font-size: 12px;">
                                        <i class="fas fa-comment"></i> ({{ $post->comments_count ?? 0 }})
                                    </span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <ul class="nav nav-pills nav-justified">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#m-viewed">Latest News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#m-read">Most Viewed</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Latest News Tab -->
                    <div id="m-viewed" class="container tab-pane active">
                        @foreach ($latest_posts as $post)
                        <div class="tn-news">
                            <div class="tn-img">
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}"/>
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default"/>
                                @endif
                            </div>
                            <div class="tn-title">
                                <a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Most Viewed Tab -->
                    <div id="m-read" class="container tab-pane fade">
                        @foreach ($num_of_views as $post)
                        <div class="tn-news">
                            <div class="tn-img">
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}"/>
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default"/>
                                @endif
                            </div>
                            <div class="tn-title">
                                <a href="{{ route('frontend.post.show', $post->slug) }}">
                                    {{ $post->title }}
                                    <span style="color: #ff6f61; font-size: 12px;">
                                        <i class="fas fa-eye"></i> ({{ $post->num_of_views ?? 0 }})
                                    </span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tab News End-->

<!-- Main News Start-->
<div class="main-news">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="row">
                    @forelse ($posts as $post)
                    <div class="col-md-4 mb-4">
                        <div class="news-card">
                            <div class="card-img">
                                <span class="card-badge">{{ $post->category->name ?? 'News' }}</span>
                                @if($post->images->isNotEmpty())
                                    <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" alt="{{ $post->title }}" class="img-fluid uniform-img">
                                @else
                                    <img src="{{ asset('frontend/img/default-image.jpg') }}" alt="Default" class="img-fluid uniform-img">
                                @endif
                            </div>
                            <div class="card-content">
                                <a href="{{ route('frontend.post.show', $post->slug) }}" class="card-title">
                                    {{ Str::limit($post->title, 60) }}
                                </a>
                                <div class="card-meta">
                                    <span><i class="far fa-eye"></i> {{ $post->num_of_views ?? 0 }}</span>
                                    <span><i class="fas fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
                                </div>
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
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="mn-list">
                    <h2>Read More</h2>
                    <ul>
                        @forelse ($read_more_posts as $post)
                            <li><a href="{{ route('frontend.post.show', $post->slug) }}">{{ $post->title }}</a></li>
                        @empty
                            <li class="text-muted">No posts available</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main News End-->

@endsection
