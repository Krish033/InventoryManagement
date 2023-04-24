@extends('layouts.layout')
@section('content')

<!-- Container-fluid starts-->
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="{{ url('/') }}" data-original-title="" title="">
							<i class="f-16 fa fa-home"></i>
						</a>
					</li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">Customer</li>
					<li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
	{{-- --}}
</div>
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header text-center">
				<h5 id="CardHeader">Customer</h5>
			</div>
			<div class="card-body">
				<form id="customerForm">
					<div class="form-group mb-2">
						<div class="form-group">
							<label for="fname">@if($isEdit !== true) Full name @else Full Name @endif</label>
							<input type="text" class="form-control" id="fname" @if($isEdit==true)
								value="{{ $EditData[0]->CName }}" @endif placeholder="Name">
							<span class="small mt-1 text-danger" data-validation data-error="Name"></span>
						</div>
					</div>
					<div class="form-group mb-1 row">
						<div class="form-group col-6">
							<label for="email">Email Address</label>
							<input type="email" class="form-control" @if($isEdit==true)
								value="{{ $EditData[0]->Email }}" @endif id="email" placeholder="bernard@hackwell.com">
							<span class="small mt-1 text-danger" data-validation data-error="Email"></span>
						</div>
						<div class="form-group col-6">
							<label for="lname">Phone number</label>
							<input type="number" min="6000000000" max="9999999999" class="form-control" id="phone"
								@if($isEdit==true) value="{{ $EditData[0]->MobileNumber }}" @endif
								placeholder="(+91) 6374867931">
							<span class="small mt-1 text-danger" data-validation data-error="MobileNumber"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="address">Address</label>
						<textarea class="form-control" id="address" placeholder="Your full address">@if($isEdit==true) {{ $EditData[0]->Address }}@endif
						</textarea>
						<span class="small mt-1 text-danger" data-validation data-error="Address"></span>
					</div>

					<div class="form-group mb-3 mt-2 row">
						<div class="form-group col-6">
							<label for="gender" class="form-label">Gender</label>
							<select id="gender" class="form-control ">
								<option @if($isEdit==true && $EditData[0]->Gender == 'male')
									selected @endif value="male">Male</option>
								<option @if($isEdit==true && $EditData[0]->Gender == 'male')
									selected @endif value="female">Female</option>
							</select>
							<span class="small mt-1 text-danger" data-validation data-error="Gender"></span>
						</div>

						<div class="form-group col-6">
							<label for="gender" class="form-label">Active status</label>
							<select id="active" class="form-control ">
								<option @if($isEdit==true && $EditData[0]->ActiveStatus == true)
									selected @endif value="1">Active</option>
								<option @if($isEdit==true && $EditData[0]->ActiveStatus == false)
									selected @endif value="0">Inactive</option>
							</select>
							<span class="small mt-1 text-danger" data-validation data-error="ActiveStatus"></span>
						</div>
					</div>
					<div class="form-group row">
						<div class="form-group col-4">
							<label for="email">Country</label>
							<select name="" class="form-control" id="country"></select>
							<span class="small mt-1 text-danger" data-validation data-error="CountryID"></span>
						</div>
						<div class="form-group col-4">
							<label for="email">State</label>
							<select name="State" class="form-control" id="State"></select>
							<span class="small mt-1 text-danger" data-validation data-error="StateID"></span>
						</div>
						<div class="form-group col-4">
							<label for="lname">City</label>
							<select name="" class="form-control" id="city"></select>
							<span class="small mt-1 text-danger" data-validation data-error="CityID"></span>
						</div>
					</div>
					<div class="form-group d-flex justify-content-end">
						<a href="{{url()->previous()}}" class="btn btn-outline-dark btn-sm me-2">Back</a>
						<button class="btn btn-outline-success btn-sm">@if($isEdit == true) Update @else Create
							@endif</button>
					</div>
				</form>
			</div>

			</form>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script>
	const getStates = (e) => {

        $.ajax({
            type:"POST",
            url:"{{url('/')}}/Get/States",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

			data: {
				CountryID: e,
			},

            error: (response) => {
                // ('.passwordError').html(response?.responseJSON?.message);
                console.log(response);
                return false;
            },
        
            success: (response) => {
                const parent = document.querySelector('#State');
                // const select = document.querySelector('#countrySelect');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    parent.appendChild(option);
                    option.textContent = res?.StateName;
                    option.value = res?.StateID
                    if(res?.StateID === "{{$UInfo->StateID}}"){
                        option.selected = true;
                    }
                });
            }
        });

    }

    const getCities = (e) => {
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/Get/City",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

			data: {
				StateID: e,
			},
            
            error: (response) => {
            ('.passwordError').html(response?.responseJSON?.message);
            console.log(response);
                return false;
            },
        
            success: (response) => {
                const parent = document.querySelector('#city');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    parent.appendChild(option);
                    option.textContent = res?.CityName;
                    option.value = res?.CityID
                    
                    if(res?.CityID === "{{ $UInfo->CityID }}"){
                        option.setAttribute('selected', '');
                    }
                });
            }
        });
    
    }

    $(document).ready(() => {

        // fetching countries
        $.ajax({
            type:"POST",
            url:"{{url('/')}}/Get/Country",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

            error: (response) => {
                ('.passwordError').html(response?.responseJSON?.message);
                return false;
            },

            success: (response) => {
                const select = document.querySelector('#country');

                response?.forEach(res => {

                    const option = document.createElement('option');
                    select.appendChild(option);

                    option.textContent = res?.CountryName;
                    option.value = res?.CountryID

                    if(res?.CountryID === "{{ $UInfo->CountryID }}") {
                        option.selected = true;
                    }
                });
            }
        });
    });
