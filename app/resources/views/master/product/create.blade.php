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
                <h5 id="CardHeader">Product</h5>
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

                        {{-- Category--}}
                        <div class="form-group col-md">
                            <label for="category" class="form-label">Category</label>
                            <select id="categoryId" class="form-control">
                            </select>
                            <span class="small mt-1 text-danger" data-validation data-error="categoryId"></span>
                        </div>
                        {{-- Category--}}
                        <div class="form-group col-md">
                            <label for="category" class="form-label">Subcategory</label>
                            <select id="subCategoryId" class="form-control">
                            </select>
                            <span class="small mt-1 text-danger" data-validation data-error="subCategoryId"></span>
                        </div>
                        {{-- Category--}}
                        <div class="form-group col-md">
                            <label for="category" class="form-label">Tax</label>
                            <select id="taxId" class="form-control">
                            </select>
                            <span class="small mt-1 text-danger" data-validation data-error="taxId"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{-- Max Quantity --}}
                        <div class="form-group col-md">
                            <label for="address">HSN / SAC code</label>
                            <input class="form-control" type="number" @if($isEdit==true)
                                value="{{ !is_null($EditData->hsn_sac_code) ? $EditData->hsn_sac_code : 0 }}" @endif
                                id="hsn_sac_code" placeholder="HSN / SAC code">
                        </div>
                        {{-- Max Quantity --}}
                        <div class="form-group col-md">
                            <label for="address">Max Quantity</label>
                            <input class="form-control" type="number" @if($isEdit==true)
                                value="{{ $EditData->maxQuantity }}" @endif id="maxQuantity" placeholder="Max quantity">
                            <span class="small mt-1 text-danger" data-validation data-error="maxQuantity"></span>
                        </div>
                        {{-- Max Quantity --}}
                        <div class="form-group col-md">
                            <label for="address">Min Quantity</label>
                            <input class="form-control" type="number" @if($isEdit==true)
                                value="{{ $EditData->minQuantity }}" @endif id="minQuantity" placeholder="Min quantity">
                            <span class="small mt-1 text-danger" data-validation data-error="minQuantity"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{-- Max Quantity --}}
                        <div class="form-group col-md">
                            <label for="address">Sales Rate</label>
                            <input class="form-control" @if($isEdit==true) value="{{ $EditData->salesRate }}" @endif
                                type="number" id="salesRate" placeholder="Max quantity">
                            <span class="small mt-1 text-danger" data-validation data-error="salesRate"></span>
                        </div>
                        {{-- Max Quantity --}}
                        <div class="form-group col-md">
                            <label for="address">Purchase Rate</label>
                            <input class="form-control" @if($isEdit==true) value="{{ $EditData->purchaseRate }}" @endif
                                type="number" id="purchaseRate" placeholder="Min quantity">
                            <span class="small mt-1 text-danger" data-validation data-error="purchaseRate"></span>
                        </div>
                    </div>

                    {{-- Active status --}}
                    <div class="form-group">
                        <label for="" class="form-label">Active status</label>
                        <select id="is_active" class="form-control ">
                            <option @if($isEdit==true && $EditData->is_active == true)
                                selected @endif value="1">Active</option>
                            <option @if($isEdit==true && $EditData->is_active == false)
                                selected @endif value="0">Inactive</option>
                        </select>
                        <span class="small mt-1 text-danger" data-validation data-error="is_active"></span>
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        <a href="{{url('/')}}/master/products" class="btn btn-outline-dark btn-sm me-2">Back</a>
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
    // States
    const categories = () => {
        $.ajax({
            type:"POST",
            url: "{{ route('product.categories')}}",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

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
                    if(res?.StateID === "{{$UInfo->StateID}}"){
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
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
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
                    if(res?.CityID === "{{ $UInfo->CityID }}"){
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
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
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
        @if($isEdit == true) 
        const categoryId = "{{$EditData->categoryId}}";
        console.log(categoryId);
        subCategories(categoryId) 
        @endif
        taxes()

        $('#categoryId').change(function () {
            $('#subCategoryId').html('');
            subCategories($('#categoryId').val());
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
		// Dynamic url :#
		@if($isEdit == true)
		submiturl = "{{ route('product.edit', $EditData->pid) }}";
		@else
		submiturl = "{{route('product.create')}}";
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

        const hsn = $("#hsn_sac_code").val();

        if(hsn != "" && hsn <= 99999){
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
                                window.location.replace("{{url('/')}}/master/products");
                            @else
                                window.location.reload();
                                // [...document.querySelectorAll('input')].map(item => item.value = "");
                            @endif
                        }
					);
				}
			},
		});
	});
</script>
@endsection