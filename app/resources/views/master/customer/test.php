@extends('layouts.layout')
@section('content')
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
<div class="container-fluid">
		<div class="row justify-content-center">
				<div class="col-sm-10">
					<div class="card">
						<div class="card-header text-center">
                            <h5>Customer</h5>
						</div>

						<div class="card-body p-60">
								<div class="row">
									<div class="col-md-4"></div>
									<div class="col-md-4 text-center userImage">
										<input type="file" id="txtCImage" class="dropify" data-default-file="<?php if($isEdit==true){if($EditData[0]->CImage !=""){ echo url('/')."/".$EditData[0]->CImage;}}?>"  data-allowed-file-extensions="jpeg jpg png gif" />
										<span class="errors" id="txtCImage-err"></span>
									</div>
									<div class="col-md-4"></div>
								</div>
							<div class="row mt-20">
								<div class="col-md-6">
									<div class="form-group">
										<label for="FirstName">Customer Name <span class="required">*</span></label>
									
								<input type="text" id="FirstName" class="form-control" placeholder="Customer Name" 	value="<?php if($isEdit==true){ echo $EditData[0]->CName;} ?>">
										<span class="errors" id="FirstName-err"></span>
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
									
										<label for="Address">Address <span class="required">*</span></label>
										<textarea class="form-control" placeholder="Address" id="Address" name="Address" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->Address;} ?></textarea>
										<span class="errors" id="Address-err"></span>
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
									<label for="">Active Status</label>
									<select class="form-control" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
                                </select>
									</div>
	
									<div class="col-md-6">
										<div class="form-group">
											<label for="MobileNumber"> MobileNumber <span id="CallCode"></span> <span class="required">*</span></label>
											<input type="number" id="MobileNumber" class="form-control" data-length="0" placeholder="Mobile Number enter without country code"  value="<?php if($isEdit==true){ echo $EditData[0]->MobileNumber;} ?>">
											
											<span class="errors" id="MobileNumber-err"></span>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="MobileNumber">Alternate MobileNumber <span class="required">*</span></label>
											<input type="number" id="AlterMobileNumber" class="form-control" data-length="0" placeholder="Alter Mobile Number enter without country code"  value="<?php if($isEdit==true){ echo $EditData[0]->AlterMobileNumber;} ?>">
											
											<span class="errors" id="AlterMobileNumber-err"></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="Industry">Industry<span class="required">*</span></label>
											<input type="text" id="Industry" class="form-control" data-length="0" placeholder="Enter Insdustry"  value="<?php if($isEdit==true){ echo $EditData[0]->Industry;} ?>">
											
											<span class="errors" id="Industry-err"></span>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="LeadSource">Lead Source<span class="required">*</span></label>
											<input type="text" id="LeadSource" class="form-control" data-length="0" placeholder="Enter LeadSource"  value="<?php if($isEdit==true){ echo $EditData[0]->LeadSource;} ?>">
											
											<span class="errors" id="LeadSource-err"></span>
										</div>
									</div>

									<div class="col-md-6">
									<div class="form-group">
										<label for="Email">Email <span class="required"></span></label>
										<input type="email" id="Email" class="form-control" placeholder="E-Mail"  value="<?php if($isEdit==true){ echo $EditData[0]->Email;} ?>">
										
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="GSTNo">GST No<span class="required"></span></label>
										<input type="GSTNo" id="GSTNo" class="form-control" placeholder="GSTNo"  value="<?php if($isEdit==true){ echo $EditData[0]->GSTNo;} ?>">
										
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="CreditDays">Credit Days<span class="required"></span></label>
										<select class="form-control" id="CreditDays">
										@for($i=1;$i<=30;$i++)
										<option @if($isEdit==true) @if($SETTINGS['CreditDays']==$i) selected @endif @endif value="{{$i}}">{{$i}}</option>
										@endfor
                                        </select>
									</div>
								</div>
								</div>

								<table id="orderItem" class="table table-bordered table-striped ">
			<thead>
				<tr>	
				<!-- <th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>								 -->
					<th>Shipping Country</th>	
					<th>Shipping State</th>	
					<th>Shipping City</th>	
					<th>Shipping PostalCode</th>		
					<th>Shipping Address</th>						
				</tr>
			</thead>
			<tbody>
			<tr>
			<!-- <td><input class="itemRow" type="checkbox"></td>																 -->
			<td>
			<div class="form-group">
				<select class="form-control select2 ShipCountry" id="ShipCountry" >
				 <option value="">Select a Country</option>
			     </select>
				 <span class="errors" id="ShipCountry-err"></span>
              </div>
			</td>				
			<td>
			<div class="form-group">
				<select class="form-control select2 ShipState" id="ShipState">
					<option value="">Select a State</option>
				</select>
				<span class="errors" id="ShipState-err"></span>
			</div>
			</td>
			<td>
			<div class="form-group">
				<select class="form-control select2 ShipCity" id="ShipCity">
					<option value="">Select a City</option>
				</select>
				<span class="errors" id="ShipCity-err"></span>
			</div>
			</td>
			<td>
			<div class="form-group">
				<select class="form-control select2Tag ShipPinCode" id="ShipPinCode">
					<option value="">Select a Postal Code</option>
				</select>
				<span class="errors" id="ShipPinCode-err"></span>
			</div>
			</td>
			<td>

			<div class="form-group">
		     <textarea class="form-control" placeholder="ShipAddress" id="ShipAddress" name="ShipAddress" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->ShippingAddress;} ?></textarea>
						<span class="errors" id="ShipAddress-err"></span>
								</div>
			</td>
           </tr>
