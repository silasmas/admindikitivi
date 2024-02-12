<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title> {{config('app.name') }} | {{isset($titre)?$titre:""}}</title>
    <meta property="og:title" content="Authentification">
    <meta name="author" content="Dkitivi">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="description" content="Portail de gestion DIKIVI">
    <meta property="og:description" content="Portail de gestion DIKIVI">
    <link rel="canonical" href="https://uselooper.com">
    <meta property="og:url" content="https://uselooper.com">
    <meta property="og:site_name" content="Dikitivi">
    <script type="application/ld+json">
      {
        "name": "Dikitivi",
        "description": "Portail de gestion DIKIVI",
        "author":
        {
          "@type": "Person",
          "name": "Dkitivi"
        },
        "@type": "WebSite",
        "url": "",
        "headline": "Authentification",
        "@context": "http://schema.org"
      }
    </script><!-- End SEO tag -->
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="144x144" href="assets/apple-touch-icon.png">
    <link rel="shortcut icon" href="assets/favicon.ico">
    <meta name="theme-color" content="#3063A0"><!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet"><!-- End Google font -->
    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"><!-- END PLUGINS STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme.min.css') }}" data-skin="default">
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme-dark.min.css') }}" data-skin="dark">
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/custom.css') }}">

    
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


    @yield("content")

      <!-- BEGIN BASE JS -->
      <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/popper.js/umd/popper.min.js') }}"></script>
      <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script> <!-- END BASE JS -->
      <!-- BEGIN PLUGINS JS -->
      <script src="{{ asset('assets/vendor/particles.js/particles.js') }}"></script>
      <script>
        /**
         * Keep in mind that your scripts may not always be executed after the theme is completely ready,
         * you might need to observe the `theme:load` event to make sure your scripts are executed after the theme is ready.
         */
        $(document).on('theme:init', () =>{
        //   particlesJS.load(@dom-id, @path-json, @callback (optional)); 
          particlesJS.load('announcement', 'assets/javascript/pages/particles.json');
        })
      </script> <!-- END PLUGINS JS -->
      <!-- BEGIN THEME JS -->
      <script src="{{ asset('assets/javascript/theme.min.js') }}"></script>

     
    </body>
  </html>