@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-12 ">&nbsp;</div>

{{--                            <div class="card-tools">--}}
{{--                                <div class="btn-group ">--}}
{{--                                    <button type="button" class="btn btn-info get_tab_records" period="today">Today</button>--}}
{{--                                    <button type="button" class="btn btn-info get_tab_records" period="week">This Week</button>--}}
{{--                                    <button type="button" class="btn btn-info get_tab_records" period="month">This Month</button>--}}
{{--                                    <button type="button" class="btn btn-info get_tab_records" period="year">This Year</button>--}}
{{--                                    <button type="button" class="btn btn-info get_tab_records active" period="all">All</button>--}}
{{--                                </div>--}}

{{--                            </div>--}}

                        </div>
                    </div>
                </div>



                <div class="card-body">

                    <div class="row">

                        @role('superuser')
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <span id="paddy_quantity">{{$paddy_procured}}</span>
                                        <sup style="font-size: 20px">tons</sup>
                                    </h3>
                                    <p>Paddy Procured</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <a href="{{route('procurement.warehouse.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        @endrole

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <span id="procurements">{{$procurements}}</span></h3>
                                    <p>No of Procurements</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money-bill-wave-alt"></i>
                                </div>
                                <a href="{{route('procurement.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>



                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <sup style="font-size: 20px">₦</sup>
                                        <span id="procurements">{{number_format($payments,0,'.',',')}}</span></h3>
                                    <p>Procurement Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money-bill-wave-alt"></i>
                                </div>
                                <a href="{{route('procurement.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-pink">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <span id="available_paddy">{{ $paddy_quantity }}</span></h3>
                                    <p>Available Paddy</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money-bill-wave-alt"></i>
                                </div>
                                <a href="{{route('procurement.warehouse.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>





                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-gradient-success">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <span id="paddy_quantity">{{$processed_paddy}}</span>
                                        <sup style="font-size: 20px">tons</sup>
                                    </h3>
                                    <p>Paddy Processed</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <a href="{{route('production.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>


                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-default">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <span id="procurements">{{$productions}}</span></h3>
                                    <p>Production</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money-bill-wave-alt"></i>
                                </div>
                                <a href="{{route('procurement.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>



                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-gradient-success">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <span id="paddy_quantity">{{$invoices_count}}</span>
                                    </h3>
                                    <p>Total Invoice</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <a href="{{route('marketing.invoice.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>


                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-orange">
                                <div class="inner">
                                    <h3 class="font-weight-bold">
                                        <sup style="font-size: 20px">₦</sup>
                                        <span id="available_paddy">{{ number_format($invoices_amount, 0,'',',') }}</span></h3>
                                    <p>Total Invoice Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money-bill-wave-alt"></i>
                                </div>
                                <a href="{{route('procurement.index')}}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>


                    </div> <!-- ROW -->

                    <div class="row">

                        @foreach($products as $product)
                            <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-store"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{$product->name}}</span>
                                    <h3 class="info-box-number" id="invoice_count">{{ $product->bags}}</h3>
                                    <sup style="font-size: 12px; float: left">bags</sup>
                                </div>

                            </div>

                        </div>
                        @endforeach


                    </div>




                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
