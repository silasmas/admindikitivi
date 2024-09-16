@extends("layouts.template",['titre'=>"Agents"])



@section("content")
<!-- .app-main -->
<main class="app-main">
    <!-- .wrapper -->
    <div class="wrapper">
        <!-- .page -->
        <div class="page has-sidebar has-sidebar-fluid has-sidebar-expand-xl">
            <!-- .page-inner -->
            <div class="page-inner page-inner-fill position-relative">
                <header class="shadow-sm page-navs bg-light">
                    <!-- .input-group -->
                    <div class="input-group has-clearable">
                        <label class="input-group-prepend" for="searchClients">
                            <span class="input-group-text">{{ count($membres->data).' Client'.s($membres->data) }} </span>
                        </label>
                    </div><!-- /.input-group -->
                </header>
                <header class="shadow-sm page-navs bg-light">
                    <!-- .input-group -->
                    <div class="input-group has-clearable">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
                        </button>
                        <label class="input-group-prepend" for="searchClients">
                            <span class="input-group-text"><span class="oi oi-magnifying-glass"></span>
                            </span></label> <input type="text" class="form-control" id="searchClients"
                            data-filter=".board .list-group-item" placeholder="Trouvez un membre">
                    </div><!-- /.input-group -->
                </header>
                {{-- <button type="button" class="btn btn-primary btn-floated position-absolute" data-toggle="modal"
                    data-target="#clientNewModal" title="Add new client"><i class="fa fa-plus"></i></button> --}}
                <!-- board -->
                <div class="p-0 board perfect-scrollbar">
                    <!-- .list-group -->
                    <div class="list-group list-group-flush list-group-divider border-top" data-toggle="radiolist">
                        <!-- .list-group-item -->
                        @forelse ($membres->data as $m)
                        <div class="list-group-item {{ $loop->first?" active":"" }}" data-toggle="sidebar"
                            data-sidebar="show" onclick="active('{{$m->email }}')">
                            <a href="{{ " #".$m->email }}" data-toggle="tab" class="stretched-link"></a>
                            <!-- .list-group-item-figure -->
                            <div class="list-group-item-figure">
                                <div class="tile tile-circle bg-blue"> {{ substr($m->firstname,0,1) }} </div>
                            </div><!-- /.list-group-item-figure -->
                            <!-- .list-group-item-body -->
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title"> {{ $m->firstname." ".$m->lastname }} </h4>
                                <p class="list-group-item-text">{{ $m->email }} </p>
                            </div><!-- /.list-group-item-body -->
                        </div>
                        @empty

                        @endforelse
                        <!-- /.list-group-item -->
                    </div><!-- /.list-group -->
                </div><!-- /board -->
            </div><!-- /.page-inner -->
            @forelse ($membres->data as $m)
            <!-- .page-sidebar -->
            <div class="tab-pane fade {{ $loop->first?" show active":"" }} element" id="{{ $m->email }}" role="tabpanel"
                aria-labelledby="{{ $m->email." -tab" }}">
                <div class="page-sidebar bg-light">
                    <!-- .sidebar-header -->
                    <header class="sidebar-header d-xl-none">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">
                                    <a href="#" onclick="Looper.toggleSidebar()"><i
                                            class="mr-2 breadcrumb-icon fa fa-angle-left"></i>Back</a>
                                </li>
                            </ol>
                        </nav>
                    </header><!-- /.sidebar-header -->
                    <!-- .sidebar-section -->
                    <div class="sidebar-section sidebar-section-fill">
                        <div class="text-center">
                            <a href="user-profile.html" class="user-avatar user-avatar-xl">
                                <img src="{{$m->avatar_url }}" alt=""></a>
                            <h2 class="mt-2 mb-0 h4">{{ $m->firstname." ".$m->lastname }}</h2>
                            <div class="my-1">
                                <i class="fa fa-star text-yellow"></i> <i class="fa fa-star text-yellow"></i> <i
                                    class="fa fa-star text-yellow"></i> <i class="fa fa-star text-yellow"></i> <i
                                    class="far fa-star text-yellow"></i>
                            </div>
                            <p class="text-muted"><i class="fa fa-envelope"></i>{{ $m->email }}</p>
                        </div>
                        <div class="nav-scroller border-bottom">
                            <!-- .nav-tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active show" data-toggle="tab"
                                        href="#client-billing-contact">Profil</a>
                                </li>
                            </ul><!-- /.nav-tabs -->
                        </div><!-- /.nav-scroller -->
                        <!-- .tab-content -->
                        <div class="pt-4 tab-content" id="clientDetailsTabs">
                            <!-- .tab-pane -->
                            <div class="tab-pane fade show active" id="client-billing-contact" role="tabpanel"
                                aria-labelledby="client-billing-contact-tab">
                                <!-- .card -->
                                <div class="card">
                                    <!-- .card-body -->
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 id="client-billing-contact-tab" class="card-title"> Detail du client
                                            </h2><button type="button" class="btn btn-link" data-toggle="modal"
                                                data-target="#clientBillingEditModal">Réinitialiser le mot de
                                                passe</button>
                                        </div>
                                        <address> {{ $m->address_2?$m->address_2:" Pas d'info de l'adresse" }}
                                        </address>
                                        <address> {{ $m->address_2?$m->address_2:" Pas d'info de l'adresse" }}
                                        </address>

                                        <p class="text-muted"><i class="fa fa-phone"></i> : {{ $m->phone?$m->phone:" Pas
                                            d'info du phone" }}</p>
                                        <p class="text-muted"><i class="fa fa-user"></i> : {{ sexe($m->gender) }}</p>

                                    </div><!-- /.card-body -->
                                </div><!-- /.card -->
                                <!-- .card -->
                                {{-- <div class="mt-4 card">
                                    <!-- .card-body -->
                                    <div class="card-body">
                                        <h2 class="card-title"> Contacts </h2><!-- .table-responsive -->
                                        <div class="table-responsive">
                                            <table class="table table-hover" style="min-width: 678px">
                                                <thead>
                                                    <tr>
                                                        <th> Name </th>
                                                        <th> Email </th>
                                                        <th> Phone </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="align-middle"> Alexane Collins </td>
                                                        <td class="align-middle"> fhauck@gmail.com </td>
                                                        <td class="align-middle"> (062) 109-9222 </td>
                                                        <td class="text-right align-middle">
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-secondary"
                                                                data-toggle="modal"
                                                                data-target="#clientContactEditModal"><i
                                                                    class="fa fa-pencil-alt"></i> <span
                                                                    class="sr-only">Edit</span></button> <button
                                                                type="button"
                                                                class="btn btn-sm btn-icon btn-secondary"><i
                                                                    class="far fa-trash-alt"></i> <span
                                                                    class="sr-only">Remove</span></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- /.table-responsive -->
                                    </div><!-- /.card-body -->
                                    <!-- .card-footer -->
                                    <div class="card-footer">
                                        <a href="#clientContactNewModal" class="card-footer-item" data-toggle="modal"><i
                                                class="mr-1 fa fa-plus-circle"></i> Add contact</a>
                                    </div><!-- /.card-footer -->
                                </div><!-- /.card --> --}}
                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div><!-- /.sidebar-section -->
                </div><!-- /.page-sidebar -->

            </div>
            @empty

            @endforelse
            <!-- Keep in mind that modals should be placed outsite of page sidebar -->
        </div><!-- /.page -->
    </div><!-- /.wrapper -->
</main><!-- /.app-main -->
@endsection
@section("script")
<!-- BEGIN PLUGINS JS -->
<script src="{{ asset('assets/vendor/sortablejs/Sortable.min.js') }}"></script>
<script>
    function active(id){
    var elements = document.querySelectorAll('.element');
    var activeElement = document.querySelector('.element.active');

    // Trouver l'index de l'élément actif
    var currentIndex = Array.from(elements).indexOf(activeElement);

    // Trouver l'index de l'élément à activer
    var nextIndex = Array.from(elements).findIndex(element => element.id === id);

    // Retirer la classe 'active show' de tous les éléments
    elements.forEach(element => {
        element.classList.remove('active', 'show');
    });

    // Ajouter la classe 'active show' à l'élément spécifié par son ID
    elements[nextIndex].classList.add('active', 'show');
  }

</script>
@endsection
