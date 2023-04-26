@extends('layouts.layout')

@section('styles')
<style>
    .createForm input,
    select {
        border: 1px solid #000 !important;
        background: transparent !important;
        color: #000 !important;
        font-size: .8em !important;
    }

    .createFormDiv {
        background: #FFF6DE;
        padding: .5em;
        /* margin: 10px 0; */
    }

    .createForm {
        border-top: 1.4px solid #000;
        border-bottom: 1.4px solid #000;
        padding: .2em 0;
    }

    .editing {
        background: #FFF6DE;
    }

    .disabled {
        pointer-events: none;
        user-select: none;
        background: rgb(196, 196, 196) !important
    }

    .floating {
        position: absolute;
        width: 100vw;

    }
</style>
@endsection


@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}/dashboard" data-original-title="" title=""><i
                                class="f-16 fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Transactions</li>
                    <li class="breadcrumb-item">Purchase</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <h1 class="h5 mb-4">Create Payment</h1>
    <div>
        <form class="form">
            <div class="d-md-flex gap-3">
                <div class="form-group d-flex" style="flex-direction: column">
                    <label for="" class="mb-0 fs-14">Date</label>
                    <input class="form-control form-control-sm" id="date" type="date">
                </div>
                <div class="form-group d-flex" style="flex-direction: column">
                    <label for="" class="mb-0 fs-14">Supplier</label>
                    <select class="form-control form-control-sm" name="" id="supplierSelect">
                        <option value="">The Main Supplier</option>
                    </select>
                </div>
                <div class="form-group d-flex" style="flex-direction: column">
                    <label for="" class="mb-0 fs-14">Payment type</label>
                    <select class="form-control form-control-sm" name="" id="paymentType">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                    </select>
                </div>
                <div class="form-group d-flex" style="flex-direction: column">
                    <label for="" class="mb-0 fs-14">Invoice Number</label>
                    <input id="ivNo" class="form-control form-control-sm bg-outline-dark" type="text"
                        placeholder="INV001">
                </div>
            </div>
        </form>
        {{-- style="background: rgba(215, 208, 79, 0.123)" --}}
        <div class="createForm">
            <form class="form" id="createForm">
                <div class="row m-0 gap-1 createFormDiv" style="align-items: center">
                    <select id="categoryId" class="form-control form-control-sm bg-outline-dark col-1"
                        style="background: transparent" type="text">
                    </select>
                    <select id="subCategoryId" class="form-control form-control-sm bg-outline-dark col-1 disabled"
                        style="background: transparent" type="text">
                    </select>
                    <select id="productId" class="form-control form-control-sm bg-outline-dark col-1 disabled"
                        style="background: transparent" type="text">
                    </select>
                    <input placeholder="Description" id="description" class="form-control form-control-sm col-1"
                        type="text">
                    <input placeholder="Quantity" id="quantity" class="form-control form-control-sm col-1 disabled"
                        type="number">
                    {{-- tax --}}
                    <input id="price" readonly placeholder="Rate(Rs)" class="form-control form-control-sm col-1"
                        type="text"> {{-- tax type
                    --}}
                    <select id="taxType" class="form-control form-control-sm bg-outline-dark col-1"
                        style="background: transparent" type="text">
                        <option value="include">Include</option>
                        <option value="exclude">Exclude</option>
                    </select>
                    <select id="taxPercentage" class="form-control form-control-sm bg-outline-dark col-1"
                        style="background: transparent" type="text">
                    </select>
                    <input class="form-control form-control-sm col-1" id="taxable" readonly value="0.00" type="text">
                    {{-- taxable
                    --}}
                    <input readonly id="taxAmount" value="0.00" class="form-control form-control-sm col-1" type="text">
                    {{-- tax
                    amount --}}
                    <input id="subtotal" readonly class="form-control form-control-sm col-1" type="text"> {{-- Amount
                    --}}
                    <button id="createPurchaseItemForm" class="btn btn-sm btn-outline-primary col-1">
                        <i class="fa fa-check"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="tableClass mt-2">
            <table class="table table-xs" style="width: 100% !important">
                <thead style="border-bottom: 1px solid #000 !important">
                    <tr>
                        <th>Category</th>
                        <th>SubCategory</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Rate(Rs)</th>
                        <th>Tax Type</th>
                        <th>Tax (%)</th>
                        <th>Taxable(Rs)</th>
                        <th>Tax Amount</th>
                        <th>Amount(Rs)</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="createTable">

                </tbody>
            </table>

            <div class="w-100 d-flex align-items-end mt-5" style="flex-direction: column">
                <span class="fs-11 bg-success p-2 rounded">SubTotal: Rs. <span id="totalSum"
                        class="h5">0.00</span></span>
                <span class="fs-11 p-2">Tax Total: Rs. <span id="taxTotal" class="h5">0.00</span></span>
                <span class="fs-11 p-2" style="border-top: 1px solid #000; border-bottom: 1px solid #000">Grand Total:
                    Rs. <span id="grandTotal" class="h5">0.00</span></span>
            </div>

            <div class="submitButtons mt-4 w-100 d-flex justify-content-end gap-2">
                <button class="btn btn-sm btn-outline-dark">Back</button>
                <button id="createPurchase" class="btn btn-sm btn-outline-success">Add</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ url('/') }}/Assets2/custom/purchase.js"></script>

