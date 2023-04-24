<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }} - Admin</title>

    {{-- Krishna validatorjs --}}
    <script src="https://cdn.jsdelivr.net/gh/Krish033/ValidatorJs/validator.js"></script>

    {{-- ======================================================================================================
    Google fonts
    ====================================================================================================== --}}
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />

    {{-- CSS ==================================================================================================
    Data Tabe
    ====================================================================================================== --}}
    <link rel="stylesheet" href="{{url('/')}}/Assets2/plugins/DataTable/css/responsive.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="{{url('/')}}/Assets2/css/datatable-extension.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.jqueryui.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    {{-- CSS ==================================================================================================
    Bootstrap
    ====================================================================================================== --}}
    <link href="{{url('/')}}/Assets2/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{url('/')}}/Assets2/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    {{-- CSS ==================================================================================================
    Style plugins
    ====================================================================================================== --}}
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/Assets2/plugins/dropify/css/dropify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/loader.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/js/lightbox/css/lightgallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    {{-- CSS ==================================================================================================
    Custom styles
    ====================================================================================================== --}}
    <link href="{{url('/')}}/Assets2/css/style.css" rel="stylesheet" />
    <style>
        .collapse,
        .nav-item {
            cursor: pointer;
        }

        .table-img {
            width: 40px;
            height: 40px;
        }


        i svg {
            width: 20px !important;
            height: 20px !important;
            overflow: hidden;
        }


        .label {
            color: #000 !important;
        }

        button .svg-inline--fa {
            pointer-events: none !important;
        }


        .card-footer-btn {
            display: flex;
            align-items: center;
            border-top-left-radius: 0 !important;
            border-top-right-radius: 0 !important;
        }

        .text-uppercase-bold-sm {
            text-transform: uppercase !important;
            font-weight: 500 !important;
            letter-spacing: 2px !important;
            font-size: .85rem !important;
        }

        .hover-lift-light {
            transition: box-shadow .25s ease, transform .25s ease, color .25s ease, background-color .15s ease-in;
        }

        .justify-content-center {
            justify-content: center !important;
        }

        .btn-group-lg>.btn,
        .btn-lg {
            padding: 0.8rem 1.85rem;
            font-size: 1.1rem;
            border-radius: 0.3rem;
        }

        .btn-dark {
            color: #fff;
            background-color: #1e2e50;
            border-color: #1e2e50;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(30, 46, 80, .09);
            border-radius: 0.25rem;
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }

        .p-5 {
            padding: 3rem !important;
        }

        .card-body {
            flex: 1 1 auto;
            padding: 1.5rem 1.5rem;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }

        .table td,
        .table th {
            border-bottom: 0;
            border-top: 1px solid #edf2f9;
        }

        .table>:not(caption)>*>* {
            padding: 1rem 1rem;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }

        .px-0 {
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .table thead th,
        tbody td,
        tbody th {
            vertical-align: middle;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .icon-circle[class*=text-] [fill]:not([fill=none]),
        .icon-circle[class*=text-] svg:not([fill=none]),
        .svg-icon[class*=text-] [fill]:not([fill=none]),
        .svg-icon[class*=text-] svg:not([fill=none]) {
            fill: currentColor !important;
        }

        .svg-icon>svg {
            width: 1.45rem;
            height: 1.45rem;
        }
    </style>

    <style>
        @media only screen and (max-width: 750px) {
            .card-div-main {
                width: 100% !important;
            }
        }
    </style>

    @yield('styles')

    <link rel="stylesheet" href="{{url('/')}}/Assets2/plugins/sweet-alert/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/themify.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/icofont.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">

    <link id="color" rel="stylesheet" href="{{url('/')}}/assets/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/Custom.css">

    {{-- ======================================================================================================
    SCRIPTS
    ====================================================================================================== --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    {{-- JS ==================================================================================================
    Bootstrap
    ====================================================================================================== --}}
    <script src="{{url('/')}}/Assets2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/bootstrap.css">
    <script src="{{url('/')}}/assets/js/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    {{-- JS ==================================================================================================
    Data Tabe
    ====================================================================================================== --}}
    <script src="{{ url('/') }}/Assets2/js/dataTableExport.js"></script>
    <script src="{{url('/')}}/assets/plugins/DataTable/js/jquery.dataTables.min.js"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.buttons.min.js">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.colVis.min.js">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.select.min.js">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.html5.js"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.print.js"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js">
    </script>
    <script src="{{url('/')}}/assets/plugins/DataTable/js/dataTables.responsive.min.js"></script>
    <script src="{{url('/')}}/assets/js/dataTableExport.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    {{-- JS ==================================================================================================
    Pluginsx
    ====================================================================================================== --}}
    <script src="{{url('/')}}/assets/plugins/dropify/js/dropify.min.js"></script>
    <script src="{{url('/')}}/assets/js/toastr.min.js"></script>
    <script src="{{url('/')}}/assets/js/select2/select2.full.min.js"></script>
    <script src="{{url('/')}}/assets/js/select2/select2-custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    </script>
    <script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js"></script>
    <script src="{{url('/')}}/assets/js/custom-prototype.js"></script>
    <script src="{{url('/')}}/Assets2/plugins/sweet-alert/sweetalert.js"></script>
    <script src="{{url('/')}}/Assets2/plugins/sweet-alert/sweetalert.min.js"></script>

    {{-- JS ==================================================================================================
    Custom
    ====================================================================================================== --}}
    <script>
        $(document).ready(function() {
            // Show success messages
            @if(session()->has('success'))
                toastr.success('{{ session()->get("success") }}')
            @endif    
            // show error messages
            @if(session()->has('error'))
                toastr.error('{{ session()->get("error") }}', 'failed')
            @endif   
            // Show info messages
            @if(session()->has('info'))
                toastr.info('{{ session()->get("info") }}')
            @endif    
        })
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" />
</head>