</script>

<script>
	$(document).ready(function () {
		$('#country').select2();
		$('#State').select2();
		$('#city').select2();
		
		getStates("{{ $UInfo->CountryID }}");
		getCities("{{ $UInfo->StateID }}");
	});
	$('#country').change(function () {
		$('#State').html('');
		getStates($('#country').val());
	});

	$('#State').change(function () {
		$('#city').html('');
		getCities($('#State').val());
	});
</script>

<script>
	$('#customerForm').submit(function (e) {
		e.preventDefault();
		let submiturl;
		
		// Dynamic url :#
		@if($isEdit == true)
		submiturl = "{{ url('/') }}/master/customer/edit/{{$EditData[0]->CID}}";
		@else
		submiturl = "{{ url('/') }}/master/Customer/create";
		@endif

		// Preparing data for validation
		const validated = {
			Name: $("#fname").val(),
			Email: $("#email").val(),
			Address: $("#address").val(),
			CityID: $("#city").val(),
			StateID: $("#State").val(),
			Gender: $("#gender").val(),
			CountryID: $("#country").val(),
			MobileNumber: $("#phone").val(),
			ActiveStatus: $("#active").val(),
		}

		// Sending data for validation 
		if(!validate(validated)){ // if validation fails
			toastr.error('Please fill in all fields');
			return false; // dont do anything
		}

		// creating a form
		const form = new FormData();
		// object entries
		Object.entries(validated).map(valid => { // Error message for name should be Name is required
			//  database name for Name is CName
			const name = valid[0] == 'Name' ? 'CName' : valid[0]; // changing if name
			form.append(name, valid[1]);
		});
		
		$.ajax({
			type: "post",
			url: submiturl,

			headers: {
				"X-CSRF-Token": $("meta[name=_token]").attr("content")
			},

			data: form,
			processData: false,
			contentType: false,

			beforeSend: function () {
				ajaxindicatorstart("Please wait Upload Process on going.");
				
				var percentVal = "0%";

				setTimeout(() => {
					$("#divProcessText").html(
						percentVal +
						" Completed.<br> Please wait for until upload process complete."
					);
				}, 100);
			},

			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener(
					"progress",

					function (evt) {
						if (evt.lengthComputable) {
							var percentComplete = (evt.loaded / evt.total) * 100;
							percentComplete = parseFloat(percentComplete).toFixed(2);
							$("#divProcessText").html(
								percentComplete +
								"% Completed.<br> Please wait for until upload process complete."
							);
						}
					},
					false
				);
				return xhr;
			},

			error: function (e, x, settings, exception) {
				ajax_errors(e, x, settings, exception);
				toastr.error('Failes', e.responseJSON?.message);
			},

			complete: function (e, x, settings, exception) {
				btnReset($("#btnSubmit"));
				ajaxindicatorstop();
			},

			success: function (response) {
				document.documentElement.scrollTop = 0; 
				if (response.status == true) {
					swal(
							{
								title: "SUCCESS",
								text: response.message,
								type: "success",

								showCancelButton: false,
								confirmButtonClass: "btn-outline-success",
								confirmButtonText: "Okay",
								closeOnConfirm: false,
							},
							function () {
								@if($isEdit==true)
									// toastr.error('Failed', response?.message);
									window.location.replace("{{url('/')}}/master/Customer");
								@else
									// toastr.error('Failed', response?.message);
									window.location.reload();
								@endif
							}
					);

				}
			},
		});
	});
</script>

@endsection