@extends('adminlte::page')

@section('title', 'Warehouse | Dashboard')

@section('content_header')
    <h1>Create Warehouse Record</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-8">
                <form method="POST" action="{{route('procurement.warehouse.store')}}" id="newform">
                    @csrf
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add New | {{$data['new_code']}}</h5>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-3 col-sm-3 border-right">
                                    <div class="description-block">
                                        <span class="description-text">PROCUREMENT CODE</span>
                                        <h5 class="description-header">{{$data['procurement_code']}}</h5>

                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-3 border-right">
                                    <div class="description-block">
                                        <span class="description-text">SUPPLIER</span>
                                        <h5 class="description-header">{{$data['supplier']}}</h5>

                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-3 border-right">
                                    <div class="description-block">
                                        <span class="description-text">CONFIRMED BAGS</span>
                                        <h5 class="description-header">{{$data['confirmed_bags']}}</h5>

                                    </div>

                                </div>



                                <div class="col-md-3 col-sm-3 ">
                                    <div class="description-block">
                                        <span class="description-text">CONFIRMED WEIGHT (tons)</span>
                                        <h5 class="description-header">{{$data['confirmed_weight']}}</h5>
                                    </div>

                                </div>

                            </div>


                            <input type="hidden" id="count_id"  name="count_id"  value="{{$data['count_id']}}">
                            <input type="hidden" id="code"  name="code"  value="{{$data['new_code']}}">
                            <input type="hidden" id="procurement_id"  name="procurement_id"  value="{{$data['procurement_id']}}">


                            <div class="form-group">
                                <label>Receipt Date:</label>
                                <div class="input-group date" id="receipt-date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="receipt_date" id="receipt_date" placeholder="Receipt Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#receipt-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bags">Number of Bags Received</label>
                                <input type="number" class="form-control form-control-border" id="bags" name="bags" placeholder="Number of Bags Received" >
                            </div>

                            <div class="form-group">
                                <label for="bags">Total Weight Received</label>
                                <input type="number" class="form-control form-control-border" id="weight" name="weight" placeholder="Total Weight Received" >
                            </div>


                            <div class="form-group">
                                <label for="received_by">Received by</label>
                                <input type="text" class="form-control form-control-border" id="received_by" name="received_by" placeholder="Name of Store Keeper" >
                            </div>

                            <div class="form-group">
                                <label for="note">Remarks</label>
                                <textarea class="form-control form-control-border" id="note" name="note" placeholder="Remarks" ></textarea>
                            </div>


                        </div> <!-- Card body -->

                        <div class="card-footer">
                            <button id="save" class="btn btn-primary" >Save</button>
                        </div>
                    </div>
                </form>
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

            //VALIDATE FORM
            var formvalidator = $('#newform').validate({
                rules: {
                    received_date: {
                        required: true,
                    },
                    bags: {
                        required: true,
                        number: true
                    },
                    weight: {
                        required: true,
                        number: true
                    },
                    received_by: {
                        required: true,
                    },
                },
                messages: {

                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });


            //SUBMIT FORM
            $('#save').click(function(e){
                e.preventDefault();

                if(!formvalidator.form()){
                    return false;
                }

                //submit
                var formData = $('#newform').serializeArray();

                $.ajax({
                    type: "POST",
                    url: '{{ route('procurement.warehouse.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('procurement.warehouse.index')}}';
                            }, 1000);
                        } else {
                            sweetToast('', response.message, 'error', true);
                        }

                    },
                    error: function (response){
                        sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                    }

                });

            });

        }); // end document ready


        //Date picker
        $('#receipt_date').datepicker({
            format: "dd-mm-yyyy",
            toggleActive: false,
            autoclose: true,
            todayHighlight: true
        }).datepicker("setDate", new Date());



    </script>


@stop


{{--@section('plugins.Datatables', true)--}}
{{--@section('plugins.DatatablesPlugins', true)--}}
{{--@section('plugins.Sweetalert2', true)--}}
{{--@section('plugins.jQueryValidation', true)--}}
{{--@section('plugins.Select2', true)--}}
