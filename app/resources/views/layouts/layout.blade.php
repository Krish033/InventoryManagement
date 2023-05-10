<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="ie=edge" http-equiv="X-UA-Compatible" />
	<meta content="{{ csrf_token() }}" name="_token" />
	<title>{{ config('app.name') }} - Admin</title>

	{{-- 
	@ Krishna -> Github modules
	 --}}
	<script src="https://cdn.jsdelivr.net/gh/Krish033/ValidatorJs/validator.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/krish033/GSTCalculator/build.min.js"></script>

	{{-- 
    @ Google fonts
    --}}
	<link href="https://fonts.gstatic.com" rel="preconnect" />
	<link
		href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
		rel="stylesheet" />

	{{--
    @ Jquery imports
	--}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	{{-- 
	@ SweetAlert imports
		--}}
	<link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.5/sweetalert2.css"
		integrity="sha512-yqCpLPABHnpDe3/QgEm1OO4Ohq0BBlBtJGMh5JbhdYEb6nahIm7sbtjilfSFyzUhxdXHS/cm8+FYfNstfpxcrg=="
		referrerpolicy="no-referrer" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.5/sweetalert2.min.js"
		integrity="sha512-jt82OWotwBkVkh5JKtP573lNuKiPWjycJcDBtQJ3BkMTzu1dyu4ckGGFmDPxw/wgbKnX9kWeOn+06T41BeWitQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	{{--
	@ Bootstrap imports
	--}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
		rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-reboot.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" rel="stylesheet" />
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>

	{{--
    @ DataTable imports
	--}}
	<script defer src="{{ url('/') }}/Assets2/js/dataTableExport.js"></script>
	<script defer src="{{ url('/') }}/Assets2/DataTable/js/dataTables.responsive.min.js"></script>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet" />
	<link
		href="https://cdn.datatables.net/v/bs4-4.6.0/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.1/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.css"
		rel="stylesheet" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
	<script
		src="https://cdn.datatables.net/v/bs4-4.6.0/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.1/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.js">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

	{{-- 
	@ Toastr imports
		--}}
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" rel="stylesheet" />

	{{--
	@ Dropify imports
	--}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" />
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.js"></script>

	{{--
    @ Individual imports
	--}}
	@yield('styles')

	{{--
    @ Fontawesome icons
	--}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

	{{--
    @ Custom types
	--}}
	<link href="{{ url('/') }}/Assets2/css/style.css" rel="stylesheet" />
	<script defer src="{{ url('/') }}/Assets2/js/main.js"></script>
	<script defer src="https://unpkg.com/alpinejs"></script>
	<script defer src="{{ url('/') }}/Assets2/helpers.js"></script>

</head>

<body>
	{{-- <textarea style="display: none;" id="txtThemeOption">{{json_encode($Theme)}}</textarea> --}}
	<input id="txtActiveName" name="txtActiveName" type="hidden" value="{{ $ActiveMenuName }}">
	<input id="txtRootUrl" name="txtRootUrl" type="hidden" value="{{ url('/') }}/">
	{{-- <div id="divsettings" class="display-none">{{json_encode($Settings)}}</div> --}}

	<header class="header fixed-top d-flex align-items-center" id="header">
		<div class="d-flex align-items-center justify-content-between">
			<a class="logo d-flex align-items-center" href="index.html">
				<img alt="" src="{{ url('/') }}/Assets2/img/logo.png" />
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
					<a class="nav-link nav-profile d-flex align-items-center pe-0" data-bs-toggle="dropdown" href="#">
						<img alt="" class="rounded-circle"
							src="{{ is_null($UInfo->ProfileImage) ? url('/') . '/' . $UInfo->ProfileImage : 'https://freesvg.org/img/abstract-user-flat-1.png' }}"
							style="width: 30px; height: 30px; object-fit: cover" />

						<span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span> </a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
						<li class="dropdown-header">
							<h6>{{ auth()->user()->name }}</h6>
							<span>{{ auth()->user()->email }}</span>
						</li>
						<li>
							<hr class="dropdown-divider" />
						</li>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="{{ url('/') }}/users-and-permissions/profile">
								<i class="bi bi-person"></i>
								<span>My Profile</span>
							</a>
						</li>

						<li>
							<form action="{{ url('/') }}/logout" method="post">
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
	<aside class="sidebar" id="sidebar">
		<ul class="sidebar-nav" id="sidebar-nav">
			@php
				echo $menus;
			@endphp
		</ul>
	</aside>

	<main class="main" id="main">
		@if (!$home)
			<nav>
				<ul class="breadcrumb main-bread">
					<li class="main-bread-back"><a href="{{ url()->previous() }}/"><i class="fa fa-arrow-left"></i></a>
					</li>
					<li class="breadcrumb-item main-bread-home"><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
					@foreach ($urls as $key => $breadcrumb)
						<li class="breadcrumb-item"><a href="{{ $breadcrumb }}">{{ $key }}</a></li>
					@endforeach
				</ul>
			</nav>
		@endif

		<section class="section dashboard">
			@yield('content')
		</section>
	</main>

	<script>
		$(document).ready(function() {
			$('.dropify').dropify();
		});
	</script>

	{{-- Scripts --}}
	@yield('scripts')

</body>

</html>
