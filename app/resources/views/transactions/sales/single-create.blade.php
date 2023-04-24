@extends('layouts.layout')

@section('styles')
<style>
    #productsTable_length,
    #productsTable_info,
    #productsTable_paginate {
        display: none;
    }

    #productsTable_filter {
        width: 100% !important;
        float: none !important;
    }

    .svg-inline--fa {
        pointer-events: none !important;
        user-select: none !important;
    }
</style>

@endsection


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
                    <li class="breadcrumb-item">@if($isEdit) Update @else Create @endif</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    {{-- {{ dd($supplier) }} --}}
    <div class="row">
        <div class="col-sm-12">
            <form class="saleCreateForm w-100 mt-3">
                <div class="w-100 d-flex justify-content-between align-items-center mb-5">
                    <h5>Create Sale</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('sales.single.home', $sales->saId) }}"
                            class="btn btn-outline-dark btn-sm m-r-10" type="button">Back</a>
                        <button type="submit" id="salesCreateForm" class="btn btn-sm btn-outline-success">
                            @if(!$isEdit) Add @else Update @endif Products</button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-md-6 col-12">
                        <label class="form-label" for="name">Date</label>
                        <input type="date" class="form-control" @if($isEdit) value="{{$single->date}}" @endif id="date"
                            placeholder="Creatively awesome item" />
                        <span class="small mt-1 text-danger" data-validation data-error="name"></span>

                        <div class="form-check form-switch ms-4 mt-2">
                            <input class="form-check-input" type="checkbox" id="useTodaysDate">
                            <label class="form-check-label small" for="useTodaysDate">Use current
                                date</label>
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="supplier_id">Customer</label>

                        <div class="">
                            <p class="mt-3">Incase you havent registered the customer, create a customer</p>
                            <a href="{{ route('customer.create') }}" class="btn btn-sm btn-dark small">New Customer</a>

                            <div class="form-check form-switch m-3">
                                <input class="form-check-input" type="checkbox" id="skipCustomer">
                                <p class="form-check-label" for="skipCustomer">Dont want to create
                                    customer for this single record</p>
                            </div>
                        </div>

                        <select id="customer_id" name="customer_id" class="form-control mb-5">
                            @if($isEdit && $single->customer_id === "defaultCustomer")
                            <option selected value="--">Select</option>
                            @elseif(!$isEdit)
                            <option value="--">Select</option>
                            @endif
                        </select>
                        <span class="small mt-1 text-danger" data-validation data-error="customer_id"></span>
                    </div>
                </div>

                <div class="form-group row gap-4">
                    <div class="col-12 col-md-6" style="overflow-x: scroll; background: #fff;">
                        <label class="mb-3">Product list</label>
                        <table class="table rounded" style="border: 1px solid #00000038; border-radius: 10px !important"
                            id="productsTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Add</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="mb-3">Selected Items</label>
                        <table class="table rounded"
                            style="border: 1px solid #00000038; border-radius: 10px !important">
                            <thead>
                                <tr>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">price</th>
                                    <th class="text-center">Remove</th>
                                </tr>
                            </thead>
                            <tbody id="saleElement">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const getInputValue = (event) => {
        const url = "{{ url('/') }}/transactions/sales/single/product/" + event;
        const product = [];

        $.ajax({
            url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            method: 'GET',

            success: (response) => {
                createSaleElement(response);
                return true;
            }, 
            error: (error) => {
                toastr.error('Something went wrong!');
                return false;
            }
        });

        return product;
    }
    
    @if($isEdit)
    const getProductsForEdit = () => {
        const url = "{{ url('/') }}/transactions/sales/single/get-products/{{ $single->siId }}";

        const ajaxConfiguration = {
            method: 'POST',
            type: 'POST',
            url,
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
        }

        $.ajax({
            ...ajaxConfiguration,
            success: (response) => {
                // create dropdown
                response?.forEach(item => {
                    createSaleElement(item)
                });
            }
        });
    }

    getProductsForEdit();
    @endif


    const createSaleElement = (element) => {
        // parent
        const tbody = document.querySelector('#saleElement');
        // main table row
        const tr = document.createElement('tr');
        tr.setAttribute('data-amount', element?.salesRate); 
        tr.setAttribute('data-pid', element?.pid); 
        tr.name = element?.name;
        tbody.appendChild(tr);
        // name
        const name = document.createElement('td');
        name.innerText = element?.name;
        tr.appendChild(name);
        // quantity
        const qty = document.createElement('td');
        qty.classList.add('qty');
        qty.setAttribute('data-qty-id', element?.pid); 
        qty.innerText = element?.quantity ? element?.quantity : 1;
        tr.appendChild(qty);
        // price
        const price = document.createElement('td');
        price.setAttribute('data-price-id', element?.pid); 
        price.setAttribute('data-price', element?.salesRate); 
        price.innerText = element?.salesRate;
        tr.appendChild(price);
        // button
        const button = document.createElement('td');
        button.setAttribute('data-delete-id', element?.pid); 
        button.classList.add('buttonHasId');
        button.innerHTML = `<button class="btn btn-sm btn-outline-danger plusButton" data-id=${element.pid}><i style="pointer-events: none !important;" class="fa fa-close plusButton"></i></button>`;
        
        button.addEventListener('click', (e) => {
            e.preventDefault();
            if(qty.innerText < 2){
                tr.remove();
            }
            qty.innerText = parseInt(qty.innerText) - 1;
            price.innerText = parseInt(element?.salesRate) * parseInt(qty.innerText)
        });
        
        tr.appendChild(button);

        return true;
    }


    const customers = () => {
        const customer = document.querySelector('#customer_id');

        const ajaxConfiguration = {
            method: 'POST',
            type: 'POST',
            url: "{{ route('sales.single.customers') }}",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
        }

        $.ajax({
            ...ajaxConfiguration,
            success: (response) => {
                // create dropdown

                response?.forEach(item => {
                    const option = document.createElement('option');
                    option.innerText = item?.CName;
                    option.value = item?.CID;

                    @if($isEdit === true)
                        option.selected = item?.CID === "{{ $single->customer_id }}"
                    @endif

                    customer.appendChild(option);
                });
            }
        });
    }

    customers();
    // $('#customer_id').select2();

    const findElementOnTable = (event) => {
        const tbody = document.querySelector('#saleElement');
        const element = tbody.querySelector(`[data-id=${event}]`) !== null;

        if(element){
            const main = tbody.querySelector(`[data-qty-id=${event}]`);
            const price = tbody.querySelector(`[data-price-id=${event}]`);
            main.innerText = parseInt(main.innerText) + 1;
            price.innerText = parseInt(price.dataset.price) * parseInt(main.innerText);
            return true;
        }
        return false;
    }

    $(document).ready(function () {


        $(document).on('click','.addProducts', function (e) {
            e.preventDefault();

            const findElement = findElementOnTable(e.target.dataset.id);

            if(findElement){
                return true;
            }

            // append itme to table
            getInputValue(e.target.dataset.id);
        });

        const url = "{{ route('sales.single.products') }}";

        $("#productsTable").DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {"url": url + "?_token="+$('meta[name=_token]').attr('content'),
            "headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
            // responsive: true,
        });
    });

