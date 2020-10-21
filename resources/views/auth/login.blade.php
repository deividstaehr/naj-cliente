<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/alert.css">
        <link href="{{ env('APP_URL') }}ampleAdmin/dist/css/style.min.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}imagens/logo-naj-2020_N - Cópia.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />

        <script src="{{ env('APP_URL') }}naj-datatables/src/sweetalert2.min.js"></script>
        <script src="{{ env('APP_URL') }}naj-datatables/src/alerts.js"></script>

        <title>NAJ Cliente - Login</title>
    </head>
    <body>
        <h1>Teste</h1>

        <script src="{{ env('APP_URL') }}js/jquery.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery/dist/jquery.min.js"></script>        
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/popper.js/dist/umd/popper.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>        
        <script>
            $('[data-toggle="tooltip"]').tooltip();
            $(".preloader").fadeOut();
            $('#to-recover').on("click", function() {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });
        </script>
    </body>
</html>