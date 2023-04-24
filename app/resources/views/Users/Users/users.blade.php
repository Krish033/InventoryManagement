@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i
								class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Users & Permissions</li>
					<li class="breadcrumb-item">User</li>
					<li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header text-center">
					<h5>User</h5>
				</div>

				<div class="card-body p-20">
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4 text-center userImage">
							<input type="file" id="txtCImage" class="dropify"
								data-default-file="<?php if($isEdit==true){if($EditData[0]->ProfileImage !=""){ echo url('/')."
								/".$EditData[0]->ProfileImage;}}?>" data-allowed-file-extensions="jpeg jpg png gif" />
							<span class="errors" id="txtCImage-err"></span>
						</div>
						<div class="col-md-4"></div>
					</div>
					<div class="row mt-20">
						<div class="col-md-6">
							<div class="form-group">
								<label for="FirstName">First Name <span class="required">*</span></label>

								<input type="text" id="FirstName" class="form-control" placeholder="First Name"
									value="<?php if($isEdit==true){ echo $EditData[0]->FirstName;} ?>">
								<span class="errors" id="FirstName-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="LastName">Last Name <span class="required">*</span></label>

								<input type="text" id="LastName" class="form-control " placeholder="Last Name"
									value="<?php if($isEdit==true){ echo $EditData[0]->LastName;} ?>">
								<span class="errors" id="LastName-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="DOB">Date Of Birth <span class="required">*</span></label>

								<input type="date" id="DOB" class="form-control date" placeholder="Date of Birth"
									value="<?php if($isEdit==true){ echo $EditData[0]->DOB;} ?>">
								<span class="errors" id="DOB-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="DOJ">Date Of Join <span class="required">*</span></label>

								<input type="date" id="DOJ" class="form-control date" placeholder="Date of Join"
									value="<?php if($isEdit==true){ echo $EditData[0]->DOJ;} ?>">
								<span class="errors" id="DOJ-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="Gender">Gender <span class="required">*</span></label>
								<select class="form-control select2" id="Gender">
									<option value="">Select a Gender</option>
								</select>
								<span class="errors" id="Gender-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="Country">Country <span class="required">*</span></label>
								<select class="form-control select2" id="Country">
									<option value="">Select a Country</option>
								</select>
								<span class="errors" id="Country-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="State">State <span class="required">*</span></label>
								<select class="form-control select2" id="State">
									<option value="">Select a State</option>
								</select>
								<span class="errors" id="State-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="City">City <span class="required">*</span></label>
								<select class="form-control select2" id="City">
									<option value="">Select a City</option>
								</select>
								<span class="errors" id="City-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="PinCode">Postal Code <span class="required">*</span></label>
								<select class="form-control select2Tag" id="PinCode">
									<option value="">Select a Postal Code</option>
								</select>
								<span class="errors" id="PinCode-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">

								<label for="Address">Address <span class="required">*</span></label>
								<textarea class="form-control" placeholder="Address" id="Address" name="Address"
									rows="2"><?php if($isEdit==true){ echo $EditData[0]->Address;} ?></textarea>
								<span class="errors" id="Address-err"></span>
							</div>
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="Email">Email <span class="required">*</span></label>
								<input type="email" id="Email" class="form-control" placeholder="E-Mail"
									value="<?php if($isEdit==true){ echo $EditData[0]->EMail;} ?>">
								<span class="errors" id="Email-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="MobileNumber"> MobileNumber <span id="CallCode"></span> <span
										class="required">*</span></label>
								<input type="number" id="MobileNumber" class="form-control" data-length="0"
									placeholder="Mobile Number enter without country code"
									value="<?php if($isEdit==true){ echo $EditData[0]->MobileNumber;} ?>">

								<span class="errors" id="MobileNumber-err"></span>
							</div>
						</div>
						<div class="col-md-6 EditPassword">
							<div class="form-group">
								<label for="FirstPass">Password <span class="required">*</span></label>
								<input type="password" id="FirstPass" class="form-control " placeholder="Password"
									value="<?php if($isEdit==true){ echo $EditData[0]->password;} ?>">

								<span class="errors" id="FirstPass-err"></span>
							</div>
						</div>
						<div class="col-md-6 EditCPassword">
							<div class="form-group">
								<label for="ConfirmPass">Confirm Password <span class="required">*</span></label>
								<input type="Password" id="ConfirmPass" class="form-control " name='Confirm Password'
									placeholder="Confirm Password"
									value="<?php if($isEdit==true){ echo $EditData[0]->Password1;} ?>">

								<span class="errors" id="ConfirmPass-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="UserRole">Role <span class="required">*</span></label>
								<select class="form-control select2Tag" id="UserRole">
									<option value="">Select a Role</option>
								</select>
								<span class="errors" id="UserRole-err"></span>
							</div>
						</div>
						<div class="col-md-6">
							<label for="">Active Status</label>
							<select class="form-control" id="lstActiveStatus">
								<option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected
									@endif @endif >Active</option>
								<option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected
									@endif @endif>Inactive</option>
							</select>
						</div>

					</div>
					<input type="hidden" id="IsEditval" class="form-control" value="{{$isEdit}}">

					<div class="row">
						<div class="col-sm-12 text-right">
							@if($crud['view']==true)
							<a href="{{url('/')}}/users-and-permissions/users/" class="btn btn-sm btn-outline-dark"
								id="btnCancel">Back</a>
							@endif

							@if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
							<button class="btn btn-sm btn-outline-success" id="btnSubmit">@if($isEdit==true) Update
								@else Save @endif</button>
							@endif
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var isEdit = $('#IsEditval').val();

