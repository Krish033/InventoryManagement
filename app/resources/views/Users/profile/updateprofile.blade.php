@extends('layouts.layout')
@section('content')
<!-- Container-fluid starts-->
<div class="container-fluid pt-5">
	<div class="edit-profile">
		<div class="row">
			<div class="col-lg-4">
				<div class="card">
					<!-- <div class="card-header">
						<h4 class="card-title mb-0">Edit Profile</h4>
						<div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-toggle="card-remove"><i class="fe fe-x"></i></a></div>
						</div> -->
					<div class="card-body">
						<form>
							<div class="form-group">
								<h6 class="form-label">Profile Image</h6>
								<input type="file" id="imgProfileImage" class="dropify" data-default-file="<?php if($EditData[0]->ProfileImage!=""){ echo url('/')."/".$EditData[0]->ProfileImage;}?>"  data-allowed-file-extensions="jpeg jpg png gif" /><span class="errors" id="imgProfileImage-err"></span>
							</div>
							<div class="form-group">
                                        <label for="txtFirstName">First Name <span class="required">*</span></label>
                                        <input type="text" id="txtFirstName" class="form-control" placeholder="First Name  must be 3-100 characters" value="{{$EditData[0]->FirstName}}">
                                        <span class="errors" id="txtFirstName-err"></span>
							</div>
                                    <div class="form-group">
                                        <label for="txtLastName">Last Name</label>
                                        <input type="text" id="txtLastName" class="form-control" placeholder="Last Name(optional)" value="{{$EditData[0]->LastName}}">
                                        <span class="errors" id="txtLastName-err"></span>
                                    </div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<form class="card">
					<!-- <div class="card-header">
						<h4 class="card-title mb-0">Edit Profile</h4>
						<div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-toggle="card-remove"><i class="fe fe-x"></i></a></div>
						</div> -->
					<div class="card-body">
						<div class="form-row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label for="txtMobileNumber"> MobileNumber <span id="lblCallingCode"></span> <span class="required">*</span></label>
									<input type="text" disabled="" id="txtMobileNumber" class="form-control" placeholder="Mobile Number enter without country code" value="{{$EditData[0]->MobileNumber}}">
									<span class="errors" id="txtMobileNumber-err"></span>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label for="txtEmail">E-Mail </label>
									<input type="email" id="txtEmail" class="form-control" placeholder="E-Mail"  value="{{$EditData[0]->EMail}}">
									<span class="errors" id="txtEmail-err"></span>
								</div>
							</div>
						
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label for="dtpDOB">Date Of Birth </label>
									<input type="date" id="dtpDOB" class="form-control" max='{{date("Y-12-31", strtotime(" - 15 year"))}}'  value="{{date('Y-m-d',strtotime($EditData[0]->DOB))}}">
									<span class="errors" id="dtpDOB-err"></span>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
									<label for="lstCountry">Country   <span class="required">*</span></label>
									<select id="lstCountry" name="lstCountry" class="form-control select2" data-selected='<?php  echo $EditData[0]->CountryID;  ?>' >
                                        <option value="">--Select Country--</option>
                                    </select>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
                                    <label for="lstState">State  <span class="required">*</span></label>
                                    <select id="lstState" name="lstState" class="form-control select2" data-country-id="lstCountry" data-selected='<?php  echo $EditData[0]->StateID;  ?>'>
                                        <option value="">--Select State--</option>
                                    </select>
									<span class="errors" id="lstState-err"></span>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
                                    <label for="lstCity">City  <span class="required">*</span></label>
                                    <select id="lstCity" name="lstCity" class="form-control select2" data-country-id="lstCountry" data-state-id="lstState" data-selected='<?php  echo $EditData[0]->CityID;  ?>'>
                                        <option value="">--Select City--</option>
                                    </select>
									<span class="errors" id="lstCity-err"></span>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
                                    <label for="PinCode">Postal Code  <span class="required">*</span></label>
                                    <select class="form-control select2Tag" id="PinCode">
											<option value="">Select a Postal Code</option>
										</select>
									<span class="errors" id="PinCode-err"></span>
								</div>
							</div>

                            <div class="col-sm-12 col-md-6">
								<div class="form-group">
                                    <label for="lstGender">Gender<span class="required">*</span></label>
                                    <select class="form-control select2" id="lstGender">
											<option value="">Select a Gender</option>
										</select>
									<span class="errors" id="lstGender-err"></span>
								</div>
							</div>


							<div class="col-sm-12 col-md-12">
								<div class="form-group">
									<label for="txtAddress">Address  </label>
									<textarea id="txtAddress" class="form-control" placeholder="Address" rows=3>{{$EditData[0]->Address}}</textarea>
									<span class="errors" id="txtAddress-err"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
						<button class="btn btn-pill btn-primary btn-air-primary btn-lg" id="btnSave" type="button">Update Profile</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Container-fluid Ends-->

