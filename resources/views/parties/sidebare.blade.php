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
                    <li class="menu-item {{ Route::current()->getName() == 'serie' ? 'has-active' : ''}}">
                        <a href="{{ route('serie') }}" class="menu-link">
                            <span class="menu-icon fas fa-rocket"></span>
                            <span class="menu-text">Séries</span>
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
                    <a href="#" class="menu-link">
                        <span class="menu-icon fas fa-rocket"></span>
                        <span class="menu-text">Landing Page</span>
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
