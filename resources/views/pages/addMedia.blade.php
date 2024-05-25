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
                            <a href="{{ route('media') }}"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i>Liste des medias</a>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title"> {{ isset($media)?"Formumaire de modification":"Formumaire d'enregistrement" }}  </h1>
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

                        <form method="POST" action="{{isset($media)?route('updateMedia') :route('registerMedia') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- .fieldset -->
                            <fieldset>
                                <legend>Base style</legend> <!-- .form-group -->
                                <div class="form-group">
                                    <input name="id" type="text" class="form-control"
                                     placeholder="" value="{{isset($media)?$media->id:"" }}" hidden>
                                    <label>Titre du Media
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Le titre principale du media"></i>
                                    </label>
                                    <input name="media_title" type="text" class="form-control"
                                     placeholder="" value="{{isset($media)?$media->media_title:"" }}">
                                </div>
                                <div class="form-group">
                                    <label for="tf6">Description du media
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="La description pour faire comprendre le media"></i>
                                    </label>
                                    <textarea name="media_description" class="form-control" id="tf6"
                                        rows="3">
                                        {{ isset($media)?$media->media_description:"" }}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Nombre des contenants
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Combien d'episode/chanson contient la serie/album??"></i>
                                    </label>
                                    <input name="belonging_count" type="number" class="form-control" placeholder=""
                                    value="{{ isset($media)?$media->belonging_count:"" }}">
                                </div>
                                <div class="form-group">
                                    <label>Source du Media
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="La source de provenance de la video. ex : YouTube, AWS..."></i>
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
                                        data-container="body" title="Le lien du media, en forma"></i></label> <!-- .input-group -->
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
                                        data-container="body" title="Une courte vidéo présentant le média"></i></label> <!-- .input-group -->
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
                                        data-container="body" title="La personne qui a publié la vidéo sur YouTube ou autre site médiatique"></i>
                                    </label>
                                    <input name="author_names" type="text" class="form-control" placeholder=""
                                    value="{{ isset($media)?$media->author_names:"" }}">
                                </div>
                                <div class="form-group">
                                    <label>Nom de l'artiste
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="L'artiste auteur et/ou compositeur, si c'est une chanson'"></i>
                                    </label>
                                    <input name="artist_names" type="text" class="form-control" placeholder=""
                                    value="{{ isset($media)?$media->artist_names:"" }}">
                                </div>
                                <div class="form-group">
                                    <label>Ecrit par :
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="La personne qui a écrit l'histoire', si c'est du cinéma"></i>
                                    </label>
                                    <input name="writer" type="text" class="form-control" placeholder=""
                                    value="{{ isset($media)?$media->writer:"" }}">
                                </div>
                                <div class="form-group">
                                    <label>Realisateur
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Le réalisateur du film ou de la chanson"></i>
                                    </label>
                                    <input name="director" type="text" class="form-control" placeholder=""
                                    value="{{ isset($media)?$media->director:"" }}">
                                </div>
                                <div class="form-group">
                                    <label>Date de publication
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="La date à laquelle le film ou la chanson a été publié pour la première fois"></i>
                                    </label>
                                    <input name="published_date" type="date" class="form-control" placeholder=""
                                    value="{{ isset($media)?$media->published_date:"" }}">
                                </div>
                                <div class="form-group">
                                    <label for="tf3">Uploader Couverture
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Une image qui sera affichée lorsque la vidéo n'est pas encore lue"></i>
                                    </label>
                                    <div class="custom-file">
                                        <input name="cover_url" type="file" class="custom-file-input" id="cover_url" multiple>
                                        <label class="custom-file-label" for="">Choisir fichier</label>
                                    </div>
                                    <label for="tf3">Uploader thumbnail
                                        <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Une image qui sera affichée lorsque la vidéo n'est pas encore lue"></i>
                                    </label>
                                    <div class="custom-file">
                                        <input name="thumbnail_url" type="file" class="custom-file-input" id="thumbnail_url" multiple>
                                        <label class="custom-file-label" for="">Choisir fichier</label>
                                    </div>
                                    @isset($media)
                                    @if ($media->cover_url!=null)
                                        <figure class="figure">
                                        <!-- .figure-img -->
                                        <div class="figure-img">
                                            <img class="img-fluid" src="{{$media->cover_url}}" alt="Card image cap">
                                            <a href="{{ asset($media->cover_url) }}" class="img-link" data-size="600x450">
                                                <span class="tile tile-circle bg-danger"><span class="oi oi-eye"></span>
                                                </span> <span class="img-caption d-none">Image caption goes here</span></a>
                                            <div class="figure-action">
                                                <a href="#" class="btn btn-block btn-sm btn-primary">Voir en detail</a>
                                            </div>
                                        </div>
                                    </figure>
                                    @endif
                                    @endisset
                                    @isset($media)
                                    @if ($media->thumbnail!=null)
                                        <figure class="figure">
                                        <!-- .figure-img -->
                                        <div class="figure-img">
                                            <img class="img-fluid" src="{{$media->thumbnail_url}}" alt="Card image cap">
                                            <a href="{{ asset($media->thumbnail_url) }}" class="img-link" data-size="600x450">
                                                <span class="tile tile-circle bg-danger"><span class="oi oi-eye"></span>
                                                </span> <span class="img-caption d-none">Image caption goes here</span></a>
                                            <div class="figure-action">
                                                <a href="#" class="btn btn-block btn-sm btn-primary">Voir en detail</a>
                                            </div>
                                        </div>
                                    </figure>
                                    @endif
                                    @endisset
                                </div>
                                <div class="form-group">
                                    <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                data-container="body" title="La tranche d'âge permise pour visionner la vidéo"></i>
                                    <div class="form-label-group">
                                        <label for="for_youth">Pour enfant ?
                                        </label>
                                        <select name="for_youth" class="custom-select" id="for_youth" required="">
                                            <option value="0" {{ isset($media)&&$media->for_youth==0?"selected":"" }}>NON</option>
                                            <option value="1" {{ isset($media)&&$media->for_youth==1?"selected":"" }}>OUI</option>
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
                                            <option value="0" {{ isset($media)&&$media->is_live==0?"selected":"" }}>NON</option>
                                            <option value="1" {{ isset($media)&&$media->is_live==1?"selected":"" }}>OUI</option>
                                        </select>
                                        <label for="is_live">Est un live? </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                    data-container="body" title="Est-ce un épisode appartenant à une série TV ou une chanson appartenant à un album ?"></i>
                                    <div class="form-label-group">
                                        <select name="belongs_to" class="custom-select" id="fls1">
                                            <option value=""> Appartien à :</option>
                                            @forelse ($medias as $m)
                                            <option value="{{ $m->id }}" {{ isset($media)&&$media->belongs_to==$m->id?"selected":"" }}>{{ $m->media_title }}</option>
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
                                            <option value="{{ $m->id }}" {{isset($media)&&$media->type->id==$m->id?"selected":"" }}>{{ $m->type_name }}</option>
                                            @empty

                                            @endforelse
                                        </select> <label for="fls1">Les type</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Choisir les catégories du media : <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Le titre principale du media"></i>
                                    </label>
                                    @forelse ($categories->data as $m)
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="categories_ids[]" class="custom-control-input"
                                           value="{{ $m->id }}" id="{{ $m->id }}" {{isset($media)?inArrayR($m->category_name, $media->categories, "category_name")?"checked":"":""}}>
                                        <label class="custom-control-label" for="{{ $m->id }}">{{ $m->category_name}}</label>
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
            <div class="page-sidebar">
                <!-- .sidebar-header -->
                <header class="sidebar-header d-sm-none">
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item active">
                        <a href="#" onclick="Looper.toggleSidebar()"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i>Back</a>
                      </li>
                    </ol>
                  </nav>
                </header><!-- /.sidebar-header -->
                <!-- .sidebar-section-fill -->
                <div class="sidebar-section-fill">
                  <!-- .card -->
                  <div class="card card-reflow">
                    <!-- .card-body -->
                    <div class="card-body">
                      <button type="button" class="close mt-n1 d-none d-xl-none d-sm-block" onclick="Looper.toggleSidebar()" aria-label="Close"><span aria-hidden="true">×</span></button>
                      <h4 class="card-title"> Summary </h4><!-- grid row -->
                      <div class="row">
                        <!-- grid column -->
                        <div class="col-6">
                          <!-- .metric -->
                          <div class="metric">
                            <h6 class="metric-value"> $83,743 </h6>
                            <p class="metric-label mt-1"> Incomes </p>
                          </div><!-- /.metric -->
                        </div><!-- /grid column -->
                        <!-- grid column -->
                        <div class="col-6">
                          <!-- .metric -->
                          <div class="metric">
                            <h6 class="metric-value"> $18,821 </h6>
                            <p class="metric-label mt-1"> Expenses </p>
                          </div><!-- /.metric -->
                        </div><!-- /grid column -->
                        <!-- grid column -->
                        <div class="col-6">
                          <!-- .metric -->
                          <div class="metric">
                            <h6 class="metric-value"> 2,630 </h6>
                            <p class="metric-label mt-1"> Leads </p>
                          </div><!-- /.metric -->
                        </div><!-- /grid column -->
                        <!-- grid column -->
                        <div class="col-6">
                          <!-- .metric -->
                          <div class="metric">
                            <h6 class="metric-value"> 40 </h6>
                            <p class="metric-label mt-1"> Clients </p>
                          </div><!-- /.metric -->
                        </div><!-- /grid column -->
                      </div><!-- /grid row -->
                    </div><!-- /.card-body -->
                    <!-- .card-body -->
                    <div class="card-body border-top pb-1">
                      <h4 class="card-title"> Leads source </h4><!-- .progress -->
                      <div class="progress mb-2">
                        <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="33.84" aria-valuemin="0" aria-valuemax="100" style="width: 33.84%">
                          <span class="sr-only">33.84% Complete</span>
                        </div>
                        <div class="progress-bar bg-indigo" role="progressbar" aria-valuenow="24.71" aria-valuemin="0" aria-valuemax="100" style="width: 24.71%">
                          <span class="sr-only">24.71% Complete</span>
                        </div>
                        <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="26.29" aria-valuemin="0" aria-valuemax="100" style="width: 26.29%">
                          <span class="sr-only">26.29% Complete</span>
                        </div>
                        <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="15.15" aria-valuemin="0" aria-valuemax="100" style="width: 15.15%">
                          <span class="sr-only">15.15% Complete</span>
                        </div>
                      </div><!-- /.progress -->
                    </div><!-- /.card -->
                    <!-- .list-group -->
                    <div class="list-group list-group-bordered list-group-reflow">
                      <!-- .list-group-item -->
                      <div class="list-group-item justify-content-between align-items-center">
                        <span><i class="fas fa-square text-teal mr-2"></i> Mailchimp</span> <span class="text-muted">890 result</span>
                      </div><!-- /.list-group-item -->
                      <!-- .list-group-item -->
                      <div class="list-group-item justify-content-between align-items-center">
                        <span><i class="fas fa-square text-indigo mr-2"></i> Facebook</span> <span class="text-muted">650 result</span>
                      </div><!-- /.list-group-item -->
                      <!-- .list-group-item -->
                      <div class="list-group-item justify-content-between align-items-center">
                        <span><i class="fas fa-square text-pink mr-2"></i> Google</span> <span class="text-muted">692 result</span>
                      </div><!-- /.list-group-item -->
                      <!-- .list-group-item -->
                      <div class="list-group-item justify-content-between align-items-center">
                        <span><i class="fas fa-square text-purple mr-2"></i> Linkedin</span> <span class="text-muted">398 result</span>
                      </div><!-- /.list-group-item -->
                    </div><!-- /.list-group -->
                    <!-- .card-body -->
                    <div class="card-body border-top">
                      <div class="d-flex justify-content-between my-3">
                        <h4 class="card-title"> Recent activity </h4><a href="#">View all</a>
                      </div><!-- .timeline -->
                      <ul class="timeline timeline-fluid">
                        <!-- .timeline-item -->
                        <li class="timeline-item">
                          <!-- .timeline-figure -->
                          <div class="timeline-figure">
                            <span class="tile tile-circle tile-sm"><i class="far fa-calendar-alt fa-lg"></i></span>
                          </div><!-- /.timeline-figure -->
                          <!-- .timeline-body -->
                          <div class="timeline-body">
                            <!-- .media -->
                            <div class="media">
                              <!-- .media-body -->
                              <div class="media-body">
                                <p class="mb-0">
                                  <a href="#">Jeffrey Wells</a> created a <a href="#">schedule</a>
                                </p><span class="timeline-date">About a minute ago</span>
                              </div><!-- /.media-body -->
                            </div><!-- /.media -->
                          </div><!-- /.timeline-body -->
                        </li><!-- /.timeline-item -->
                        <!-- .timeline-item -->
                        <li class="timeline-item">
                          <!-- .timeline-figure -->
                          <div class="timeline-figure">
                            <span class="tile tile-circle tile-sm"><i class="oi oi-chat fa-lg"></i></span>
                          </div><!-- /.timeline-figure -->
                          <!-- .timeline-body -->
                          <div class="timeline-body">
                            <!-- .media -->
                            <div class="media">
                              <!-- .media-body -->
                              <div class="media-body">
                                <p class="mb-0">
                                  <a href="#">Anna Vargas</a> logged a <a href="#">chat</a> with team </p><span class="timeline-date">3 hours ago</span>
                              </div><!-- /.media-body -->
                            </div><!-- /.media -->
                          </div><!-- /.timeline-body -->
                        </li><!-- /.timeline-item -->
                        <!-- .timeline-item -->
                        <li class="timeline-item">
                          <!-- .timeline-figure -->
                          <div class="timeline-figure">
                            <span class="tile tile-circle tile-sm"><i class="fa fa-tasks fa-lg"></i></span>
                          </div><!-- /.timeline-figure -->
                          <!-- .timeline-body -->
                          <div class="timeline-body">
                            <!-- .media -->
                            <div class="media">
                              <!-- .media-body -->
                              <div class="media-body">
                                <p class="mb-0">
                                  <a href="#">Arthur Carroll</a> created a <a href="#">task</a>
                                </p><span class="timeline-date">8:14pm</span>
                              </div><!-- /.media-body -->
                            </div><!-- /.media -->
                          </div><!-- /.timeline-body -->
                        </li><!-- /.timeline-item -->
                        <!-- .timeline-item -->
                        <li class="timeline-item">
                          <!-- .timeline-figure -->
                          <div class="timeline-figure">
                            <span class="tile tile-circle tile-sm"><i class="fas fa-user-plus fa-lg"></i></span>
                          </div><!-- /.timeline-figure -->
                          <!-- .timeline-body -->
                          <div class="timeline-body">
                            <!-- .media -->
                            <div class="media">
                              <!-- .media-body -->
                              <div class="media-body">
                                <p class="mb-0">
                                  <a href="#">Sara Carr</a> invited to <a href="#">Stilearn Admin</a> project </p><span class="timeline-date">7:21pm</span>
                              </div><!-- /.media-body -->
                            </div><!-- /.media -->
                          </div><!-- /.timeline-body -->
                        </li><!-- /.timeline-item -->
                        <!-- .timeline-item -->
                        <li class="timeline-item">
                          <!-- .timeline-figure -->
                          <div class="timeline-figure">
                            <span class="tile tile-circle tile-sm"><i class="fa fa-folder fa-lg"></i></span>
                          </div><!-- /.timeline-figure -->
                          <!-- .timeline-body -->
                          <div class="timeline-body">
                            <!-- .media -->
                            <div class="media">
                              <!-- .media-body -->
                              <div class="media-body">
                                <p class="mb-0">
                                  <a href="#">Angela Peterson</a> added <a href="#">Looper Admin</a> to collection </p><span class="timeline-date">5:21pm</span>
                              </div><!-- /.media-body -->
                            </div><!-- /.media -->
                          </div><!-- /.timeline-body -->
                        </li><!-- /.timeline-item -->
                        <!-- .timeline-item -->
                        <li class="timeline-item">
                          <!-- .timeline-figure -->
                          <div class="timeline-figure">
                            <span class="tile tile-circle tile-sm"><i class="oi oi-person fa-lg"></i></span>
                          </div><!-- /.timeline-figure -->
                          <!-- .timeline-body -->
                          <div class="timeline-body">
                            <!-- .media -->
                            <div class="media">
                              <!-- .media-body -->
                              <div class="media-body">
                                <div class="avatar-group mb-2">
                                  <a href="#" class="user-avatar user-avatar-sm"><img src="assets/images/avatars/uifaces4.jpg" alt=""></a> <a href="#" class="user-avatar user-avatar-sm"><img src="assets/images/avatars/uifaces5.jpg" alt=""></a> <a href="#" class="user-avatar user-avatar-sm"><img src="assets/images/avatars/uifaces6.jpg" alt=""></a> <a href="#" class="user-avatar user-avatar-sm"><img src="assets/images/avatars/uifaces7.jpg" alt=""></a>
                                </div>
                                <p class="mb-0">
                                  <a href="#">Willie Dixon</a> and 3 others followed you </p><span class="timeline-date">4:32pm</span>
                              </div><!-- /.media-body -->
                            </div><!-- /.media -->
                          </div><!-- /.timeline-body -->
                        </li><!-- /.timeline-item -->
                      </ul><!-- /.timeline -->
                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
                </div><!-- /.sidebar-section-fill -->
              </div>
        </div>
    </div>
</div>

</main>

@endsection

