@extends('layouts.layout')
@section('content')
	<div class="container-fluid">
		<div class="row d-flex justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header text-center">
						<h5 class="mt-10">{{ $header }}</h5>
					</div>
					<div class="card-body ">
						<div class="row mb-4  d-flex justify-content-center">
							<label class="txtSCName">Sub Category Image</label>
							<div class="col">
								<input class="dropify" data-allowed-file-extensions="jpeg jpg png gif" data-default-file="<?php if ($isEdit == true) {
								    if ($EditData[0]->SCImage != '') {
								        echo url('/') . '/' . $EditData[0]->SCImage;
								    }
								} ?>"
									id="txtCImage" type="file">
							</div>
						</div>
						<div class="row">

							<div class="col-sm-12">
								<div class="form-group">
									<label class="txtSCName"> Sub Category Name <span class="required"> * </span></label>
									<input class="form-control" id="txtSCName" type="text" value="<?php if ($isEdit == true) {
									    echo $EditData[0]->SCName;
									} ?>">
									<div class="errors" id="txtSCName-err"></div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label class="lstCategory"> Category<span class="required"> * </span></label>
									<select class="form-control select2" data-cid="<?php if ($isEdit == true) {
									    echo $EditData[0]->CID;
									} ?>" id="lstCategory">

									</select>
									<div class="errors" id="lstCategory-err"></div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label class="txtSubCategory"> Active Status</label>
									<select class="form-control" id="lstActiveStatus">
										<option @if ($isEdit && $EditData[0]->ActiveStatus == '1') selected @endif value="1">Active
										</option>
										<option @if ($isEdit && $EditData[0]->ActiveStatus == '0') selected @endif value="0">Inactive
										</option>
									</select>
									<div class="errors" id="txtSubCategory-err"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-right">
								@if ($crud['view'] == true)
									<a class="btn btn-sm btn-outline-dark" href="{{ url('/') }}/master/SubCategory/" id="btnCancel">Back</a>
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
				let SCName = $('#txtSCName').val();
				let CID = $('#lstCategory').val();
				if (SCName == "") {
					$('#txtSCName-err').html('The Sub Category name is required.');
					status = false;
				} else if (SCName.length < 2) {
					$('#txtSCName-err').html('Sub Category Name must be greater than 2 characters');
					status = false;
				} else if (SCName.length > 100) {
					$('#txtSCName-err').html('Sub Category Name may not be greater than 100 characters');
					status = false;
				}
				if (CID == '') {
					$('#lstCategory-err').html('The Category  is required.');
					status = false;
				}
				return status;
			}

			const lstCategory = async () => {
				let editCID = $('#lstCategory').attr('data-cid');
				$('#lstCategory option').remove();
				$('#lstCategory').append('<option value="">Select a Category</option>');
				$.ajax({
					type: "post",
					url: "{{ url('/') }}/master/SubCategory/getCategory",
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
					error: function(e, x, settings, exception) {
						ajax_errors(e, x, settings, exception);
						toastr.error('Something went wrong', 'Failed')
					},

					success: function(response) {
						for (item of response) {
							let selected = "";
							if (item.CID == editCID) {
								selected = "selected";
							}
							$('#lstCategory').append('<option ' + selected + '  value="' + item
								.CID + '">' + item.CName + '</option>');
						}
					}
				});
				$('#lstCategory').select2();
			}

			lstCategory();
			$('#btnSave').click(function() {
				let status = formValidation();

				const swalConfig = {
					title: "Are you sure?",
					text: "You want @if ($isEdit == true)Update @else Save @endif this SubCategory!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn btn-outline-success",
					confirmButtonText: "Yes, @if ($isEdit == true)Update @else Save @endif it!",
					closeOnConfirm: false
				}

				if (status) {
					swal(swalConfig, function() {

						let postUrl = "{{ url('/') }}/master/SubCategory/create";
						let formData = new FormData();

						formData.append('SCName', $('#txtSCName').val());
						formData.append('CID', $('#lstCategory').val());

						formData.append('ActiveStatus', $('#lstActiveStatus').val());

						if ($('#txtCImage').val() != "") {
							formData.append('SCImage', $('#txtCImage')[0].files[0]);
						}

						@if ($isEdit == true)
							formData.append('SCID', "{{ $EditData[0]->SCID }}");
							postUrl =
								"{{ url('/') }}/master/SubCategory/edit/{{ $EditData[0]->SCID }}";
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

							success: function(response) {

								const swalMainCOnfig = {
									title: "SUCCESS",
									text: response.message,
									type: "success",
									showCancelButton: false,
									confirmButtonClass: "btn-outline-success",
									confirmButtonText: "Okay",
									closeOnConfirm: false
								}

								if (response.status == true) {

									swal(swalMainCOnfig, function() {
										@if ($isEdit == true)
											window.location.replace(
												"{{ url('/') }}/master/SubCategory"
											);
										@else
											window.location.reload();
										@endif

									});

								} else {
									toastr.error('Something went wrong', 'Failed')
									if (response['errors'] != undefined) {
										$('.errors').html('');
										$.each(response['errors'], function(KeyName,
											KeyValue) {
											var key = KeyName;
											if (key == "SCName") {
												$('#txtSCName-err').html(KeyValue);
											}
											if (key == "CID") {
												$('#lstCategory-err').html(
													KeyValue);
											}
											if (key == "SCImage") {
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
