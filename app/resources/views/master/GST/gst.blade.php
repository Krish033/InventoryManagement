@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i
                                class="f-16 fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Tax</li>
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
                <div class="card-header text-center">
                    <h5 class="mt-10">Tax</h5>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="txtGST"> Tax Name <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txtGST"
                                    value="<?php if($isEdit==true){ echo $EditData[0]->TaxName;} ?>">
                                <div class="errors" id="txtGST-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="Percentage"> Percentage <span class="required"> * </span></label>
                                <input type="number" step="0.01" class="form-control" id="Percentage"
                                    value="<?php if($isEdit==true){ echo $EditData[0]->TaxPercentage;} ?>">
                                <div class="errors" id="Percentage-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="txtCategory"> Active Status</label>
                                <select class="form-control" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected
                                        @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected
                                        @endif @endif>Inactive</option>
                                </select>
                                <div class="errors" id="txtCategory-err"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/tax" class="btn btn-sm btn-outline-dark"
                                id="btnCancel">Back</a>
                            @endif

                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                            <button class="btn btn-sm btn-outline-success" id="btnSave">@if($isEdit==true) Update @else
                                Save @endif</button>
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
            let GST=$('#txtGST').val();
            let Percentage=$('#Percentage').val();
            if(GST==""){
                $('#txtGST-err').html('The category name is required.');status=false;
            }
            else if(GST.length<2){
                $('#Percentage-err').html('GST  must be greater than 2 characters');status=false;
            }else if(GST.length>100){
                $('#txtGST-err').html('Category Name may not be greater than 100 characters');status=false;
            }
            
            if(Percentage==""){
                $('#Percentage-err').html('The Percentage  is required.');status=false;
            }else if(Percentage.length>100){
                $('#Percentage-err').html('Percentage  may not be greater than 100 characters');status=false;
            }
            
            return status;
        }
        $('#btnSave').click(function(){
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
                    btnLoading($('#btnSave'));
                    let postUrl="{{url('/')}}/master/gst/create";
                    let formData=new FormData();
                    formData.append('GstName',$('#txtGST').val());
                    formData.append('Percentage',$('#Percentage').val());
                    formData.append('ActiveStatus',$('#lstActiveStatus').val());

                    @if($isEdit==true)
                        formData.append('TaxID',"{{$EditData[0]->TaxID}}");
                        postUrl="{{url('/')}}/master/gst/edit/{{$EditData[0]->TaxID}}";
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
                        complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
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
                                        window.location.replace("{{url('/')}}/master/gst");
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
                                        if(key=="GstName"){$('#txtGST-err').html(KeyValue);}
                          
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