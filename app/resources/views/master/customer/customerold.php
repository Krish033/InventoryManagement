@extends('layouts.layout')
@section('content')

<!-- Container-fluid starts-->

<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">Customer</li>
					<li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
          <div class="row justify-content-center">
              <div class="col-md-8">
                <div class="card">
                  <div class="card-header text-center">
                    <h5>Customer</h5>
                  </div>
                  <div class="card-body">
                    <form class="form-wizard" id="regForm" action="#" method="POST">
                      <div class="tab">
					  <div class="row mb-3">
									<div class="col-md-4"></div>
									<div class="col-md-4 text-center userImage">
										<input type="file" id="txtCImage" class="dropify" data-default-file="<?php if($isEdit==true){if($EditData[0]->CImage !=""){ echo url('/')."/".$EditData[0]->CImage;}}?>"  data-allowed-file-extensions="jpeg jpg png gif" />
										<span class="errors" id="txtCImage-err"></span>
									</div>
									<div class="col-md-4"></div>
								</div>

								<div class="row mb-3">
								<div class="col-md-6">
									<div class="form-group">
								<label for="FirstName">Company Name <span class="required">*</span></label>	
								<input type="text" id="FirstName" class="form-control FormWizVal" placeholder="Customer Name" 	value="<?php if($isEdit==true){ echo $EditData[0]->CName;} ?>">
										<span class="errors" id="FirstName-err"></span>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="CID">Category ID <span class="required">*</span></label>
										<select class="form-control select2" id="CategoryID">
											<option value="">Select a CID</option>
										</select>
										<span class="errors" id="CategoryID-err"></span>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="SCID">Sub Category<span class="required">*</span></label>
										<select class="form-control select2" id="SubCID">
											<option value="">Select a SCID</option>
										</select>
										<span class="errors" id="SubCID-err"></span>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
								<label for="ContactPerson">Contact Person<span class="required">*</span></label>	
								<input type="text" id="ContactPerson" class="form-control " placeholder="Customer Name" 	value="<?php if($isEdit==true){ echo $EditData[0]->CName;} ?>">
										<span class="errors" id="ContactPerson-err"></span>
									</div>
								</div>
	
									<div class="col-md-6">
										<div class="form-group">
											<label for="MobileNumber"> MobileNumber <span id="CallCode"></span> <span class="required">*</span></label>
											<input type="text" id="MobileNumber" class="form-control FormWizVal" data-length="0" placeholder="Mobile Number enter without country code"  value="<?php if($isEdit==true){ echo $EditData[0]->MobileNumber;} ?>">
											
											<span class="errors" id="MobileNumber-err"></span>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="MobileNumber">Alternate MobileNumber</label>
											<input type="text" id="AlterMobileNumber" class="form-control " data-length="0" placeholder="Alter Mobile Number enter without country code"  value="<?php if($isEdit==true){ echo $EditData[0]->AlterMobileNumber;} ?>">
											
											<span class="errors" id="AlterMobileNumber-err"></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="Industry">Industry<span class="required">*</span></label>
											<input type="text" id="Industry" class="form-control FormWizVal" data-length="0" placeholder="Enter Insdustry"  value="<?php if($isEdit==true){ echo $EditData[0]->Industry;} ?>">
											
											<span class="errors" id="Industry-err"></span>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="LeadSource">Lead Source</label>
											<input type="text" id="LeadSource" class="form-control " data-length="0" placeholder="Enter LeadSource"  value="<?php if($isEdit==true){ echo $EditData[0]->LeadSource;} ?>">
											
											<span class="errors" id="LeadSource-err"></span>
										</div>
									</div>

									<div class="col-md-6">
									<div class="form-group">
										<label for="Email">Email <span class="required"></span></label>
										<input type="email" id="Email" class="form-control " placeholder="E-Mail"  value="<?php if($isEdit==true){ echo $EditData[0]->Email;} ?>">
										
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="GSTNo">GST No<span class="required"></span></label>
										<input type="GSTNo" id="GSTNo" class="form-control " placeholder="GSTNo"  value="<?php if($isEdit==true){ echo $EditData[0]->GSTNo;} ?>">
										
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="CreditDays">Credit Days<span class="required"></span></label>
									
										<?php
										$value="";
										if($isEdit==true){$value = ($EditData[0]->CreditDays);}else{
											$value = ($SETTINGS['CreditDays']);
											
										} 
										
										?>
										<input type="text" id="CreditDays" class="form-control " placeholder="CreditDays"  value="<?php echo $value ;?>">
                                  
									</div>
								</div>
								<div class="col-md-6">
									<label for="">Active Status</label>
									<select class="form-control FormWizVal" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
                                </select>
									</div>
