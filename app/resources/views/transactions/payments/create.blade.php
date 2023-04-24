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
                    <li class="breadcrumb-item">Transactions</li>
                    <li class="breadcrumb-item">Payments</li>
                    <li class="breadcrumb-item">@if($isEdit)Update @else Create @endif</li>
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
                <h5 id="CardHeader">Payments</h5>
            </div>
            <div class="card-body">
                {{-- form startts --}}
                <form id="createPaymentsForm">

                    <div class="form-group">
                        <label for="fname">Date of payment</label>
                        <input type="date" class="form-control" id="date" @if($isEdit==true)
                            value="{{ $EditData->date }}" @endif>
                        <span class="small mt-1 text-danger" data-validation data-error="date"></span>
                    </div>

                    <div class="form-group">
                        <label for="fname" class="mb-0">Description</label>
                        <span class="small d-block mt-0 mb-2">Consider writting a small note on why the payment created
                            for</span>
                        <textarea placeholder="Description" id="description" class="form-control"
                            id="date">@if($isEdit==true){{ $EditData->description }} @endif</textarea>
                        <span class="small mt-1 text-danger" data-validation data-error="description"></span>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="form-label">Payment type</label>
                        <select id="payment_type" class="form-control ">
                            <option @if($isEdit==true && $EditData->payment_type == 'expense')
                                selected @endif value="expense">Expense</option>

                            <option @if($isEdit==true && $EditData->payment_type == 'income')
                                selected @endif value="income">Income</option>
                        </select>
                        <span class="small mt-1 text-danger" data-validation data-error="payment_type"></span>
                    </div>

                    <div class="form-check form-switch m-4">
                        <input class="form-check-input" @if($isEdit && boolval($EditData->completed)) checked
                        @endif type="checkbox"
                        id="completed">
                        <label class="form-check-label" for="completed">Mark payment as
                            completed</label>
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm me-2">Back</a>
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
    $('#createPaymentsForm').submit(function (e) {
		e.preventDefault();

		const submiturl = @if($isEdit == true) "{{ route('payment.update', $EditData->pyid) }}" @else "{{route('payment.create')}}" @endif
		// Preparing data for validation
		let validated = {
			date: $("#date").val(),
			description: $("#description").val(),
			payment_type: $("#payment_type").val(),
		}
         // Sending data for validation 
		if(!validate(validated)){ // if validation fails
			toastr.error('Please fill in all fields');
			return false; // dont do anything
		} // creating a form

        validated = {
            ...validated,
            completed: document.querySelector('#completed').checked
        }

		const form = new FormData();
		// object entries
		Object.entries(validated).map(valid => {
			form.append(valid[0], valid[1]);
		});

		$.ajax({ // Ajax form submit
			type: "POST",
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
						" completed.<br> Please wait for until upload process completed."
					);
				}, 100);
			}, // load function
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener(
					"progress",
					function (evt) {
						if (evt.lengthComputable) {
							var percentcompleted = (evt.loaded / evt.total) * 100;
							percentcompleted = parseFloat(percentcompleted).toFixed(2);
							$("#divProcessText").html(
								percentcompleted +
								"% completed.<br> Please wait for until upload process completed."
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
				ajaxindicatorstop();
				toastr.error('Error', e.responseJSON?.message);
                return false;
			},
            // completed
			completed: function (e, x, settings, exception) {
				btnReset($("#btnSubmit"));
				ajaxindicatorstop();
			},
            // Success
			success: function (response) {
                ajaxindicatorstop();
                swal({
                        title: "SUCCESS",
                        text: response.message,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-outline-success",
                        confirmButtonText: "Okay",
                        closeOnConfirm: false,
                    }, function () {
                        @if(!$isEdit)
                        window.location.replace("{{ url('/') }}/transactions/payment/" + response.pyid);
                        @else
                        window.location.replace("{{ url()->previous() }}");
                        @endif
                    }
                );
			},
		});
	});
</script>
@endsection