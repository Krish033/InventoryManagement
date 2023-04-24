@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i
                                class="f-16 fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Transactions</li>
                    <li class="breadcrumb-item">Payments</li>
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
                                    <h5>Payments</h5>
                                </div>
                                <div class="col-md-4 my-2 text-right text-md-right">
                                    {{-- @if($crud['restore']==1) --}}
                                    {{-- <a href="{{ route('payment.trash') }}"
                                        class="btn  btn-outline-light btn-sm m-r-10" type="button"> Trash view </a> --}}
                                    {{-- @endif --}}
                                    {{-- @if($crud['add']==1) --}}
                                    <a href="{{ route('payment.create') }}"
                                        class="btn  btn-outline-success btn-air-success btn-sm" type="button">Create</a>
                                    <!-- full-right -->
                                    {{-- @endif --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <table class="table" id="tblpayments">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pyid</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center">Completed</th>
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
        
        // payment Table url
        const url = "{{ route('payment.home.api') }}";
        // console.log();

        const fetchDataForTable= async () => {

			$('#tblpayments').dataTable({

				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": url + "?_token="+$('meta[name=_token]').attr('content'),
                "headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',

                columnDefs: [
                    {"className": "dt-center", "targets":2},
                    {"className": "dt-center", "targets":3}
                ],
				buttons: [
					'pageLength',
					{extend: 'excel'}, 
					{extend: 'copy'},
					{extend: 'csv'},
					{extend: 'print'},
					{extend: 'pdf'},
				],
			});
        }

		$(document).on('click','.deletePayment', function () {
			// getting the attribute
            const id = $(this).attr('data-id');

			swal({
                title: "Are you sure?",
                text: "You want Delete this payment!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-danger",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{ url('/') }}/transactions/payment/delete/" + id,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",

                    success: function(response) {
                        $('#tblpayments').DataTable().ajax.reload();
                        toastr.success(response.message, "Success")
                    },

                    error: function(response) {
                        $('#tblpayments').DataTable().ajax.reload();
                        toastr.success("Something went wrong!")
                    },
            	});
            });
		});
        fetchDataForTable();
    });
</script>

@endsection