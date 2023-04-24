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
                    <li class="breadcrumb-item">Supplier</li>
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
                <h5 id="CardHeader">Supplier</h5>
            </div>
            <div class="card-body">

                {{-- form startts --}}
                <form id="supplierForm">
                    <div class="form-group mb-2">
                        {{-- image --}}
                        <div class="form-group">
                            <label for="fname">Image</label>
                            <input class="dropify" type="file" @if($isEdit && $EditData->img) data-default-file="{{
                            url('/') . '/' . $EditData->img }}"
                            @endif class="form-control" id="img">
                            <span class="small mt-1 text-danger" data-validation data-error="img"></span>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="fname">Name</label>
                            <input type="text" class="form-control" id="name" @if($isEdit==true)
                                value="{{ $EditData->name }}" @endif placeholder="Bernard Hackwell">
                            <span class="small mt-1 text-danger" data-validation data-error="name"></span>
                        </div>
                    </div>

                    <div class="form-group mb-1 row">
                        {{-- Email --}}
                        <div class="form-group col-6">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" @if($isEdit==true) value="{{ $EditData->email }}"
                                @endif id="email" placeholder="bernard@hackwell.com">
                            <span class="small mt-1 text-danger" data-validation data-error="email"></span>
                        </div>

                        {{-- Phone number --}}
                        <div class="form-group col-6">
                            <label for="lname">Phone number</label>
                            <input type="number" min="6000000000" max="9999999999" class="form-control" id="phone"
                                @if($isEdit==true) value="{{ $EditData->phone }}" @endif placeholder="(+91) 6374867931">
                            <span class="small mt-1 text-danger" data-validation data-error="phone"></span>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" placeholder="Your full address">@if($isEdit==true) {{ $EditData->address }}@endif
						</textarea>
                        <span class="small mt-1 text-danger" data-validation data-error="address"></span>
                    </div>

                    {{-- Active status --}}
                    <div class="form-group">
                        <label for="gender" class="form-label">Active status</label>
                        <select id="is_active" class="form-control ">
                            <option @if($isEdit==true && $EditData->is_active == true)
                                selected @endif value="1">Active</option>
                            <option @if($isEdit==true && $EditData->is_active == false)
                                selected @endif value="0">Inactive</option>
                        </select>
                        <span class="small mt-1 text-danger" data-validation data-error="is_active"></span>
                    </div>

                    <div class="form-group row">
                        {{-- country id --}}
                        <div class="form-group col-4">
                            <label for="email">Country</label>
                            <select name="" class="form-control" id="country"></select>
                            <span class="small mt-1 text-danger" data-validation data-error="countryId"></span>
                        </div>

                        {{--State id --}}
                        <div class="form-group col-4">
                            <label for="email">State</label>
                            <select name="State" class="form-control" id="State"></select>
                            <span class="small mt-1 text-danger" data-validation data-error="stateId"></span>
                        </div>

                        {{--City id --}}
                        <div class="form-group col-4">
                            <label for="lname">City</label>
                            <select name="" class="form-control" id="city"></select>
                            <span class="small mt-1 text-danger" data-validation data-error="cityId"></span>
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
    const contentEditable = "{{ $isEdit }}";
    // States
    const getStates = (e) => {
        $.ajax({
            type:"POST",
            url:"{{url('/')}}/Get/States",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

			data: {
				CountryID: e,
			},

            error: (response) => {
                toastr.error(response?.responseJSON?.message)
                return false;
            },
        
            success: (response) => {
                const parent = document.querySelector('#State');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    parent.appendChild(option);
                    option.textContent = res?.StateName;
                    option.value = res?.StateID

                    @if($isEdit == true)
                    option.selected = res?.StateID == "{{ $EditData->stateId }}";
                    @endif
                });
            }
        });
    }
    // cities
    const getCities = (e) => {
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/Get/City",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
			data: {
				StateID: e,
			}, // error
            error: (response) => {
                ('.passwordError').html(response?.responseJSON?.message);
                console.log(response);
                return false;
            }, // success
            success: (response) => {
                const parent = document.querySelector('#city');
                response?.forEach(res => {
                    const option = document.createElement('option');
                    parent.appendChild(option);
                    option.textContent = res?.CityName;
                    option.value = res?.CityID

                    @if($isEdit == true)
                    option.selected = res?.CityID == "{{ $EditData->cityId }}";
                    @endif
                });
            }
        });
    }

    // document ready
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

                    @if($isEdit == true)
                    option.selected = res?.CountryID == "{{ $EditData->countryId }}";
                    @endif

                });
            }
        });

        // end of main document
        $('#country').select2();
        $('#State').select2();
        $('#city').select2();

        getStates("{{ $UInfo->CountryID }}");
        getCities("{{ $UInfo->StateID }}");

        $('#country').change(function () {
            $('#State').html('');
            getStates($('#country').val());
        });
        
        $('#State').change(function () {
            $('#city').html('');
            getCities($('#State').val());
        });
    });
</script>
<script>
    $('#supplierForm').submit(function (e) {
		e.preventDefault();
		let submiturl;
        
		@if($isEdit == true)
		submiturl = "{{ route('supplier.edit', $EditData->sid) }}";
		@else
		submiturl = "{{route('supplier.create')}}";
		@endif
		// Preparing data for validation
		const validated = {
			name: $("#name").val(),
			email: $("#email").val(),
			address: $("#address").val(),
			cityId: $("#city").val(),
			stateId: $("#State").val(),
			countryId: $("#country").val(),
			phone: $("#phone").val(),
			is_active: $("#is_active").val(),
		}
         // Sending data for validation 
		if(!validate(validated)){ // if validation fails
			toastr.error('Please fill in all fields');
			return false; // dont do anything
		} // creating a form
        
		const form = new FormData();
        if(hasFile($('#img'))) form.append('img', $('#img')[0].files[0]);
		// object entries
		Object.entries(validated).map(valid => {
			form.append(valid[0], valid[1]);
		});

		$.ajax({ // Ajax form submit
			type: "post",
			url: submiturl, // Dynamic url
			headers: { // CSRF
				"X-CSRF-Token": $("meta[name=_token]").attr("content")
			},
			data: form,
			processData: false,
			contentType: false,
            // prepare loaders
			beforeSend: function () {
				ajaxindicatorstart("Please wait Upload Process on going.");
				var percentVal = "0%";
				setTimeout(() => {
					$("#divProcessText").html(
						percentVal +
						" Completed.<br> Please wait for until upload process complete."
					);
				}, 100);
			}, // load function
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
            // Error
			error: function (e, x, settings, exception) {
				ajax_errors(e, x, settings, exception);
				toastr.error('Failes', e.responseJSON?.message);
			},
            // Complete
			complete: function (e, x, settings, exception) {
				btnReset($("#btnSubmit"));
				ajaxindicatorstop();
			},
            // Success
			success: function (response) {
				document.documentElement.scrollTop = 0; 
				if (response.status == true) {
					swal({
                            title: "SUCCESS",
                            text: response.message,
                            type: "success",

                            showCancelButton: false,
                            confirmButtonClass: "btn-outline-success",
                            confirmButtonText: "Okay",
                            closeOnConfirm: false,
                        }, function () {
                            @if($isEdit==true)
                                window.location.replace("{{url('/')}}/master/suppliers");
                            @else
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