</div>
                      </div>
                      <div class="tab">
					  <div class="card">
				<div class="card-header text-center"><h5 class="mt-10">Billing Address</h5>

			       </div>
				<div class="card-body " >
					<div class ="row mb-3">
		
					<div class="col-md-12 ">
					<div class="checkbox text-center mb-2 mt-2 checkbox-primary">
              <input id="checkbox-primary-1" class="filladdress" type="checkbox">
              <label for="checkbox-primary-1">Same As Address</label>
            </div>
						</div>
					</div>
                      <div class="row">

							
					  <div class="col-md-6">
					  <div class="form-group">
									
									<label for="Address">Address <span class="required">*</span></label>
									<textarea class="form-control FormWizVal" placeholder="Address" id="Address" name="Address" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->Address;} ?></textarea>
									<span class="errors" id="Address-err"></span>
								</div>
						
									<div class="form-group">
										<label for="Country">Billing Country <span class="required">*</span></label>
										<select class="form-control FormWizVal select2" id="Country">
											<option value="">Country</option>
										</select>
										<span class="errors" id="Country-err"></span>
									</div>
						
									<div class="form-group">
										<label for="State">Billing State <span class="required">*</span></label>
										<select class="form-control FormWizVal select2" id="State">
											<option value="">State</option>
										</select>
										<span class="errors" id="State-err"></span>
									</div>
							
									<div class="form-group">
										<label for="City">Billing City <span class="required">*</span></label>
										<select class="form-control FormWizVal select2" id="City">
											<option value="">City</option>
										</select>
										<span class="errors" id="City-err"></span>
									</div>
									
									<div class="form-group">
										<label for="PinCode">Billing Postal Code <span class="required">*</span></label>
										<select class="form-control FormWizVal select2Tag" id="PinCode">
											<option value="">Postal Code</option>
										</select>
										<span class="errors" id="PinCode-err"></span>
									</div>
							
								
									

								</div>
						<div class="col-md-6">

						<!-- <div class="form-group">
						<div class="checkbox text-center mb-2 mt-2 checkbox-primary">
						 <label for="Country">Same As Address<span class="required">*</span></label>
					
						<input id="checkbox-primary-1" class="filladdress" type="checkbox">
                         </div>
									
									</div> -->

									<div class="form-group">
			<label for="Address">Shpping Address<span class="required">*</span></label>
		     <textarea class="form-control FormWizVal" placeholder="ShipAddress" id="ShipAddress" name="ShipAddress" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->ShippingAddress;} ?></textarea>
						<span class="errors" id="ShipAddress-err"></span>
								</div>


						<div class="form-group">
						<label for="Country">Shpping Country <span class="required">*</span></label>
				<select class="form-control FormWizVal select2 ShipCountry" id="ShipCountry" >
				 <option value="">Shpping Country</option>
			     </select>
				 <span class="errors" id="ShipCountry-err"></span>
              </div>
		
			<div class="form-group">
			<label for="State">Shpping State <span class="required">*</span></label>
				<select class="form-control FormWizVal select2 ShipState" id="ShipState">
					<option value="">Shpping State</option>
				</select>
				<span class="errors" id="ShipState-err"></span>
			</div>
		
			<div class="form-group">
			<label for="City">Shpping City <span class="required">*</span></label>
				<select class="form-control FormWizVal select2 ShipCity" id="ShipCity">
					<option value="">Shpping City</option>
				</select>
				<span class="errors" id="ShipCity-err"></span>
			</div>
		
			<div class="form-group">
			<label for="PinCode">Shpping PostalCode <span class="required">*</span></label>
				<select class="form-control FormWizVal select2Tag ShipPinCode" id="ShipPinCode">
					<option value="">Shpping PostalCode</option>
				</select>
				<span class="errors" id="ShipPinCode-err"></span>
			</div>
		

			
						</div>
					  </div>

									</div>
									</div>
								
                      </div>
				
                      <!-- Circles which indicates the steps of the form:-->
        					  <div class="row mb-3">
									<div class="col-md-4"></div>
									<div class="col-md-4 text-center">
										<span class="step"></span>
										<span class="step"></span>
									</div>
									<div class="col-md-4"></div>
								</div>
								<div class="text-end pull-right ">
						@if($crud['view']==true)
                            <a href="{{url('/')}}/master/Customer/" class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                          <button class="btn btn-secondary" id="prevBtn" type="button" onclick="nextPrev(-1)">Previous</button>
	
                          <button class="btn btn-primary btn-save" id="btnSubmit" type="button" onclick="nextPrev(1)"></button>

                        </div>

                      <!-- Circles which indicates the steps of the form:-->
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->

		  <script>
			"use strict";
