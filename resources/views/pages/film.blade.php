@extends("layouts.template",['titre'=>"Film"])

@section("style")
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/photoswipe.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/default-skin/default-skin.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/plyr/plyr.css') }}">

<style>
    .video-container {
        display: none;
        /* Masquer la vidéo par défaut */
        position: absolute;
        z-index: 10;
    }

    .image-container {
        position: relative;
        display: inline-block;
    }
</style>
@endsection
@section("content")
<main class="app-main">
    <div class="wrapper">
        <!-- .page -->
        <div class="py-0 page">
            <!-- .page-inner -->
            <div class="page-inner">

                <!-- .section-block -->
                <div class="section-block d-sm-flex justify-content-between">

                    <h2 class="section-title">Page des medias</h2>
                    <P>{{$medias->count }} Media(s) trouvés</P>
                    <p class="text-muted">
                        <a href="{{ route('createMedia') }}" class="btn btn-success">
                            Ajouter
                        </a>
                    </p>
                </div><!-- /.section-block -->
                <div class="row">
                    <div class="col-sm-7">
                        <div class="input-group has-clearable">
                            <button type="button" class="close" aria-label="Close">
                                <span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
                            </button>
                            <label class="input-group-prepend" for="searchClients">
                                <span class="input-group-text"><span class="oi oi-magnifying-glass"></span>
                                </span></label>
                            {{-- <input type="text" class="form-control" id="searchClients"
                                data-filter=".board .list-group-item" placeholder="Trouvez un membre"> --}}
                            <input type="text" class="form-control" id="searchInput"
                                placeholder="Recherche sur la page active en tapant le nom du media, le type, la catégorie">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div id="resultCount" class="alert alert-info alert-dismissible has-icon fade show"
                            style="display: none;">
                            <button class="close" type="button" data-dismiss="alert">x</button>
                            <div class="alert-icon">
                                <span class="oi oi-info"></span>
                            </div>
                            <h6 class="alert-heading"> Resultat de la recherche </h6>
                            <strong></strong><br>
                            <span id="infoTexte" class="class=mb-0"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card-body">
                        <hr>
                        <div class="el-example">
                            <ul class="pagination">
                                @if(!request()->has('page')|| request()->get('page')==1)
                                <li class="page-item disabled" hidden>
                                    <a class="page-link" href="">«</a>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="?page=1">«</a>
                                </li>
                                @endif
                                {{-- {{ dd($medias->lastPage) }} --}}
                                @for ($i=1; $i <= $medias->lastPage; $i++)
                                    <li
                                        class="page-item {{!request()->has('page') || request()->get('page')==$i ? ' active' : '' }}">
                                        <a class="page-link" href="{{ '/media?page=' . $i }}">{{ $i }}</a>
                                    </li>

                                    @endfor
                                    @if(request()->get('page')==$medias->lastPage)
                                    <li class="page-item" hidden>
                                        <a class="page-link" href="?{{ $medias->lastPage }}">»</a>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $medias->lastPage }}">»</a>
                                    </li>
                                    @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- grid row -->
                <div class="row">
                    @forelse ($medias->data as $m)
                    <div class="col-xl-4 col-lg-4 col-sm-6">
                        @if (!empty($m->media_url))
                        <div class="card card-body  item">
                            {{-- playsinline --}}
                            {{-- <span>{{Str::limit($m->media_title, 50, '...')}}</span> --}}
                            {{-- <div class="embed-responsive embed-responsive-16by9 w-100"> --}}
                                <div class="card card-figure">
                                    <!-- .card-figure -->
                                    <figure class="figure">
                                        <!-- .figure-img -->
                                        <div class="image-container">
                                            <img src="{{$m->cover_url?$m->cover_url:asset('"
                                                assets/blank-media-cover.webp"') }}" alt="Image" id="hover-image"
                                                width="300">
                                            @if ($m->source=="AWS")
                                            <div class="ratio ratio-16x9">
                                                {{-- <video autoplay muted poster="{{$m->cover_url?$m->cover_url:"" }}"
                                                    controls>
                                                    <source src="{{ $m->media_url }}" type="video/mp4">
                                                </video> --}}
                                                <video class="video-container" controls id="hover-video" width="300" autoplay muted poster="{{$m->cover_url?$m->cover_url:"" }}">
                                                    <source src="{{ $m->media_url }}" type="video/mp4">
                                                    {{-- Votre navigateur ne prend pas en charge la vidéo. --}}
                                                </video>
                                            </div>
                                            @else
                                            <iframe id="youtube-9854" frameborder="0" allowfullscreen="1"  id="hover-video"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                referrerpolicy="strict-origin-when-cross-origin"
                                                title="{{ $m->media_title }}" class="embed-responsive-item"
                                                src="{{ $m->media_url }}?autoplay=0&amp;controls=0&amp;disablekb=1&amp;playsinline=1&amp;cc_load_policy=0&amp;cc_lang_pref=auto&amp;widget_referrer=file%3A%2F%2F%2FB%3A%2Ftheme-boostrap%2Flooper-bak%2Fdist%2Fcomponent-rich-media.html&amp;noCookie=false&amp;rel=0&amp;showinfo=0&amp;iv_load_policy=3&amp;modestbranding=1&amp;enablejsapi=1&amp;widgetid=1">
                                            </iframe>
                                            @endif
                                        </div>
                                        <!-- /.figure-img -->
                                    </figure><!-- /.card-figure -->
                                </div>

                                {{--
                            </div> --}}
                            <div class="mt-3 mb-1 ml-5 row">
                                <div class="col-sm-12">
                                    <figcaption class="figure-caption">
                                        <ul class="mb-0 list-inline text-muted">
                                            <li class="list-inline-item">
                                                <a href="{{ route('editeMedia',['id'=>$m->id]) }}">
                                                    <span class="oi oi-pencil"></span>
                                                </a>
                                            </li>
                                            <li class="float-right list-inline-item">
                                                <a href="{{ route('deleteMedia',['id'=>$m->id]) }}"
                                                    onclick="event.preventDefault();deletemedia({{$m->id}})">
                                                    <span class="oi oi-trash"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </figcaption>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <div class="item">
                                        <span>Type :</span>
                                        <a href="{{ route('types') }}">{{ $m->type->type_name }}</a> /
                                        <span>Groupe :</span>
                                        <a href="{{ route('groupes') }}">{{ $m->type->group->group_name }}</a> /

                                        <span>Catégories :</span>
                                        @forelse ($m->categories as $cat)
                                        <a href="{{ route('categories') }}">{{ $cat->category_name }}</a>,
                                        @empty

                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.card -->
                        @else
                        <div class="card card-body  item">
                            <span>{{ Str::limit($m->media_title, 50, '...') }}</span>
                            <div class="pswp-gallery ratio ratio-16x9">
                                <div class="card card-figure">
                                    <!-- .card-figure -->
                                    <figure class="figure">
                                        <!-- .figure-img -->
                                        <div class="figure-img">
                                            <img class="img-fluid" src="{{ asset($m->cover_url)}}" alt="Card image cap">
                                            <a href="{{ asset($m->cover_url) }}" class="img-link" data-size="600x450">
                                                <span class="tile tile-circle bg-danger"><span class="oi oi-eye"></span>
                                                </span> <span class="img-caption d-none">Image caption goes
                                                    here</span></a>
                                            <div class="figure-action">
                                                <a href="#" class="btn btn-block btn-sm btn-primary">Voir en detail</a>
                                            </div>
                                        </div><!-- /.figure-img -->

                                    </figure><!-- /.card-figure -->
                                </div><!-- /.card -->
                            </div>
                            <div class="mt-3 mb-1 ml-5 row">
                                <div class="col-sm-3">
                                    <figcaption class="figure-caption">
                                        <ul class="mb-0 list-inline text-muted">
                                            <li class="list-inline-item">
                                                <a href="{{ route('editeMedia',['id'=>$m->id]) }}">
                                                    <span class="oi oi-pencil"></span>
                                                </a>
                                            </li>
                                            <li class="float-right list-inline-item">
                                                <a href="{{ route('deleteMedia',['id'=>$m->id]) }}"
                                                    onclick="event.preventDefault();deletemedia({{$m->id}})">
                                                    <span class="oi oi-trash"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </figcaption>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="item">
                                        <span>Type :</span>
                                        <a href="{{ route('types') }}">{{ $m->type->type_name }}</a> /
                                        <span>Groupe :</span>
                                        <a href="{{ route('groupes') }}">{{ $m->type->group->group_name }}</a> /

                                        <span>Catégories :</span>
                                        @forelse ($m->categories as $cat)
                                        <a href="{{ route('categories') }}">{{ $cat->category_name }}</a>,
                                        @empty

                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- /.card -->
                    @empty

                    @endforelse
                </div><!-- /.page-inner -->
                <div class="row">
                    <div class="card-body">
                        <hr>
                        <div class="el-example">
                            <ul class="pagination">
                                @if(!request()->has('page')|| request()->get('page')==1)
                                <li class="page-item disabled" hidden>
                                    <a class="page-link" href="">«</a>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="?page=1">«</a>
                                </li>
                                @endif
                                {{-- {{ dd($medias->lastPage) }} --}}
                                @for ($i=1; $i <= $medias->lastPage; $i++)
                                    <li
                                        class="page-item {{!request()->has('page') || request()->get('page')==$i ? ' active' : '' }}">
                                        <a class="page-link" href="{{ '/media?page=' . $i }}">{{ $i }}</a>
                                    </li>

                                    @endfor
                                    @if(request()->get('page')==$medias->lastPage)
                                    <li class="page-item" hidden>
                                        <a class="page-link" href="?{{ $medias->lastPage }}">»</a>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $medias->lastPage }}">»</a>
                                    </li>
                                    @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- /.page -->
        </div>
    </div>
