@extends('layouts.templateAuth')


@section("content")
<main class="auth">
    <header id="auth-header" class="auth-header" style="background-image: url(assets/images/illustration/img-1.png);">
        <h1>
            DIKITIVI
            <span class="sr-only">Sign Up</span>
        </h1>
        <p> Already have an account? please <a href="{{ route('login') }}">Connectez-vous</a>
        </p>
    </header><!-- form -->
    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <div class="form-label-group">
                <input name="firstname" type="text" id="inputUser" class="form-control" placeholder="Nom" required="">
                <label for="inputUser">Nom</label>
            </div>
        </div>
        <!-- .form-group -->
        <div class="form-group">
            <div class="form-label-group">
                <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email" required="" autofocus="">
                <label for="inputEmail">Email</label>
            </div>
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <!-- .form-group -->
        <div class="form-group">
            <div class="form-label-group">
                <input name="password"  type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
                <label for="inputPassword">Password</label>
            </div>
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <input type="password" name="password_confirmation" id="inputPassword" class="form-control" placeholder="Password" required="">
                <label for="inputPassword">Password</label>
            </div>
        </div>
        <!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Créer</button>
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group text-center">
            <div class="custom-control custom-control-inline custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="newsletter"> <label class="custom-control-label"
                    for="newsletter">Sign me up for the newsletter</label>
            </div>
        </div><!-- /.form-group -->
        <!-- recovery links -->
        <p class="text-center text-muted mb-0"> By creating an account you agree to the <a href="#">Terms of Use</a> and
            <a href="#">Privacy Policy</a>. </p><!-- /recovery links -->
    </form><!-- /.auth-form -->
    <!-- copyright -->
    <footer class="auth-footer"> © 2024 All Rights Reserved. </footer>
</main><!-- /.auth -->

@endsection
