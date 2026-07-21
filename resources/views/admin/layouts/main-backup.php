<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite([
        'resources/css/vertical-layout-light/style.css',
        'resources/vendors/feather/feather.css',
        'resources/vendors/ti-icons/css/themify-icons.css',
        'resources/vendors/css/vendor.bundle.base.css',
    ])
</head>

<body>
<div class="container-scroller">
    @include('layouts.header')
    @include('layouts.sidebar')

    {{ $slot }}
</div>


<!-- CORE Vendor (WAJIB ADA) -->
<script src="/vendors/js/vendor.bundle.base.js"></script>

<!-- DataTables -->
<script src="/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<link rel="stylesheet" href="/vendors/datatables.net-bs4/dataTables.bootstrap4.css">


<!-- Stack scripts MUST be here -->
@stack('scripts')

<!-- Plugin Vendors -->
<script src="/vendors/chart.js/Chart.min.js"></script>

<!-- Template Core Scripts -->
<script src="/js/off-canvas.js"></script>
<script src="/js/hoverable-collapse.js"></script>
<script src="/js/template.js"></script>
<script src="/js/settings.js"></script>
<script src="/js/todolist.js"></script>

<!-- Custom Dashboard Scripts -->
<script src="/js/dashboard.js"></script>
<script src="/js/Chart.roundedBarCharts.js"></script>


</body>
</html>
