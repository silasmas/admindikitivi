@extends('layouts.templateAuth')


@section("content")

<!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
    <![endif]-->
<!-- .auth -->

<main class="auth auth-floated">
    <!-- form -->
    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="mb-4">
            <div class="mb-3">
                <img class="rounded" src="{{ asset('assets/images/logo.png') }}" alt="" height="72">
            </div>
            <h1 class="h3"> Connexion </h1>
        </div>
        {{-- <p class="mb-4 text-left"> Don't have a account? <a href="{{ route('register') }}">Crée un compte</a> --}}
        </p><!-- .form-group -->
        <div class="mb-4 form-group">
            <label class="text-left d-block" for="inputUser">Email</label>
            <input type="text" id="inputUser" name="email" class="form-control form-control-lg"
                value="{{ old('email') }}" required="" autofocus="">

        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="mb-4 form-group">
            <label class="text-left d-block" for="inputPassword">Mot de passe</label>
            <input type="password" id="inputPassword" name="password" class="form-control form-control-lg" required="">
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="mb-4 form-group">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Connexion</button>
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="text-center form-group">
            <div class="custom-control custom-control-inline custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="remember" id="remember-me">
                {{-- <label class="custom-control-label" for="remember-me">Keep me sign in</label> --}}
            </div>
        </div><!-- /.form-group -->
        <!-- recovery links -->
        {{-- <p class="py-2">
            <a href="auth-recovery-username.html" class="link">Forgot Username?</a> <span class="mx-2">·</span> <a
                href="auth-recovery-password.html" class="link">Forgot Password?</a>
        </p><!-- /recovery links --> --}}
        <!-- copyright -->
        <p class="px-3 mb-0 text-center text-muted"> © 2024 Tous droits réservés. <a href="https://dikitivi.com">Dikitivi</a>, <a target="blank" href="https://dikitivi.com/about/privacy_policy"> Confidentialité</a> & <a
                href="https://dikitivi.com/about/terms_of_use" target="blank">Conditions d'utilisation</a>
        </p>
    </form><!-- /.auth-form -->
    <!-- .auth-announcement -->
    <div id="announcement" class="auth-announcement"
        style="background-image: url(assets/images/bg-home.webp);">
        <div class="announcement-body">
            <h2 class="announcement-title"> Profitez de notre plateforme</h2>
            <a href="https://dikitivi.com" class="btn btn-warning"><i class="fa fa-fw fa-angle-right"></i>Aller sur le site</a>
        </div>
    </div><!-- /.auth-announcement -->
</main><!-- /.auth -->

@endsection
