@extends("layouts.template",['titre'=>"Film"])

@section("style")
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/photoswipe.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/photoswipe/default-skin/default-skin.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/vendor/plyr/plyr.css') }}" @endsection @section("content") <main
    class="app-main">
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
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="card card-figure">
                        <!-- .card-figure -->
                        <figure class="figure">
                          <!-- .figure-img -->
                          <div class="figure-img">
                            <img class="img-fluid" src="assets/images/dummy/img-7.jpg" alt="Card image cap">
                            <div class="figure-description">
                              <h6 class="figure-title"> Figure description </h6>
                              <p class="text-muted mb-0">
                                <small>Laboriosam neque officia adipisci quo ut placeat labore? Doloribus, ipsam? Voluptates, minus.</small>
                              </p>
                            </div>
                            <div class="figure-tools">
                              <a href="#" class="tile tile-circle tile-sm mr-auto"><span class="oi oi-data-transfer-download"></span></a> <span class="badge badge-warning">Gadget</span>
                            </div>
                            <div class="figure-action">
                              <a href="#" class="btn btn-block btn-sm btn-primary">Quick Action</a>
                            </div>
                          </div><!-- /.figure-img -->
                          <figcaption class="figure-caption">
                            <ul class="list-inline d-flex text-muted mb-0">
                              <li class="list-inline-item mr-auto">
                                <span class="oi oi-paperclip"></span> 2MB </li>
                              <li class="list-inline-item">
                                <span class="oi oi-calendar"></span>
                              </li>
                            </ul>
                          </figcaption>
                        </figure><!-- /.card-figure -->
                      </div>

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
