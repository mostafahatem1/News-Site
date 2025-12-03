<!-- Tab News Start -->
<div class="tab-news py-4 bg-light">
    <div class="container">
        <div class="row">
            <!-- Left Tab: Oldest vs Popular -->
            <div class="col-md-6">
                <ul class="nav nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#featured">Editors Pick</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#popular">Most Popular</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="featured" class="container tab-pane active p-0">
                        @foreach ($oldest_post as $post)
                            @include('frontend.partials.horizontal-card', ['post' => $post])
                        @endforeach
                    </div>
                    <div id="popular" class="container tab-pane fade p-0">
                        @foreach ($popular_posts as $post)
                            @include('frontend.partials.horizontal-card', ['post' => $post, 'show_comments' => true])
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Tab: Latest vs Most Viewed -->
            <div class="col-md-6">
                <ul class="nav nav-pills nav-justified mb-3">
                    <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#m-viewed">Latest</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#m-read">Trending</a></li>
                </ul>
                <div class="tab-content">
                    <div id="m-viewed" class="container tab-pane active p-0">
                        @foreach ($latest_posts as $post)
                            @include('frontend.partials.horizontal-card', ['post' => $post])
                        @endforeach
                    </div>
                    <div id="m-read" class="container tab-pane fade p-0">
                        @foreach ($popular_posts as $post)
                            @include('frontend.partials.horizontal-card', ['post' => $post, 'show_views' => true])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tab News End -->
