@extends('backend.layouts.master')

@section('title', 'Show Post')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-primary font-weight-bold">Post Details</h1>
    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            <div class="row mb-4 align-items-center">
                <div class="col-md-2 text-center">
                    @if ($post->user && $post->user->image)
                    <img src="{{ asset('frontend/img/user/' . $post->user->image) }}" alt="User Image" width="80"
                        height="80" style="object-fit:cover; border-radius:50%; border:2px solid #007bff;">
                    @else
                    <img src="{{ asset('frontend/img/user/default.jpg') }}" alt="User Image" width="80" height="80"
                        style="object-fit:cover; border-radius:50%; border:2px solid #007bff;">
                    @endif
                    <div class="mt-2 small text-muted">
                        <i class="fas fa-user"></i> {{ $post->user->name ?? '-' }}
                    </div>
                </div>
                <div class="col-md-10">
                    <!-- Carousel -->
                    <div id="newsCarousel" class="carousel slide mb-3" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach ($post->images as $index => $image)
                            <li data-target="#newsCarousel" data-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner rounded shadow-sm">
                            @forelse ($post->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('frontend/img/' . $image->path) }}"
                                    class="d-block w-100 carousel-img-small" alt="Post Image"
                                    style="width:100%;height:auto;max-height:350px;object-fit:contain;border-radius:12px;">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>{{ $post->title }}</h5>
                                </div>
                            </div>
                            @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('frontend/img/default_post.jpg') }}"
                                    class="d-block w-100 carousel-img-small" alt="Post Image"
                                    style="max-height:260px;object-fit:cover;border-radius:12px;">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>{{ $post->title }}</h5>
                                </div>
                            </div>
                            @endforelse
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
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <table class="table table-bordered table-striped shadow-sm bg-white rounded">
                        <tr>
                            <th class="w-25 text-right bg-light">Title:</th>
                            <td>{{ $post->title }}</td>
                        </tr>
                        <tr>
                            <th class="text-right bg-light">Status:</th>
                            <td>
                                @if ($post->status == 1)
                                <span class="badge badge-success px-3 py-1">Active</span>
                                @else
                                <span class="badge badge-danger px-3 py-1">Inactive</span>
                                @endif
                                <a href="{{ route('admin.posts.status', $post->id) }}"
                                    class="btn btn-sm btn-warning ml-2" title="Toggle Status">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right bg-light">User:</th>
                            <td>{{ $post->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right bg-light">Category:</th>
                            <td>{{ $post->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right bg-light"><i class="fas fa-comments"></i> Comments Count:</th>
                            <td>{{ $post->comments_count ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th class="text-right bg-light"><i class="fas fa-eye"></i> Number of Views:</th>
                            <td>{{ $post->num_of_views ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th class="text-right bg-light">Description:</th>
                            <td>{!! $post->desc !!}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.posts.index', ['page' => request()->page]) }}" class="btn btn-secondary mt-3 px-4">
                        <i class="fas fa-arrow-left"></i> Back to Post List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
