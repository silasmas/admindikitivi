@extends("layouts.template",['titre'=>"Ajouter un media"])

@section("style")
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/photoswipe.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/default-skin/default-skin.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/plyr/plyr.css') }}">
@endsection

@section("content")

<main class="app-main">
    <!-- .wrapper -->
    <div class="wrapper">
        <!-- .page -->
        <div class="page has-sidebar has-sidebar-expand-xl">
            <!-- .page-inner -->
            <div class="page-inner">
                <header class="page-title-bar">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">
                                <a href="{{ route('media') }}"><i
                                        class="breadcrumb-icon fa fa-angle-left mr-2"></i>Liste
                                    des medias</a>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title"> {{ isset($media)?"Formumaire de modification":"Formumaire d'enregistrement"
                        }}
                    </h1>
                    @if(session()->has("msg"))
                    <div class="alert alert-primary" role="alert">
                        {{ session()->get("msg") }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </header>
                <div class="page-section">
                    <div class="d-xl-none">
                        <button class="btn btn-danger btn-floated" type="button" data-toggle="sidebar"><i
                                class="fa fa-th-list"></i></button>
                    </div><!-- .card -->
                    <div id="base-style" class="card">
                        <!-- .card-body -->
                        <div class="card-body">
                            {{-- <form method="POST" action="{{isset($media)?route('updateMedia') :route('registerMedia') }}"
                                enctype="multipart/form-data" id="data"> --}}
                            <form id="{{isset($media)?'dataUpdate':'data'}}">
                                @csrf
                                <!-- .fieldset -->
                                <fieldset>
                                    <legend>Base style</legend> <!-- .form-group -->
                                    <div class="form-group">
                                        <input name="id" id="idMedia" type="text" class="form-control" placeholder=""
                                            value="{{isset($media)?$media->id:"" }}" hidden>
                                        <label>Titre du Media
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body" title="Le titre principale du media"></i>
                                        </label>
                                        <input name="media_title" type="text" class="form-control" placeholder=""
                                            value="{{isset($media)?$media->media_title:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tf6">Description du media
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="La description pour faire comprendre le media"></i>
                                        </label>
                                        <textarea name="media_description" class="form-control" id="tf6" rows="3">
                                        {{ isset($media)?$media->media_description:"" }}
                                    </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre des contenants
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="Combien d'episode/chanson contient la serie/album??"></i>
                                        </label>
                                        <input name="belonging_count" type="number" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->belonging_count:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Source du Media
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="La source de provenance de la video. ex : YouTube, AWS..."></i>
                                        </label>
                                        <input name="source" type="text" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->source:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Temps du Media
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body" title="Le temps que met la vidéo (hh:mm)"></i>
                                        </label>
                                        <input name="time_length" type="time" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->time_length:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="pi3">URL du media
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body" title="Le lien du media, en forma"></i></label>
                                        <!-- .input-group -->
                                        <div class="input-group input-group-alt">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">http://</span>
                                            </div><input name="media_url" type="text" class="form-control" id="pi3"
                                                placeholder="" value="{{isset($media)?$media->media_url:"" }}">
                                        </div><!-- /.input-group -->
                                    </div>
                                    <div class="form-group">
                                        <label for="pi3">URL du teaser
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="Une courte vidéo présentant le média"></i></label>
                                        <!-- .input-group -->
                                        <div class="input-group input-group-alt">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">http://</span>
                                            </div><input name="teaser_url" type="text" class="form-control" id="pi3"
                                                placeholder="" value="{{ isset($media)?$media->teaser_url:"" }}">
                                        </div><!-- /.input-group -->
                                    </div>
                                    <div class="form-group">
                                        <label>Auteur
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="La personne qui a publié la vidéo sur YouTube ou autre site médiatique"></i>
                                        </label>
                                        <input name="author_names" type="text" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->author_names:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Nom de l'artiste
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="L'artiste auteur et/ou compositeur, si c'est une chanson'"></i>
                                        </label>
                                        <input name="artist_names" type="text" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->artist_names:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Ecrit par :
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="La personne qui a écrit l'histoire', si c'est du cinéma"></i>
                                        </label>
                                        <input name="writer" type="text" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->writer:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Realisateur
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="Le réalisateur du film ou de la chanson"></i>
                                        </label>
                                        <input name="director" type="text" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->director:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Date de publication
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="La date à laquelle le film ou la chanson a été publié pour la première fois"></i>
                                        </label>
                                        <input name="published_date" type="date" class="form-control" placeholder=""
                                            value="{{ isset($media)?$media->published_date:"" }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tf3">Uploader la vidéo
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body" title="Uploader la vidéo du media"></i>
                                        </label>
                                        <div class="custom-file">
                                            <input name="media_file_url" type="file" class="custom-file-input"
                                                id="media_file_url" multiple>
                                            <label class="custom-file-label" for="">Choisir la video</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tf3">Uploader Couverture
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="Une image qui sera affichée lorsque la vidéo n'est pas encore lue"></i>
                                        </label>
                                        <div class="custom-file">
                                            <input name="cover_url" type="file" class="custom-file-input" id="cover_url"
                                                multiple>
                                            <label class="custom-file-label" for="">Choisir fichier</label>
                                        </div>
                                        @isset($media)
                                        @if ($media->cover_url!=null)
                                        <figure class="figure">
                                            <!-- .figure-img -->
                                            <div class="figure-img">
                                                <img class="img-fluid" src="{{$media->cover_url}}" alt="Card image cap">
                                                <a href="{{ asset($media->cover_url) }}" class="img-link"
                                                    data-size="600x450">
                                                    <span class="tile tile-circle bg-danger"><span
                                                            class="oi oi-eye"></span>
                                                    </span> <span class="img-caption d-none">Image caption goes
                                                        here</span></a>
                                                <div class="figure-action">
                                                    <a href="#" class="btn btn-block btn-sm btn-primary">Voir en
                                                        detail</a>
                                                </div>
                                            </div>
                                        </figure> <br>
                                        @endif
                                        @endisset

                                        <label for="tf3">Uploader thumbnail
                                            <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body"
                                                title="Une image qui sera affichée lorsque la vidéo n'est pas encore lue (270X310)"></i>
                                        </label>
                                        <div class="custom-file">
                                            <input name="thumbnail_url" type="file" class="custom-file-input"
                                                id="thumbnail_url" multiple>
                                            <label class="custom-file-label" for="">Choisir fichier</label>
                                        </div>

                                        @isset($media)
                                        @if ($media->thumbnail_url!=null)
                                        <figure class="figure">
                                            <!-- .figure-img -->
                                            <div class="figure-img">
                                                <img class="img-fluid" src="{{$media->thumbnail_url}}" width="270"
                                                    height="310" alt="Card image cap">
                                                <a href="{{ asset($media->thumbnail_url) }}" class="img-link"
                                                    data-size="270x310">
                                                    <span class="tile tile-circle bg-danger"><span
                                                            class="oi oi-eye"></span>
                                                    </span> <span class="img-caption d-none">Image caption goes
                                                        here</span></a>
                                                <div class="figure-action">
                                                    <a href="#" class="btn btn-block btn-sm btn-primary">Voir en
                                                        detail</a>
                                                </div>
                                            </div>
                                        </figure>
                                        @endif
                                        @endisset
                                    </div>
                                    <div class="form-group">
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                            data-container="body"
                                            title="La tranche d'âge permise pour visionner la vidéo"></i>
                                        <div class="form-label-group">
                                            <label for="for_youth">Pour enfant ?
                                            </label>
                                            <select name="for_youth" class="custom-select" id="for_youth" required="">
                                                <option value="0" {{ isset($media)&&$media->for_youth==0?"selected":""
                                                    }}>NON</option>
                                                <option value="1" {{ isset($media)&&$media->for_youth==1?"selected":""
                                                    }}>OUI</option>
                                            </select>
                                            <label for="for_youth">Pour enfant ? </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                            data-container="body" title="Le média est il en direct ?"></i>
                                        <div class="form-label-group">
                                            <label for="is_live">Est un live?
                                            </label>
                                            <select name="is_live" class="custom-select" id="is_live" required="">
                                                <option value="0" {{ isset($media)&&$media->is_live==0?"selected":""
                                                    }}>NON
                                                </option>
                                                <option value="1" {{ isset($media)&&$media->is_live==1?"selected":""
                                                    }}>OUI
                                                </option>
                                            </select>
                                            <label for="is_live">Est un live? </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                            data-container="body"
                                            title="Est-ce un épisode appartenant à une série TV ou une chanson appartenant à un album ?"></i>
                                        <div class="form-label-group">
                                            <select name="belongs_to" class="custom-select" id="fls1">
                                                <option value=""> Appartien à :</option>
                                                @forelse ($medias as $m)
                                                <option value="{{ $m->id }}" {{ isset($media)&&$media->
                                                    belongs_to==$m->id?"selected":"" }}>{{ $m->media_title }}</option>
                                                @empty

                                                @endforelse
                                            </select> <label for="fls1">Les medias</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                            data-container="body" title="Sélectionner le type du media"></i>
                                        <div class="form-label-group">
                                            <select name="type_id" class="custom-select" id="type_id" required="">
                                                <option value=""> Type du media : </option>
                                                @forelse ($type->data as $m)
                                                <option value="{{ $m->id }}" {{isset($media)&&$media->
                                                    type->id==$m->id?"selected":"" }}>{{ $m->type_name }}</option>
                                                @empty

                                                @endforelse
                                            </select> <label for="fls1">Les type</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block">Choisir les catégories du media : <i tabindex="0"
                                                class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                                data-container="body" title="Le titre principale du media"></i>
                                        </label>
                                        @forelse ($categories->data as $m)
                                        <div class="custom-control custom-control-inline custom-checkbox">
                                            <input type="checkbox" name="categories_ids[]" class="custom-control-input"
                                                value="{{ $m->id }}" id="{{ $m->id }}"
                                                {{isset($media)?inArrayR($m->category_name, $media->categories,
                                            "category_name")?"checked":"":""}}>
                                            <label class="custom-control-label" for="{{ $m->id }}">{{
                                                $m->category_name}}</label>
                                            <div class="text-muted"> </div>
                                        </div>
                                        @empty

                                        @endforelse
                                    </div>
                                    <!-- /.form-group -->
                                    <button type="submit" class="btn btn-primary">{{ isset($media)?"Modifier":"Enregistrer" }}</button><br>
                                    <div class="d-flex justify-content-center mt-5 text-center request-message"></div>

                                </fieldset>
                                <!-- /.fieldset -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- <script>

</script> --}}
@endsection

