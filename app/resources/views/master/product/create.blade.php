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

							{{-- Category --}}
							<div class="form-group col-md">
								<label class="form-label" for="category">Category</label>
								<select class="form-control" id="categoryId">
								</select>
								<span class="small mt-1 text-danger" data-error="categoryId" data-validation></span>
							</div>
							{{-- Category --}}
							<div class="form-group col-md">
								<label class="form-label" for="category">Subcategory</label>
								<select class="form-control" id="subCategoryId">
								</select>
								<span class="small mt-1 text-danger" data-error="subCategoryId" data-validation></span>
							</div>
							{{-- Category --}}
							<div class="form-group col-md">
								<label class="form-label" for="category">Tax</label>
								<select class="form-control" id="taxId">
								</select>
								<span class="small mt-1 text-danger" data-error="taxId" data-validation></span>
							</div>
						</div>

						<div class="form-group row">
							{{-- Max Quantity --}}
							<div class="form-group col-md">
								<label for="address">HSN / SAC code</label>
								<input
									@if ($isEdit == true) value="{{ !is_null($EditData->hsn_sac_code) ? $EditData->hsn_sac_code : 0 }}" @endif
									class="form-control" id="hsn_sac_code" placeholder="HSN / SAC code" type="number">
							</div>
							{{-- Max Quantity --}}
							<div class="form-group col-md">
								<label for="address">Max Quantity</label>
								<input @if ($isEdit == true) value="{{ $EditData->maxQuantity }}" @endif class="form-control"
									id="maxQuantity" placeholder="Max quantity" type="number">
								<span class="small mt-1 text-danger" data-error="maxQuantity" data-validation></span>
							</div>
							{{-- Max Quantity --}}
							<div class="form-group col-md">
								<label for="address">Min Quantity</label>
								<input @if ($isEdit == true) value="{{ $EditData->minQuantity }}" @endif class="form-control"
									id="minQuantity" placeholder="Min quantity" type="number">
								<span class="small mt-1 text-danger" data-error="minQuantity" data-validation></span>
							</div>
						</div>

						<div class="form-group row">
							{{-- Max Quantity --}}
							<div class="form-group col-md">
								<label for="address">Sales Rate</label>
								<input @if ($isEdit == true) value="{{ $EditData->salesRate }}" @endif class="form-control"
									id="salesRate" placeholder="Max quantity" type="number">
								<span class="small mt-1 text-danger" data-error="salesRate" data-validation></span>
							</div>
							{{-- Max Quantity --}}
							<div class="form-group col-md">
								<label for="address">Purchase Rate</label>
								<input @if ($isEdit == true) value="{{ $EditData->purchaseRate }}" @endif class="form-control"
									id="purchaseRate" placeholder="Min quantity" type="number">
								<span class="small mt-1 text-danger" data-error="purchaseRate" data-validation></span>
							</div>
						</div>

						{{-- Active status --}}
						<div class="form-group">
							<label class="form-label" for="">Active status</label>
							<select class="form-control " id="is_active">
								<option @if ($isEdit == true && $EditData->is_active == true) selected @endif value="1">Active</option>
								<option @if ($isEdit == true && $EditData->is_active == false) selected @endif value="0">Inactive</option>
							</select>
							<span class="small mt-1 text-danger" data-error="is_active" data-validation></span>
						</div>

						<div class="form-group d-flex justify-content-end">
							<a class="btn btn-outline-dark btn-sm me-2" href="{{ url('/') }}/master/products">Back</a>
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
		// States
		const categories = () => {
			$.ajax({
				type: "POST",
				url: "{{ route('product.categories') }}",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},

				error: (response) => {
					// ('.passwordError').html(response?.responseJSON?.message);
					toastr.error('Failed to Load Category');
					return false;
				},

				success: (response) => {
					const parent = document.querySelector('#categoryId');
					// const select = document.querySelector('#countrySelect');
					response?.forEach(res => {
						const option = document.createElement('option');
						parent.appendChild(option);
						option.textContent = res?.CName;
						option.value = res?.CID;
						if (res?.StateID === "{{ $UInfo->StateID }}") {
							option.selected = true;
						}
					});
				}
			});
		}
		// cities
		const subCategories = (e) => {
			$.ajax({
				type: "POST",
				url: "{{ route('product.subCategories') }}",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},
				data: {
					category: !empty(e) ? e : $('#categoryId').val(),
				}, // error
				error: (response) => {
					toastr.error('Failed to Load SubCategory');
					return false;
				}, // success
				success: (response) => {
					const parent = document.querySelector('#subCategoryId');
					response?.forEach(res => {
						const option = document.createElement('option');
						parent.appendChild(option);
						option.textContent = res?.SCName;
						option.value = res?.SCID
						if (res?.CityID === "{{ $UInfo->CityID }}") {
							option.setAttribute('selected', '');
						}
					});
				}
			});
		}

		// cities
		const taxes = (e) => {
			$.ajax({
				type: "POST",
				url: "{{ route('product.taxes') }}",
				headers: {
					'X-CSRF-Token': $('meta[name=_token]').attr('content')
				},
				data: {
					StateID: e,
				}, // error
				error: (response) => {
					toastr.error('Failed to Load Tax');
					return false;
				}, // success
				success: (response) => {
					const parent = document.querySelector('#taxId');
					response?.forEach(res => {
						const option = document.createElement('option');
						parent.appendChild(option);
						option.textContent = res?.TaxName;
						option.value = res?.TaxID
						//  option
					});
				}
			});
		}

		// document ready
		$(document).ready(() => {
			// end of main document
			$('#categoryId').select2();
			$('#subCategoryId').select2();
			$('#taxId').select2();

			// Fetch all Categories
			categories();
			@if ($isEdit == true)
				const categoryId = "{{ $EditData->categoryId }}";
				console.log(categoryId);
				subCategories(categoryId)
			@endif
			taxes()

			$('#categoryId').change(function() {
				$('#subCategoryId').html('');
				subCategories($('#categoryId').val());
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
			// Dynamic url :#
			@if ($isEdit == true)
				submiturl = "{{ route('product.edit', $EditData->pid) }}";
			@else
				submiturl = "{{ route('product.create') }}";
			@endif
			// Preparing data for validation
			const validated = {
				name: $("#name").val(),
				categoryId: $("#categoryId").val(),
				subCategoryId: $("#subCategoryId").val(),
				taxId: $("#taxId").val(),
				maxQuantity: $("#maxQuantity").val(),
				minQuantity: $("#minQuantity").val(),
				salesRate: $("#salesRate").val(),
				purchaseRate: $("#purchaseRate").val(),
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

			const hsn = $("#hsn_sac_code").val();

			if (hsn != "" && hsn <= 99999) {
				toastr.error("Please enter a valid HSN/SAC code");
				return false;
			}

			form.append('hsn_sac_code', $("#hsn_sac_code").val())

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
								window.location.replace("{{ url('/') }}/master/products");
							@else
								window.location.reload();
								// [...document.querySelectorAll('input')].map(item => item.value = "");
							@endif
						});
					}
				},
			});
		});
	</script>
@endsection
