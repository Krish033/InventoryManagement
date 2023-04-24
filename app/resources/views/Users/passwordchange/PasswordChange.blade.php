@extends('layouts.layout')
@section('content')<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Users & Permissions</li>
					<li class="breadcrumb-item">Password Change</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
	<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header text-center">
							<h5>Password Change</h5>
						</div>
						<div class="card-body">
                            <div class="row mt-20">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="txtCurrentPassword">Current Password <span class="required">*</span></label>
                                        <input type="password" id="txtCurrentPassword" class="form-control" placeholder="Current Password" value="">
                                        <span class="errors" id="txtCurrentPassword-err"></span>
                                    </div>
                                </div>
							</div>
							<div class="row mt-10">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="txtNewPassword">New Password <span class="required">*</span></label>
                                        <input type="password" id="txtNewPassword" class="form-control" placeholder="New Password" value="">
                                        <span class="errors" id="txtNewPassword-err"></span>
                                    </div>
                                </div>
                            </div>
							<div class="row mt-10">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="txtConfirmPassword">Confirm Password <span class="required">*</span></label>
                                        <input type="password" id="txtConfirmPassword" class="form-control" placeholder="Confirm Password" value="">
                                        <span class="errors" id="txtConfirmPassword-err"></span>
                                    </div>
                                </div>
                            </div>
							<div class="row">
								<div class="col-sm-12 text-right">
									<button id="btnChangePassword" type="button" class="btn btn-outline-success ">Change Password</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
        let RootUrl=$('#txtRootUrl').val();

		const FormValidation=async()=>{
			var FormData={}
				FormData['Password']= $('#txtNewPassword').val();
				FormData['ConfirmPassword']= $('#txtConfirmPassword').val();
				FormData['CurrentPassword']= $('#txtCurrentPassword').val();
				var blnFormValidation=true;
				if(FormData.CurrentPassword==""){
					$('#txtCurrentPassword-err').html('Current Password is required');blnFormValidation=false;
				}else if(FormData.CurrentPassword.length<3){
					$('#txtCurrentPassword-err').html('Current Password must be at least 5 characters');blnFormValidation=false;
				}
				if(FormData.Password==""){
					$('#txtNewPassword-err').html('Password is required');blnFormValidation=false;
				}else if(FormData.Password.length<3){
					$('#txtNewPassword-err').html('Password must be at least 5 characters');blnFormValidation=false;
				}
				if(FormData.ConfirmPassword==""){
					$('#txtConfirmPassword-err').html('Confirm Password is required');blnFormValidation=false;
				}else if(FormData.ConfirmPassword.length<3){
					$('#txtConfirmPassword-err').html('Confirm Password must be at least 5 characters');blnFormValidation=false;
				}else if(FormData.Password!==FormData.ConfirmPassword){
					$('#txtConfirmPassword-err').html('Confirm Password does not match with password');blnFormValidation=false;
				}
			return blnFormValidation
		}

		$('#btnChangePassword').click(async(e)=>{
			e.preventDefault();
			$('.errors').html('');
			let  formData = new FormData();
				formData.append('Password', $('#txtNewPassword').val());
				formData.append('ConfirmPassword', $('#txtConfirmPassword').val());
				formData.append('CurrentPassword', $('#txtCurrentPassword').val());
			let status=await FormValidation();
			if(status==true){
				btnLoading($('#btnChangePassword'));
				var submiturl="{{url('/')}}/users-and-permissions/PasswordChange";
				$.ajax({
					type:"post",
					url:submiturl,
					headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
					data:formData,
					cache: false,
					processData: false,
					contentType: false,
					error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
					complete: function(e, x, settings, exception){btnReset($('#btnChangePassword'));},
					success:function(response){
						document.body.scrollTop = 0; // For Safari
						document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
						if(response.status==true){
							swal({
								title: "Success",
								text: response.message,
								type: "success",
								showCancelButton: false,
								confirmButtonClass: "btn-primary",
								confirmButtonText: "OK",
								closeOnConfirm: false
							},function(){
								window.location.reload();
							});
							
						}else{
							toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
							if(response['errors']!=undefined){
								$('.errors').html('');
								$.each( response['errors'], function( KeyName, KeyValue ) {
									var key=KeyName;
									if(key=="Password"){$('#txtNewPassword-err').html(KeyValue);}
									if(key=="ConfirmPassword"){$('#txtConfirmPassword-err').html(KeyValue);}
									if(key=="CurrentPassword"){$('#txtCurrentPassword-err').html(KeyValue);}

								});
                            }
						}
					}
				});
			}
		});
    });
</script>
@endsection