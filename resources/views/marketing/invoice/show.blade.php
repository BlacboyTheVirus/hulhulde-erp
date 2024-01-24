@extends('adminlte::page')

@section('title', 'Invoice | Dashboard')

@section('content_header')
    <h1>Invoice</h1>
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
                                <h2 class="float-right  text-lg font-weight-bold"> Invoice</h2>
                            </div>

                        </div>

                    </div>



                    <div class="row">
                        <div class="col-12 mb-3 mt-1">
                            <h4>
                                <i class="fas fa-hashtag"></i> {{ $invoice->code }}
                                <small class="float-right"><b>Date:</b> {{ $invoice->date }}</small>
                            </h4>
                        </div>

                    </div>


                    <div class="row invoice-info ">


                        <div class="col-sm-3 invoice-col">
                            From
                            <address>
                                <strong><span class="text-md">Hulhulde Intl. Ltd</span></strong><br>
                                Zaria<br>
                                Kaduna - Nigeria<br>
                            </address>
                        </div>



                        <div class="col-sm-3 invoice-col">
                            To
                            <address>
                                <strong><span class="text-md">{{ $invoice->customer->name }}</span></strong><br>

                                Phone : {{ $invoice->customer->customer_phone }}

                            </address>
                        </div>


                        <div class="col-sm-3 invoice-col">

                            <address>
                                <br><b>Invoice Total:</b> ₦
                                {{ number_format($invoice->grand_total, 2, '.', ',') }}<br>
                                <b>Amount Paid:</b> ₦
                                {{ number_format($invoice->amount_paid, 2, '.', ',') }}<br>
                                <b>Amount Due:</b> ₦ {{ number_format($invoice->amount_due, 2, '.', ',') }}<br>
                                <b>Payment Status:</b>
                                <span
                                    class="badge text-sm  font-weight-normal
                                            @if ($invoice->payment_status == \App\Enums\PaymentStatus::UNPAID) badge-danger
                                            @elseif($invoice->payment_status == \App\Enums\PaymentStatus::PARTIAL) badge-warning
                                            @elseif ($invoice->payment_status == \App\Enums\PaymentStatus::PAID)   badge-success @endif
                                                                          ">
                                        {{ $invoice->payment_status }}</span><br>

                            </address>
                        </div>



                    </div>


                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table ">
                                <thead>
                                <tr class="bg-gray-light">
                                    <th width="5%">S/No</th>
                                    <th width="15%">Description</th>
                                    <th width="15%" class="text-center">Unit Price</th>
                                    <th width="20%" class="text-center">Quantity</th>
                                    <th width="20%" class="text-right">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invoice->invoiceitems as $key=>$invoiceitem)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ $invoiceitem->product->name }}</td>
                                    <td class="text-center">{{ $invoiceitem->unit_price }}</td>
                                    <td class="text-center">{{ $invoiceitem->quantity }}</td>
                                    <td class="text-right">{{ $invoiceitem->unit_amount }}</td>

                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>



                    <div class="row">
                        <div class=" order-2 order-sm-2 p-2 col-md-5">

                            <h3 class="card-title lead mb-2 font-weight-bold">Payment Details</h3>

                            <div class="table table-responsive ">
                                <table class="table text-nowrap table-condensed table-bordered table-hover">
                                    <thead>
                                    <tr class="bg-gray-light">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th class="text-center" width="30%">Note</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!$invoice->payments)
                                        <tr>
                                            <td colspan="5"> No payment detail for invoice yet.</td>
                                        </tr>
                                    @else

                                        @foreach ($invoice->payments as $key => $payment)
                                        <tr>
                                            <td> {{ $key+1 }}</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ $payment->payment_type }}</td>
                                            <td>{{ $payment->note }}</td>
                                            <td class="text-right pr-3">{{ $payment->amount }} </td>
                                        </tr>
                                        @endforeach
                                    @endif


                                    </tbody>
                                </table>
                            </div>



                            <h3 class="card-title lead mb-2 font-weight-bold">Release Details</h3>

                            <div class="table table-responsive ">
                                <table class="table text-nowrap table-condensed table-bordered table-hover">
                                    <thead>
                                    <tr class="bg-gray-light">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th class="text-center" width="30%">Quantity</th>
                                        <th class="text-center">Released By</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!$invoice->stores)
                                        <tr>
                                            <td colspan="5"> No release detail for invoice yet.</td>
                                        </tr>
                                    @else

                                        @foreach ($invoice->stores as $key => $store)
                                            <tr>
                                                <td> {{ $key+1 }}</td>
                                                <td>{{ $store->released_date }}</td>
                                                <td>{{ \App\Models\Product::where('id','=', $store->product_id)->value('name') }}</td>
                                                <td>{{ $store->quantity }}</td>
                                                <td class="text-right pr-3">{{ $store->released_by }} </td>
                                            </tr>
                                        @endforeach
                                    @endif


                                    </tbody>
                                </table>
                            </div>



                            <h3 class="card-title lead mb-2 font-weight-bold">Outstanding Products</h3>

                            <div class="table table-responsive ">
                                <table class="table text-nowrap table-condensed table-bordered table-hover">
                                    <thead>
                                    <tr class="bg-gray-light">
                                        <th>#</th>
                                        <th>Product</th>
                                        <th class="text-center" width="30%">Quantity</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!count($invoice->invoiceitems->where('quantity_left', '>', 0) ) > 0)
                                        <tr>
                                            <td colspan="5"> No outstanding detail for invoice yet.</td>
                                        </tr>
                                    @else

                                        @foreach ($invoice->invoiceitems as $key => $item)
                                            <tr>
                                                <td> {{ $key+1 }}</td>
                                                <td>{{ \App\Models\Product::where('id','=', $item->product_id)->value('name') }}</td>
                                                <td>{{ $item->quantity_left }}</td>

                                            </tr>
                                        @endforeach
                                    @endif


                                    </tbody>
                                </table>
                            </div>





                        </div>

                        <div class="  order-1 order-sm-1 p-2  col-md-5 offset-7">


                            <div class="table-responsive">
                                <table class="table text-right">
                                    <tbody>

                                    <tr style="border-top: 2px solid #cccccc">

                                        <th width="50%">Subtotal:</th>
                                        <td>&nbsp;</td>
                                        <td>{{ $invoice->sub_total }}</td>

                                    </tr>
                                    <tr>
                                        <th>Less Discount</th>
                                        <td>&nbsp;</td>
                                        <td>{{ $invoice->discount }}</td>

                                    </tr>

                                    <tr>
                                        <th>Total : </th>
                                        <td>&nbsp;</td>
                                        <td style="font-weight: 700; font-size:1.5rem">₦ {{ number_format($invoice->grand_total,2,'.', ',') }}
                                        <input type="hidden" id="numtotal" value="{{$invoice->grand_total}}"></td>

                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <span id="numinwords"></span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>



                        </div>

                    </div>




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

    <script>
    $(document).ready(function(){

        $('#numinwords').html(numToWordsDec($('#numtotal').val()));

    });
    </script>
@stop


{{--@section('plugins.Datatables', true)--}}
{{--@section('plugins.DatatablesPlugins', true)--}}
{{--@section('plugins.Sweetalert2', true)--}}
{{--@section('plugins.jQueryValidation', true)--}}
{{--@section('plugins.Select2', true)--}}
