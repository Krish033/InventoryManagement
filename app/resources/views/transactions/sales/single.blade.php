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
                    <li class="breadcrumb-item">Sales</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    {{-- {{ dd($supplier) }} --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="row p-2">
                <div class="col-md-5 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex w-100 justify-content-center" style="flex-direction: column">
                                <h1 class="h5 text-dark">
                                    {{$sales->date}}
                                </h1>
                                <div class="d-flex w-100 justify-content-start align-items-center gap-2 mb-0">
                                    <p class="mb-0">{{$sales->start}}</p>-
                                    <p class="text-dark">{{$sales->end}}</p>
                                </div>
                                <div class="d-flex mt-0 pt-0 w-100 justify-content-start align-items-center gap-2 mb-3"
                                    style="align-self: flex-start">
                                    <span class="small fs-bold text-dark">Tax id</span>
                                    <p class="text-dark">{{$sales->tax_id}}</p>
                                </div>
                                <div class="d-flex w-100 justify-content-start gap-2">
                                    <a href="{{ route('sales.home') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                    <a class="btn btn-outline-dark btn-sm"
                                        href="{{ route('sales.update', $sales->saId) }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-outline-success btn-sm"
                                        href="{{ route('sales.single.create', $sales->saId) }}">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>

                                <div class="w-100 mt-3">
                                    <span class="ms-0">Tax record for the Sale</span>
                                    <form id="updateTaxRecord" class="row gap-1 mt-2 mx-1">
                                        <select class="form-control col-9" name="tax_id" id="tax_id">
                                            <option>Select a tax</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary col-2 fs-xs">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title mb-0 pb-0">Sales record</h1>
                            <div class="payment">
                                @if($sales->auto_update_payment)
                                <span class="small">The <span class="text-dark fw-bold">Payment records</span> are
                                    automatically updated for this
                                    Sale</span>
                                @else
                                <span class="small">The <span class="text-dark fw-bold">Payment records</span> are
                                    not maintained automatically</span>
                                @endif
                                <div class="updateRecords ms-3 mt-3 mb-0 pb-0">
                                    <div class="form-check form-switch mb-0 pb-0">
                                        <input class="form-check-input" @if($sales->auto_update_payment) checked
                                        @endif type="checkbox"
                                        id="flexSwitchCheckDefault">
                                        <label class="form-check-label small" for="flexSwitchCheckDefault">Update
                                            Payment records automatically</label>
                                    </div>
                                    <hr />
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between slign-items-center mb-2"
                                        style="border-bottom: 1px solid #0000001b; flex-wrap: wrap">
                                        <span class="small">Number of items</span>
                                        <h5 class="h4" id="salesCount"></h5>
                                    </div>
                                    <div class="d-flex justify-content-between slign-items-center"
                                        style="flex-wrap: wrap">
                                        <span class="small">GST</span>
                                        <h5 id="gst" class="bg-info text-light rounded p-2 small"></h5>
                                    </div>
                                    <div class="d-flex justify-content-between slign-items-center"
                                        style="flex-wrap: wrap">
                                        <span class="small">Total Amount</span>
                                        <div class="d-flex gap-2 align-items-baseline">
                                            <h5 id="totalAmount" class="h5"></h5>
                                            <i class="fa fa-rupee-sign small"></i>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between slign-items-center"
                                        style="flex-wrap: wrap">
                                        <span class="small">Total Amount Including GST</span>
                                        <div class="d-flex gap-2 align-items-baseline">
                                            <h5 id="totalWithGST" class="h5"></h5>
                                            <i class="fa fa-rupee-sign small"></i>
                                        </div>
                                    </div>
                                </div>

                                @if($sales->auto_update_payment)
                                <div class="mt-2 pt-0 d-flex justify-content-between align-items-center gap-2 p-0"
                                    style="flex-wrap: wrap">
                                    <span class="small">
                                        <i class="fa fa-circle-exclamation"></i>
                                        The Payment records are not updated!</span>
                                    <button id="updatePaymentRecords" class="small btn btn-sm btn-dark">Update
                                        now</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="lead mb-3">Active sales list</h5>

                <table class="table" id="salesItems">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Sale Id</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#flexSwitchCheckDefault').click((e) => {

        const saId = "{{ $sales->saId }}";
        const url = "{{ url('/') }}/" + `transactions/sales/auto-update-payments/${saId}`;
        const checked = e.target.checked;

        const ajaxConfiguration = {
            method: "POST",
            type: "POST",
            url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data: { auto_update_payment: checked }
        }

        $.ajax({
            ...ajaxConfiguration,
            success: (response) => {
                toastr.success(response?.message);
                window.location.reload();
                return;
            },
            error: (error) => {
                toastr.error(error?.responseJSON?.message);
                return;
            }
        });
    });



</script>

<script>
    $('#updateTaxRecord').submit((e) => {

        e.preventDefault();
        const saId = "{{ $sales->saId }}";
        const url = "{{ url('/') }}/" + `transactions/sales/assign-tax/${saId}`;

        const ajaxConfiguration = {
            method: "POST",
            type: "POST",
            url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data: { tax_id: $('#tax_id').val() }
        }

        const swalConfiguration = {
            title: "Are you sure?",
            text: "Do you want update tax record for this sales?",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-info",
            confirmButtonText: "Yes, Delete it!",
            closeOnConfirm: true
        }

        swal(swalConfiguration, function () {
            $.ajax({
                ...ajaxConfiguration,
                success: (response) => {
                    toastr.success(response?.message);
                    window.location.reload();
                    return;
                },
                error: (error) => {
                    toastr.error(error?.responseJSON?.message);
                    return;
                }
            });
        });
    });
