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
                    <li class="breadcrumb-item">customer</li>
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
                                    <h5>customer</h5>
                                </div>
                                <div class="col-md-4 my-2 text-right text-md-right">
                                    <a href="{{ route('purchase.home') }}" class="btn btn-outline-dark btn-sm m-r-10"
                                        type="button">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <table class="table" id="tblpurchase">
                                <thead>
                                    <tr>
                                        <th class="text-center">Id</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Active status</th>
                                        <th class="text-center">Created by</th>
                                        <th class="text-center">Actions</th>
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
{{-- @endsection --}}

{{-- @section('scripts') --}}
<script>
    $(document).ready(() => {
        
        // purchase Table url
        const url = "{{ route('purchase.trash.api') }}";
        // console.log();

        const fetchDataForTable= async () => {
			$('#tblpurchase').dataTable({

				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": url + "?_token="+$('meta[name=_token]').attr('content'),
                "headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "GET"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
                columnDefs: [
                    {"className": "dt-center", "targets":2},
                    {"className": "dt-center", "targets":3}
                ],
				buttons: [
					'pageLength' 
					@if($crud['excel']) ,{extend: 'excel'} @endif 
					@if($crud['copy']) ,{extend: 'copy'} @endif
					@if($crud['csv']) ,{extend: 'csv'} @endif
					@if($crud['print']) ,{extend: 'print'} @endif
					@if($crud['pdf']) ,{extend: 'pdf'} @endif
				],
			});
        }

        fetchDataForTable();
        $(document).on('click','.restorePurchase', function () {
			// getting the attribute
            const id = $(this).attr('data-id');

			swal({
                title: "Are you sure?",
                text: "You want Restore this customer!",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-success",
                confirmButtonText: "Restore",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{ url('/') }}/transactions/purchase/restore/" + id,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                        $('#tblpurchase').DataTable().ajax.reload();
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblcustomer').DataTable().ajax.reload();
                    		toastr.success(response.message, "Success")
                    	}else{
                    		toastr.error(response.message, "Failed")
                    	}
                    }
            	});
            });
		});
		
    });
</script>

@endsection