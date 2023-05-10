@extends('layouts.layout')
@section('content')
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header text-center">
					<h5 id="CardHeader">{{ $header }}</h5>
				</div>
				<div class="card-body">

					{{-- form startts --}}
					<form id="supplierForm">
						<div class="form-group mb-2">
							{{-- image --}}
							<div class="form-group">
								<label for="fname">Image</label>
								<input @if ($isEdit && $EditData->img) data-default-file="{{ url('/') . '/' . $EditData->img }}" @endif
									class="dropify" class="form-control" id="img" type="file">
								<span class="small mt-1 text-danger" data-error="img" data-validation></span>
							</div>

							{{-- Name --}}
							<div class="form-group">
								<label for="fname">Name</label>
								<input @if ($isEdit == true) value="{{ $EditData->name }}" @endif class="form-control" id="name"
									placeholder="Bernard Hackwell" type="text">
								<span class="small mt-1 text-danger" data-error="name" data-validation></span>
							</div>
						</div>

						<div class="form-group mb-1 row">
							{{-- Email --}}
							<div class="form-group col-6">
								<label for="email">Email Address</label>
								<input @if ($isEdit == true) value="{{ $EditData->email }}" @endif class="form-control"
									id="email" placeholder="bernard@hackwell.com" type="email">
								<span class="small mt-1 text-danger" data-error="email" data-validation></span>
							</div>

							{{-- Phone number --}}
							<div class="form-group col-6">
								<label for="lname">Phone number</label>
								<input @if ($isEdit == true) value="{{ $EditData->phone }}" @endif class="form-control"
									id="phone" max="9999999999" min="6000000000" placeholder="(+91) 6374867931" type="number">
								<span class="small mt-1 text-danger" data-error="phone" data-validation></span>
							</div>
						</div>

						{{-- Address --}}
						<div class="form-group">
							<label for="address">Address</label>
							<textarea class="form-control" id="address" placeholder="Your full address">
@if ($isEdit == true)
{{ $EditData->address }}
@endif
</textarea>
							<span class="small mt-1 text-danger" data-error="address" data-validation></span>
						</div>

						{{-- Active status --}}
						<div class="form-group">
							<label class="form-label" for="gender">Active status</label>
							<select class="form-control " id="is_active">
								<option @if ($isEdit == true && $EditData->is_active == true) selected @endif value="1">Active</option>
								<option @if ($isEdit == true && $EditData->is_active == false) selected @endif value="0">Inactive</option>
							</select>
							<span class="small mt-1 text-danger" data-error="is_active" data-validation></span>
						</div>

						<div class="form-group row">
							{{-- country id --}}
							<div class="form-group col-4">
								<label for="email">Country</label>
								<select class="form-control" id="country" name=""></select>
								<span class="small mt-1 text-danger" data-error="countryId" data-validation></span>
							</div>

							{{-- State id --}}
							<div class="form-group col-4">
								<label for="email">State</label>
								<select class="form-control" id="State" name="State"></select>
								<span class="small mt-1 text-danger" data-error="stateId" data-validation></span>
							</div>

							{{-- City id --}}
							<div class="form-group col-4">
								<label for="lname">City</label>
								<select class="form-control" id="city" name=""></select>
								<span class="small mt-1 text-danger" data-error="cityId" data-validation></span>
							</div>
						</div>

						<div class="form-group d-flex justify-content-end">
							<a class="btn btn-outline-dark btn-sm me-2" href="{{ url()->previous() }}">Back</a>
							<button class="btn btn-outline-success btn-sm">
								@if ($isEdit == true)
									Update
								@else
									Create
								@endif
							</button>
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
				type: "POST",
				url: "{{ url('/') }}/Get/States",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},

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

						@if ($isEdit == true)
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
				url: "{{ url('/') }}/Get/City",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},
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

						@if ($isEdit == true)
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
				type: "POST",
				url: "{{ url('/') }}/Get/Country",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},

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

						@if ($isEdit == true)
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

			$('#country').change(function() {
				$('#State').html('');
				getStates($('#country').val());
			});

			$('#State').change(function() {
				$('#city').html('');
				getCities($('#State').val());
			});
		});
	</script>
	<script>
		$('#supplierForm').submit(function(e) {
			e.preventDefault();
			let submiturl;

			@if ($isEdit == true)
				submiturl = "{{ route('supplier.edit', $EditData->sid) }}";
			@else
				submiturl = "{{ route('supplier.create') }}";
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
			if (!validate(validated)) { // if validation fails
				toastr.error('Please fill in all fields');
				return false; // dont do anything
			} // creating a form

			const form = new FormData();
			if (hasFile($('#img'))) form.append('img', $('#img')[0].files[0]);
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
				beforeSend: function() {
					ajaxindicatorstart("Please wait Upload Process on going.");
					var percentVal = "0%";
					setTimeout(() => {
						$("#divProcessText").html(
							percentVal +
							" Completed.<br> Please wait for until upload process complete."
						);
					}, 100);
				}, // load function
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener(
						"progress",
						function(evt) {
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
				error: function(e, x, settings, exception) {
					ajax_errors(e, x, settings, exception);
					toastr.error('Failes', e.responseJSON?.message);
				},
				// Complete
				complete: function(e, x, settings, exception) {
					btnReset($("#btnSubmit"));
					ajaxindicatorstop();
				},
				// Success
				success: function(response) {
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
						}, function() {
							@if ($isEdit == true)
								window.location.replace("{{ url('/') }}/master/suppliers");
							@else
								window.location.reload();
							@endif
						});
					}
				},
			});
		});
	</script>
@endsection
