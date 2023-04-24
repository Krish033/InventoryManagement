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
    <div class="row p-2">
        <div class="col-md-5 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex w-100 justify-content-center" style="flex-direction: column">
                        <h1 class="h5 text-dark">{{$payment->date}}</h1>
                        <div class="d-flex w-100 justify-content-start align-items-center gap-2 mb-0">
                            <p class="mb-0">{{$payment->payment_type}}</p>
                            <p class="text-dark">{{$payment->category}}</p>
                        </div>
                        <div class="d-flex mt-0 pt-0 w-100 justify-content-start align-items-center gap-2 mb-3"
                            style="align-self: flex-start">
                            <p class="text-dark">{{$payment->description}}</p>
                        </div>
                        <div class="d-flex w-100 justify-content-start gap-2">
                            <a href="{{ route('payment.home') }}" class="btn btn-outline-info btn-sm">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <a class="btn btn-outline-dark btn-sm" href="{{ route('payment.update', $payment->pyid) }}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createPurchaseItemModal">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal rounded fade" id="createPurchaseItemModal">
                <div class="modal-dialog">
                    <form class="modal-content" id="manualPaymentCreateForm">
                        <div class="modal-header">
                            <h1 class="h5">Create payment item</h1>
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

                            <div class="form-group mb-4">
                                <label for="is_active">Amount for single item</label>
                                <input type="number" class="form-control" placeholder="100.00" id="amount">
                                <span class="small mt-1 text-danger" data-validation data-error="amount"></span>
                            </div>

                            <div class="form-group mb-4">
                                <label for="is_active" class="mb-0 pb-0">Quantity</label>
                                <small class="small d-block mb-3">The amount will be automatically updated for
                                    quantity</small>
                                <input type="number" class="form-control" placeholder="100.00" id="quantity">
                                <span class="small mt-1 text-danger" data-validation data-error="quantity"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a data-bs-dismiss="modal" class="btn btn-outline-dark btn-sm">
                                Close
                            </a>
                            <button data-bs-dismiss="modal" class="btn btn-outline-success btn-sm">
                                <i class="fa fa-plus"></i>
                                Add payment item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title mb-0 pb-0">payment record</h1>
                    <div class="payment">
                        <div>
                            <div class="d-flex justify-content-between slign-items-center mt-3 mb-2"
                                style="border-bottom: 1px solid #0000001b; flex-wrap: wrap">
                                <span class="small">Number of items</span>
                                <h5 class="h4" id="purchaseCount"></h5>
                            </div>
                            <div class="d-flex justify-content-between slign-items-center" style="flex-wrap: wrap">
                                <span class="small">Total Amount</span>
                                <div class="d-flex gap-2 align-items-baseline">
                                    <h5 id="totalAmount" class="h5"></h5>
                                    <i class="fa fa-rupee-sign small"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 w-100 d-flex justify-content-between">
                            <span>Update modified payments</span>
                            <button id="updatePaymentRecord" class="btn btn-outline-primary btn-sm">Update
                                Payment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <table class="table" id="manualPaymentItems">
                <thead>
                    <tr>
                        <th>Pyid</th>
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

@endsection


@section('scripts')
<script>
    // create purchased item
    $(document).ready(() => {

        getStatsData("{{ $payment->pyid }}");


        $('#manualPaymentCreateForm').submit(e => {
            e.preventDefault();

            // Objects
            const validated = {
                name: $('#name').val(),
                amount: $('#amount').val(),
                quantity: $('#quantity').val(),
            }
            
            //  Validation
            if(!validate(validated)){
                return false;
            }

            const swalConfigurationForCreateForm = {
                title: "Are you sure?",
                text: "Create a new payment item!",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-success",
                confirmButtonText: "Confrim",
                closeOnConfirm: true
            }
            swal(swalConfigurationForCreateForm, () => confrimSendCreateRequest(validated));
        })
    });

    const confrimSendCreateRequest = (validated) => {
        $.ajax({
            url: "{{ url('/') }}/transactions/payments/item/create/" + "{{ $payment->pyid }}",
            type: 'POST',
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data: {
                ...validated,
            },
            
            error: error => {
                toastr.error(error?.responseJSON?.message);
                return;
            },

            success: response => {
                $("#manualPaymentItems").DataTable().ajax.reload();
                getStatsData("{{ $payment->pyid }}");
                toastr.success(response?.message, "Success");
            }
        });
    }
</script>

<script>
    const fetchDataForTable = async () => {
        $('#manualPaymentItems').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            // payment Table url
            "ajax": {"url": "{{ url('/') }}/transactions/payments/api/item/list/" + "{{ $payment->pyid }}" + "?_token="+$('meta[name=_token]').attr('content'),
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

    fetchDataForTable()
</script>


<script>
    // 
    $(document).on('click','.deletePaymentButton', function (e) {
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
                url: "{{ url('/') }}/transactions/payments/item/delete/" + e.target.dataset.id,
                type: 'POST',
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                // data: validated,
                
                error: error => {
                    toastr.error(error?.responseJSON?.message);
                    return;
                },
                
                success: response => {
                    getStatsData("{{ $payment->pyid }}");
                    $("#manualPaymentItems").DataTable().ajax.reload();
                    toastr.success(response?.message);
                }
            });
        });
    });
</script>


<script>
    // /transactions/api/purchased-item/stats/{puid} GET
        const getStatsData = (puid) => {
            const appUrl =  "{{ url('/') }}" + `/transactions/payments/item/stats/${puid}`;
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
                    $('#purchaseCount').html(response?.purchaseCount);
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
</script>


<script>
    // updatePaymentRecord
    $(document).on('click','#updatePaymentRecord', function (e) {

        swal({
            title: "Are you sure?",
            text: "Update payment records!",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-success",
            confirmButtonText: "Yes! Delete item",
            closeOnConfirm: true
            }, function () {

            $.ajax({
                url: "{{ url('/') }}/transactions/payments/update-payments/{{ $payment->pyid }}",
                type: 'GET',
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                // data: validated,
                
                error: error => {
                    toastr.error(error?.responseJSON?.message);
                    return;
                },
                
                success: response => {
                    toastr.success("Payment has been updated");
                    return true;
                }
            });
        });
    });

</script>


@endsection