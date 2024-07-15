<!-- .app-aside -->
<aside class="app-aside app-aside-expand-md app-aside-light">
    <!-- .aside-content -->
    <div class="aside-content">
        {{--
        <!-- .aside-header -->
        <header class="aside-header d-block d-md-none">
            <!-- .btn-account -->
            <button class="btn-account" type="button" data-toggle="collapse" data-target="#dropdown-aside">
                <span class="user-avatar user-avatar-lg"><img src="assets/images/avatars/profile.jpg" alt=""></span>
                <span class="account-icon"><span class="fa fa-caret-down fa-lg"></span></span>
                <span class="account-summary"><span class="account-name">Beni Arisandi</span>
                    <span class="account-description">Marketing Manager</span></span></button> <!-- /.btn-account -->
            <!-- .dropdown-aside -->
            <div id="dropdown-aside" class="dropdown-aside collapse">
                <!-- dropdown-items -->
                <div class="pb-3">
                    <a class="dropdown-item" href="#"><span class="dropdown-icon oi oi-person"></span> Profile</a> <a
                        class="dropdown-item" href="auth-signin-v1.html"><span
                            class="dropdown-icon oi oi-account-logout"></span> Logout</a>
                    <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Help Center</a> <a
                        class="dropdown-item" href="#">Ask Forum</a> <a class="dropdown-item" href="#">Keyboard
                        Shortcuts</a>
                </div><!-- /dropdown-items -->
            </div><!-- /.dropdown-aside -->
        </header><!-- /.aside-header --> --}}
        <!-- .aside-menu -->
        <div class="overflow-hidden aside-menu">
            <!-- .stacked-menu -->
            <nav id="stacked-menu" class="stacked-menu">
                <!-- .menu -->
                <ul class="menu">
                    <!-- .menu-item -->
                    <li class="menu-item {{ Route::current()->getName() == 'dashboard' ? 'has-active' : ''}}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <span class="menu-icon fas fa-home"></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li><!-- /.menu-item -->

                    <!-- .menu-item -->
                    <li class="menu-item {{ Route::current()->getName() == 'media' ? 'has-active' : ''}}">
                        <a href="{{ route('media') }}" class="menu-link">
                            <span class="menu-icon fas fa-film"></span>
                            <span class="menu-text">Medias</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::current()->getName() == 'categories' ? 'has-active' : ''}}">
                        <a href="{{ route('categories') }}" class="menu-link">
                            <span class="menu-icon fas fa-tags"></span>
                            <span class="menu-text">Gestion Catégorie</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::current()->getName() == 'types' ? 'has-active' : ''}}">
                        <a href="{{ route('types') }}" class="menu-link">
                            <span class="menu-icon fas fa-list-ul"></span>
                            <span class="menu-text">Gestion des types</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::current()->getName() == 'groupes' ? 'has-active' : ''}}">
                        <a href="{{ route('groupes') }}" class="menu-link">
                            <span class="menu-icon fas fa-rocket"></span>
                            <span class="menu-text">Gestion des groupes</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::current()->getName() == 'pays' ? 'has-active' : ''}}">
                        <a href="{{ route('pays') }}" class="menu-link">
                            <span class="menu-icon fas fa-globe"></span>
                            <span class="menu-text">Gestion des pays</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::current()->getName() == 'roles' ? 'has-active' : ''}}">
                        <a href="{{ route('roles') }}" class="menu-link">
                            <span class="menu-icon fas fa-user-circle"></span>
                            <span class="menu-text">Gestion des roles</span>
                        </a>
                    </li>
                        <li class="menu-item {{ Route::current()->getName() == 'client' ? 'has-active' : ''}}">
                        <a href="{{ route('client') }}" class="menu-link">
                            <span class="menu-icon fas fa-users"></span>
                            <span class="menu-text">Clients</span>
                        </a>
                    </li><!-- /.menu-item -->
                    <!-- .menu-header -->
                    <li class="menu-header">Partenaire </li><!-- /.menu-header -->
                    <li class=" {{ Route::current()->getName() == 'gifted' ? 'has-active' : ''}}">
                        <a href="{{ route('gifted') }}" class="menu-link">
                            <span class="menu-icon fas fa-gift"></span>
                            <span class="menu-text">Donations</span>
                        </a>

                    </li>
                    <a href="{{ route('gifted') }}" class="menu-link">
                        <span class="menu-icon fas fa-handshake"></span>
                        <span class="menu-text">Fournisseur</span>
                    </a>

                </ul><!-- /.menu -->
            </nav><!-- /.stacked-menu -->
        </div><!-- /.aside-menu -->
        <!-- Skin changer -->
        <footer class="p-2 aside-footer border-top">
            <button class="btn btn-light btn-block text-primary" data-toggle="skin"><span
                    class="d-compact-menu-none">Night mode</span> <i class="ml-1 fas fa-moon"></i></button>
        </footer><!-- /Skin changer -->
    </div><!-- /.aside-content -->
</aside><!-- /.app-aside -->
