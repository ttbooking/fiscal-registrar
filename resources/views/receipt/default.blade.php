<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>{{ __('fiscal-registrar::main.title') }}{{ config('app.name') ? ' - '.config('app.name') : '' }}</title>

    <style>
        @media print {
            html {
                height: 100%;
            }
            body {
                transform: scale(.5);
                transform-origin: top left;
            }
            #receipt {
                border: 1px solid black;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            #receipt tfoot {
                break-inside: avoid;
            }
        }

        body {
            margin: 0;
        }
    </style>
</head>
<body>
@include('fiscal-registrar::receipt.body')
</body>
