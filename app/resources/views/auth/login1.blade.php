<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="ProPlus Logics">
    <meta name="_token" content="{{ csrf_token() }}" />

    <link rel="apple-touch-icon" sizes="180x180" href="{{url('/')}}/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{url('/')}}/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/')}}/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{url('/')}}/assets/images/favicon/manifest.json">
    <link rel="mask-icon" href="{{url('/')}}/assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <title> {{ config('app.name', 'SSA') }}</title>
    <!-- Google font-->
    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/fontawesome.css?r={{date('dmyHis')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/icofont.css?r={{date('dmyHis')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/themify.css?r={{date('dmyHis')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/flag-icon.css?r={{date('dmyHis')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/feather-icon.css?r={{date('dmyHis')}}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/bootstrap.css?r={{date('dmyHis')}}">

    <!-- DataTable css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/datatables.css?r={{date('dmyHis')}}">
    <link rel="stylesheet"
        href="{{url('/')}}/assets/plugins/DataTable/css/responsive.dataTables.min.css?r={{date('dmyHis')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/datatable-extension.css?r={{date('dmyHis')}}">
    <link rel="stylesheet" href="{{url('/')}}/assets/plugins/dynamic-form/v2/dynamicForm.min.css?r={{date('dmyHis')}}">
    <link rel="stylesheet" type="text/css"
        href="{{url('/')}}/assets/js/lightbox/css/lightgallery.css?r={{date('dmyHis')}}">

    <!-- sweetalert css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/sweetalert2.css?r={{date('dmyHis')}}">
    <!-- select2 css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/select2.css?r={{date('dmyHis')}}">
    <!-- toastr css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/toastr.css?r={{date('dmyHis')}}">
    <!-- dropify css-->
    <link rel="stylesheet" type="text/css"
        href="{{url('/')}}/assets/plugins/dropify/css/dropify.min.css?r={{date('dmyHis')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/loader.css?r={{date('dmyHis')}}">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css?r={{date('dmyHis')}}">
    <link id="color" rel="stylesheet" href="{{url('/')}}/assets/css/color-1.css?r={{date('dmyHis')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css?r={{date('dmyHis')}}">

    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/Custom.css?r={{date('dmyHis')}}">


    <script src="{{url('/')}}/assets/js/jquery-3.5.1.min.js?r={{date('dmyHis')}}"></script>



    <!-- sweetalert JS-->
    <script src="{{url('/')}}/assets/js/sweet-alert/sweetalert.min.js?r={{date('dmyHis')}}"></script>
    <!-- toastr JS-->
    <script src="{{url('/')}}/assets/js/toastr.min.js?r={{date('dmyHis')}}"></script>
    <!-- Select2 JS-->
    <script src="{{url('/')}}/assets/js/select2/select2.full.min.js?r={{date('dmyHis')}}"></script>
    <!-- dropify JS-->
    <script src="{{url('/')}}/assets/plugins/dropify/js/dropify.min.js?r={{date('dmyHis')}}"></script>
    <!-- bootbox JS-->
    <script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js?r={{date('dmyHis')}}"></script>
    <!-- DataTable JS-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js?r={{date('dmyHis')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js?r={{date('dmyHis')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.min.js?r={{date('dmyHis')}}"></script>

    <script src="{{url('/')}}/assets/plugins/DataTable/js/jquery.dataTables.min.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.buttons.min.js?r={{date('dmyHis')}}">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.colVis.min.js?r={{date('dmyHis')}}">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js?r={{date('dmyHis')}}">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.select.min.js?r={{date('dmyHis')}}">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js?r={{date('dmyHis')}}">
    </script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.html5.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatable-extension/buttons.print.js?r={{date('dmyHis')}}"></script>
    <script
        src="{{url('/')}}/assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js?r={{date('dmyHis')}}">
    </script>
    <script src="{{url('/')}}/assets/plugins/DataTable/js/dataTables.responsive.min.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/dataTableExport.js?r={{date('dmyHis')}}"></script>

    <script type="text/javascript" src="{{url('/')}}/assets/js/devtools-detector.min.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/bootstrap-multiselect/bootstrap-multiselect.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/custom-prototype.js?r={{date('dmyHis')}}"></script>
</head>

<body class="login-page">
    <input type="hidden" id="txtRootUrl" value="{{url('/')}}/">
    {{-- <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{url('/')}}/assets/images/login-page-img.png" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Login To {{ config('app.name', 'SSA') }}</h2>
                        </div>
                        <form id="frmLogin">
                            <div id="DivErrMsg" class="alert alert-danger dark text-center display-none" role="alert">
                                This is a warning alert—check it out!</div>
                            <div class="form-group">
                                <div class="input-group custom">
                                    <input type="text" id="txtUserName" class="form-control form-control-lg"
                                        placeholder="Username" />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text">
                                            <i class="icon-copy dw dw-user1"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="errors" id="txtUserName-err"></span>
                            </div>
                            <div class="form-group">
                                <div class="input-group custom">
                                    <input type="password" id="txtPassword" class="form-control form-control-lg"
                                        placeholder="**********" />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text">
                                            <i class="dw dw-padlock1"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="errors" id="txtPassword-err"></span>
                            </div>
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chkRememberMe" />
                                        <label class="custom-control-label" for="chkRememberMe">Remember</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <button class="btn btn-primary btn-lg btn-block" id="btnlogin">Sign In</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- latest jquery-->

    <!-- Bootstrap js-->
    <script src="{{url('/')}}/assets/js/bootstrap/popper.min.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.js?r={{date('dmyHis')}}"></script>
    <!-- feather icon js-->
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js?r={{date('dmyHis')}}"></script>

    <!-- Product Zoom -->
    <script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/lightbox/js/lightgallery.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/wheelzoom/wheelzoom.js?r={{date('dmyHis')}}"></script>

    <!-- Sidebar jquery-->
    <script src="{{url('/')}}/assets/js/sidebar-menu.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/config.js?r={{date('dmyHis')}}"></script>
    <!-- Plugins JS start-->
    <script src="{{url('/')}}/assets/js/tooltip-init.js?r={{date('dmyHis')}}"></script>
    <!-- Plugins JS Ends-->


    <!-- Theme js-->
    <script src="{{url('/')}}/assets/js/script.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/theme-customizer/customizer.js?r={{date('dmyHis')}}"></script>
    <!-- login js-->
    <script src="{{url('/')}}/assets/js/custom.js?r={{date('dmyHis')}}"></script>
    <script src="{{url('/')}}/assets/js/support.js?r={{date('dmyHis')}}"></script>
    <!-- Plugin used-->
    <script>
        $(document).ready(function(){
                $('#frmLogin').submit((e)=>{
                    e.preventDefault();
                    btnLoading($('#btnLogin'));
                    var RememberMe=0;if($("#chkRememberMe").prop('checked') == true){RememberMe=1;}
					$('.errors').html('');
                    $.ajax({
                        type:"post",
                        url:"{{url('/')}}/Clogin",
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:{email:$('#txtUserName').val(),password:$('#txtPassword').val(),remember:RememberMe,_token:$('meta[name=_token]').attr('content')},
                        error:function(e, x, settings, exception){btnReset($('#btnLogin'));ajax_errors(e, x, settings, exception);},
                        success:function(response){
							
                            btnReset($('#btnLogin'));
                            if(response.status==true){
                                    window.location.replace("{{url('/') }}/");
                            }else{
                                $('#DivErrMsg').removeClass('display-none');
                                $('#DivErrMsg').html(response.message);
								if(response.email!=undefined){
                                	$('#txtUserName-err').html(response.email);
								}
								if(response.password!=undefined){
                                	$('#txtPassword-err').html(response.password);
								}
                            }
                        }
                    });
                });
			});
    </script>
</body>

</html>