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
										@if ($crud['restore'] == 1)
											<a class="btn  btn-outline-dark btn-sm m-r-10" href="{{ url('/') }}/master/SubCategory/trash-view"
												type="button">Trash view</a>
										@endif
										@if ($crud['add'] == 1)
											<a class="btn  btn-outline-success btn-air-success btn-sm"
												href="{{ url('/') }}/master/SubCategory/create" type="button">Create</a> <!-- full-right -->
										@endif
									</div>
								</div>
							</div>
							<div class="card-body ">
								<table class="table" id="tblSubCategory">
									<thead>
										<tr>
											<th class="text-center">ID</th>
											<th class="text-center">SubCategory</th>
											<th class="text-center">Category</th>
											<th class="text-center">Active Status</th>
											<th class="text-center">action</th>
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
			let RootUrl = $('#txtRootUrl').val();
			const LoadTable = async () => {
				@if ($crud['view'] == 1)
					$('#tblSubCategory').dataTable({
						"bProcessing": true,
						"bServerSide": true,
						"ajax": {
							"url": RootUrl + "master/SubCategory/data?_token=" + $('meta[name=_token]')
								.attr('content'),
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
								"targets": 3
							},
							{
								"className": "dt-center",
								"targets": 4
							}
						]
					});
				@endif
			}
			$(document).on('click', '.btnEdit', function() {
				window.location.replace("{{ url('/') }}/master/SubCategory/edit/" + $(this).attr(
					'data-id'));
			});

			$(document).on('click', '.btnDelete', function() {
				let ID = $(this).attr('data-id');
				swal({
						title: "Are you sure?",
						text: "You want Delete this SubCategory!",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-outline-danger",
						confirmButtonText: "Yes, Delete it!",
						closeOnConfirm: false
					},
					function() {
						// swal.close();
						$.ajax({
							type: "post",
							url: "{{ url('/') }}/master/SubCategory/delete/" + ID,
							headers: {
								'X-CSRF-Token': $('meta[name=_token]').attr('content')
							},
							dataType: "json",
							success: function(response) {
								// swal.close();
								if (response.status == true) {
									$('#tblSubCategory').DataTable().ajax.reload();
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
