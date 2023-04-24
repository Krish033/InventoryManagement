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

    {{-- {{ dd($supplier) }} --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="row p-2">
                <div class="col-md-5 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex w-100 justify-content-center" style="flex-direction: column">
                                <h1 class="h5 text-dark">{{$purchase->name}}</h1>
                                <div class="d-flex w-100 justify-content-start align-items-center gap-2 mb-0">
                                    <p class="mb-0">{{$purchase->puid}}</p>
                                    <p class="text-dark">{{$purchase->date}}</p>
                                </div>
                                <div class="d-flex mt-0 pt-0 w-100 justify-content-start align-items-center gap-2 mb-3"
                                    style="align-self: flex-start">
                                    <span class="small fs-bold text-dark">Tax id</span>
                                    <p class="text-dark">{{$purchase->tax_id}}</p>
                                </div>
                                <div class="d-flex w-100 justify-content-start gap-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-info btn-sm">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                    <a class="btn btn-outline-dark btn-sm"
                                        href="{{ route('purchase.update', $purchase->puid) }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createPurchaseItemModal">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>

                                <div class="w-100 mt-3">
                                    <span class="ms-0">Tax record for the purchase</span>
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

                    <div class="modal rounded fade" id="createPurchaseItemModal">
                        <div class="modal-dialog">
                            <form class="modal-content" id="purchaseItemCreateForm">
                                <div class="modal-header">
                                    <h1 class="h5">Create Purchase item</h1>
                                    <a data-bs-dismiss="modal" role="button" class="btn btn-sm btn-outline-dark"><i
                                            class="fa fa-close"></i></a>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Item name</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Creatively awesome item" />
                                        <span class="small mt-1 text-danger" data-validation data-error="name"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="desc">Item description</label>
                                        <textarea id="desc" style="resize: none; height: 15vh;" class="form-control"
                                            placeholder="Not so good description"></textarea>
                                        <span class="small mt-1 text-danger" data-validation
                                            data-error="description"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="supplier_id">Supplier</label>
                                        <select id="supplier_id" name="supplier_id" class="form-control">
                                            <option value="--">Select</option>
                                        </select>
                                        <span class="small mt-1 text-danger" data-validation
                                            data-error="supplier_id"></span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="is_active">Amount</label>
                                        <input type="number" class="form-control" placeholder="100.00" id="amount">
                                        <span class="small mt-1 text-danger" data-validation data-error="amount"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a data-bs-dismiss="modal" class="btn btn-outline-dark btn-sm">
                                        Close
                                    </a>
                                    <button data-bs-dismiss="modal" class="btn btn-outline-success btn-sm">
                                        <i class="fa fa-plus"></i>
                                        Add Purchase item
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title mb-0 pb-0">Purchase record</h1>
                            <div class="payment">
                                @if($purchase->auto_update_payment)
                                <span class="small">The <span class="text-dark fw-bold">Payment records</span> are
                                    automatically updated for this
                                    purchase</span>
                                @else
                                <span class="small">The <span class="text-dark fw-bold">Payment records</span> are
                                    not maintained automatically</span>
                                @endif
                                <div class="updateRecords ms-3 mt-3 mb-0 pb-0">
                                    <div class="form-check form-switch mb-0 pb-0">
                                        <input class="form-check-input" @if($purchase->auto_update_payment) checked
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
                                        <h5 class="h4" id="itemCount"></h5>
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

                                @if($purchase->auto_update_payment)
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
                <h5 class="lead mb-3">Active purchase list</h5>

                <table class="table" id="purchaseItems">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Description</th>
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
    // /transactions/purchase/auto-update-payments/{puid}

    $('#flexSwitchCheckDefault').click((e) => {

        const puid = "{{ $purchase->puid }}";
        const url = "{{ url('/') }}/" + `transactions/purchase/auto-update-payments/${puid}`;
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
        const puid = "{{ $purchase->puid }}";
        const url = "{{ url('/') }}/" + `transactions/purchase/assign-tax/${puid}`;

        const ajaxConfiguration = {
            method: "POST",
            type: "POST",
            url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data: { tax_id: $('#tax_id').val() }
        }

        const swalConfiguration = {
            title: "Are you sure?",
            text: "Do you want update tax record for this purchase?",
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

        const puid = "{{ $purchase->puid }}";
        const taxRecordUrl = "{{ url('/') }}/" + `transactions/api/purchase/tax-records`;
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
                    const tax_id = "{{ $purchase->tax_id }}";
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

        // /transactions/api/purchased-item/stats/{puid} GET
        const getStatsData = (puid) => {
            const appUrl =  "{{ url('/') }}" + `/transactions/api/purchased-item/stats/${puid}`;
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
                    $('#itemCount').html(response?.purchaseCount);
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
    
        getStatsData(puid);
        
        const fetchDataForTable= async () => {

			$('#purchaseItems').DataTable({

				"bProcessing": true,
				"bServerSide": true,
        // purchase Table url
                "ajax": {"url": "{{ url('/') }}/transactions/api/purchased/list/" + "{{ $purchase->puid }}" + "?_token="+$('meta[name=_token]').attr('content'),
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
                    url:"{{ url('/') }}/transactions/purchase/delete/" + id,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                            $('#purchaseItems').DataTable().ajax.reload();
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
        $.ajax({
            url: "{{ route('purchase.item.suppliers') }}",
            method: "GET",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

            error: (response) => {
                toastr.error(response?.data, 'Error');
            },

            success: (response) => {
                const parent = document.querySelector('#supplier_id');

                response?.forEach(item => {
                    const option = document.createElement('option');
                    option.innerText = item?.name;
                    option.value = item?.sid;
                    parent.appendChild(option);
                });
            }
        });

    const confrimSendCreateRequest = (validated) => {
        $.ajax({
            url: "{{ url('/') }}/transactions/api/purchased-item/create/" + "{{ $purchase->puid }}",
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
                $("#purchaseItems").DataTable().ajax.reload();
                toastr.success(response?.message, "Success");
                getStatsData(puid);
            }
        });
    }
    // create purchased item
    $('#purchaseItemCreateForm').submit(e => {
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
            text: "Create a new Purchased item!",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-success",
            confirmButtonText: "Confrim",
            closeOnConfirm: true
        }

        swal(swalConfigurationForCreateForm, () => confrimSendCreateRequest(validated));
    })

    

    $(document).on('click','.deletePurchaseItem', function (e) {
        swal({
            title: "Are you sure?",
            text: "Delete the Purchased item!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-success",
            confirmButtonText: "Yes! Delete item",
            closeOnConfirm: true

            }, function () {

            $.ajax({
                url: "{{ url('/') }}/transactions/api/purchased-item/delete/" + e.target.dataset.id,
                type: 'POST',
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                // data: validated,
                
                error: error => {
                    console.log(error);
                    toastr.error(error?.responseJSON?.message);
                    return;
                },
                
                success: response => {
                    getStatsData(puid);
                    $("#purchaseItems").DataTable().ajax.reload();
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
        // getting purchase auto update feature
        const autoUpdateAvailable = Boolean("{{ $purchase->auto_update_payment }}");
        // does not has auto update
        if(!autoUpdateAvailable){
            return false;
        }

        const url = "{{ url('/') }}/transactions/api/purchased-item/payment-record/{{ $purchase->puid }}" ;

        const ajaxConfigurationForAutoUpdateUpdates = {
            method: "POST",
            type: "POST",
            url: url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
        }

        const sweetAlertConfiguration = {
            title: "Are you sure?",
            text: "Add payment records for this purchase!",
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