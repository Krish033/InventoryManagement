@extends('layouts.layout')
@section('content')
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-original-title="" href="{{ url('/') }}" title=""><i
									class="f-16 fa fa-home"></i></a></li>
						<li class="breadcrumb-item">Transactions</li>
						<li class="breadcrumb-item">Sales</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
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
										<h5>Sale</h5>
									</div>
									<div class="col-md-4 my-2 text-right text-md-right">
										{{-- @if ($crud['restore'] == 1) --}}
										<a class="btn  btn-outline-light btn-sm m-r-10" href="{{ route('sales.trash') }}" type="button"> Trash view
										</a>
										{{-- @endif --}}
										{{-- @if ($crud['add'] == 1) --}}
										<a class="btn  btn-outline-success btn-air-success btn-sm" href="{{ route('sales.create') }}"
											type="button">Create</a>
										<!-- full-right -->
										{{-- @endif --}}
									</div>
								</div>
							</div>
							<div class="card-body ">
								<table class="table" id="tblsales">
									<thead>
										<tr>
											<th class="text-center">Id</th>
											<th class="text-center">Date</th>
											<th class="text-center">Customer</th>
											<th class="text-center">MOP</th>
											<th class="text-center">Taxable</th>
											<th class="text-center">Tax amount</th>
											<th class="text-center">Total amount</th>
											<th class="text-center">Paid amount</th>
											<th class="text-center">Balance amount</th>
											<th class="text-center">Created by</th>
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

			// sales Table url
			const url = "{{ route('sales.home.api') }}";
			// console.log();

			const fetchDataForTable = async () => {

				$('#tblsales').dataTable({

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
							"className": "dt-center",
							"targets": 2
						},
						{
							"className": "dt-center",
							"targets": 3
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

			$(document).on('click', '.deleteSale', function() {
				// getting the attribute
				const id = $(this).attr('data-id');

				swal({
						title: "Are you sure?",
						text: "You want Delete this Sale!",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-outline-danger",
						confirmButtonText: "Yes, Delete it!",
						closeOnConfirm: false
					},
					function() {
						swal.close();
						$.ajax({
							type: "post",
							url: "{{ url('/') }}/transactions/sales/delete/" + id,

							headers: {
								'X-CSRF-Token': $('meta[name=_token]').attr('content')
							},

							success: function(response) {
								$('#tblsales').DataTable().ajax.reload();
								toastr.success(response.message)
							},
							error: () => {
								toastr.error("Something went wrong", "Failed")
								return;
							}
						});
					});
			});
			fetchDataForTable();
		});

		// markSaleAsCompleted
		// startSale
		// endSale
		// deleteSale

		$(document).ready(function() {

			$(document).on('click', '.markSaleAsCompleted', function(e) {

				const id = e.target.dataset.id;
				const url = "{{ url('/') }}/transactions/api/sales/mark-completed/" + id;

				const swalConfiguration = {
					title: "Are you sure?",
					text: "You want Mark this Sale as Completed!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-outline-success",
					confirmButtonText: "Confrim",
					closeOnConfirm: true
				}

				const ajaConfiguration = {
					url,
					method: 'POST',
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
				}

				swal(swalConfiguration, function() {
					$.ajax({
						...ajaConfiguration,
						success: (response) => {
							toastr.success(response?.message);
							$('#tblsales').DataTable().ajax.reload();
							return;
						},
						error: (response) => {
							toastr.error(response?.message);
							return false
						},
					});
				});
			});

			$(document).on('click', '.endSale', function(e) {

				const id = e.target.dataset.id;
				const url = "{{ url('/') }}/transactions/api/sales/end-sale/" + id;

				const swalConfiguration = {
					title: "This action cannot reverted",
					text: "You want End this sale?!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-outline-danger",
					confirmButtonText: "Confrim",
					closeOnConfirm: true
				}

				const ajaConfiguration = {
					url,
					method: 'POST',
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
				}

				swal(swalConfiguration, function() {
					$.ajax({
						...ajaConfiguration,
						success: (response) => {
							toastr.success(response?.message);
							$('#tblsales').DataTable().ajax.reload();
							return;
						},
						error: (response) => {
							toastr.error(response?.message);
							return false
						},
					});
				});
			});


			$(document).on('click', '.startSale', function(e) {

				const id = e.target.dataset.id;
				const url = "{{ url('/') }}/transactions/api/sales/start-sale/" + id;

				const swalConfiguration = {
					title: "This action cannot reverted",
					text: "You want Start this sale?!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-outline-success",
					confirmButtonText: "Confrim",
					closeOnConfirm: true
				}

				const ajaConfiguration = {
					url,
					method: 'POST',
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
				}

				swal(swalConfiguration, function() {
					$.ajax({
						...ajaConfiguration,
						success: (response) => {
							toastr.success(response?.message);
							$('#tblsales').DataTable().ajax.reload();
							return;
						},
						error: (response) => {
							toastr.error(response?.message);
							return false
						},
					});
				});
			});
		});
	</script>
@endsection
