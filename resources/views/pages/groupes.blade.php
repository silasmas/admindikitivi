@extends("layouts.template",['titre'=>"Groupe"])

@section("style")
<link href="{{ asset('assets/stylesheets/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section("content")
<main class="app-main">
    <div class="wrapper">
        <!-- .page -->
        <div class="page py-0">
            <div class="page-inner">
                <header class="page-title-bar">
                    <!-- .breadcrumb -->
                    <!-- /.breadcrumb -->
                    <!-- floating action -->
                    <button type="button" id="btnrond" class="btn btn-success btn-floated" data-toggle="modal"
                        data-target="#modalBoardConfig">
                        <span id="spanbtnrond" class="fa fa-plus">
                        </span></button> <!-- /floating action -->
                    <!-- title and toolbar -->
                    <div class="d-md-flex align-items-md-start">
                        <h1 class="page-title mr-sm-auto"> Liste des groupes </h1><!-- .btn-toolbar -->
                        <div id="dt-buttons" class="btn-toolbar"></div><!-- /.btn-toolbar -->
                    </div><!-- /title and toolbar -->
                </header>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom du groupe</th>
                                            <th>Description</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         @forelse ($groupes->data as $g)
                                        <tr class="gradeX">
                                            <td>{{ $loop->index+1}}</td>
                                            <td>{{ $g->group_name}}</td>
                                            <td>{{ $g->group_description}}</td>
                                            <td class="center">
                                                <p>
                                                    <a href="{{ $g->id}}" id="deleteGroup"
                                                        onclick="event.preventDefault();deletemedia({{$g->id}})"
                                                        class="btn btn-outline btn-danger dim">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <a href="" onclick="event.preventDefault();edite({{$g->id}})"
                                                        class="btn btn-outline btn-warning dim">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </p>
                                            </td>
                                        </tr>
                                        @empty
                                        <h2>
                                            {{ $groupes->message }}
                                        </h2>
                                        @endforelse
                                    </tbody> 
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom du groupe</th>
                                            <th>Description </th>
                                            <th>Options</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .modalBoardConfig -->
    <div class="modal modal-drawer fade" id="modalBoardConfig" tabindex="-1" role="dialog"
        aria-labelledby="modalBoardConfigTitle" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog modal-drawer-right" role="document">
            <!-- .modal-content -->
            <div id="modalContentLayer1" class="modal-content">
                <!-- .modal-header -->
                <div class="modal-header">
                    <h5 id="modalBoardConfigTitle" class="modal-title"> Formulaire pour enregistrer le groupe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body">
                    <form method="POST" id="formGroupe">
                        @csrf
                        <!-- .fieldset -->
                        <fieldset>
                            <input name="id" id="idGroupe" type="text" class="form-control" placeholder="" value=""
                                hidden>
                            <div class="form-group">
                                <label>Nom du groupe (FR)
                                    <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Le nom que doit avoir le group en français"></i>
                                </label>
                                <input name="group_name_fr" id="group_name_fr" type="text" class="form-control"
                                    placeholder="" value="">
                            </div>
                            <div class="form-group">
                                <label>Nom du groupe (EN)
                                    <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Le nom que doit avoir le group en Anglais"></i>
                                </label>
                                <input name="group_name_en" id="group_name_en" type="text" class="form-control"
                                    placeholder="" value="">
                            </div>
                            <div class="form-group">
                                <label>Nom du groupe (LN)
                                    <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body" title="Le nom que doit avoir le group en Lingala"></i>
                                </label>
                                <input name="group_name_ln" id="group_name_ln" type="text" class="form-control"
                                    placeholder="" value="">
                            </div>
                            <div class="form-group">
                                <label for="tf6">Description du groupe
                                    <i tabindex="0" class="fa fa-info-circle text-gray" data-toggle="tooltip"
                                        data-container="body"
                                        title="La description pour expliquer le sens du groupe"></i>
                                </label>
                                <textarea name="group_description" id="group_description" class="form-control" id="tf6"
                                    rows="3">

                        </textarea>
                            </div>
                            <!-- /.form-group -->
                            <button type="submit" id="btnCat" class="btn btn-primary">Enregistrer</button>
                        </fieldset><!-- /.fieldset -->
                    </form>
                </div><!-- /.modal-body -->
            </div><!-- .modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modalBoardConfig -->
