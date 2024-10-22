<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('/vendor/fiscal-registrar/img/favicon.png') }}" />

    <title>{{ __('fiscal-registrar::main.title') }}{{ config('app.name') ? ' - '.config('app.name') : '' }}</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />

    @vite('resources/js/app.js')
    {{
        Vite::useHotFile('vendor/fiscal-registrar/hot')
            ->useBuildDirectory('vendor/fiscal-registrar/build')
            ->withEntryPoints(['resources/js/app.js'])
    }}
</head>
<body>
<div id="fiscal-registrar" v-cloak>
    <router-view></router-view>
</div>

<!-- Global Fiscal Registrar Object -->
<script type="text/javascript">
    window.FiscalRegistrar = @json($fiscalRegistrarScriptVariables);
</script>

</body>
</html>
