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
                    <li class="breadcrumb-item">Purchase</li>
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
                <h5 id="CardHeader">Products</h5>
            </div>
            <div class="card-body">
                {{-- form startts --}}
                <form id="supplierForm">
                    <div class="form-group mb-2">
                        {{-- Name --}}
                        <div class="form-group">
                            <label for="fname">Name</label>
                            <input type="text" class="form-control" id="name" @if($isEdit==true)
                                value="{{ $EditData->name }}" @endif placeholder="Bernard Hackwell">
                            <span class="small mt-1 text-danger" data-validation data-error="name"></span>
                        </div>

                        <div class="form-group">
                            <label for="fname">Date of Purchase</label>
                            <input type="date" class="form-control" id="date" @if($isEdit==true)
                                value="{{ $EditData->date }}" @endif>
                            <span class="small mt-1 text-danger" data-validation data-error="date"></span>
                        </div>
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

                    <div class="form-group d-flex justify-content-end">
                        <a href="{{ route('purchase.home') }}" class="btn btn-outline-dark btn-sm me-2">Back</a>
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
    $('#supplierForm').submit(function (e) {
		e.preventDefault();

		let submiturl;
        
		@if($isEdit == true)
		submiturl = "{{ route('purchase.update', $EditData->puid) }}";
		@else
		submiturl = "{{route('purchase.create')}}";
		@endif
		// Preparing data for validation
		const validated = {
			name: $("#name").val(),
			date: $("#date").val(),
			is_active: $("#is_active").val(),
		}
         // Sending data for validation 
		if(!validate(validated)){ // if validation fails
			toastr.error('Please fill in all fields');
			return false; // dont do anything
		} // creating a form
        
		const form = new FormData();
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
				toastr.error('Error', e.responseJSON?.message);
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
                            window.location.replace("{{route('purchase.home')}}");
                        }
					);
				}
			},
		});
	});
</script>
@endsection