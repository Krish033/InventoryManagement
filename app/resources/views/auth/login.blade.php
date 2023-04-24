<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="_token" content="{{ csrf_token() }}" />
  <title>{{ucfirst(config('app.name'))}}</title>

  <link href="https://fonts.gstatic.com" rel="preconnect" />
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" />
  <link href="{{ url('/') }}/Assets2/css/style.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="{{url('/')}}/Assets2/js/jquery-3.5.1.min.js"></script>
  <script src="{{url('/')}}/Assets2/js/bootstrap/popper.min.js"></script>
  <script src="{{url('/')}}/Assets2/js/bootstrap/bootstrap.js"></script>
  <script src="{{url('/')}}/Assets2/js/sweet-alert/sweetalert.min.js?r={{date('dmyHis')}}"></script>
  <script src="{{url('/')}}/Assets2/js/toastr.min.js?r={{date('dmyHis')}}"></script>
  <script src="{{url('/')}}/Assets2/js/select2/select2.full.min.js?r={{date('dmyHis')}}"></script>
  <script src="{{url('/')}}/Assets2/plugins/dropify/js/dropify.min.js?r={{date('dmyHis')}}"></script>
</head>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="{{ url('/') }}/Assets2/img/logo.png" alt="" />
                  <span class="d-none d-lg-block fs-xs">{{ config('app.name') }}</span>
                </a>
              </div>
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-xs">
                      Login to Your Account
                    </h5>
                    <p class="text-danger small mt-1 text-center error"></p>
                  </div>

                  <form class="row g-3 needs-validation" id="frmLogin" novalidate>
                    <input type="hidden" id="csrf" value="{{ csrf_token() }}" />
                    <div class="col-12">
                      <label for="email" class="small">Email Address</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="username" class="form-control" id="txtUserName" required />
                        <div class="invalid-feedback">
                          Please enter your username.
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="small">Password</label>
                      <input type="password" name="password" class="form-control" id="txtPassword" required />
                      <div class="invalid-feedback">
                        Please enter your password!
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true"
                          id="chkRememberMe" />
                        <label class="form-check-label small" for="rememberMe">Remember
                          me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">
                        Login
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <script>
    $(document).ready(function () {

      $('#frmLogin').submit((e) => {
          e.preventDefault();
          var RememberMe=0;

          if($("#chkRememberMe").prop('checked') == true){
            RememberMe=1;
          }

          $('.errors').html('');

          $.ajax({
            type:"post",
            url:"{{url('/')}}/Clogin",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data: {
              email:$('#txtUserName').val(), 
              password:$('#txtPassword').val(),
              remember:RememberMe,
              _token:$('meta[name=_token]').attr('content')
            },
            
            error: function(e, x, settings, exception) {
              $('.error').html(e?.responseJSON?.email);
            },
            
            success: function(response) {
              if(response.status==true){
                // window.location.replace("{{url('/') }}");
                window.location.reload();
              }else{
                  $('.error').html(response.message);
                if(response.email!=undefined){
                  $('.error').html(response.email);
                }
                if(response.password!=undefined){
                  $('.error').html(response.password);
                }
              }
            }
          });
      });
	  });
  </script>
</body>

</html>