</main>
@endsection
@section("script")
<script src="{{asset('assets/javascript/dataTables/datatables.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.dataTables-example').DataTable({
            language: {
                processing: "Traitement en cours...",
                search: "Rechercher&nbsp;:",
                lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
                info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                infoPostFix: "",
                loadingRecords: "Chargement en cours...",
                zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable: "Aucune donnée disponible dans le tableau",
                paginate: {
                    first: "Premier",
                    pagingType: "full_numbers", // Afficher tous les boutons de pagination
                    previous: "Pr&eacute;c&eacute;dent",
                    next: "Suivant",
                    last: "Dernier"
                },
                aria: {
                    sortAscending: ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            },
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'NewsLetter'
                },
                {
                    extend: 'pdf',
                    title: 'NewsLetter'
                },

                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],"columnDefs": [
            { "width": "700px", "targets": 2 }, // Définir une largeur de 100 pixels pour la première colonne
            { "width": "200px", "targets": 3 }, // Définir une largeur de 100 pixels pour la première colonne
            { "width": "200px", "targets": 1 }, // Définir une largeur de 150 pixels pour la deuxième colonne
            // Ajouter d'autres colonnes avec leurs largeurs respectives
        ]

        });

        $(document).on("click", "#deleteCat", function (e) {
                e.preventDefault();
                var id = $(this).attr("href");
                deleteTeame(id, 'destroy_slide');
            });
    });

    $("#formGroupe").on("submit", function (e) {
            e.preventDefault();
            //  alert("register")
            add("#formGroupe", 'POST', 'addGroupe')
    });

        $(document).on("submit","#formGroupEdite", function (e) {
            e.preventDefault();
            // alert("ok")
             add("#formGroupEdite", 'POST', 'updateGroupe')
        });
        function edite(id) {
            Swal.fire({
                title: 'Merci de patienter...',
                icon: 'info'
            })
            $.ajax({
                url:'editGroupe/' + id,
                method: "GET",
                success: function(data) {
                    if (!data.reponse) {
                        Swal.fire({
                            title: data.msg,
                            icon: 'error'
                        })
                    } else {
                        // Remplir les champs du formulaire avec les données reçues
                    $('#group_name_fr').val(data.data.group_name_fr);
                    $('#group_name_en').val(data.data.group_name_en);
                    $('#group_name_ln').val(data.data.group_name_ln);
                    $('#idGroupe').val(data.data.id);
                    $('#group_description').val(data.data.group_description);

                    // Changer le texte du bouton
                    $('#btnCat').text('Modifier');
                    $("#formGroupe").off("submit");
                    $('#formGroupe').attr('id', 'formGroupEdite');
                    // Sélectionner le bouton qui déclenche l'ouverture du modal
                    var button = $('#btnrond');
                        // Simuler un clic sur le bouton pour ouvrir le modal
                    button.click();
                    $('#modalBoardConfigTitle').text('Formulaire pour modifier le groupe');
                        Swal.fire({
                            title: data.msg,
                            icon: 'success'
                        })
                        // actualiser();
                    }
                },
            });
        }
    function deletemedia(id) {
            Swal.fire({
                title: "Suppression d'une type",
                text: "êtes-vous sûre de vouloir supprimer ce type ?",
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
                            addCard(id,"","deleteGroupe");
                        }
                });
            }


            function addCard(form, idLoad, url) {

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
    function add(form, mothode, url) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var f = form;
        var u = url;
        Swal.fire({
            title: 'Merci de patienter...',
            icon: 'info'
        })
        $.ajax({
            url: u,
            method: mothode,
            data: $(f).serialize(),
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

                    $(f)[0].reset();
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