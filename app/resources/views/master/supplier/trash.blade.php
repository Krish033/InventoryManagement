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
										<a class="btn  btn-outline-dark btn-sm me-2" href="{{ url('/') }}/master/suppliers"
											type="button">Back</a>
									</div>
								</div>
							</div>
							<div class="card-body ">
								<table class="table" id="tblsupplier">
									<thead>
										<tr>
											<th class="text-center">Image</th>
											<th class="text-center">Name</th>
											<th class="text-center">Mobile</th>
											<th class="text-center">Email</th>
											<th class="text-center">Address</th>
											<th class="text-center">Country ID</th>
											<th class="text-center">Active Status</th>
											<th class="text-center">Action</th>
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
	<script>
		$(document).ready(function() {
			let RootUrl = "{{ route('supplier.trash.api') }}";
			const LoadTable = async () => {
				@if ($crud['view'] == 1)
					$('#tblsupplier').dataTable({
						"bProcessing": true,
						"bServerSide": true,
						"ajax": {
							"url": RootUrl + "?_token=" + $('meta[name=_token]').attr('content'),
							"headers": {
								'X-CSRF-Token': $('meta[name=_token]').attr('content')
							},
							"type": "POST"
						},
						deferRender: true,
						responsive: true,
						dom: 'Bfrtip',
						"iDisplayLength": 10,
						"lengthMenu": [
							[10, 25, 50, 100, 250, 500, -1],
							[10, 25, 50, 100, 250, 500, "All"]
						],
						buttons: [
							'pageLength'
							@if ($crud['excel'] == 1)
								, {
									extend: 'excel',
									footer: true,
									title: "{{ $header }}",
									"action": DataTableExportOption,
									exportOptions: {
										columns: "thead th:not(.noExport)"
									}
								}
							@endif
							@if ($crud['copy'] == 1)
								, {
									extend: 'copy',
									footer: true,
									title: "{{ $header }}",
									"action": DataTableExportOption,
									exportOptions: {
										columns: "thead th:not(.noExport)"
									}
								}
							@endif
							@if ($crud['csv'] == 1)
								, {
									extend: 'csv',
									footer: true,
									title: "{{ $header }}",
									"action": DataTableExportOption,
									exportOptions: {
										columns: "thead th:not(.noExport)"
									}
								}
							@endif
							@if ($crud['print'] == 1)
								, {
									extend: 'print',
									footer: true,
									title: "{{ $header }}",
									"action": DataTableExportOption,
									exportOptions: {
										columns: "thead th:not(.noExport)"
									}
								}
							@endif
							@if ($crud['pdf'] == 1)
								, {
									extend: 'pdf',
									footer: true,
									title: "{{ $header }}",
									"action": DataTableExportOption,
									exportOptions: {
										columns: "thead th:not(.noExport)"
									}
								}
							@endif
						],
						columnDefs: [{
								"className": "dt-center",
								"targets": 2
							},
							{
								"className": "dt-center",
								"targets": 3
							}
						]
					});
				@endif
			}

			$(document).on('click', '.btnRestore', function() {
				let ID = $(this).attr('data-id');
				swal({
						title: "Are you sure?",
						text: "You want Restore this Item!",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-outline-success",
						confirmButtonText: "Yes, Restore it!",
						closeOnConfirm: true
					},
					function() {

						$.ajax({
							type: "post",
							url: "{{ url('/') }}/master/suppliers/restore/" + ID,
							headers: {
								'X-CSRF-Token': $('meta[name=_token]').attr('content')
							},
							dataType: "json",
							error: function(e, x, settings, exception) {
								ajax_errors(e, x, settings, exception);

							},
							success: function(response) {

								if (response.status == true) {
									$('#tblsupplier').DataTable().ajax.reload();

									toastr.success(response.message, "Success")
								} else {
									toastr.error(response.message, "Failed")
								}
							}
						});
					});
			});
			LoadTable();
		});
	</script>
@endsection
