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
                    <li class="breadcrumb-item"><a href="{{ url('/') }}/master/customer/" data-original-title=""
                            title="">customer</a></li>
                    <li class="breadcrumb-item">Trash View</li>
                </ol>
            </div>
        </div>
    </div>
</div>
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
                                    <h5>Product Trash View</h5>
                                </div>
                                <div class="col-md-4 my-2 text-right text-md-right">
                                    <a href="{{ url('/') }}/master/products"
                                        class="btn  btn-outline-light btn-sm m-r-10" type="button">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <table class="table" id="tblproducts">
                                <thead>
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Subcategory</th>
                                        <th class="text-center">Tax</th>
                                        <th class="text-center">Max</th>
                                        <th class="text-center">Min</th>
                                        <th class="text-center">Purchase rate</th>
                                        <th class="text-center">Sales Rate</th>
                                        <th class="text-center">Active</th>
                                        <th class="text-center">Action</th>
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
    $(document).ready(function(){
        let RootUrl="{{ route('product.trash.api') }}";
        const LoadTable=async()=>{
			@if($crud['view']==1)
			$('#tblproducts').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": RootUrl+"?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength' 
					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'User Roles',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif 
					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'User Roles',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'User Roles',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'User Roles',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'User Roles',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
				],
				columnDefs: [
					{"className": "dt-center", "targets":2},
					{"className": "dt-center", "targets":3}
				]
			});
			@endif
        }
		
		$(document).on('click','.btnRestore',function(){
			let ID=$(this).attr('data-id');
			swal({
                title: "Are you sure?",
                text: "You want Restore this Item!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-success",
                confirmButtonText: "Yes, Restore it!",
                closeOnConfirm: false
            },
            function(){
                swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{url('/')}}/master/products/restore/"+ID,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);swal.close();},
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblproducts').DataTable().ajax.reload();
                    		toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}else{
                    		toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}
                    }
            	});
            });
		});
        LoadTable();
    });
</script>
@endsection