<script>
    $(document).ready(function(){
        let MobileNumberStatus=false;
        let RootUrl=$('#txtRootUrl').val();
        
		const getCountries=async()=>{
            let CountryID=$('#lstCountry').attr('data-selected');
			$('#lstCountry').select2('destroy');
			$('#lstCountry option').remove();
			$('#lstCountry').append('<option value="">--Select Country--</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Country",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				async:false,
				dataType:"json",
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				success:function(response){
					for(var i=0;i<response.length;i++){
						if((response[i]['CountryID']==CountryID)||(response[i]['sortname'].toLowerCase()==CountryID.toLowerCase())){
							$('#lstCountry').append('<option data-phone-length="'+response[i]['PhoneLength']+'" data-phone-code="'+response[i]['PhoneCode']+'" selected value="'+response[i]['CountryID']+'">'+response[i]['CountryName']+'</option>');
                            $('#lblCallingCode').html("( +"+response[i]['PhoneCode']+" )");
						}else{
							$('#lstCountry').append('<option data-phone-length="'+response[i]['PhoneLength']+'" data-phone-code="'+response[i]['PhoneCode']+'" value="'+response[i]['CountryID']+'">'+response[i]['CountryName']+'</option>');
						}
                    }
                    if($('#lstCountry').val()!=""){
                        getStates();
                    }
				}
			})
			$('#lstCountry').select2();
		}
		const getStates=async()=>{
            let StateID=$('#lstState').attr('data-selected');
            let countryID=$('#lstCountry').val();
			$('#lstState').select2('destroy');
			$('#lstState option').remove();
			$('#lstState').append('<option value="">--Select State--</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/States",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				data:{CountryID:countryID},
				async:false,
				dataType:"json",
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				success:function(response){
					for(var i=0;i<response.length;i++){
						if((response[i]['StateID']==StateID)||(response[i]['StateName'].split(" ").join("").toLowerCase()==StateID.split(" ").join("").toLowerCase())){
							$('#lstState').append('<option selected value="'+response[i]['StateID']+'">'+response[i]['StateName']+'</option>');
						}else{
							$('#lstState').append('<option value="'+response[i]['StateID']+'">'+response[i]['StateName']+'</option>');
						}
                    }
                    if($('#lstState').val()!=""){
                        getCities();
                        getPostalCodes();
                    }
				}
			})
			$('#lstState').select2();
		}
		const getCities=async()=>{
            let CityID=$('#lstCity').attr('data-selected');
            let countryID=$('#lstCountry').val();
            let StateID=$('#lstState').val();
			$('#lstCity').select2('destroy');
			$('#lstCity option').remove();
			$('#lstCity').append('<option value="">--Select City--</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/City",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				data:{CountryID:countryID,StateID:StateID},
				async:false,
				dataType:"json",
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				success:function(response){
					for(let i=0;i<response.length;i++){
						if((response[i]['CityID']==CityID)||(response[i]['CityName'].split(" ").join("").toLowerCase()==CityID.split(" ").join("").toLowerCase())){
							$('#lstCity').append('<option selected value="'+response[i]['CityID']+'">'+response[i]['CityName']+'</option>');
						}else{
							$('#lstCity').append('<option value="'+response[i]['CityID']+'">'+response[i]['CityName']+'</option>');
						}
					}
				}
			})
			$('#lstCity').select2();
		}
		const getPostalCodes=async()=>{

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
						console.log(item.PID)
						let selected="";
						@php
							$PostalCode="";
							// $PID="";
							if($isEdit==true){
                               
								$PostalCode=$EditData[0]->PostalCodeID;
                              
							}
						@endphp
						
						if(item.PID=="{{$PostalCode}}"){selected="selected";}
						else if(item.PostalCode=="{{$PostalCode}}"){selected="selected";}
						$('#PinCode').append('<option '+selected+'  value="'+item.PID+'">'+item.PostalCode+'</option>');
					}
				}
			});
			$('#PinCode').select2({tags:true});
        }
		const clear_State_City=async()=>{
			//State list Clear
			$('#lstState').select2('destroy');
			$('#lstState option').remove();
			$('#lstState').append('<option value="">--Select State--</option>');
			$('#lstState').select2();
			//City list Clear
			$('#lstCity').select2('destroy');
			$('#lstCity option').remove();
			$('#lstCity').append('<option value="">--Select City--</option>');
			$('#lstCity').select2();
			//Postal Code list Clear
			$('#PinCode').select2('destroy');
			$('#PinCode option').remove();
			$('#PinCode').append('<option value="">--Select Postal Code--</option>');
			$('#PinCode').select2({tags: true});
        }
		const getGender=async()=>{
			$('#lstGender').select2('destroy');
			$('#lstGender option').remove();
			$('#lstGender').append('<option value="">Select a Gender</option>');
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
						$('#lstGender').append('<option '+selected+'  value="'+item.GID+'">'+item.Gender+'</option>');
					}
				}
			});
			$('#lstGender').select2();
		}
        getGender();
		const FormValidation=async()=>{
			var FormData={}
			FormData['FirstName']=$('#txtFirstName').val();
			FormData['LastName']=$('#txtLastName').val();
			FormData['DOB']=$('#dtpDOB').val();
			FormData['GenderID']=$('#lstGender').val();
			FormData['Address']= $('#txtAddress').val();
			FormData['CountryID']= $('#lstCountry').val();
			FormData['StateID']= $('#lstState').val();
			FormData['CityID']= $('#lstState').val();
			FormData['PostalCodeID']= $('#PinCode').val();
			FormData['EMail']= $('#txtEmail').val();
			$('.errors').html('');
			let blnFormValidation=true;
			if(FormData.FirstName==""){
				$('#txtFirstName-err').html('First Name is required');blnFormValidation=false;
			}else if(FormData.FirstName.length<3){
				$('#txtFirstName-err').html('First Name must be at least 3 characters');blnFormValidation=false;
			}else if(FormData.FirstName.length>100){
				$('#txtFirstName-err').html('First Name may not be greater than 100 characters');blnFormValidation=false;
			}
			if(FormData.LastName!=""){
				if(FormData.LastName.length>100){
					$('#txtLastName-err').html('Last Name may not be greater than 100 characters');blnFormValidation=false;
				}
			}
			
			if(FormData.Address!=""){
				if(FormData.Address.length<5){
    				$('#txtAddress-err').html('Address must be at least 5 characters');blnFormValidation=false;
    			}
            }
			if(FormData.GenderID==""){
				$('#lstGender-err').html('Gender is required');blnFormValidation=false;
			}
			if(FormData.CountryID==""){
				$('#lstCountry-err').html('Country is required');blnFormValidation=false;
			}
			if(FormData.StateID==""){
				$('#lstState-err').html('State is required');blnFormValidation=false;
			}
			if(FormData.CityID==""){
				$('#lstCity-err').html('City is required');blnFormValidation=false;
			}
			if(FormData.PostalCodeID==""){
				$('#PinCode-err').html('Postal Code is required');blnFormValidation=false;
			}
			return blnFormValidation
		}
		const App_init=async()=>{
			getCountries();
		}
		$('#lstCountry').change(async function(){
            let CallingCode=$('#lstCountry option:selected').attr('data-phone-code');
            if(CallingCode!=""){
                CallingCode="( +"+CallingCode+" )";
            }
            $('#lblCallingCode').html(CallingCode);
            clear_State_City();
            getStates();
		});
		$('#lstState').change(function(){
            getCities();
            getPostalCodes();
        });
        $('#btnSave').click(async function(){
			let  formData = new FormData();
			formData.append('FirstName', $('#txtFirstName').val());
			formData.append('LastName', $('#txtLastName').val());
			formData.append('DOB', $('#dtpDOB').val());
			formData.append('GenderID', $('#lstGender').val());
			formData.append('Address', $('#txtAddress').val());
			formData.append('CountryID', $('#lstCountry').val());
			formData.append('StateID', $('#lstState').val());
			formData.append('CityID', $('#lstCity').val());
			formData.append('PostalCodeID', $('#PinCode').val());
			formData.append('PostalCode', $('#PinCode option:selected').text());
			formData.append('EMail', $('#txtEmail').val());
			   
			if($('#imgProfileImage').val()!=""){
                        formData.append('ProfileImage', $('#imgProfileImage')[0].files[0]);
                    }      
			let status=await FormValidation();
			if((status==true)){
                btnLoading($('#btnSave'));
                
                swal({
                    title: "Are you sure?",
                    text: "Do you want update your profile!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes, Update it!",
                    closeOnConfirm: false
                },
                async function(){
                    $.ajax({
                        type:"post",
                        url:"{{url('/')}}/users-and-permissions/profileupdate",
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                        complete: function(e, x, settings, exception){btnReset($('#btnSave'));},
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
                                        window.location.replace("{{url('/')}}/users-and-permissions/profile");
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
                                        if(key=="FirstName"){$('#txtFirstName-err').html(KeyValue[0]);}
                                        if(key=="LastName"){$('#txtLastName-err').html(KeyValue);}
                                        if(key=="DOB"){$('#dtpDOB-err').html(KeyValue);}
                                        if(key=="Gender"){$('#lstGender-err').html(KeyValue);}
                                        if(key=="Address"){$('#txtAddress-err').html(KeyValue);}
                                        if(key=="CountryID"){$('#lstCountry-err').html(KeyValue);}
                                        if(key=="StateID"){$('#lstState-err').html(KeyValue);}
                                        if(key=="CityID"){$('#lstCity-err').html(KeyValue);}
                                        if(key=="PostalCodeID"){$('#PinCode-err').html(KeyValue);}
                                        if(key=="EMail"){$('#txtEmail-err').html(KeyValue);}
                                        if(key=="ProfileImage"){$('#imgProfileImage-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }
        })
		App_init();
    });
</script>
@endsection