@extends('layouts.layout')
@section('content')
	<div class="container-fluid">

		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header text-center">
								<div class="form-row align-items-center">
									<div class="col-md-4"> </div>
									<div class="col-md-4 my-2">
										<h5>{{ $header }}</h5>
									</div>
									<div class="col-md-4 my-2 text-right text-md-right">
										<a class="btn  btn-outline-dark btn-sm me-2" href="{{ route('purchase.trash') }}" type="button">Trash view</a>
										<a class="btn  btn-outline-success btn-air-success btn-sm" href="{{ route('purchase.create') }}"
											type="button">Create</a>
									</div>
								</div>
							</div>
							<div class="card-body ">
								<table class="table" id="tblpurchase">
									<thead>
										<tr>
											<th class="text-center">Number</th>
											<th class="text-center">Date</th>
											<th class="text-center">Invoice Number</th>
											<th class="text-center">Supplier</th>
											<th class="text-center">MOP</th>
											<th class="text-center">Taxable</th>
											<th class="text-center">TaxAmount</th>
											<th class="text-center">TotalAmount</th>
											<th class="text-center">Actions</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- @endsection --}}

	{{-- @section('scripts') --}}
	<script>
		$(document).ready(() => {

			// purchase Table url
			const url = "{{ route('purchase.home.api') }}";
			// console.log();

			const fetchDataForTable = async () => {

				$('#tblpurchase').dataTable({

					"bProcessing": true,
					"bServerSide": true,
					"ajax": {
						"url": url + "?_token=" + $('meta[name=_token]').attr('content'),
						"headers": {
							'X-CSRF-Token': $('meta[name=_token]').attr('content')
						},
						"type": "GET"
					},
					deferRender: true,
					responsive: true,
					dom: 'Bfrtip',
					"iDisplayLength": 10,
					"lengthMenu": [
						[10, 25, 50, 100, 250, 500, -1],
						[10, 25, 50, 100, 250, 500, "All"]
					],
					columnDefs: [{
							targets: 2,
							className: 'dt-body-center'
						},
						{
							targets: 5,
							className: 'dt-body-right'
						},
						{
							targets: 6,
							className: 'dt-body-right'
						},
						{
							targets: 7,
							className: 'dt-body-right'
						}
					],
					buttons: [
						'pageLength'
						@if ($crud['excel'])
							, {
								extend: 'excel'
							}
						@endif
						@if ($crud['copy'])
							, {
								extend: 'copy'
							}
						@endif
						@if ($crud['csv'])
							, {
								extend: 'csv'
							}
						@endif
						@if ($crud['print'])
							, {
								extend: 'print'
							}
						@endif
						@if ($crud['pdf'])
							, {
								extend: 'pdf'
							}
						@endif
					],
				});
			}

			$(document).on('click', '.deletePurchase', function() {
				// getting the attribute
				const id = $(this).attr('data-id');

				swal({
						title: "Are you sure?",
						text: "You want Delete this Purchase!",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-outline-danger",
						confirmButtonText: "Yes, Delete it!",
						closeOnConfirm: true
					},
					function() {
						$.ajax({
							type: "post",
							url: "{{ url('/') }}/transactions/purchase/delete/" + id,
							headers: {
								'X-CSRF-Token': $('meta[name=_token]').attr('content')
							},
							dataType: "json",
							success: function(response) {
								$('#tblpurchase').DataTable().ajax.reload();
								if (response.status == true) {
									$('#tblcustomer').DataTable().ajax.reload();
									toastr.success(response.message, "Success")
								} else {
									toastr.error(response.message, "Failed")
								}
							}
						});
					});
			});
			fetchDataForTable();
		});
	</script>
@endsection
