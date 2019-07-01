<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Acompanhamento ENEE</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrpHz18FMieMTLFKo6OZkSSyy088HxfUE&libraries=places">
    </script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="ead-icon" sizes="180x180" href="/imagens/ead-icon.png">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">


</head>

<body>
    <div id="app" v-m16y-ctrls>
        <vue-layout></vue-layout>
    </div>

</body>

</html>
<style>
    .access-ctrls-wrapper .m-access-ctrls-btn {
        margin-top: 94px !important;
        padding: 0px !important;
        margin-right: 20px;
    }

    .m-access-ctrls-btn__content {
        font-size: 10px;
    }
</style>