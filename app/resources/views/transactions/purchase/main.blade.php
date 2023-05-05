@extends('layouts.layout') @section('styles')
	<style>
		.createForm input,
		select {
			border: 1px solid #000;
			background: transparent !important;
			color: #000;
			font-size: 0.8em !important;
		}

		.createFormDiv {
			padding: 0.5em;
		}

		.createForm {
			padding: 0.2em 0;
		}

		.editing {
			background: whitesmoke;
		}

		.disabled {
			pointer-events: none;
			user-select: none;
			background: rgb(240, 239, 239) !important;
		}

		.btn-td {
			display: flex;
			justify-content: center;
			align-content: center;
		}

		.createFormRow .form-group {
			width: 100%;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: flex-start;
			align-self: center;
		}

		.createFormRow button {
			align-self: center;
			margin-top: 7px;
		}

		.createFormRow .form-group label {
			font-size: 0.8em !important;
		}

		hr {
			background: #000 !important;
			margin: 0 0 10px 0 !important;
		}

		.validation-error {
			border: 1px solid rgb(214, 0, 0) !important;
			color: rgb(160, 6, 6) !important;
		}

		.createFormRow .form-group label::after {
			content: "*";
			color: lightcoral;
			font-size: 0.9em;
			margin-left: 4px;
			font-weight: 900;
		}

		#descriptionLabel::after {
			content: "";
		}
	</style>
	@endsection @section('content')
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a data-original-title="" href="{{ url('/') }}/dashboard" title=""><i class="f-16 fa fa-home"></i></a>
						</li>
						<li class="breadcrumb-item">Transactions</li>
						<li class="breadcrumb-item">Purchase</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<h1 class="h5 mb-4">Create Payment</h1>
		<div>
			<form class="form">
				<div class="d-md-flex gap-3">
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 fs-14" for="">Date <span class="text-danger fs-14">*</span></label>
						<input @if ($isEdit) value="{{ $EditData->tranDate }}" @endif
							class="form-control form-control-sm" id="tranDate" type="date" />
						<span class="fs-13 text-danger" data-error="tranDate" data-validation></span>
					</div>
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 fs-14" for="">Supplier
							<span class="text-danger fs-14">*</span></label>
						<select class="form-control form-control-sm" id="supplierId">
							<option selected value="">Select a Supplier</option>
						</select>
						<span class="fs-13 text-danger" data-error="supplierId" data-validation></span>
					</div>
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 fs-14" for="">Mode of Payment
							<span class="text-danger fs-14">*</span></label>
						<select class="form-control form-control-sm" id="mop" name="">
							<option @if (!$isEdit) selected @endif value="">
								Select Payment type
							</option>
							<option @if ($isEdit && $EditData->mop == 'cash') selected @endif value="cash">Cash
							</option>
							<option @if ($isEdit && $EditData->mop == 'card') selected @endif value="card">Card
							</option>
						</select>
						<span class="fs-13 text-danger" data-error="mop" data-validation></span>
					</div>
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 fs-14" for="">Invoice Number</label>
						<input @if ($isEdit) value="{{ $EditData->invoiceNo }}" @endif
							class="form-control form-control-sm bg-outline-dark" id="ivNo" placeholder="INV001" type="text" />
					</div>
				</div>
			</form>
			{{-- style="background: rgba(215, 208, 79, 0.123)" --}}
			<div class="createForm px-2 rounded">
				<form class="form p-2" id="createForm">
					<hr />
					<div class="row createFormRow">
						<div class="form-group col-2">
							<label class="form-label text-dark" for="">Category</label>
							<select class="form-control form-control-sm bg-outline-dark w-100" data-toggle="tooltip" id="categoryId"
								title="Category" type="text"></select>
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="">Subcategory</label>
							<select class="form-control form-control-sm bg-outline-dark disabled" data-toggle="tooltip" id="subCategoryId"
								style="background: transparent" title="Subcategory" type="text"></select>
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="">Product</label>
							<select class="form-control form-control-sm bg-outline-dark disabled" data-toggle="tooltip" id="productId"
								style="background: transparent" title="product" type="text"></select>
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="" id="descriptionLabel">Description</label>
							<input class="form-control form-control-sm" data-toggle="tooltip" id="description" placeholder="Description"
								title="description" type="text" />
						</div>
						<div class="form-group col-3 col-md-1 d-flex w-100">
							<label class="form-label text-dark" for="">Quantity</label>
							<input class="form-control form-control-sm disabled" data-toggle="tooltip" id="quantity"
								placeholder="Quantity" title="Quantity" type="number" />
						</div>
						<div class="form-group col-3 col-md-1 d-flex w-100">
							<label class="form-label text-dark" for="">Rate</label>
							<input class="form-control form-control-sm" data-toggle="tooltip" id="price" placeholder="Rate(Rs)"
								readonly title="Rate" type="text" />
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="">Tax type</label>
							<select class="form-control form-control-sm bg-outline-dark" data-toggle="tooltip" id="taxType"
								style="background: transparent" title="Tax type" type="text">
								<option value="include">Include</option>
								<option value="exclude">Exclude</option>
							</select>
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="">Tax percentage</label>
							<select class="form-control form-control-sm bg-outline-dark col" data-toggle="tooltip" id="taxPercentage"
								style="background: transparent" title="Tax percentage" type="text"></select>
						</div>
						<div class="form-group col-3 col-md-1 d-flex w-100">
							<label class="form-label text-dark" for="">Taxable</label>
							<input class="form-control form-control-sm col" data-toggle="tooltip" id="taxable" readonly title="Taxable"
								type="text" />
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="">Tax amount</label>
							<input class="form-control form-control-sm col" data-toggle="tooltip" id="taxAmount" readonly
								title="Tax amount" type="text" />
						</div>
						<div class="form-group col-4 col-md-2 d-flex w-100">
							<label class="form-label text-dark" for="">Sub total</label>
							<input class="form-control form-control-sm col" data-toggle="tooltip" id="subtotal" readonly
								title="Sub total" type="text" />
						</div>

						<button class="btn btn-sm btn-outline-primary col-1" id="createPurchaseItemForm">
							<i class="fa fa-check"></i>
						</button>
					</div>
					<hr />
				</form>
			</div>

			<div class="tableClass mt-2">
				<table class="table table-responsive table-xs">
					<thead style="border-bottom: 1px solid #000 !important">
						<tr>
							<th>Category</th>
							<th>SubCategory</th>
							<th>Product</th>
							<th>Description</th>
							<th>Quantity</th>
							<th>Rate(Rs)</th>
							<th>Tax Type</th>
							<th>Tax (%)</th>
							<th>Taxable(Rs)</th>
							<th>Tax Amount</th>
							<th>Amount(Rs)</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="createTable"></tbody>
				</table>

				<div class="w-100 d-flex align-items-end mt-5" style="flex-direction: column">
					<span class="fs-11 p-2 rounded">SubTotal: <span class="h5" id="totalSum">0.00</span></span>
					<span class="fs-11 p-2">Tax Total: <span class="h5" id="taxTotal">0.00</span></span>
					<span class="fs-11 p-2 text-success"
						style="
                        border-top: 1px solid #000;
                        border-bottom: 1px solid #000;
                    ">Grand
						Total:
						<span class="h5" id="grandTotal">0.00</span></span>
				</div>

				<div class="submitButtons mt-4 w-100 d-flex justify-content-end gap-2">
					<a class="btn btn-sm btn-outline-dark" href="{{ url()->previous() }}">Back</a>
					<button class="btn btn-sm btn-outline-success" id="createPurchase">
						@if (!$isEdit)
							Add
						@else
							Update
						@endif
					</button>
				</div>
			</div>
		</div>
	</div>
	@endsection @section('scripts')
	{{-- main scripts --}}
	<script src="{{ url('/') }}/Assets2/custom/purchase.js"></script>

	<script>
		// main request configuartion
		const request = createHttpRequest("{{ url('/') }}");
		// ! loading categories
		$(document).ready(async () => {
			//getting and appending categories
			const {
				data,
				isError
			} = await requestCategories();
			const categoryId = document.querySelector('#categoryId');
			// caheck error and create error
			if (isError) return toastr.error("Something went wrong!", 'Failed');
			createSelectOption(categoryId, data, 'CID', 'CName')
		});

		$('#categoryId').change(async function(e) {
			clearSelectInputs(['categoryId'])
			// clear subcategories
			const subCategoryId = document.querySelector('#subCategoryId');
			// getting sub categories purchase.subcategory
			const {
				data,
				isError,
				error
			} = await requestSubCategories(e.target.value);
			if (isError) return toastr.error("Something went wrong!", 'Failed');

			subCategoryId.classList.remove('disabled');
			createSelectOption(subCategoryId, data, 'SCID', 'SCName')
		});

		$('#subCategoryId').change(async function(e) {
			clearSelectInputs(['categoryId', 'subCategoryId']);
			// clear subcategories
			const productId = document.querySelector('#productId');
			const {
				data,
				isError
			} = await requestProducts(e.target.value);
			if (isError) return toastr.error("Something went wrong!", 'Failed');
			// getting sub categories purchase.subcategory
			productId.classList.remove('disabled');
			createSelectOption(productId, data, 'pid', 'name')
		});
		// clear subcategories
		$('#productId').change(async function(e) {
			clearSelectInputs(['categoryId', 'subCategoryId', 'productId']);

			const productId = document.querySelector('#productId');
			const {
				data,
				isError
			} = await requestSingleProducts(e.target.value);
			if (isError) return toastr.error("Something went wrong!", 'Failed');

			const quantity = document.querySelector('#quantity');
			quantity.classList.remove('disabled');
			document.querySelector('#price').value = data?.purchaseRate;

			//getting and appending categories
			const {
				data: taxData,
				isError: isTaxError
			} = await requestTaxes();
			if (isTaxError) return toastr.error("Something went wrong!", 'Failed');
			const taxSelect = document.querySelector('#taxPercentage');
			createSelectOption(taxSelect, taxData, 'TaxPercentage', 'TaxName', data?.taxId)
		});

		// clear subcategories
		$(document).on('input', '#quantity', async function(e) {
			updateEverything();
			return true;
		});

		$('#taxType').change(async function(e) {
			updateEverything();
		});
		// clear subcategories
		$('#taxPercentage').change(async function(e) {
			updateEverything();
		});

		// submit records
		$(document).on('click', '#createPurchaseItemForm', async (e) => {
			e.preventDefault();
			const validated = requestValidatedArray();

			if (!validated) { // validated returns an array | boolean (false)
				return false;
			} // find editings
			if (document?.querySelector('.editing') !== null) {
				document?.querySelector('.editing').remove();
			}
			if (document?.querySelector('.clearButton') !== null) {
				document?.querySelector('.clearButton').remove();
			}
			[...document?.querySelectorAll('.validation-error')].map(item => {
				return item.classList.remove('validation-error')
			});

			const parentNode = document.querySelector("#createTable");
			const twin = parentNode.querySelector(`[data-productId=${validated?.productId}`);

			if (
				hasTwin(validated)
			) {
				addToTwin(validated)
				return true;
			} else {
				createDOMElement(validated);
				return
			}
			return;
		});

		const appendEditValues = async (e, row, tr) => {

			if (!document.querySelector('.clearButton')) {
				const closeBtn = document.createElement('button');
				closeBtn.className = "btn btn-sm btn-outline-danger ms-2 col-1 clearButton";
				closeBtn.innerHTML = "<i class=\"fa fa-close\"></i>";
				const form = document.querySelector('.createFormRow');
				form.appendChild(closeBtn);

				closeBtn.addEventListener('click', event => {
					tr.classList.remove('editing');
					closeBtn.remove();
					clearInputs();
					getCategoryItems();
				});
			}
			// add values to inboxes when editted
			Object.entries(row).map((item) => {
				const el = document.querySelector("#" + item[
					0]); // object entries returns an array with two tiems
				if (el) { // changing the elements values
					el.value = item[1];
				}
			});

			//getting and appending categories
			const {
				data,
				isError
			} = await requestCategories();
			const categoryId = document.querySelector("#categoryId");

			// caheck error and create error
			if (isError) return toastr.error("Something went wrong!", "Failed");
			createSelectOption(categoryId, data, "CID", "CName", tr.dataset.categoryid);

			const subCategoryId = document.querySelector('#subCategoryId');
			// getting sub categories purchase.subcategory
			const {
				data: sub,
				isError: subError,
				error
			} = await requestSubCategories(tr.dataset.categoryid);
			if (subError) return toastr.error("Something went wrong!", 'Failed');

			subCategoryId.classList.remove('disabled');
			createSelectOption(subCategoryId, sub, 'SCID', 'SCName', tr.dataset.subcategoryid)

			const productId = document.querySelector('#productId');
			const {
				data: product,
				isError: productError
			} = await requestProducts(tr.dataset.subcategoryid);
			if (productError) return toastr.error("Something went wrong!", 'Failed');
			// getting sub categories purchase.subcategory
			productId.classList.remove('disabled');
			createSelectOption(productId, product, 'pid', 'name', tr.dataset.productid)
			const quantity = document.querySelector('#quantity');
			quantity.classList.remove('disabled');

			//getting and appending categories
			const {
				data: taxData,
				isError: isTaxError
			} = await requestTaxes();
			if (isTaxError) return toastr.error("Something went wrong!", 'Failed');
			const taxSelect = document.querySelector('#taxPercentage');
			createSelectOption(taxSelect, taxData, 'TaxPercentage', 'TaxName', tr.dataset.taxpercentage)

			tr.classList.add("editing");
			genepriceGrandTotal();
			return true;
		};

		// grandTotal price
		genepriceGrandTotal();

		$(document).ready(async () => {
			//getting and appending categories
			const {
				data: suppliers,
				isError
			} = await requestSuppliers();
			const supplierSelect = document.querySelector('#supplierId');
			createSelectOption(supplierSelect, suppliers, 'sid', 'name',
				@if ($isEdit)
					"{{ $EditData->supplierId }}"
				@endif )
		})

		$(document).on('click', '#createPurchase', async (e) => {
			const swalConfiguration = {
				title: "Are you sure?",
				text: "Create this purchase!",
				type: "info",
				showCancelButton: true,
				confirmButtonClass: "btn-outline-success",
				confirmButtonText: "Confrim",
				closeOnConfirm: true
			}
			e.preventDefault();
			// get values
			let products = [...document.querySelector('#createTable').children].map(item => {
				return item.dataset;
			}); // return return account
			products = products.map(item => {
				let obj = {};
				Object.entries(item).map(data => {
					obj = {
						...obj,
						[data[0]]: data[1]
					}
				}); // main object
				return obj;
			}); // validation
			const validated = {
				tranDate: $('#tranDate').val(),
				supplierId: $('#supplierId').val(),
				mop: $('#mop').val()
			} // validated

			if (!products.length) {
				return toastr.error('Cannot create Empty Purchase');
			}

			if (!validate(validated)) {
				toastr.error('Cannot create record with empty field data')
				return false;
			} // bundle

			// getting the amount of taxable and taxAmount values by calculating it
			const taxableCalcuation = requestTotalInfo();
			const taxAmountCalcuation = requestTotalTax();

			const mainData = {
				...validated,
				invoiceNo: $('#ivNo').val(),
				taxable: taxableCalcuation,
				taxAmount: taxAmountCalcuation,
				TotalAmount: parseFloat(taxableCalcuation) + parseFloat(taxAmountCalcuation),
				paidAmount: 0,
				balanceAmount: parseFloat(taxableCalcuation) + parseFloat(taxAmountCalcuation),
				products,
			}

			console.log(mainData);

			// send ajax request to post the data
			const url =
				@if (!$isEdit)
					'/transactions/api/purchase/create-record'
				@else
					"/transactions/api/purchase/update-record/{{ $EditData->tranNo }}"
				@endif

			swal(swalConfiguration, async function() {
				const {
					data,
					isError
				} = await request.http({
					url,
					method: 'POST',
					data: mainData,
				});

				if (isError) return toastr.error("Something went wrong!", 'Failed');
				toastr.success("Purchase Made successfully!", 'Success!')
				window.location.replace("{{ url('/') }}/transactions/purchase");
			});
		});

		@if ($isEdit)

			const requestCreatedNodes = () => {
				const tranId = "{{ $EditData->tranNo }}";
				return request.http({
					url: "/transactions/api/purchase/request-created-products/" + tranId,
					method: "GET",
				});
			};

			$(document).ready(async () => {
				const {
					data,
					isError
				} = await requestCreatedNodes();
				if (isError) return toastr.error("Something went wrong!", 'Failed');
				// creating the items from the database
				data?.map(item => {
					createDOMElement(item);
				});
			});
		@endif
	</script>
@endsection
