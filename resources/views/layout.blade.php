<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Fiscal Registrar{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link href="{{ asset(mix('app.css', 'vendor/fiscal-registrar')) }}" rel="stylesheet" />
</head>
<body>
<div id="fiscal-registrar">
Welcome to Fiscal Registrar home page!
</div>

<script type="text/javascript" src="{{asset(mix('app.js', 'vendor/fiscal-registrar'))}}"></script>
</body>
</html>
