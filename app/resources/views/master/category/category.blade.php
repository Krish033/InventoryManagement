@extends('layouts.layout')
@section('content')

	<div class="container-fluid">
		<div class="row d-flex justify-content-center">
			<div class="col-sm-5">
				<div class="card">
					<div class="card-header text-center">
						<h5 class="mt-10">{{ $header }}</h5>
					</div>
					<div class="card-body">
						<div class="row mb-3 d-flex justify-content-center">
							<div class="col">
								<label class="txtCName">Category Image<span class="text-danger">*</span></label>
								<input class="dropify" data-allowed-file-extensions="jpeg jpg png gif" data-default-file="<?php if ($isEdit == true) {
								    if ($EditData[0]->CImage != '') {
								        echo url('/') . '/' . $EditData[0]->CImage;
								    }
								} ?>"
									id="txtCImage" type="file">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="txtCName"> Category Name <span class="text-danger"> * </span></label>
									<input class="form-control" id="txtCName" type="text" value="<?php if ($isEdit == true) {
									    echo $EditData[0]->CName;
									} ?>">
									<div class="errors" id="txtCName-err"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label class="txtCategory"> Active Status</label>
									<select class="form-control" id="lstActiveStatus">
										<option @if ($isEdit && $EditData[0]->ActiveStatus == '1') selected @endif value="1">
											Active
										</option>
										<option @if ($isEdit && $EditData[0]->ActiveStatus == '0') selected @endif value="0">
											Inactive
										</option>
									</select>
									<div class="errors" id="txtCategory-err"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-right">
								@if ($crud['view'] == true)
									<a class="btn btn-sm btn-outline-dark" href="{{ url('/') }}/master/category/" id="btnCancel">Back</a>
								@endif

								@if (($crud['add'] == true && $isEdit == false) || ($crud['edit'] == true && $isEdit == true))
									<button class="btn btn-sm btn-outline-success" id="btnSave">
										@if ($isEdit == true)
											Update
										@else
											Save
										@endif
									</button>
								@endif
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			const formValidation = () => {
				$('.errors').html('');
				let status = true;
				let CName = $('#txtCName').val();
				if (CName == "") {
					$('#txtCName-err').html('The category name is required.');
					status = false;
				} else if (CName.length < 2) {
					$('#txtCName-err').html('Category Name must be greater than 2 characters');
					status = false;
				} else if (CName.length > 100) {
					$('#txtCName-err').html('Category Name may not be greater than 100 characters');
					status = false;
				}
				return status;
			}
			$('#btnSave').click(function() {
				let status = formValidation();
				if (status) {
					swal({
						title: "Are you sure?",
						text: "You want @if ($isEdit == true)Update @else Save @endif this Category?!",
						type: "info",
						showCancelButton: true,
						confirmButtonClass: "btn btn-outline-success",
						confirmButtonText: "Yes, @if ($isEdit == true)Update @else Save @endif it!",
						closeOnConfirm: true
					}, function() {

						let postUrl = "{{ url('/') }}/master/category/create";
						let formData = new FormData();

						formData.append('CName', $('#txtCName').val());
						formData.append('ActiveStatus', $('#lstActiveStatus').val());

						if ($('#txtCImage').val() != "") {
							formData.append('CImage', $('#txtCImage')[0].files[0]);
						}
						@if ($isEdit == true)
							formData.append('CID', "{{ $EditData[0]->CID }}");
							postUrl =
								"{{ url('/') }}/master/category/edit/{{ $EditData[0]->CID }}";
						@endif
						$.ajax({
							type: "post",
							url: postUrl,
							headers: {
								'X-CSRF-Token': $('meta[name=_token]').attr('content')
							},
							data: formData,
							cache: false,
							processData: false,
							contentType: false,


							error: function(e, x, settings, exception) {
								ajax_errors(e, x, settings, exception);
								toastr.error('Something went wrong', 'Failed')
							},

							success: function(response) {
								document.documentElement.scrollTop =
									0; // For Chrome, Firefox, IE and Opera
								if (response.status == true) {
									swal({
										title: "SUCCESS",
										text: response.message,
										type: "success",
										showCancelButton: false,
										confirmButtonClass: "btn-outline-success",
										confirmButtonText: "Okay",
										closeOnConfirm: false
									}, function() {
										@if ($isEdit == true)
											window.location.replace(
												"{{ url('/') }}/master/category"
											);
										@else
											window.location.reload();
										@endif

									});

								} else {
									toastr.error(response.message, "Failed")

									if (response['errors'] != undefined) {
										$('.errors').html('');
										$.each(response['errors'], function(KeyName,
											KeyValue) {
											var key = KeyName;
											if (key == "CName") {
												$('#txtCName-err').html(KeyValue);
											}
											if (key == "CImage") {
												$('#txtCImage-err').html(KeyValue);
											}
										});
									}
								}
							}
						});
					});
				}
			});
		});
	</script>
@endsection
