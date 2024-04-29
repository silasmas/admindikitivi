@extends("layouts.template",['titre'=>"Ajouter un media"])

@section("style")
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/photoswipe.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/default-skin/default-skin.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/plyr/plyr.css') }}" @endsection @section("content") <main
    class="app-main">
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
                            <a href="#"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i>Liste des medias</a>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title"> Formumaire d'enregistrement </h1>
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

                        <form method="POST" action="{{ route('registerMedia') }}" accept="multipart/form-data">
                            @csrf
                            <!-- .fieldset -->
                            <fieldset>
                                <legend>Base style</legend> <!-- .form-group -->
                                <div class="form-group">
                                    <label>Titre du Media</label>
                                    <input name="media_title" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="tf6">Description du media</label>
                                    <textarea name="media_description" class="form-control" id="tf6"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Nombre des contenants</label>
                                    <input name="belonging_count" type="number" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Source du Media</label>
                                    <input name="source" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Temps du Media</label>
                                    <input name="time_length" type="time" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="pi3">URL du media</label> <!-- .input-group -->
                                    <div class="input-group input-group-alt">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">http://</span>
                                        </div><input name="media_url" type="text" class="form-control" id="pi3"
                                            placeholder="uselooper.com">
                                    </div><!-- /.input-group -->
                                </div>
                                <div class="form-group">
                                    <label for="pi3">URL du teaser</label> <!-- .input-group -->
                                    <div class="input-group input-group-alt">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">http://</span>
                                        </div><input name="teaser_url" type="text" class="form-control" id="pi3"
                                            placeholder="uselooper.com">
                                    </div><!-- /.input-group -->
                                </div>
                                <div class="form-group">
                                    <label>Auteur</label>
                                    <input name="author_names" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Nom de l'artiste</label>
                                    <input name="artist_names" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Ecrit par :</label>
                                    <input name="writer" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Realisateur</label>
                                    <input name="director" type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>Date de publication</label>
                                    <input name="published_date" type="date" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="tf3">Uploader Couverture</label>
                                    <div class="custom-file">
                                        <input name="cover_url" type="file" class="custom-file-input" id="tf3" multiple>
                                        <label class="custom-file-label" for="tf3">Choisir fichier</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label for="for_youth">Pour enfant ?</label>
                                        <select name="for_youth" class="custom-select" id="for_youth" required="">
                                            <option value="0">NON</option>
                                            <option value="1">OUI</option>
                                        </select>
                                        <label for="for_youth">Pour enfant ? </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label for="is_live">Est un live?</label>
                                        <select name="is_live" class="custom-select" id="is_live" required="">
                                            <option value="0">NON</option>
                                            <option value="1">OUI</option>
                                        </select>
                                        <label for="is_live">Est un live? </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <select name="belongs_to" class="custom-select" id="fls1">
                                            <option value=""> Appartien à : </option>
                                            @forelse ($medias as $m)
                                            <option value="{{ $m->id }}">{{ $m->media_title }}</option>
                                            @empty

                                            @endforelse
                                        </select> <label for="fls1">Les medias</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <select name="type_id" class="custom-select" id="fls1" required="">
                                            <option value=""> Type du media : </option>
                                            @forelse ($type->data as $m)
                                            <option value="{{ $m->id }}">{{ $m->type_name }}</option>
                                            @empty

                                            @endforelse
                                        </select> <label for="fls1">Les type</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Choisir les catégories du media :</label>
                                    @forelse ($categories->data as $m)
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="categories_ids[]" class="custom-control-input"
                                            id="{{ $m->id }}">
                                        <label class="custom-control-label" for="{{ $m->id }}">{{ $m->category_name
                                            }}</label>
                                        <div class="text-muted"> </div>
                                    </div>
                                    @empty

                                    @endforelse
                                </div>
                                <!-- /.form-group -->
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </fieldset><!-- /.fieldset -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</main>

@endsection
