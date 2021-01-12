<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/static/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/static/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/static/favicons/favicon-16x16.png">
    <link rel="manifest" href="/assets/static/favicons/site.webmanifest">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>UnitiBudget</title>
    <meta name="description" content="A simple but powerful family budget tracker helping manage where hard earned money going.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script>window.env = @json($env)</script>
</head>
<body>
<div id="app"></div>

<!-- build files will be auto injected -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
