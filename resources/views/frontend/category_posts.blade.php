@extends('frontend.layouts.master')

@section('title', $category->name . ' Posts')

@section('breadcrumb')
@parent
 <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
<li class="breadcrumb-item active"><a href="{{ route('frontend.category.posts', $category->slug) }}">{{ $category->name }}</a></li>
@endsection


@section('content')
<!-- Main News Start-->
<div class="main-news" style="margin-top: 35px;">
    <div class="container ">
        <div class="row ">
            <div class="col-lg-8">
                <div class="row">
                    @forelse ($posts as $post)
                    <div class="col-md-4">
                        <div class="mn-img">
                            <img src="{{ asset('frontend/img/'.$post->images->first()->path) }}" />
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
                            {{ $posts->links() }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="mn-list">
                    <h2 >Other Categories</h2>
                    <ul>
                        @foreach ($categories as $category)
                            <li><a href="{{ route('frontend.category.posts', $category->slug) }}" title="{{ $category->name}}" class="dropdown-item">{{ $category->name }}</a></li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main News End-->
@endsection
