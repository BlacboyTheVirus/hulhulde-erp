@extends('adminlte::page')

@section('title', 'Procurements | Dashboard')

@section('content_header')
    <h1>Create New Procurement</h1>
@stop

@section('content')

    <style>
        .table td, .table th{
            padding: 0.3em !important;
        }
    </style>

    <div class="container-fluid">
        <div class="row">

            <div class="col-12">


                <div class="invoice p-3 mb-3">

                    <div class="row">
                        <div class="col-12">

                            <div class="callout callout-success mb-4">
                                <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" width="30px">
                                <h2 class="float-right  text-lg font-weight-bold"> {{$procurement->input->name}} Procurement</h2>
                            </div>

                        </div>

                    </div>



                    <div class="row">
                        <div class="col-12 mb-3 mt-1">
                            <h4>
                                <i class="fas fa-hashtag"></i> {{ $procurement->code }}
                                <small class="float-right"><b>Date:</b> {{ $procurement->procurement_date }}</small>
                            </h4>
                        </div>

                    </div>


                    <div class="row invoice-info ">


                        <div class="col-sm-3 invoice-col">
                            <address class="text-md">
                                <br>Supplier:
                                <b>{{$procurement->supplier->name}}</b><br>
                                Phone:
                                <b>{{$procurement->supplier->phone}}</b><br>
                                Email:
                                <b>{{$procurement->supplier->email}}</b><br>
                            </address>
                        </div>

                        <div class="col-sm-3 invoice-col">
                            <address class="text-md">
                                <br>Status:
                                <b>{{$procurement->status}}</b><br>
                                Approval:
                                <b>{{$procurement->approval->status ? "Approved" : "Pending"}}</b><br>
                                </address>
                        </div>

                        <div class="col-sm-3 invoice-col">
                            <address class="text-md">
                                <br>Account Number:
                                <b>{{$procurement->supplier->bank_account}}</b><br>
                                Bank Name:
                                <b>{{$procurement->supplier->bank_name}}</b><br>
                            </address>
                        </div>

                        <div class="col-sm-3 invoice-col">
                            <address class="text-md">
                                <br>Total:
                                <b>₦ {{ number_format($weight * $price,0, '.', ',')  }}</b><br>
                                Paid:
                                <b>₦ {{ number_format($procurement->payments->sum('amount'), 0,'.',',')}}</b><br>

                                Balance:
                                <b>₦ {{ number_format($weight * $price - $procurement->payments->sum('amount'), 0,'.',',')}}</b><br>


                            </address>
                        </div>


                    </div>


                    <div class="row">
                        @if($procurement->security)
                        <div class="col-md-6">
                            <div class="card card-outline card-info">

                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                Security Details | {{ $procurement->security->code  }}
                                            </div>

                                            <div class="card-tools">
                                                {{ $procurement->security->checkin_date }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                            <tr>
                                                <th>Vehicle Number:</th>
                                                <td>{{ $procurement->security->vehicle_no }}</td>
                                            </tr>
                                           <tr>
                                                <th>Number of Bags</th>
                                                <td>{{ $procurement->security->bags }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif

                        @if($procurement->weighbridge)
                        <div class="col-md-6">
                            <div class="card card-outline card-info">

                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                Weighbridge Details | {{ $procurement->weighbridge->code  }}
                                            </div>

                                            <div class="card-tools">
                                                {{ $procurement->weighbridge->first_date }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                            <tr>
                                                <th>Net Weight:</th>
                                                <td>{{ $procurement->weighbridge->weight }} tons</td>
                                            </tr>
                                            <tr>
                                                <th>Number of Bags</th>
                                                <td>{{ $procurement->weighbridge->bags }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif

                        @if($procurement->quality)
                        <div class="col-md-6">
                            <div class="card card-outline card-info">

                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                Quality Details | {{ $procurement->quality->code  }}
                                            </div>

                                            <div class="card-tools">
                                                {{ $procurement->quality->analysis_date }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                            <tr>
                                                <th>Mositure:</th>
                                                <td>{{ $procurement->quality->moisture }} %</td>
                                            </tr>
                                            <tr>
                                                <th>Rejected Weight / Bags</th>
                                                <td>{{ $procurement->quality->rejected_weight }} tons / {{$procurement->quality->rejected_bags}}</td>
                                            </tr>

                                            <tr>
                                                <th>Recommended Price/ton:</th>
                                                <td>₦ {{ number_format($procurement->quality->recommended_price,0, '.', ',') }} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif


                        @if($procurement->warehouse)
                        <div class="col-md-6">
                            <div class="card card-outline card-info">

                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                Warehouse Details | {{ $procurement->warehouse->code  }}
                                            </div>

                                            <div class="card-tools">
                                                {{ $procurement->warehouse->receipt_date }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                            <tr>
                                                <th>Weight:</th>
                                                <td>{{ $procurement->warehouse->weight }} tons</td>
                                            </tr>
                                            <tr>
                                                <th>Bags</th>
                                                <td>{{$procurement->warehouse->bags}}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                       @endif




                        <div class="col-md-12">
                            <div class="card card-outline card-info">

                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                Finance Details
                                            </div>

                                            <div class="card-tools">

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>

                                            <tr>
                                                <th>Bags</th>
                                                <td>{{$procurement->warehouse ? $procurement->warehouse->bags: $procurement->expected_bags}}</td>
                                            </tr>

                                            <tr>
                                                <th>Total Weight:</th>
                                                <td>{{$procurement->warehouse ? $procurement->warehouse->weight : $procurement->expected_weight }} tons</td>
                                            </tr>

                                            @if($procurement->approval->approved_price)
                                                <tr>
                                                    <th>Approved Price</th>
                                                    <td>₦ {{  number_format($price,0, '.', ',')}}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>Recommended Price</th>
                                                    <td>₦ {{  number_format($price,0, '.', ',') }}</td>
                                                </tr>
                                            @endif



                                            <tr>
                                                <th>Amount Due</th>
                                                <td>₦ {{ number_format($weight * $price,0, '.', ',')  }}</td>
                                            </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div> <!-- row -->

                        </div> <!-- invoice -->




                        </div>

                    </div>




                    <div class="row no-print">
                        <div class="col-12">
                            <a href="#" class="btn btn-warning">
                                <i class="fas fa-edit"></i>Edit Invoice
                            </a>

                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print"></i> Print Invoice
                            </button>



                        </div>
                    </div>




                </div>

            </div>


        </div>


        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')


@stop


{{--@section('plugins.Datatables', true)--}}
{{--@section('plugins.DatatablesPlugins', true)--}}
{{--@section('plugins.Sweetalert2', true)--}}
{{--@section('plugins.jQueryValidation', true)--}}
{{--@section('plugins.Select2', true)--}}