</script>

<script>
    const getFormattedProductJSON = (data) => {
        // dta recieved should ne HTMLElement -> tr
        const main = [...data].map(item => {

            const qty = item?.querySelector('.qty').innerText;

            return {
                pid: item.dataset.pid,
                quantity: qty,
                salesRate: parseInt(item.dataset.amount) * parseInt(qty),
            }
        });

        return main;
    }


    $(document).ready(() => {
        const tbody = document.querySelector('#saleElement');

        const date = new Date();
        
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        
        // This arrangement can be altered based on how we want the date's format to appear.
        const defaultDate = `${day}-${month}-${year}`;

        // initialize click event
        $('#salesCreateForm').click(e => {
            e.preventDefault();

            const customerId = document?.querySelector('#skipCustomer').checked ? "defaultCustomer" : $('#customer_id').val();
            const currentDate = document?.querySelector('#useTodaysDate').checked ? defaultDate : $('#date').val()

            const validated = {
                date: currentDate,
                customer_id: customerId,
            }

            if(validated.customer_id == "--") {
                toastr.error('All fields are required');
                return false;
            }

            const formatted = getFormattedProductJSON(tbody.children);

            if (!formatted.length) {
                toastr.error('Select a product to save');
                return false;
            }

            if(!validate(validated)){
                toastr.error('All fields are required');
                return false;
            }

            const swalConfiguration = {
                title: "Are you sure?",
                text: "Do you want strore this sale?",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-info",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: true
            }


            const url = @if($isEdit == true) "{{ url('/') }}/transactions/sales/single/update/{{$single->siId}}" @else "{{ url('/') }}/transactions/sales/single/create/{{ $sales->saId }}" @endif

            swal(swalConfiguration, function () {
                $.ajax({
                    url,
                    method: 'POST',
                    type: 'POST',
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data: {
                        ...validated,
                        data: formatted,
                    },

                    success: (response) => {
                        toastr.success('Sale record created successfully');
                        window.location.replace("{{ route('sales.single.home', $sales->saId) }}");
                        return true;
                    },

                    error: () => {
                        toastr.error('Something went wrong');
                        return false;
                    }
                });
            });
        });
    });

    
</script>


@endsection