<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title> {{config('app.name') }} | {{isset($titre)?$titre:""}}</title>
    <meta property="og:title" content="Dashboard">
    <meta name="author" content="Dikitivi">
    <meta property="og:locale" content="fr_FR">
    <meta name="description" content="La partie admin de la plateforme DIKITIVI">
    <meta property="og:description" content="La partie admin de la plateforme DIKITIVI">
    <link rel="canonical" href="https://dikitivi.com">
    <meta property="og:url" content="https://dikitivi.com">
    <meta property="og:site_name" content="Dikitivi">
    <script type="application/ld+json">
      {
        "name": "Dikitivi - Dasboard",
        "description": "La partie admin de la plateforme DIKITIVI",
        "author":
        {
          "@type": "Person",
          "name": "Silas Masimango"
        },
        "@type": "WebSite",
        "url": "silasmas.com",
        "headline": "Dashboard",
        "@context": "http://dikitivi.com"
      }
    </script><!-- End SEO tag -->
    <!-- FAVICONS -->
    <link rel="apple-touch-icon" sizes="144x144" href="assets/apple-touch-icon.png">
    <link rel="shortcut icon" href="assets/favicon.ico">
    <meta name="theme-color" content="#3063A0"><!-- End FAVICONS -->
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet"><!-- End GOOGLE FONT -->
    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/open-iconic/font/css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}"><!-- END PLUGINS STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme.min.css') }}" data-skin="default">
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme-dark.min.css') }}" data-skin="dark">
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/custom.css') }}">
    @yield("style")

    <script>
      var skin = localStorage.getItem('skin') || 'default';
      var isCompact = JSON.parse(localStorage.getItem('hasCompactMenu'));
      var disabledSkinStylesheet = document.querySelector('link[data-skin]:not([data-skin="' + skin + '"])');
      // Disable unused skin immediately
      disabledSkinStylesheet.setAttribute('rel', '');
      disabledSkinStylesheet.setAttribute('disabled', true);
      // add flag class to html immediately
      if (isCompact == true) document.querySelector('html').classList.add('preparing-compact-menu');
    </script><!-- END THEME STYLES -->
  </head>
  <body>
     <!-- .app -->
     <div class="app">
