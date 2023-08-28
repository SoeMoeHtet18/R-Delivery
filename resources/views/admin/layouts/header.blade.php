<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
      {{config('app.delivery_company_name')}} - Dashboard
    </title>

    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0"
          name="viewport"/>
    <meta http-equiv="Content-type"
          content="text/html; charset=utf-8">

      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all"
          rel="stylesheet"
          type="text/css"/>

    <link rel="stylesheet"
          href="{{ asset('quickadmin/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('quickadmin/css/components.css') }}"/>
    <link rel="stylesheet" href="{{ asset('quickadmin/css/quickadmin-layout.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('quickadmin/css/quickadmin-theme-default.css') }}"/>
    <link rel="stylesheet"
          href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

    <!-- <link rel="stylesheet"
          href="{{ asset('quickadmin/css/jquery.dataTables.min.css') }}"/> -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/css/bootstrap-slider.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- count start -->
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('quickadmin/css/ionicons.min.css') }}">
    <!-- jvectormap -->
    <!-- <link rel="stylesheet" href="{{ asset('quickadmin/css/jquery-jvectormap.css') }}"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('quickadmin/css/AdminLTE.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://kit.fontawesome.com/2a8a85ebf9.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ asset('fonts/vfs_fonts.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @include('admin.layouts.css')
    @stack('style')
    @yield('style')
</head>

<body class="page-header-fixed">