</main>
@endsection

@section("script")
<!-- BEGIN PLUGINS JS -->
<script src="{{ asset('assets/vendor/photoswipe/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/vendor/photoswipe/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/vendor/plyr/plyr.min.js') }}"></script>
<script src="{{ asset('assets/javascript/pages/photoswipe-demo.js') }} "></script>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
    let query = this.value.toLowerCase();
    let items = document.querySelectorAll('.item');
    let count = 0;

    const image = document.getElementById('hover-image');
    const video = document.getElementById('hover-video');

    image.addEventListener('mouseenter', () => {
        video.style.display = 'block'; // Afficher la vidéo
        video.play(); // Démarrer la lecture de la vidéo
    });

    image.addEventListener('mouseleave', () => {
        video.pause(); // Mettre en pause la vidéo
        video.currentTime = 0; // Revenir au début de la vidéo
        video.style.display = 'none'; // Masquer la vidéo
    });

    items.forEach(item => {
        let text = item.textContent.toLowerCase();
        if (text.includes(query)) {
            item.style.display = 'block';
            count++;
        } else {
            item.style.display = 'none';
        }
    });
    let resultCount = document.getElementById('resultCount');
    let resulText = document.getElementById('infoTexte');
    if (query.length > 0) {
        resultCount.style.display = 'block';
        resulText.textContent = "Nombre d'éléments trouvés : "+count;
    } else {
        resultCount.style.display = 'none';
    }
});

    function deletemedia(id) {
            Swal.fire({
                title: "Suppression d'un media",
                text: "êtes-vous sûre de vouloir supprimer ce media ?",
                icon: 'warning',
                inputAttributes: {
                autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "OUI",
                cancelButtonText: "NON",
                showLoaderOnConfirm: true,
                preConfirm: async (login) => {
                    // alert('alert')
                            try {

                            } catch (error) {

                            }
                },allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                        if (result.isConfirmed) {
                            addCard(id,"","deleteMedia");
                        }
                });
            }



            function addCard(form, idLoad, url) {
        // event.preventDefault()
        var header = {'X-CSRF-TOKEN': $('[name="csrf"]').attr('content'),'Authorization': 'Bearer ' + $('[name="jpt-devref"]').attr('content'), 'Accept': 'application/json', 'X-localization': navigator.language};

        var autre = idLoad == '' ? '' : '../';
        Swal.fire({
            title: 'Merci de patienter...',
            icon: 'info'
        })
        $.ajax({
            url: url + '/' + form,
            method: "GET",
            // data: {
            //     'id': form
            // },
            success: function(data) {
                if (!data.reponse) {
                    Swal.fire({
                        title: data.msg,
                        icon: 'error'
                    })
                } else {
                    Swal.fire({
                        title: data.msg,
                        icon: 'success'
                    })
                    actualiser();
                }
            },
        });

    }
    function actualiser() {
        location.reload();
    }
</script>
@endsection