</tbody>		
		</table>
		<!-- <div class="row">								
		&nbsp;<button class="btn btn-danger delete" id="removeRows" type="button"> Delete</button>
		<button class="btn btn-success" id="addRows" type="button"> Add More</button>								
		</div>		                                     -->
							</div>
							
		
							<div class="card-footer">
							<input type="hidden" id="IsEditval" class="form-control"   value="{{$isEdit}}">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/Customer/" class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                <button class="btn btn-sm btn-outline-success" id="btnSubmit">@if($isEdit==true) Update @else Save @endif</button>
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
	
	
        const formValidation=()=>{

			$('.errors').html('');
            let status=true;

let FirstName = $('#FirstName').val();
let Gender = $('#Gender').val();
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

if (Industry == "") {
		$('#Industry-err').html('Industry is required');status = false;
	} else if (Industry.length > 50) {
		$('#Industry-err').html('Industry may not be greater than 50 characters');status = false;
	}else if (Industry.length <3) {
		$('#Industry-err').html('Industry may not be leesthen than 3 characters');status = false;
	}

	if (LeadSource == "") {
		$('#LeadSource-err').html('Industry is required');status = false;
	} else if (LeadSource.length > 50) {
		$('#LeadSource-err').html('Industry may not be greater than 50 characters');status = false;
	}else if (LeadSource.length <3) {
		$('#LeadSource-err').html('Industry may not be leesthen than 3 characters');status = false;
	}
	if (FirstName == "") {
		$('#FirstName-err').html('First Name is required');status = false;
	} else if (FirstName.length > 50) {
		$('#FirstName-err').html('First Name may not be greater than 50 characters');status = false;
	}else if (FirstName.length <3) {
		$('#FirstName-err').html('First Name may not be leesthen than 3 characters');status = false;
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
			getGender();
			ShipCountry();
			
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

		const ShipCountry=async()=>{
			
			$('#ShipCountry').select2('destroy');
			$('#ShipCountry option').remove();
			$('#ShipCountry').append('<option value="">Select a ShipCountry</option>');
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
			$('#ShipState').select2('destroy');
			$('#ShipState option').remove();
			$('#ShipState').append('<option value="">Select a ShipState</option>');
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
			$('#ShipCity').append('<option value="">Select a ShipCity</option>');
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
			$('#ShipPinCode').append('<option value="">Select a Postal Code</option>');
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
				formData.append('Gender', $('#Gender').val());
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
                                        }else if (key == "Gender") {
                                            $('#Gender-err').html(KeyValue);
                                        }else if (key == "City") {
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
        });



    });
</script>


@endsection