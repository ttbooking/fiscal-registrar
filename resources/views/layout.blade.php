<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('/vendor/fiscal-registrar/img/favicon.png') }}" />

    <title>{{ __('fiscal-registrar::main.title') }}{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link href="{{ asset(mix('app.css', 'vendor/fiscal-registrar')) }}" rel="stylesheet" />
</head>
<body>
<div id="fiscal-registrar" v-cloak>
    <router-view></router-view>
</div>

<!-- Global Fiscal Registrar Object -->
<script type="text/javascript">
    window.FiscalRegistrar = @json($fiscalRegistrarScriptVariables);
</script>

<script type="text/javascript" src="{{ asset(mix('manifest.js', 'vendor/fiscal-registrar')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('vendor.js', 'vendor/fiscal-registrar')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('app.js', 'vendor/fiscal-registrar')) }}"></script>
</body>
</html>
