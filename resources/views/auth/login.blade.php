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
            <h1 class="h3"> Sign In </h1>
        </div>
        <p class="text-left mb-4"> Don't have a account? <a href="{{ route('register') }}">Crée un compte</a>
        </p><!-- .form-group -->
        <div class="form-group mb-4">
            <label class="d-block text-left" for="inputUser">Email</label>
            <input type="text" id="inputUser" name="email" class="form-control form-control-lg"
                value="{{ old('email') }}" required="" autofocus="">

        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group mb-4">
            <label class="d-block text-left" for="inputPassword">Mot de passe</label>
            <input type="password" id="inputPassword" name="password" class="form-control form-control-lg" required="">
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group mb-4">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Connexion</button>
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group text-center">
            <div class="custom-control custom-control-inline custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="remember-me">
                <label class="custom-control-label" for="remember-me">Keep me sign in</label>
            </div>
        </div><!-- /.form-group -->
        <!-- recovery links -->
        <p class="py-2">
            <a href="auth-recovery-username.html" class="link">Forgot Username?</a> <span class="mx-2">·</span> <a
                href="auth-recovery-password.html" class="link">Forgot Password?</a>
        </p><!-- /recovery links -->
        <!-- copyright -->
        <p class="mb-0 px-3 text-muted text-center"> © 2018 All Rights Reserved. Dikitivi<a href="#">Privacy</a> and <a
                href="#">Terms</a>
        </p>
    </form><!-- /.auth-form -->
    <!-- .auth-announcement -->
    <div id="announcement" class="auth-announcement"
        style="background-image: url(assets/images/illustration/img-1.png);">
        <div class="announcement-body">
            <h2 class="announcement-title"> How to Prepare for an Automated Future </h2><a href="#"
                class="btn btn-warning"><i class="fa fa-fw fa-angle-right"></i> Check Out Now</a>
        </div>
    </div><!-- /.auth-announcement -->
</main><!-- /.auth -->

@endsection
