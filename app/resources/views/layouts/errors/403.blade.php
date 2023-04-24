@extends('layouts.errors')
@section('content')
        <!-- error-400 start-->
        <div class="error-wrapper">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-sm-7 col-md-5 col-lg-4 col-xl-3">
                        <img class="img-fluid err-img" src="../assets/images/error-img.png" alt="">
                    </div>
                </div>
                <!-- <img class="img-100" src="../assets/images/error-img.png" alt=""> -->
                <div class="error-heading">
                    <h2 class="headline font-info">403</h2>
                </div>
                <div class="col-md-8 offset-md-2">
                    <p class="sub-content">You do not have permission to access this Page that you requested.</p>
                </div>
                <div>
                    <a class="btn btn-pill btn-danger btn-air-danger btn-lg" href="{{url('/')}}/"> <i class="fa fa-home"></i> BACK TO HOME PAGE</a>
                </div>
            </div>
        </div>
@endsection