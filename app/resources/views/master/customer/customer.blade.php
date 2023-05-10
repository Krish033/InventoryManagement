@extends('layouts.layout')
@section('content')
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header text-center">
					<h5 id="CardHeader">{{ $header }}</h5>
				</div>
				<div class="card-body">
					<form id="customerForm">
						<div class="form-group mb-2">
							<div class="form-group">
								<label for="fname">
									@if ($isEdit !== true)
										Full name
									@else
										Full Name
									@endif
								</label>
								<input @if ($isEdit == true) value="{{ $EditData[0]->CName }}" @endif class="form-control"
									id="fname" placeholder="Name" type="text">
								<span class="small mt-1 text-danger" data-error="Name" data-validation></span>
							</div>
						</div>
						<div class="form-group mb-1 row">
							<div class="form-group col-6">
								<label for="email">Email Address</label>
								<input @if ($isEdit == true) value="{{ $EditData[0]->Email }}" @endif class="form-control"
									id="email" placeholder="bernard@hackwell.com" type="email">
								<span class="small mt-1 text-danger" data-error="Email" data-validation></span>
							</div>
							<div class="form-group col-6">
								<label for="lname">Phone number</label>
								<input @if ($isEdit == true) value="{{ $EditData[0]->MobileNumber }}" @endif class="form-control"
									id="phone" max="9999999999" min="6000000000" placeholder="(+91) 6374867931" type="number">
								<span class="small mt-1 text-danger" data-error="MobileNumber" data-validation></span>
							</div>
						</div>
						<div class="form-group">
							<label for="address">Address</label>
							<textarea class="form-control" id="address" placeholder="Your full address">
@if ($isEdit == true)
{{ $EditData[0]->Address }}
@endif
</textarea>
							<span class="small mt-1 text-danger" data-error="Address" data-validation></span>
						</div>

						<div class="form-group mb-3 mt-2 row">
							<div class="form-group col-6">
								<label class="form-label" for="gender">Gender</label>
								<select class="form-control " id="gender">
									<option @if ($isEdit == true && $EditData[0]->Gender == 'male') selected @endif value="male">Male</option>
									<option @if ($isEdit == true && $EditData[0]->Gender == 'male') selected @endif value="female">Female</option>
								</select>
								<span class="small mt-1 text-danger" data-error="Gender" data-validation></span>
							</div>

							<div class="form-group col-6">
								<label class="form-label" for="gender">Active status</label>
								<select class="form-control " id="active">
									<option @if ($isEdit == true && $EditData[0]->ActiveStatus == true) selected @endif value="1">Active</option>
									<option @if ($isEdit == true && $EditData[0]->ActiveStatus == false) selected @endif value="0">Inactive</option>
								</select>
								<span class="small mt-1 text-danger" data-error="ActiveStatus" data-validation></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="form-group col-4">
								<label for="email">Country</label>
								<select class="form-control" id="country" name=""></select>
								<span class="small mt-1 text-danger" data-error="CountryID" data-validation></span>
							</div>
							<div class="form-group col-4">
								<label for="email">State</label>
								<select class="form-control" id="State" name="State"></select>
								<span class="small mt-1 text-danger" data-error="StateID" data-validation></span>
							</div>
							<div class="form-group col-4">
								<label for="lname">City</label>
								<select class="form-control" id="city" name=""></select>
								<span class="small mt-1 text-danger" data-error="CityID" data-validation></span>
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
					// ('.passwordError').html(response?.responseJSON?.message);
					console.log(response);
					return false;
				},

				success: (response) => {
					const parent = document.querySelector('#State');
					// const select = document.querySelector('#countrySelect');
					response.forEach(res => {
						const option = document.createElement('option');
						parent.appendChild(option);
						option.textContent = res.StateName;
						option.value = res.StateID
						if (res.StateID === "{{ $UInfo->StateID }}") {
							option.selected = true;
						}
					});
				}
			});

		}

		const getCities = (e) => {
			$.ajax({
				type: "POST",
				url: "{{ url('/') }}/Get/City",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},

				data: {
					StateID: e,
				},

				error: (response) => {
					('.passwordError').html(response.responseJSON.message);
					console.log(response);
					return false;
				},

				success: (response) => {
					const parent = document.querySelector('#city');
					response.forEach(res => {
						const option = document.createElement('option');
						parent.appendChild(option);
						option.textContent = res.CityName;
						option.value = res.CityID

						if (res.CityID === "{{ $UInfo->CityID }}") {
							option.setAttribute('selected', '');
						}
					});
				}
			});

		}

		$(document).ready(() => {

			// fetching countries
			$.ajax({
				type: "POST",
				url: "{{ url('/') }}/Get/Country",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},

				error: (response) => {
					('.passwordError').html(response.responseJSON.message);
					return false;
				},

				success: (response) => {
					const select = document.querySelector('#country');

					response.forEach(res => {

						const option = document.createElement('option');
						select.appendChild(option);

						option.textContent = res.CountryName;
						option.value = res.CountryID

						if (res.CountryID === "{{ $UInfo->CountryID }}") {
							option.selected = true;
						}
					});
				}
			});
		});
	</script>

	<script>
		$(document).ready(function() {
			$('#country').select2();
			$('#State').select2();
			$('#city').select2();

			getStates("{{ $UInfo->CountryID }}");
			getCities("{{ $UInfo->StateID }}");
		});
		$('#country').change(function() {
			$('#State').html('');
			getStates($('#country').val());
		});

		$('#State').change(function() {
			$('#city').html('');
			getCities($('#State').val());
		});
	</script>

	<script>
		$('#customerForm').submit(function(e) {
			e.preventDefault();
			let submiturl;

			// Dynamic url :#
			@if ($isEdit == true)
				submiturl = "{{ url('/') }}/master/customer/edit/{{ $EditData[0]->CID }}";
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
			if (!validate(validated)) { // if validation fails
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

				beforeSend: function() {
					ajaxindicatorstart("Please wait Upload Process on going.");

					var percentVal = "0%";

					setTimeout(() => {
						$("#divProcessText").html(
							percentVal +
							" Completed.<br> Please wait for until upload process complete."
						);
					}, 100);
				},

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
						}, false
					);
					return xhr;
				},

				error: function(e, x, settings, exception) {
					ajax_errors(e, x, settings, exception);
					toastr.error('Failes', e.responseJSON.message);
				},

				complete: function(e, x, settings, exception) {
					btnReset($("#btnSubmit"));
					ajaxindicatorstop();
				},

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
								// toastr.error('Failed', response?.message);
								window.location.replace("{{ url('/') }}/master/Customer");
							@else
								// toastr.error('Failed', response?.message);
								window.location.reload();
							@endif
						});

					}
				},
			});
		});
	</script>
@endsection