</script>
{{-- @section('scripts') --}}
<script>
    $(document).ready(() => {

        const saId = "{{ $sales->saId }}";
        const taxRecordUrl = "{{ url('/') }}/" + `transactions/api/sales/tax-records`;
        // TODO -> get tax records
        const ajaxConfiguration = {
            method: "GET",
            type: "GET",
            url: taxRecordUrl,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
        }

        $.ajax({
            ...ajaxConfiguration,
            success: (response) => {
                const taxes = document.querySelector('#tax_id');

                response?.map(tax => {
                    const option = document.createElement('option');
                    option.textContent = tax?.TaxName;
                    option.value = tax?.TaxID

                    // checked tax
                    const tax_id = "{{ $sales->tax_id }}";
                    option.selected = tax?.TaxID === tax_id
                    taxes.appendChild(option);
                });
                return;
            },
            error: (error) => {
                toastr.error('Unable to fetch tax records');
                return;
            }
        });

        // /transactions/api/salesd-item/stats/{saId} GET
        const getStatsData = (saId) => {
            const appUrl =  "{{ url('/') }}" + `/transactions/api/sales/stats/${saId}`;
            let data = {};
            // configuration
            const ajaxConfig = {
                method: "GET",
                type: "GET",
                url: appUrl,
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            }

            //ajax
            $.ajax({
                ...ajaxConfig, // configuration
                success: (response) => {
                    $('#itemCount').html(response?.salesCount);
                    $('#totalAmount').html(response?.calculatedPriceTotal);
                    $('#totalWithGST').html(response?.calculatedPriceTotalIncludingGST);
                    $('#gst').html(response?.tax + '%');
                }, // error
                error: (error) => {
                    toastr.error(error?.responseJSON?.message);
                    return false;
                }
            });
            return data;
        }
    
        getStatsData(saId);
        
        const fetchDataForTable= async () => {

			$('#salesItems').DataTable({

				"bProcessing": true,
				"bServerSide": true,
        // sales Table url
                "ajax": {"url": "{{ route('sales.single.api', $sales->saId) }}" + "?_token="+$('meta[name=_token]').attr('content'),
                "headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
                columnDefs: [
                    {"className": "dt-center", "targets":2},
                    {"className": "dt-center", "targets":3}
                ],
				buttons: [
					'pageLength', {extend: 'excel'}, {extend: 'copy'} 
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
                    url:"{{ url('/') }}/transactions/sales/delete/" + id,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                            $('#salesItems').DataTable().ajax.reload();
                    		toastr.success(response.message, "Success")
                    	}else{
                    		toastr.error(response.message, "Failed")
                    	}
                    }
            	});
            });
		});
        fetchDataForTable();
    

    // get Suppliers 
        // calling ajax 
        

    const confrimSendCreateRequest = (validated) => {
        

        $.ajax({
            url: "{{ url('/') }}/transactions/api/sales/single/create/" + "{{ $sales->saId }}",
            type: 'POST',
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data: {
                ...validated,
                is_active: true
            },
            
            error: error => {
                console.log(error);
                toastr.error(error?.responseJSON?.message);
                return;
            },

            success: response => {
                $("#salesItems").DataTable().ajax.reload();
                toastr.success(response?.message, "Success");
                getStatsData(saId);
            }
        });
    }
    // create salesd item
    $('#salesItemCreateForm').submit(e => {
        e.preventDefault();

        // Objects
        const validated = {
            name: $('#name').val(),
            description: $('#desc').val(),
            amount: $('#amount').val(),
            supplier_id: $('#supplier_id').val(),
        }
        
        //  Validation
        if(!validate(validated)){
            return false;
        }

        const swalConfigurationForCreateForm = {
            title: "Are you sure?",
            text: "Create a new Sales item!",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-success",
            confirmButtonText: "Confrim",
            closeOnConfirm: true
        }

        swal(swalConfigurationForCreateForm, () => confrimSendCreateRequest(validated));
    })

    $(document).on('click','.deleteSalesItem', function (e) {
        swal({
            title: "Are you sure?",
            text: "Delete the Sales item!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-success",
            confirmButtonText: "Yes! Delete item",
            closeOnConfirm: true

            }, function () {

            $.ajax({
                url: "{{ url('/') }}/transactions/sales/single/destroy/" + e.target.dataset.id,
                type: 'POST',
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                // data: validated,
                
                error: error => {
                    toastr.error(error?.message);
                    return;
                },
                
                success: response => {
                    $("#salesItems").DataTable().ajax.reload();
                    getStatsData(saId);
                    toastr.success(response?.message, "Success");
                }
            });
        });
    });
});
</script>

<script>
    // updatePaymentRecords
    $('#updatePaymentRecords').click((e) => {
        // getting sales auto update feature
        const autoUpdateAvailable = Boolean("{{ $sales->auto_update_payment }}");
        // does not has auto update
        if(!autoUpdateAvailable){
            return false;
        }

        const url = "{{ url('/') }}/transactions/api/sales/payment-record/{{ $sales->saId }}" ;

        const ajaxConfigurationForAutoUpdateUpdates = {
            method: "POST",
            type: "POST",
            url: url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
        }

        const sweetAlertConfiguration = {
            title: "Are you sure?",
            text: "Add payment records for this sales!",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-success",
            confirmButtonText: "Confrim",
            closeOnConfirm: true
        }

        swal(
            sweetAlertConfiguration,
            function () {
                // ajax
                $.ajax({
                    ...ajaxConfigurationForAutoUpdateUpdates,

                    error: () => {
                        toastr.error("Something went wrong!");
                        return false;
                    },
                    
                    success: (response) => {
                        toastr.success(response?.message);
                        // window.location.reload();
                        return true;
                    }
                })
            }
        )
    });

</script>
@endsection