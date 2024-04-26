@extends("layouts.template",['titre'=>"Film"])

@section("style")
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/photoswipe.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/default-skin/default-skin.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/plyr/plyr.css') }}" @endsection @section("content") <main
    class="app-main">
<div class="wrapper">
    <!-- .page -->
    <div class="page py-0">
        <!-- .page-inner -->
        <div class="page-inner">

            <!-- .section-block -->
            <div class="section-block d-sm-flex justify-content-between">

                <h2 class="section-title">Page des medias</h2>
                <p class="text-muted">
                    <a href="{{ route('createMedia') }}" class="btn btn-success">
                        Ajouter
                    </a>
                </p>

            </div><!-- /.section-block -->

            <!-- grid row -->
            <div class="row">

                <!-- grid column -->
                {{-- <div class="col-xl-4 col-sm-6"> --}}
                    <!-- .card -->
                    {{-- {{ dd($medias->data) }} --}}
                    @forelse ($medias->data as $m)
                    <div class="col-sm-6">
                        @if (!empty($m->media_url))
                        <div class="card card-body">
                            <div class="embed-responsive embed-responsive-16by9 w-100">
                                <iframe id="youtube-9854" frameborder="0" allowfullscreen="1"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" title="{{ $m->media_title }}"
                                    class="embed-responsive-item"
                                    src="{{ $m->media_url }}?autoplay=0&amp;controls=0&amp;disablekb=1&amp;playsinline=1&amp;cc_load_policy=0&amp;cc_lang_pref=auto&amp;widget_referrer=file%3A%2F%2F%2FB%3A%2Ftheme-boostrap%2Flooper-bak%2Fdist%2Fcomponent-rich-media.html&amp;noCookie=false&amp;rel=0&amp;showinfo=0&amp;iv_load_policy=3&amp;modestbranding=1&amp;enablejsapi=1&amp;widgetid=1">
                                </iframe>
                            </div>
                        </div><!-- /.card -->
                        @else
                        <div class="pswp-gallery ratio ratio-16x9">
                            <!-- .card -->
                            <div class="card card-figure">
                                <!-- .card-figure -->
                                <figure class="figure">
                                    <!-- .figure-img -->
                                    <div class="figure-img">
                                        <img class="img-fluid" src="{{ asset($m->cover_url)}}" alt="Card image cap">
                                        <a href="{{ asset($m->cover_url) }}" class="img-link" data-size="600x450">
                                            <span class="tile tile-circle bg-danger"><span class="oi oi-eye"></span>
                                            </span> <span class="img-caption d-none">Image caption goes here</span></a>
                                        <div class="figure-action">
                                            <a href="#" class="btn btn-block btn-sm btn-primary">Voir en detail</a>
                                        </div>
                                    </div><!-- /.figure-img -->
                                    <!-- .figure-caption -->
                                    <figcaption class="figure-caption">
                                        <ul class="list-inline text-muted mb-0">
                                            <li class="list-inline-item">
                                                <span class="oi oi-paperclip"></span> 0.62MB
                                            </li>
                                            <li class="list-inline-item float-right">
                                                <span class="oi oi-calendar"></span>
                                            </li>
                                        </ul>
                                    </figcaption><!-- /.figure-caption -->
                                </figure><!-- /.card-figure -->
                            </div><!-- /.card -->

                        </div>

                        @endif
                    </div><!-- /.card -->
                    @empty

                    @endforelse
                    <div class="card-body">
                        <hr>
                        <div class="el-example">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">«</a>
                                </li>
                                {{-- {{ dd($medias->lastPage) }} --}}
                                {{-- @for ($i=1; $i <= $medias->lastPage; $i++)
                                    <li class="page-item{{ $i == $medias->currentPage() ? ' active' : '' }}">
                                        <a class="page-link" href="{{ '/media?page=' . $i }}">{{ $i }}</a>
                                    </li>
                                    @endfor --}}
                                    <li class="page-item">
                                        <a class="page-link" href="#">»</a>
                                    </li>
                            </ul>
                        </div>

                    </div>

                </div><!-- /grid row -->


            </div><!-- /.page-inner -->
        </div><!-- /.page -->
    </div>
    </main>
    @endsection

    @section("script")
    <!-- BEGIN PLUGINS JS -->
    <script src="{{ asset('assets/vendor/photoswipe/photoswipe.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/photoswipe/photoswipe-ui-default.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/plyr/plyr.min.js') }}"></script>
    <script src="{{ asset('assets/javascript/pages/photoswipe-demo.js') }} "></script>

    @endsection
