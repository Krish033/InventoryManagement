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
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item">Purchase</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- {{dd($purchase)}} --}}
<div class="container-fluid">
    <div class="d-md-flex">
        <div class="card-div-main d-md-flex" style="flex-direction: column; width: 40%">
            <div class="card p-2 w-100">
                <div class="card-body">
                    <h1 class="h5 mb-0 pb-0">Purchase List</h1>
                    <p class="mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto, officiis.</p>
                    <a href="{{ route('purchase.home') }}" class="btn btn-sm btn-outline-dark">View Purchase</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h1 class="h5 mb-0 pb-1 fw-bold">Total Purchase</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro, ipsum.</p>
                    <div>
                        <span>Amount: </span>
                        <span class="avgPurchaseDay"><b>{{ $purchase['purchasePerMonth'] }}</b> Rs</span>
                    </div>
                    <div class="mb-3">
                        <span>Items Per Day: </span>
                        <span class="avgPurchaseDay"><b>{{ $purchase['purchaseQty'] }}</b>. Nos</span>
                    </div>
                    <hr>

                    <h1 class="h5 mb-0 pb-1 fw-bold">Average purchase per year</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro, ipsum.</p>
                    <div>
                        <span>Amount: </span>
                        <span class="avgPurchaseDay"><b>{{$purchase['thisYearPurchase']}}</b> Rs</span>
                    </div>
                    <div>
                        <span>Items sold: </span>
                        <span class="avgPurchaseDay"><b>{{$purchase['itemsSoldPerYear']}}</b>. Nos</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-div-main" style="width: 60%">
            {{-- @include('reports.purchase.invoice') --}}
            <div class="mt-6 mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h2>
                                    {{ config('app.name') }}
                                </h2>
                                <p class="fs-sm">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae repellendus
                                    reprehenderit
                                    fugiat
                                </p>
                                <div class="border-top border-gray-200 pt-4 mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-muted mb-2">Payment No.</div>
                                            <strong>#100001</strong>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <div class="text-muted mb-2">Duration</div>
                                            <strong>This Year</strong>
                                        </div>
                                    </div>
                                </div>

                                <table class="table border-bottom border-gray-200 mt-3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="fs-sm text-dark text-uppercase-bold-sm px-0">
                                                Description</th>
                                            <th scope="col" class="fs-sm text-dark text-uppercase-bold-sm px-0">No. of
                                                purchase
                                            </th>
                                            <th scope="col"
                                                class="fs-sm text-dark text-uppercase-bold-sm text-end px-0">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-0">Total Purchase</td>
                                            <td class="text-center px-0">{{$purchase['purchaseQty']}}</td>
                                            <td class="text-end px-0">{{$purchase['purchasePerMonth']}} Rs</td>
                                        </tr>
                                        <tr>
                                            <td class="px-0">Average purchase per year</td>
                                            <td class="text-center px-0">{{$purchase['itemsSoldPerYear']}}</td>
                                            <td class="text-end px-0">{{$purchase['thisYearPurchase']}} Rs</td>
                                        </tr>

                                    </tbody>
                                </table>

                                <div class="mt-5">
                                </div>
                            </div>
                            <a href="#!"
                                class="btn btn-dark btn-lg card-footer-btn justify-content-center text-uppercase-bold-sm hover-lift-light">
                                <span class="svg-icon text-white me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512"
                                        viewBox="0 0 512 512">
                                        <title>ionicons-v5-g</title>
                                        <path d="M336,208V113a80,80,0,0,0-160,0v95"
                                            style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                        </path>
                                        <rect x="96" y="208" width="320" height="272" rx="48" ry="48"
                                            style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                                        </rect>
                                    </svg>
                                </span>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}


@endsection