@extends('layouts.layout')

@section('styles')

@endsection

@section('content')
<div class="row">

  <div class="col-xl-4">
    <div class="card">

      {{-- {{dd($UserData)}} --}}
      <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
        <img src="{{ url('/') . '/' . $UserData->ProfileImage}}" alt="Profile"
          class="user-profile-img rounded-circle" />
        <h2 class="h5">{{auth()->user()->name}}</h2>
        <h3 class="small">{{auth()->user()->email}}</h3>
        <div class="social-links mt-2">
          <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-8">
    <div class="card">
      <div class="card-body pt-3">
        <!-- Bordered Tabs -->
        <ul class="nav nav-tabs nav-tabs-bordered">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">
              Overview
            </button>
          </li>
        </ul>
        <div class="tab-content pt-2">
          <div class="tab-pane fade show active profile-overview" id="profile-overview">
            <h5 class="card-title">Profile Details</h5>

            <div class="row mb-2">
              <div class="col-lg-3 col-md-4 label">Full Name</div>
              <div class="col-lg-9 col-md-8">{{$UInfo->FirstName}} {{ $UInfo->LastName }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3 col-md-4 label">Email</div>
              <div class="col-lg-9 col-md-8">{{$UInfo->EMail}}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3 col-md-4 label">Phone Number</div>
              <div class="col-lg-9 col-md-8">{{ $UInfo->MobileNumber }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3 col-md-4 label">Address</div>
              <div class="col-lg-9 col-md-8">
                {{ $UInfo->Address }}, {{$UInfo->CityName}}, {{ $UInfo->StateName }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3 col-md-4 label">Gender</div>
              <div class="col-lg-9 col-md-8">{{ $UInfo->Gender }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3 col-md-4 label">Country</div>
              <div class="col-lg-9 col-md-8">
                {{$UInfo->CountryName}}
              </div>
            </div>

            <a href="{{url('/')}}/users-and-permissions/UpdateProfile" class="btn btn-sm btn-outline-dark">Update
              profile</a>
          </div>
        </div>
        <!-- End Bordered Tabs -->
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script>
  const getStates = (e) => {

        $.ajax({
            type:"GET",
            url:"{{ url('/') }}/profile/get-states/" + e,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

            error: (response) => {
                // ('.passwordError').html(response?.responseJSON?.message);
                console.log(response);
                return false;
            },
        
            success: (response) => {
                const parent = document.querySelector('#stateSelect');
                // const select = document.querySelector('#countrySelect');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    parent.appendChild(option);
                    option.textContent = res?.StateName;
                    option.value = res?.StateID
                    if(res?.StateID === option.value){
                        option.selected = true;
                    }
                });
            }
        });

    }

    const getCities = (e) => {
    
        $.ajax({
            type:"GET",
            url:"{{ url('/') }}/profile/get-cities/" + e,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            
            error: (response) => {
            ('.passwordError').html(response?.responseJSON?.message);
            console.log(response);
                return false;
            },
        
            success: (response) => {
                const parent = document.querySelector('#citySelect');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    parent.appendChild(option);
                    option.textContent = res?.CityName;
                    option.value = res?.CityID
                    
                    if(res?.CityID === option.value){
                        option.setAttribute('selected', '');
                    }
                });
            }
        });
    
    }

    // exportable form data
    const form = () => {
        // new foerm
        const form = new FormData();
        form.append('FirstName', $('#firstName').val());
        form.append('LastName', $('#lastName').val());
        form.append('EMail', $('#email').val());
        
        form.append('MobileNumber', $('#phone').val());
        form.append('CountryID', $('#countrySelect').val());
        form.append('StateID', $('#stateSelect').val());
        //
        form.append('CityID', $('#citySelect').val());
        form.append('ProfileImage', $('#profile')[0].files[0]);
        form.append('Gender', $('#gender').val());
        form.append('_token', $('meta[name=_token]').attr('content'));
        // returning the filled form
        return form;
    }

    $(document).ready(() => {
        // Ajax request
        $('#userInfo').submit((e)=>{
            e.preventDefault();
            // Request
                $.ajax({
                type:"post",
                url:"",
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                // Request body
                data: form(),
                processData: false,
                contentType: false,
                // Error response
                error: (response) => {
                    $('.userError').html(response?.responseJSON?.message);
                    return false;
                }, 
                // Success response
                success: (response) => {
                    return window.location.reload();
                }
            });
        });

        // confrim password
        $('#passwordChangeForm').submit(e => {
            e.preventDefault();

            $.ajax({
                type:"post",
                url:"",
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                // Request body
                data: {
                    _token: $('meta[name=_token]').attr('content'),
                    oldPassword: $('#oldPassword').val(),
                    password: $('#password').val(),
                    password_confrimation: $('#password2').val(),
                },
                
                // Error response
                error: (response) => {
                    $('.passwordError').html(response?.responseJSON?.message);
                    return false;
                },
                // Success response
                success: (response) => {
                    return window.location.reload();
                }
            })
        });

        // fetching countries
        $.ajax({
            type:"GET",
            url:"",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

            error: (response) => {
                ('.passwordError').html(response?.responseJSON?.message);
                return false;
            },

            success: (response) => {
                const select = document.querySelector('#countrySelect');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    select.appendChild(option);
                    option.textContent = res?.CountryName;
                    option.value = res?.CountryID
                    if(res?.CountryID === "{{ $UInfo->CountryID }}") {
                        option.selected = true;
                    }
                });
            
            const country = "{{ $UInfo->CountryID }}";
            const state = "{{ $UInfo->StateID }}";
            // const country = "{{ $UInfo->CountryID }}";
            console.log(country);
                getStates(country);
                getCities("{{ $UInfo->StateID }}");
            }
        });
    });
</script>
@endsection