if(isEdit == true){
					$('.EditPassword').prop("hidden", true);
					$('.EditCPassword').prop("hidden", true);
				}

        const formValidation=()=>{

			$('.errors').html('');
            let status=true;

let FirstName = $('#FirstName').val();
let LastName = $('#LastName').val();
let FirstPass = $('#FirstPass').val();
let ConfirmPass = $('#ConfirmPass').val();
let DOB = $('#DOB').val();
let DOJ = $('#DOJ').val();
let Gender = $('#Gender').val();
let Country = $('#Country').val();
let State = $('#State').val();
let City = $('#City').val();
let Address = $('#Address').val();
let PinCode = $('#PinCode').val();
let Email = $('#Email').val();
let MobileNumber = $('#MobileNumber').val();
let txtCImage = $('#txtCImage').val();
let imagePath = $('#txtCImage').attr('data-default-file');
let lstActiveStatus = $('#lstActiveStatus').val();
let lstReportTo = $('#lstReportTo').val();
let UserRole = $('#UserRole').val();

	if (FirstName == "") {
		$('#FirstName-err').html('First Name is required');status = false;
	} else if (FirstName.length > 50) {
		$('#FirstName-err').html('First Name may not be greater than 50 characters');status = false;
	}else if (FirstName.length <3) {
		$('#FirstName-err').html('First Name may not be leesthen than 3 characters');status = false;
	}
	if (LastName == "") {
		$('#LastName-err').html('Last Name is required');status = false;
	} else if (LastName.length > 50) {
		$('#LastName-err').html('Last Name may not be greater than 50 characters');status = false;
	}else if (LastName.length < 3) {
		$('#LastName-err').html('Last Name may not be leesthen than 3 characters');status = false;
	}
	if (FirstPass == "") {
		$('#FirstPass-err').html('Password is required');status = false;
	} else if (FirstPass.length < 5) {
		$('#FirstPass-err').html('Password may not be less than 5 characters');status = false;
	}
	if (ConfirmPass == "") {
		$('#ConfirmPass-err').html('Password is required');status = false;
	} else if (ConfirmPass.length < 5) {
		$('#ConfirmPass-err').html('Password may not be less than 5 characters');status = false;
	}
	if(isEdit == false){
		
		if (FirstPass != ConfirmPass) {
			$('#ConfirmPass-err').html('Passwords did not match');status = false;
		}
	}

	if (DOB == "") {
		$('#DOB-err').html('Date of Birth  is required');status = false;
	}if (DOJ == "") {
		$('#DOJ-err').html('Date of Join  is required');status = false;
	}
	if (Gender == "") {
		$('#Gender-err').html('plese select Gender');status = false;
	}
	if (Country == "") {
		$('#Country-err').html('please select country');status = false;
	}
	if (State == "") {
		$('#State-err').html('please select State');status = false;
	}
	if (City == "") {
		$('#City-err').html('please select City');status = false;
	}
	if (Address == "") {
		$('#Address-err').html('Address  is required');status = false;
	}
	if (Address.length < 10) {
		$('#Address-err').html('Address  may not be greater than 10 characters');status = false;
	}

	if (PinCode == "") {
		$('#PinCode-err').html('plese select Postalcode');status = false;
	}

	if (Email == "") {
		$('#Email-err').html('E-Mail  is required');status = false;
	}
	var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if(!Email.match(mailformat))
		{
			$('#Email-err').html('Valid E-Mail  is required');status = false;
		}
	if (MobileNumber == "") {
		$('#MobileNumber-err').html('Mobile Number  is required');status = false;
	}
	
	if(typeof(txtCImage) != "undefined" && txtCImage !== null && txtCImage !== ''){
		let validation = fileValidation();
		if(validation !=''){
			$('#txtCImage-err').html(validation);status = false;
		}
	}
	if (UserRole == "") {
		$('#UserRole-err').html('Please Select Role');status = false;
	}
	return status;
        }
		function fileValidation() {
			var errorMsg = '';
            var fileInput = 
                document.getElementById('txtCImage');
              
            var filePath = fileInput.value;
          
            // Allowing file type
            var allowedExtensions = 
                    /(\.jpg|\.jpeg|\.png|\.gif)$/i;
              
            if (!allowedExtensions.exec(filePath)) {
                errorMsg='Invalid file type';
                fileInput.value = '';
                
            } 
			return errorMsg;
            
        }
		const appInit = async () => {
			getCountry();
			getGender();
			getRole();
		}
		const getRole = async () => {
			$('#UserRole').select2('destroy');
			$('#UserRole option').remove();
			$('#UserRole').append('<option value="">Select a Role</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Role",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$RoleID="";
							if($isEdit==true){
								$RoleID=$EditData[0]->RoleID;
							}
						@endphp
						if(item.RoleID=="{{$RoleID}}"){selected="selected";}
						$('#UserRole').append('<option '+selected+'  value="'+item.RoleID+'">'+item.RoleName+'</option>');
					}
				}
			});
			$('#Gender').select2();
		}
		const getGender=async()=>{
			$('#Gender').select2('destroy');
			$('#Gender option').remove();
			$('#Gender').append('<option value="">Select a Gender</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Gender",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$GenderID="";
							if($isEdit==true){
								$GenderID=$EditData[0]->GenderID;
							}
						@endphp
						if(item.GID=="{{$GenderID}}"){selected="selected";}
						$('#Gender').append('<option '+selected+'  value="'+item.GID+'">'+item.Gender+'</option>');
					}
				}
			});
			$('#Gender').select2();
		}

		const getCountry=async()=>{
			
			$('#Country').select2('destroy');
			$('#Country option').remove();
			$('#Country').append('<option value="">Select a Country</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Country",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$CountryID="";
							$CountryName="";
							if($isEdit==true){
								$CountryID=$EditData[0]->CountryID;
							}
						@endphp
						if(item.CountryID=="{{$CountryID}}"){selected="selected";}
						else if(item.CountryName=="{{$CountryName}}"){selected="selected";}
						$('#Country').append('<option '+selected+' data-phone-code="'+item.PhoneCode+'" data-phone-lenth="'+item.PhoneLength+'" value="'+item.CountryID+'">'+item.CountryName+'</option>');
					}
					let PhoneLength=0;
					if($('#Country').val()!=""){
						GetStateName();
						let CallingCode=$('#Country option:selected').attr('data-phone-code');
							PhoneLength=$('#Country option:selected').attr('data-phone-lenth');
						$('#CallCode').html(' (+'+CallingCode+')')
					}else{
						$('#CallCode').html('');
					}
					if((PhoneLength=="")||(PhoneLength==undefined)){PhoneLength=0;}
					$('#MobileNumber').attr('data-length',PhoneLength)
				}
			});
			$('#Country').select2();
		}

		const GetStateName=async()=>{
			let CountryID=$('#Country').val();
			$('#State').select2('destroy');
			$('#State option').remove();
			$('#State').append('<option value="">Select a State</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/States",
				data:{CountryID:CountryID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$StateID="";
							$StateName="";
							if($isEdit==true){
								$StateID=$EditData[0]->StateID;
							}else{
								// $StateName=$Location['State'];
							}
						@endphp

						if(item.StateID=="{{$StateID}}"){selected="selected";}
						else if(item.StateName=="{{$StateName}}"){selected="selected";}
						$('#State').append('<option  '+selected+' value="'+item.StateID+'">'+item.StateName+'</option>');
					}
					if($('#State').val()!=""){
						GetCityName();
					
					}
				}
			});
			$('#State').select2();
		}
		const GetCityName=async()=>{
			let CountryID=$('#Country').val();
			let StateID=$('#State').val();
			$('#City').select2('destroy');
			$('#City option').remove();
			$('#City').append('<option value="">Select a City</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/City",
				data:{CountryID:CountryID,StateID:StateID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$CityID="";
							$City="";
							if($isEdit==true){
								$CityID=$EditData[0]->CityID;
							}
						@endphp
						if(item.CityID=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#City').append('<option '+selected+'  value="'+item.CityID+'">'+item.CityName+'</option>');
					}
				}
			});
			
			$('#City').select2();
		}
		const GetPinCode=async()=>{
			let CountryID=$('#Country').val();
			let StateID=$('#State').val();
			$('#PinCode').select2('destroy');
			$('#PinCode option').remove();
			$('#PinCode').append('<option value="">Select a Postal Code</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/PostalCode",
				data:{CountryID:CountryID,StateID:StateID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						// console.log(item.PID)
						let selected="";
						@php
							$PostalCode="";
							// $PID="";
							if($isEdit==true){
								$PostalCode=$EditData[0]->PostalCode;
								// $PID=$EditData[0]->PID;
							}
						@endphp
						
						if(item.PostalCode=="{{$PostalCode}}"){selected="selected";}
						else if(item.PostalCode=="{{$PostalCode}}"){selected="selected";}
						$('#PinCode').append('<option '+selected+'  value="'+item.PID+'">'+item.PostalCode+'</option>');
					}
				}
			});
			$('#PinCode').select2({tags:true});
		}
		GetPinCode();
		$('#Country').change(function(){
			GetStateName();
			let PhoneLength=0;
			if($('#Country').val()!=""){
				let CallingCode=$('#Country option:selected').attr('data-phone-code');
				PhoneLength=$('#Country option:selected').attr('data-phone-lenth');
				$('#CallCode').html(' (+'+CallingCode+')')
			}else{
				$('#CallCode').html('');
			}
			if((PhoneLength=="")||(PhoneLength==undefined)){PhoneLength=0;}
			$('#MobileNumber').attr('data-length',PhoneLength)
		})
		$('#State').change(function(){
			GetCityName();
		})

		
		$('.userImage .dropify-clear').click(function(){
			$('#txtCImage').attr('data-default-file', '');
		})
		appInit();
        $('#btnSubmit').click(function(){
            let status=formValidation();
            if(status){
			
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this Category!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-success",
                    confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSubmit'));
        
                    let formData=new FormData();
					console.log(formData);
                    formData.append('FirstName', $('#FirstName').val());
				formData.append('LastName', $('#LastName').val());
				formData.append('Password', $('#FirstPass').val());
				formData.append('CPassword', $('#ConfirmPass').val());
				formData.append('Email', $('#Email').val());
				formData.append('DOB', $('#DOB').val());
				formData.append('DOJ', $('#DOJ').val());
				formData.append('Gender', $('#Gender').val());
				formData.append('Country', $('#Country').val());
				formData.append('State', $('#State').val());
				formData.append('City', $('#City').val());
				formData.append('Address', $('#Address').val());
				formData.append('PostalCodeID', $('#PinCode').val());
				formData.append('PostalCode', $('#PinCode option:selected').text());
				formData.append('MobileNumber', $('#MobileNumber').val());
				formData.append('ActiveStatus', $('#lstActiveStatus').val());

				formData.append('RoleID', $('#UserRole').val());

                    if($('#txtCImage').val()!=""){
                        formData.append('ProfileImage', $('#txtCImage')[0].files[0]);
                    }

					@if($isEdit == true)
					formData.append('UserID',"{{$EditData[0]->UserID}}");
					var  submiturl = "{{ url('/') }}/users-and-permissions/users/edit/{{$EditData[0]->UserID}}";
           			 @else
						var  submiturl = "{{ url('/') }}/users-and-permissions/users/create";
           			 @endif
                    $.ajax({
                        type:"post",
                        url:submiturl,
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;
                                    percentComplete=parseFloat(percentComplete).toFixed(2);
                                    $('#divProcessText').html(percentComplete+'% Completed.<br> Please wait for until upload process complete.');
                                    //Do something with upload progress here
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            ajaxindicatorstart("Please wait Upload Process on going.");

                            var percentVal = '0%';
                            setTimeout(() => {
                            $('#divProcessText').html(percentVal+' Completed.<br> Please wait for until upload process complete.');
                            }, 100);
                        },
                        error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                        complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));ajaxindicatorstop();},
                        success:function(response){
                            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                            if(response.status==true){
                                swal({
                                    title: "SUCCESS",
                                    text: response.message,
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonClass: "btn-outline-success",
                                    confirmButtonText: "Okay",
                                    closeOnConfirm: false
                                },function(){
                                    @if($isEdit==true)
                                        window.location.replace("{{url('/')}}/users-and-permissions/users/");
                                    @else
                                        window.location.reload();
                                    @endif
                                    
                                });
                                
                            }else{
                                toastr.error(response.message, "Failed")
                                if(response['errors']!=undefined){
                                    $('.errors').html('');
										$.each(response['errors'], function(KeyName, KeyValue) {
                                        var key = KeyName;
                                        if (key == "Email") {
                                            $('#Email-err').html(KeyValue);
                                        }else
										if (key == "MobileNumber") {
                                            $('#MobileNumber-err').html(KeyValue);
                                        }else if (key == "FirstName") {
                                            $('#FirstName-err').html(KeyValue);
                                        }else if (key == "LastName") {
                                            $('#LastName-err').html(KeyValue);
                                        }else if (key == "State") {
                                            $('#State-err').html(KeyValue);
                                        }else if (key == "Gender") {
                                            $('#Gender-err').html(KeyValue);
                                        }else if (key == "City") {
                                            $('#City-err').html(KeyValue);
                                        }else if (key == "Country") {
                                            $('#Country-err').html(KeyValue);
                                        }else if (key == "PostalCode") {
                                            $('#PinCode-err').html(KeyValue);
                                        }else if (key == "ReportTo") {
                                            $('#lstReportTo-err').html(KeyValue);
                                        }else if (key == "Password") {
                                            $('#Password-err').html(KeyValue);
                                        }
                                        if(key=="CImage"){$('#txtCImage-err').html(KeyValue);}
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