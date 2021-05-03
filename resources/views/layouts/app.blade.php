<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="#" target="_self">
    <title>
        午餐整合平台
    </title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url('/AdminLTE/plugins/summernote/summernote-bs4.min.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"/>
    <link   href="{{ asset('css/app.css') }}"   rel="stylesheet">
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ url('corp/logo_sakawa_horizontal.svg') }}" alt="SkwLogo" height="60" width="60">
    </div>

    <!-- Navbar -->

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>
    </nav>

    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="{{ url('/corp/skw_logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Sakawa</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <div class="img-circle elevation-2">

                    </div>
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ request()->is(['user','user/*']) ? 'active' : '' }}" disabled>
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>
                                管理使用者
                            </p>
                        </a>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link {{ request()->is(['/','record']) ? 'active' : '' }} ">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>
                                錢錢
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }} ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>扣款/儲值</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/record') }}" class="nav-link {{ request()->is('record') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>金錢紀錄</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link {{ request()->is('task','task/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-utensils"></i>
                            <p>
                                訂餐任務
                                {{--TODO 這裡的active要修--}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('task.index') }}" class="nav-link {{ request()->is('task','task/*') ? 'active' : '' }} ">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{--TODO 這裡的active要修--}}
                                    <p>任務列表</p>
                                </a>
                            </li>
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ url('/record') }}" class="nav-link {{ request()->is('') ? 'active' : '' }}">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    --}}{{--TODO 這裡的active要修--}}
{{--                                    <p>參加任務</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('user.index') }}" class="nav-link {{ request()->is('') ? 'active' : '' }}">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    --}}{{--TODO 這裡的active要修--}}
{{--                                    <p>任務結算</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-business-time"></i>
                            <p>
                                打卡
                                {{--TODO 這裡的active要修--}}
                                <i class="right fas fa-angle-left"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('task.index') }}" class="nav-link {{ request()->is('/task') ? 'active' : '' }} ">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{--TODO 這裡的active要修--}}
                                    <p>歷史紀錄</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link {{ request()->is(['restaurant','restaurant/*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-store"></i>
                            <p>
                                餐廳
                                {{--TODO 這裡的active要修--}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('restaurant.index') }}" class="nav-link {{ request()->is('restaurant') ? 'active' : '' }} ">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{--TODO 這裡的active要修--}}
                                    <p>餐廳列表</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                管理
                                {{--TODO 這裡的active要修--}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('task.index') }}" class="nav-link {{ request()->is('/task') ? 'active' : '' }} ">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{--TODO 這裡的active要修--}}
                                    <p>人員資產表</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item justify-content-center">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="btn btn-danger btn-lg" type="submit">登出
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            @yield('title')
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        @yield('main.body')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2021 <a href="https://github.com/STUTuna/laravel-corp">帥氣鮪魚</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 0.0.1
        </div>
    </footer>

    <aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- AdminLTE App -->
<!-- Bootstrap 4 -->
<script src=" {{ url('/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src=" {{ url('/AdminLTE/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src=" {{ url('/AdminLTE/plugins/sparklines/sparkline.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src=" {{ url('/AdminLTE/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src=" {{ url('/AdminLTE/plugins/moment/moment.min.js') }}"></script>
<script src=" {{ url('/AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src=" {{ url('/AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src=" {{ url('/AdminLTE/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src=" {{ url('/AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src=" {{ url('/AdminLTE/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{--<script src=" {{ url('/AdminLTE/dist/js/demo.js') }}"></script>--}}


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js" integrity="sha256-92U7H+uBjYAJfmb+iNPi7DPoj795ZCTY4ZYmplsn/fQ=" crossorigin="anonymous"></script>

@yield('script')
@stack('js')
</body>
</html>
