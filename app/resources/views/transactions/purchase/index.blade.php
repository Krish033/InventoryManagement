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
                                    <h5>Purchase</h5>
                                </div>
                                <div class="col-md-4 my-2 text-right text-md-right">
                                    {{-- @if($crud['restore']==1) --}}
                                    <a href="{{ route('purchase.trash') }}" class="btn  btn-outline-light btn-sm m-r-10"
                                        type="button"> Trash view </a>
                                    {{-- @endif --}}
                                    {{-- @if($crud['add']==1) --}}
                                    <a href="{{ route('purchase.create') }}"
                                        class="btn  btn-outline-success btn-air-success btn-sm" type="button">Create</a>
                                    <!-- full-right -->
                                    {{-- @endif --}}
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
                                        <th class="text-center">Active</th>
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
        const url = "{{ route('purchase.home.api') }}";
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

		$(document).on('click','.deletePurchase', function () {
			// getting the attribute
            const id = $(this).attr('data-id');

			swal({
                title: "Are you sure?",
                text: "You want Delete this Purchase!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-danger",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{ url('/') }}/transactions/purchase/delete/" + id,
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
        fetchDataForTable();
    });
</script>

@endsection