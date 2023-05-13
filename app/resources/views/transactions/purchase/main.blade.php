@extends('layouts.layout') @section('styles')
	<style>
		.createForm input,
		select {
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
			cursor: not-allowed !important;
			pointer-events: none;
			user-select: none;
			background: #f9f9f9 !important;
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

		.createFormRow {
			display: flex;
			justify-content: flex-start !important;
			border-radius: none !important;
			gap: .5em;
		}

		.createFormRow .form-group {
			width: 170px !important;
		}

		.createFormRow .form-group input,
		.createFormRow .form-group select {
			border-radius: 4px !important;
		}

		.createForm {
			background: #d0ccf04e;
		}

		.select2-selection__rendered,
		.select2-results__option {
			font-size: .8em;
		}
	</style>
	@endsection @section('content')
	<div class="container-fluid">
		<h1 class="h5 mb-4">{{ $header }}</h1>
		<div>
			<form class="form">
				<div class="d-md-flex gap-3">
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 small" for="">Date<span class="text-danger fs-14">*</span></label>
						<input @if ($isEdit) value="{{ $EditData->tranDate }}" @endif
							class="form-control form-control-sm" id="tranDate" type="date" />
						<span class="small text-danger" data-error="tranDate" data-validation></span>
					</div>
					<div class="form-group d-flex" style="flex-direction: column;">
						<label class="mb-0 small" for="">Supplier
							<span class="text-danger fs-14">*</span></label>
						<select class="form-control form-control-sm" id="supplierId" style=" width: 250px">
							<option selected value="">Select a Supplier</option>
						</select>
						<span class="small text-danger" data-error="supplierId" data-validation></span>
					</div>
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 small" for="">Mode of Payment
							<span class="text-danger fs-14">*</span></label>
						<select class="form-control form-control-sm" id="mop" name="" style=" width: 250px">
							<option @if (!$isEdit) selected @endif value="">
								Select Payment type
							</option>
							<option @if ($isEdit && $EditData->mop == 'cash') selected @endif value="cash">Cash
							</option>
							<option @if ($isEdit && $EditData->mop == 'card') selected @endif value="card">Card
							</option>
						</select>
						<span class="small text-danger" data-error="mop" data-validation></span>
					</div>
					<div class="form-group d-flex" style="flex-direction: column">
						<label class="mb-0 small" for="">Invoice Number</label>
						<input @if ($isEdit) value="{{ $EditData->invoiceNo }}" @endif
							class="form-control form-control-sm bg-outline-dark" id="ivNo" placeholder="INV001" type="text" />
					</div>
				</div>
			</form>
			{{-- style="background: rgba(215, 208, 79, 0.123)" --}}
			<div class="createForm px-2 rounded">
				<form class="form p-2 d-flex" id="createForm" style="flex-wrap: wrap">

					<hr />
					<div class="createFormRow d-flex justify-content-center" style="flex-wrap: wrap">
						<div class="form-group">
							<label class="form-label text-dark" for="">Category</label>
							<select class="form-control w-100" data-toggle="tooltip" id="categoryId" title="Category" type="text"></select>
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Subcategory</label>
							<select class="form-control" data-toggle="tooltip" id="subCategoryId" title="Subcategory" type="text"></select>
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Product</label>
							<select class="form-control disabled" data-toggle="tooltip" id="productId" title="product"
								type="text"></select>
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="" id="descriptionLabel">Description</label>
							<input class="form-control" data-toggle="tooltip" id="description" placeholder="Description" title="description"
								type="text" />
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Quantity</label>
							<input class="form-control disabled" data-toggle="tooltip" id="quantity" placeholder="Quantity" title="Quantity"
								type="number" />
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Rate</label>
							<input class="form-control" data-toggle="tooltip" id="price" placeholder="Rate(Rs)" readonly
								title="Rate" type="text" />
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Tax type</label>
							<select class="form-control bg-outline-dark" data-toggle="tooltip" id="taxType" title="Tax type"
								type="text">
								<option disabled value="">Select</option>
								<option value="include">Include</option>
								<option value="exclude">Exclude</option>
							</select>
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Tax percentage</label>
							<select class="form-control" data-toggle="tooltip" id="taxPercentage" title="Tax percentage"
								type="text"></select>
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Taxable</label>
							<input class="form-control disabled" data-toggle="tooltip" id="taxable" readonly title="Taxable"
								type="text" />
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Tax amount</label>
							<input class="form-control disabled" data-toggle="tooltip" id="taxAmount" readonly title="Tax amount"
								type="text" />
						</div>
						<div class="form-group">
							<label class="form-label text-dark" for="">Sub total</label>
							<input class="form-control disabled" data-toggle="tooltip" id="subtotal" readonly title="Sub total"
								type="text" />
						</div>

						<button class="btn btn-sm btn-outline-primary" id="createPurchaseItemForm">
							<i class="fa fa-check"></i> <span class="ms-1">Create</span>
						</button>

						<input id="main-taxable" type="hidden">
						<input id="main-taxamount" type="hidden">
					</div>
					<p class="notice d-block small text-info"></p>
					<hr />
				</form>
			</div>

			<div class="tableClass mt-2">
				<table class="table" style="font-size: .9em !important">
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
@endsection
@section('scripts')
	{{-- main scripts --}}
	<script src="{{ url('/') }}/Assets2/custom/purchase/requests.js"></script>
	<script src="{{ url('/') }}/Assets2/custom/purchase/purchase.js"></script>
	<script src="{{ url('/') }}/Assets2/custom/purchase/createPurchase.js"></script>
	<script src="{{ url('/') }}/Assets2/custom/purchase/updatePurchase.js"></script>

	<script src="{{ url('/') }}/Assets2/custom/purchase/tax.js"></script>
	<script src="{{ url('/') }}/Assets2/custom/purchase/helpers.js"></script>
	<script src="{{ url('/') }}/Assets2/custom/purchase/validation.js"></script>

	<script>
		// main request configuartion
		const request = createHttpRequest("{{ url('/') }}");

		// get suppliers
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

		// create purchase
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

				const denyList = ['category', 'subCategory', 'product', 'mainTaxableInput',
					'mainTaxAmountInput'
				];
				let obj = {};
				Object.entries(item).map(data => {
					if (!denyList.includes(item[0])) {
						obj = {
							...obj,
							[data[0]]: data[1]
						}
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
			const {
				taxamount,
				taxable,
				total,
			} = getMainTotals();

			const mainData = {
				...validated,
				invoiceNo: $('#ivNo').val(),
				taxable,
				taxAmount,
				TotalAmount: total,
				paidAmount: 0,
				balanceAmount: total,
				products,
			}

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

		// main data being edited
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