<body oncontextmenu="return true;">
    {{-- <textarea style="display: none;" id="txtThemeOption">{{json_encode($Theme)}}</textarea> --}}
    <input type="hidden" name="txtActiveName" id="txtActiveName" value="{{$ActiveMenuName}}">
    <input type="hidden" name="txtRootUrl" id="txtRootUrl" value="{{url('/')}}/">
    {{-- <div id="divsettings" class="display-none">{{json_encode($Settings)}}</div> --}}

    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="{{ url('/') }}/Assets2/img/logo.png" alt="" />
                <span class="d-none d-lg-block">{{ config('app.name') }}</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle" href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li>
                {{-- {{ dd($user) }} --}}
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{url('/')}}/{{$UInfo->ProfileImage}}" alt="Profile" class="rounded-circle"
                            style="width: 30px; height: 30px; object-fit: cover" />
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span> </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{auth()->user()->name}}</h6>
                            <span>{{ auth()->user()->email }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ url('/') }}/users-and-permissions/profile">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        {{-- <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                <i class="bi bi-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li> --}}
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        {{-- <li>
                            <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                                <i class="bi bi-question-circle"></i>
                                <span>Need Help?</span>
                            </a>
                        </li> --}}
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <form action="{{url('/')}}/logout" method="post">
                                @csrf
                                <button class="dropdown-item d-flex align-items-center" href="#">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            @php
            echo $menus;
            @endphp
        </ul>
    </aside>

    <main id="main" class="main">
        <section class="section dashboard">
            @yield('content')
        </section>
    </main>

    {{-- Other Scripts --}}
    <script src="{{url('/')}}/Assets2/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="{{url('/')}}/Assets2/vendor/chart.js/chart.umd.js"></script>
    <script src="{{url('/')}}/Assets2/vendor/echarts/echarts.min.js"></script>
    <script src="{{url('/')}}/Assets2/vendor/quill/quill.min.js"></script>
    <script src="{{url('/')}}/Assets2/vendor/tinymce/tinymce.min.js"></script>
    <script src="{{url('/')}}/Assets2/vendor/php-email-form/validate.js"></script>

    <script src="{{url('/')}}/assets/js/bootstrap/popper.min.js"></script>
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.js"></script>
    <script src="{{url('/')}}/assets/js/tooltip-init.js"></script>
    <script src="{{url('/')}}/assets/js/custom.js"></script>
    <script src="{{url('/')}}/assets/js/support.js"></script>
    <script src="{{url('/')}}/Assets2/js/main.js"></script>
    <script>
        $(document).ready(function () {
            $('.dropify').dropify();
        });
    </script>
    {{-- Scripts --}}
    @yield('scripts')
</body>

</html>