var currentTab = 0; 
showTab(currentTab);
function showTab(n) {
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) { 

    document.getElementsByClassName("btn-save")[0].innerHTML = "Submit";
  } else {
    document.getElementsByClassName("btn-save")[0].innerHTML = "Next";
	
  }
  fixStepIndicator(n)
}
function nextPrev(n) {
  var x = document.getElementsByClassName("tab");
  if (n == 1 && !validateForm()) return false;
  x[currentTab].style.display = "none";
  currentTab = currentTab + n;
  if (currentTab >= x.length) {
    // document.getElementById("regForm").submit();
    return false;
  }
  showTab(currentTab);
}
function validateForm() {
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByClassName("FormWizVal");
 
  for (i = 0; i < y.length; i++) {
    if (y[i].value == "") {
      y[i].className += " invalid";
      valid = false;
    }
  }
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid;
}
function fixStepIndicator(n) {
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  x[n].className += " active";
}
		  </script>
		  <script>
    $(document).ready(function(){
	
	
        const formValidation=()=>{

			$('.errors').html('');
            let status=true;

let FirstName = $('#FirstName').val();

let Country = $('#Country').val();
let State = $('#State').val();
let City = $('#City').val();
let Address = $('#Address').val();
let PinCode = $('#PinCode').val();
let MobileNumber = $('#MobileNumber').val();
let txtCImage = $('#txtCImage').val();
let imagePath = $('#txtCImage').attr('data-default-file');
let lstActiveStatus = $('#lstActiveStatus').val();
let Email = $('#Email').val();
let CreditDays = $('#CreditDays').val();
let Industry = $('#Industry').val();
let LeadSource =$('#LeadSource').val();
let AlterMobileNumber =$('#AlterMobileNumber').val();
let ShipCountry =$('#ShipCountry').val();
let ShipState =$('#ShipState').val();
let ShipCity =$('#ShipCity').val();
let ShipPinCode=$('#ShipPinCode').val();
let ShipAddress =$('#ShipAddress').val();
let ContactPerson =$('#ContactPerson').val();
let CategoryID = $('#CategoryID').val();
let SubCID = $('#SubCID').val();


if (Industry == "") {
		$('#Industry-err').html('Industry is required');status = false;
	} else if (Industry.length > 50) {
		$('#Industry-err').html('Industry may not be greater than 50 characters');status = false;
	}else if (Industry.length <3) {
		$('#Industry-err').html('Industry may not be leesthen than 3 characters');status = false;
	}

	if(ContactPerson ==""){
		$('#ContactPerson-err').html('ContactPerson is required');status = false;
	}

	if (CategoryID == "") {
		$('#CategoryID-err').html('please select CategoryID');status = false;
	}
	if (SubCID == "") {
		$('#SubCID-err').html('please select SubCID');status = false;
	}

	// if (LeadSource == "") {
	// 	$('#LeadSource-err').html('LeadSource is required');status = false;
	// } else if (LeadSource.length > 50) {
	// 	$('#LeadSource-err').html('LeadSource may not be greater than 50 characters');status = false;
	// }else if (LeadSource.length <3) {
	// 	$('#LeadSource-err').html('LeadSource may not be leesthen than 3 characters');status = false;
	// }
	if (FirstName == "") {
		$('#FirstName-err').html(' Name is required');status = false;
	} else if (FirstName.length > 50) {
		$('#FirstName-err').html(' Name may not be greater than 50 characters');status = false;
	}else if (FirstName.length <3) {
		$('#FirstName-err').html(' Name may not be leesthen than 3 characters');status = false;
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
	if (ShipCountry == "") {
		$('#ShipCountry-err').html('please select Shipping Address');status = false;
	}

	if (ShipState == "") {
		$('#ShipState-err').html('please select Shipping State');status = false;
	}

	if (ShipCity == "") {
		$('#ShipCity-err').html('please select Shipping City');status = false;
	}

	if (ShipPinCode == "") {
		$('#ShipPinCode-err').html('please select Shipping Code');status = false;
	}

	if (ShipAddress == "") {
		$('#ShipAddress-err').html('please select Shipping Address');status = false;
	}
	
	if (MobileNumber == "") {
		$('#MobileNumber-err').html('Mobile Number  is required');status = false;
	}	 else if (isNaN(MobileNumber)) {
              $('#MobileNumber-err').html("Use Only Numeric");status = false;
          } else if (MobileNumber.length != 10) {
              $('#MobileNumber-err').html("Please Enter 10 Digit MobileNumber");status = false;

          }

		  if(isNaN(AlterMobileNumber)) {
              $('#AlterMobileNumber-err').html("Use Only Numeric");status = false;
          }
		  else if (AlterMobileNumber.length != 10 && AlterMobileNumber !="") {
              $('#AlterMobileNumber-err').html("Please Enter 10 Digit MobileNumber");status = false;

          }

	if(typeof(txtCImage) != "undefined" && txtCImage !== null && txtCImage !== ''){
		let validation = fileValidation();
		if(validation !=''){
			$('#txtCImage-err').html(validation);status = false;
		}
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
		const appInit=async()=>{
		
			getCountry();
			ShipCountry();
			getCategoryID();
            GetGst(); 
			
		}


		const getCategoryID=async()=>{
			
			$('#CategoryID').select2('destroy');
			$('#CategoryID option').remove();
			$('#CategoryID').append('<option value="">Select a CategoryID</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/master/rawmeterial/CategoryID",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$CategoryID="";
							$CName="";
							if($isEdit==true){
								$CategoryID=$EditData[0]->CategoryID;
							}
						@endphp
						if(item.CID=="{{$CategoryID}}"){selected="selected";}
						else if(item.CName=="{{$CName}}"){selected="selected";}
						$('#CategoryID').append('<option '+selected+' value="'+item.CID+'">'+item.CName+'</option>');
					}
					if($('#CategoryID').val()!=""){
						GetSubCIDName();
					
					}
				}
			});
			
			$('#CategoryID').select2();

		}

		$('#CategoryID').change(function(){
			GetSubCIDName();
			
		})

		const GetGst=async()=>{
			
			$('#TaxID').select2('destroy');
			$('#TaxID option').remove();
			$('#TaxID').append('<option value="">Select a Tax</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/master/rawmeterial/TaxID",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$TaxID="";
							$TaxName="";
							if($isEdit==true){
								$TaxID=$EditData[0]->GSTNo;
							}
						@endphp
						if(item.TaxID=="{{$TaxID}}"){selected="selected";}
						else if(item.TaxName=="{{$TaxName}}"){selected="selected";}
						$('#TaxID').append('<option '+selected+' value="'+item.TaxID+'">'+item.TaxPercentage+'</option>');
					}
				}
			});
			$('#TaxID').select2();
		}
		const GetSubCIDName=async()=>{
			let CategoryID=$('#CategoryID').val();
			$('#SubCID').select2('destroy');
			$('#SubCID option').remove();
			$('#SubCID').append('<option value="">Select a SubCID</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/master/rawmeterial/SubCID",
				data:{CategoryID:CategoryID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$SCID="";
							$SubCName="";
							if($isEdit==true){
								$SCID=$EditData[0]->SCID;

							}
						@endphp

						if(item.SCID=="{{$SCID}}"){selected="selected";}
						else if(item.SCName=="{{$SubCName}}"){selected="selected";}
						$('#SubCID').append('<option  '+selected+' value="'+item.SCID+'">'+item.SCName+'</option>');
					}
				
				}
			});
			$('#SubCID').select2();
		}
		const getCountry=async()=>{
			
			$('#Country').select2('destroy');
			$('#Country option').remove();
			$('#Country').append('<option value="">Country</option>');
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
			$('#State').append('<option value="">State</option>');
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
			$('#City').append('<option value="">City</option>');
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
			$('#PinCode').append('<option value="">Postal Code</option>');
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
		var trigState="";

		var trigCity="";

		$(".filladdress").on("click", function(){

if(this.checked){
	let Country = $('#Country').val();
 trigState = $('#State').val();
  trigCity = $('#City').val();

  let PinCode = $('#PinCode').val();

  let Address = $('#Address').val();
  
$("#ShipCountry").val(Country).trigger("change");

$("#ShipPinCode").val(PinCode).trigger("change");

$("#ShipAddress").val(Address);



if($('#ShipCountry').val()!=""){

					GetShipStateName();
					 trigState = ($('#State').val());
					
				}


   }
	else
	{
			$("#ShipCountry").val('');
			$("#ShipState").val('');
			$("#ShipCity").val('');       
	}
});





		const ShipCountry=async()=>{
			
			$('#ShipCountry').select2('destroy');
			$('#ShipCountry option').remove();
			$('#ShipCountry').append('<option value="">Shipping Country</option>');
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
							$ShipCountryID="";
							$ShipCountryName="";
							if($isEdit==true){
								$ShipCountryID=$EditData[0]->ShippingCountry;
							}
						@endphp
						if(item.CountryID=="{{$ShipCountryID}}"){selected="selected";}
						else if(item.CountryName=="{{$ShipCountryName}}"){selected="selected";}
						$('#ShipCountry').append('<option '+selected+' data-phone-code="'+item.PhoneCode+'" data-phone-lenth="'+item.PhoneLength+'" value="'+item.CountryID+'">'+item.CountryName+'</option>');
					}

					if($('#ShipCountry').val()!=""){
						GetShipStateName();
					
					}
			
				}
			});
			$('#ShipCountry').select2();
		}


		
		const GetShipStateName=async()=>{
			let CountryID=$('#ShipCountry').val();
			// alert(CountryID+"GetShipStateName");
			// alert(trigState+"trigState");
			$('#ShipState').select2('destroy');
			$('#ShipState option').remove();
			$('#ShipState').append('<option value="">Shipping State</option>');
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
							$ShipStateID="";
							$ShipStateName="";
							if($isEdit==true){
								$ShipStateID=$EditData[0]->ShippingState;
							}else{
								// $ShipStateName=$Location['ShipState'];
							}
						@endphp
		
						if(item.StateID==trigState){selected="selected"}
						if(item.StateID=="{{$ShipStateID}}"){selected="selected";}
						else if(item.StateName=="{{$ShipStateName}}"){selected="selected";}
						$('#ShipState').append('<option  '+selected+' value="'+item.StateID+'">'+item.StateName+'</option>');
					}
					if($('#ShipState').val()!=""){
						GetShipCityName();
					
					}
				}
			});
			$('#ShipState').select2();
		}
		const GetShipCityName=async()=>{
			let CountryID=$('#ShipCountry').val();
			let StateID=$('#ShipState').val();
			$('#ShipCity').select2('destroy');
			$('#ShipCity option').remove();
			$('#ShipCity').append('<option value="">Shipping City</option>');
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
							$ShipCityID="";
							$ShipCity="";
							if($isEdit==true){
								$ShipCityID=$EditData[0]->ShippingCity;
							}
						@endphp
						
						if(item.CityID==trigCity){selected="selected";}
						if(item.CityID=="{{$ShipCityID}}"){selected="selected";}
						else if(item.CityName=="{{$ShipCity}}"){selected="selected";}
						$('#ShipCity').append('<option '+selected+'  value="'+item.CityID+'">'+item.CityName+'</option>');
					}
				}
			});
			
			$('#ShipCity').select2();
		}
		const GetShipPinCode=async()=>{
			let CountryID=$('#ShipCountry').val();
			let StateID=$('#ShipState').val();
			$('#ShipPinCode').select2('destroy');
			$('#ShipPinCode option').remove();
			$('#ShipPinCode').append('<option value="">Shipping PostalCode</option>');
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
						$('#ShipPinCode').append('<option '+selected+'  value="'+item.PID+'">'+item.PostalCode+'</option>');
					}
				}
			});
			$('#ShipPinCode').select2({tags:true});
		}
		GetShipPinCode();
		$('#ShipCountry').change(function(){
			GetShipStateName();
		})
		$('#ShipState').change(function(){
			GetShipCityName();
		})

		appInit();

		
		$('.userImage .dropify-clear').click(function(){
			$('#txtCImage').attr('data-default-file', '');
		})

	
	
        $('#btnSubmit').click(function(){

			var TextValue = $('#btnSubmit').text();
			
			let status=formValidation();

			if(TextValue=='Submit'){

          
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

				formData.append('Country', $('#Country').val());
				formData.append('State', $('#State').val());
				formData.append('City', $('#City').val());
				formData.append('Address', $('#Address').val());
				formData.append('PostalCodeID', $('#PinCode').val());
				// 
				formData.append('Industry', $('#Industry').val());
				formData.append('LeadSource', $('#LeadSource').val());
				formData.append('AlterMobileNumber', $('#AlterMobileNumber').val());
				formData.append('ShipCountry', $('#ShipCountry').val());
				formData.append('ShipState', $('#ShipState').val());
				formData.append('ShipCity', $('#ShipCity').val());
				formData.append('ShipAddress', $('#ShipAddress').val());
				formData.append('ShipPinCode', $('#ShipPinCode').val());
				formData.append('CreditDays', $('#CreditDays').val());
				// 
				formData.append('PostalCode', $('#PinCode option:selected').text());
				formData.append('MobileNumber', $('#MobileNumber').val());
				formData.append('ActiveStatus', $('#lstActiveStatus').val());
				formData.append('Email', $('#Email').val());
				formData.append('GSTNo', $('#GSTNo').val());
				// 
				formData.append('CategoryID', $('#CategoryID').val());
				formData.append('SubCID', $('#SubCID').val());
				formData.append('ContactPerson', $('#ContactPerson').val());
				
                    if($('#txtCImage').val()!=""){
                        formData.append('ProfileImage', $('#txtCImage')[0].files[0]);
                    }

					@if($isEdit == true)
					formData.append('UserID',"{{$EditData[0]->CID}}");
					var  submiturl = "{{ url('/') }}/master/customer/edit/{{$EditData[0]->CID}}";
           			 @else
						var  submiturl = "{{ url('/') }}/master/Customer/create";
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
                                        window.location.replace("{{url('/')}}/master/Customer");
                                    @else
                                        window.location.reload();
                                    @endif
                                    
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
										$.each(response['errors'], function(KeyName, KeyValue) {
                                        var key = KeyName;
										if (key == "MobileNumber") {
                                            $('#MobileNumber-err').html(KeyValue);
                                        }else if (key == "FirstName") {
                                            $('#FirstName-err').html(KeyValue);
                                        }else if (key == "State") {
                                            $('#State-err').html(KeyValue);
                                        }
										else if (key == "City") {
                                            $('#City-err').html(KeyValue);
                                        }else if (key == "Country") {
                                            $('#Country-err').html(KeyValue);
                                        }else if (key == "PostalCode") {
                                            $('#PinCode-err').html(KeyValue);
                                        }
                                        if(key=="CImage"){$('#txtCImage-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }

		// 
		}

        });



    });
</script>
@endsection

const GetGst=async()=>{
			
			$('#TaxID').select2('destroy');
			$('#TaxID option').remove();
			$('#TaxID').append('<option value="">Select a Tax</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/master/rawmeterial/TaxID",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$TaxID="";
							$TaxName="";
							if($isEdit==true){
								$TaxID=$EditData[0]->GSTNo;
							}
						@endphp
						if(item.TaxID=="{{$TaxID}}"){selected="selected";}
						else if(item.TaxName=="{{$TaxName}}"){selected="selected";}
						$('#TaxID').append('<option '+selected+' value="'+item.TaxID+'">'+item.TaxPercentage+'</option>');
					}
				}
			});
			$('#TaxID').select2();
		}

		if (HsnTax == "") {
		$('#HsnTax-err').html('Hsn/Tax is required');status = false;
	}	 else if (isNaN(HsnTax)) {
              $('#HsnTax-err').html("Use Only Numeric");status = false;
          }