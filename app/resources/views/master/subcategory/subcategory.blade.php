@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">SubCategory</li>
                    <li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row d-flex justify-content-center">
		<div class="col-sm-6">
			<div class="card">
				<div class="card-header text-center"><h5 class="mt-10">Sub Category</h5></div>
				<div class="card-body " >
                    <div class="row mb-20  d-flex justify-content-center">
                        <div class="col-sm-6">
                            <input type="file" class="dropify" id="txtCImage" data-default-file="<?php if($isEdit==true){if($EditData[0]->SCImage!=""){ echo url('/')."/".$EditData[0]->SCImage;}}?>"  data-allowed-file-extensions="jpeg jpg png gif" >
                        </div>
                    </div>
                    <div class="row">
                   
                    <div class="col-sm-12">
                            <div class="form-group">
                                <label class="txtSCName"> Sub Category Name <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txtSCName" value="<?php if($isEdit==true){ echo $EditData[0]->SCName;} ?>">
                                <div class="errors" id="txtSCName-err"></div>
                            </div>
                        </div>
                             
                    <div class="col-sm-12">
                            <div class="form-group">
                                <label class="lstCategory"> Category<span class="required"> * </span></label>
                                <select class="form-control select2" id="lstCategory" data-cid="<?php if($isEdit==true){ echo $EditData[0]->CID; }?>">
  
                                </select>
                                <div class="errors" id="lstCategory-err"></div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="txtSubCategory"> Active Status</label>
                                <select class="form-control" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
                                </select>
                                <div class="errors" id="txtSubCategory-err"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/SubCategory/" class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                <button class="btn btn-sm btn-outline-success" id="btnSave">@if($isEdit==true) Update @else Save @endif</button>
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
            let SCName=$('#txtSCName').val();
            let CID =$('#lstCategory').val();
            if(SCName==""){
                $('#txtSCName-err').html('The Sub Category name is required.');status=false;
            }else if(SCName.length<2){
                $('#txtSCName-err').html('Sub Category Name must be greater than 2 characters');status=false;
            }else if(SCName.length>100){
                $('#txtSCName-err').html('Sub Category Name may not be greater than 100 characters');status=false;
            }
            if(CID==''){
                $('#lstCategory-err').html('The Category  is required.');status=false; 
            }
            return status;
        }

      
		const lstCategory=async()=>{
            let editCID=$('#lstCategory').attr('data-cid');
			$('#lstCategory').select2('destroy');
            $('#lstCategory option').remove();
			$('#lstCategory').append('<option value="">Select a Category</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/SubCategory/getCategory",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
						if(item.CID==editCID){selected="selected";}
						$('#lstCategory').append('<option '+selected+'  value="'+item.CID+'">'+item.CName+'</option>');
					}
				}
			});
			$('#lstCategory').select2();
        }
        lstCategory();
        $('#btnSave').click(function(){
            let status=formValidation();
            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this SubCategory!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-success",
                    confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{url('/')}}/master/SubCategory/create";
                    let formData=new FormData();
                    formData.append('SCName',$('#txtSCName').val());
                    formData.append('CID',$('#lstCategory').val());
                    formData.append('ActiveStatus',$('#lstActiveStatus').val());
                    if($('#txtCImage').val()!=""){
                        formData.append('SCImage', $('#txtCImage')[0].files[0]);
                    }
                    @if($isEdit==true)
                        formData.append('SCID',"{{$EditData[0]->SCID}}");
                        postUrl="{{url('/')}}/master/SubCategory/edit/{{$EditData[0]->SCID}}";
                    @endif
                    $.ajax({
                        type:"post",
                        url:postUrl,
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
                        complete: function(e, x, settings, exception)
                        {btnReset($('#btnSave'));ajaxindicatorstop();},
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
                                        window.location.replace("{{url('/')}}/master/SubCategory");
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
                                    $.each( response['errors'], function( KeyName, KeyValue ) {
                                        var key=KeyName;
                                        if(key=="SCName"){$('#txtSCName-err').html(KeyValue);}
                                        if(key=="CID"){$('#lstCategory-err').html(KeyValue);}
                                        if(key=="SCImage"){$('#txtCImage-err').html(KeyValue);}
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