@section("script")
    <script>
        // alert('ok')
        const navigator = window.navigator;
        const currentLanguage = $('html').attr('lang');
        const currentUser = $('[name="dktv-visitor"]').attr('content');
        const currentHost = $('[name="dktv-url"]').attr('content');
        const apiHost = $('[name="dktv-api-url"]').attr('content');
        /* Register form-data */
        $('form#data').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var categories = [];

            document.querySelectorAll('[name="categories_ids"]').forEach(item => {
                if (item.checked === true) {
                    categories.push(parseInt(item.value));
                }
            });

            for (let i = 0; i < categories.length; i++) {
                formData.append('categories_ids[' + i + ']', categories[i]);
            }

            $.ajax({
                headers: { 'Authorization': 'Bearer 23|fEmzaqAOGb6ld8Cej6NMU0VdXl3UISFkMDhoMLPp1754add6', 'Accept': 'multipart/form-data', 'X-localization': navigator.language },
                type: 'POST',
                contentType: 'multipart/form-data',
                url:'registerMedia',
                // url: apiHost + '/media',
                data: formData,
                beforeSend: function () {
                    // $('form#data .request-message').html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
                    Swal.fire({
                                title: 'Merci de patienter upload de la vidéo...',
                                icon: 'info'
                            })
                },
                success: function (res) {
                    console.log(res)
                    if (!res.reponse) {
                        Swal.fire({
                            title: res.msg,
                            icon: 'error'
                        })
                        console.log(res.data)
                        } else {
                        Swal.fire({
                            title: res.msg,
                            icon: 'success'
                            })

                        $("#data")[0].reset();
                        actualiser();
                        }
                    // var formElement = document.getElementById('form#data');
                    // formData.append('idMedia', res.data.id);
                    // add(formData, 'POST', 'registerMedia',"#data")
                },
                cache: false,
                contentType: false,
                processData: false,
                error: function (xhr, error, status_description) {
                    if ($('form#data .request-message').hasClass('text-success')) {
                        $('form#data .request-message').removeClass('text-success');
                    }
                    Swal.fire({
                                title: "Erreur de traitement",
                                icon: 'error'
                            })
                    console.log(xhr.status);
                    console.log(error);
                    console.log(status_description);

                    if (xhr.responseJSON) {
                        $('form#data .request-message').addClass('text-danger').html(xhr.responseJSON.message);
                        console.log(xhr.responseJSON);
                    }
                }
            });
        });
        $('form#dataUpdate').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var categories = [];
            var idMedia = document.getElementById('idMedia');
            document.querySelectorAll('[name="categories_ids"]').forEach(item => {
                if (item.checked === true) {
                    categories.push(parseInt(item.value));
                }
            });

            for (let i = 0; i < categories.length; i++) {
                formData.append('categories_ids[' + i + ']', categories[i]);
            }

            $.ajax({
                headers: { 'Authorization': 'Bearer 23|fEmzaqAOGb6ld8Cej6NMU0VdXl3UISFkMDhoMLPp1754add6', 'Accept': 'multipart/form-data', 'X-localization': navigator.language },
                type: 'PUT',
                contentType: 'multipart/form-data',
                url: apiHost + '/media/'+idMedia.value,
                data: formData,
                beforeSend: function () {
                    Swal.fire({
                                title: 'Merci de patienter la modification de la vidéo...',
                                icon: 'info'
                            })
                },
                success: function (res) {
                    console.log(res.data)
                    console.log( formData)
                    var formElement = document.getElementById('form#dataUpdate');
                    formData.append('idMedia', res.data.id);
                    add(formData, 'POST', '../updateMedia',"#dataUpdate")
                },
                cache: false,
                contentType: false,
                processData: false,
                error: function (xhr, error, status_description) {
                    console.log(xhr);
                    // if ($('form#data .request-message').hasClass('text-success')) {
                    //     $('form#data .request-message').removeClass('text-success');
                    // }

                    // if (xhr.responseJSON) {
                    //     $('form#data .request-message').addClass('text-danger').html(xhr.responseJSON.message);
                    //     console.log(xhr.responseJSON);
                    // }
                    Swal.fire({
                                title: xhr,
                                icon: 'error'
                            })
                    console.log(xhr.status);
                    console.log(error);
                    console.log(status_description);
                }
            });
        });
        function add(form, mothode, url,idf) {
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var f = form;
                var u = url;
                var idform = idf;
                Swal.fire({
                title: 'Merci de patienter enregistrement des données...',
                icon: 'info'
                })
                console.log(form)
                $.ajax({
                    url: u,
                    method: mothode,
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (data) {
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

                        $(idform)[0].reset();
                        actualiser();
                        }
                    },
                    error: function(xhr, status, error){
                        console.log(xhr);
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            $.each(errors, function(key, value){
                            errorMessage += value + '<br>';
                            });
                                        // Afficher les erreurs de validation à l'utilisateur
                            Swal.fire({
                                title: xhr.msg,
                                html: errorMessage,
                                icon: 'error'
                            })
                    }
                });
        }
        function actualiser() {
        location.reload();
        }
    </script>
@endsection