<script>
    // main request configuartion
    const request = createHttpRequest("{{ url('/') }}");
    // ! loading categories
    $(document).ready(async () => {
        //getting and appending categories
        const { data, isError } = await requestCategories();
        const categoryId = document.querySelector('#categoryId');
        // caheck error and create error
        if(isError) return toastr.error("Something went wrong!", 'Failed');
        createSelectOption(categoryId, data, 'CID', 'CName')
    });

    $('#categoryId').change(async function(e) {
        clearSelectInputs(['categoryId'])
        // clear subcategories
        const subCategoryId = document.querySelector('#subCategoryId');
        // getting sub categories purchase.subcategory
        const { data, isError, error } = await requestSubCategories(e.target.value);
        if(isError) return toastr.error("Something went wrong!", 'Failed');

        subCategoryId.classList.remove('disabled');
        createSelectOption(subCategoryId, data, 'SCID', 'SCName')
    });

    $('#subCategoryId').change(async function(e) {
        clearSelectInputs(['categoryId', 'subCategoryId']);
        // clear subcategories
        const productId = document.querySelector('#productId');
        const { data, isError } = await requestProducts(e.target.value);
        if(isError) return toastr.error("Something went wrong!", 'Failed');
        // getting sub categories purchase.subcategory
        productId.classList.remove('disabled');
        createSelectOption(productId, data, 'pid', 'name')
    });
        // clear subcategories
    $('#productId').change(async function(e) {
        clearSelectInputs(['categoryId', 'subCategoryId', 'productId']);

        const productId = document.querySelector('#productId');
        const { data, isError } = await requestSingleProducts(e.target.value);
        if(isError) return toastr.error("Something went wrong!", 'Failed');

        const quantity = document.querySelector('#quantity');
        quantity.classList.remove('disabled');
        document.querySelector('#price').value = data?.purchaseRate;

        //getting and appending categories
        const { data: taxData, isError: isTaxError } = await requestTaxes();
        if(isTaxError) return toastr.error("Something went wrong!", 'Failed');
        const taxSelect = document.querySelector('#taxPercentage');
        createSelectOption(taxSelect, taxData, 'TaxPercentage', 'TaxName', data?.taxId)
    });

        // clear subcategories
    $(document).on('input', '#quantity', async function(e) {
        const taxable = document.querySelector('#taxable');
        if(e.target.value < 0) {
            return;
        } // entering taxable value
        taxable.value = parseFloat($('#quantity').val()) * parseFloat($('#price').val())
        // totalAmount(taxable.value, calculateTaxAmount(taxable.value,$('#taxPercentage').val()));
        const calculatedTaxAmount = calculateTaxAmount(taxable.value, $('#taxPercentage').val());
        $('#taxAmount').val(calculatedTaxAmount)
        totalAmount(taxable.value, calculatedTaxAmount)
        return true;
    });

    // clear subcategories
    $('#taxPercentage').change(async function(e) {
        const taxable = document.querySelector('#taxable').value;
        const calculatedTaxAmount = calculateTaxAmount(taxable, e.target.value);
        $('#taxAmount').val(calculatedTaxAmount)
        totalAmount(taxable, calculatedTaxAmount)
    });

    // submit records
    $(document).on('click', '#createPurchaseItemForm', async (e) => {
        e.preventDefault();
        const validated = requestValidatedArray();

        if(!validated){
            return false;
        } // find editings
        if(document?.querySelector('.editing') !== null){
            document?.querySelector('.editing').remove();
        }
        createDOMElement(validated);
        return;
    });

    requestItemsFromLocalStorage()
    genepriceGrandTotal();

    $(document).ready(async () => {
        //getting and appending categories
        const {data: suppliers, isError} = await requestSuppliers();
        const supplierSelect = document.querySelector('#supplierSelect');
        createSelectOption(supplierSelect, suppliers, 'sid', 'name')
    })

    $(document).on('click', '#createPurchase', async (e) => {
        e.preventDefault();
        // get values
        let products = [...document.querySelector('#createTable').children].map(item => {
            return item.dataset;
        }); // return return account
        products = products.map(item => {
            let obj = {};
            Object.entries(item).map(data => {
                obj = {
                    ...obj,
                    [data[0]]: data[1]
                }
            }); // main object
            return obj;
        }); // validation
        const validated = {
            tranDate: $('#date').val(),
            supplierId: $('#supplierSelect').val(),
            mop: $('#paymentType').val()
        } // validated

        if(!validate(validated)){
            toastr.error('Cannot create record with empty field data')
            return false;
        } // bundle
        const mainData = {
            ...validated,
            invoiceNo: $('#ivNo').val(),
            taxable: $('#totalSum').html(),
            taxAmount: $('#taxTotal').html(),
            TotalAmount: $('#grandTotal').html(),
            paidAmount: 0,
            balanceAmount: $('#grandTotal').html(),
            products,
        } // send ajax request to post the data

        const { data, isError } = await request.http({
            url: "/transactions/api/purchase/create-record",
            method: 'POST',
            data: mainData,
        });

        if(isError) return toastr.error("Something went wrong!", 'Failed');
        window.location.replace("{{ url('/') }}/transactions/purchase");
    })
</script>
@endsection