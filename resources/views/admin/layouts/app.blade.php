<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta tags  -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />

  <title>Rolin - Project Test</title>
  <link rel="icon" type="image/png" href="images/favicon.png" />

  <!-- CSS Assets -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">



  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>


  <!-- Javascript Assets -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Javascript Third Party Libraries: Forms -->
  <script src="{{ asset('js/libs/forms.js') }}" defer></script>


  <!-- Special script for current page -->
  <script src="{{ asset('js/pages/components-popover.js') }}" defer></script>

  <!-- Special script for current page -->
  <script src="{{ asset('js/pages/components-table-advanced.js') }}" defer></script>

  <script src="{{ asset('js/pages/components-modal.js') }}" defer></script>


  <script src="{{ asset('js/custom.js') }}" defer></script>

  <!-- SWAL -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- FONTAWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <script>
    /**
     * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
     */
    localStorage.getItem("dark-mode") === "dark" &&
      document.documentElement.classList.add("dark");
  </script>
  <style>
    .dataTables_length {
      margin-bottom: 1rem;
      margin-top: 1rem;
    }

    .dataTables_paginate {
      margin-top: 1rem;
      /* jarak dari tabel */
    }

    .dataTables_info {
      margin-top: 1rem;
      /* jarak dari tabel */
    }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="is-header-blur is-sidebar-close">
  <!-- App preloader-->
  <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
    <div class="app-preloader-inner relative inline-block size-48"></div>
  </div>

  <!-- Page Wrapper -->
  <div id="root" class="min-h-100vh cloak flex grow bg-slate-50 dark:bg-navy-900">


    <!-- App Header Wrapper-->
    @include('admin.layouts.header')
    <!-- Sidebar -->
    <!-- dipindah ke header -->

    <!-- Sidemenu -->
    @include('admin.layouts.sidemenu')

    <!-- Mobile Searchbar -->
    @include('admin.layouts.mobilesearchbar')

    <!-- Right Sidebar -->
    @include('admin.layouts.rightbar')

    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8">
      {{ $slot }}
    </main>

  </div>

  @stack('scripts')

  <!-- Alpine.js for interactive components (accordion, dropdowns, etc.) -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>