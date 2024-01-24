@extends('adminlte::page')

@section('title', 'Security | Dashboard')

@section('content_header')
    <h1>Create Security Check</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-6">
                <form method="POST" action="{{route('procurement.security.store')}}" id="newform">
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
                                        <span class="description-text">EXPECTED BAGS</span>
                                        <h5 class="description-header">{{$data['expected_bags']}}</h5>

                                    </div>

                                </div>



{{--                                <div class="col-md-3 col-sm-3 ">--}}
{{--                                    <div class="description-block">--}}
{{--                                        <span class="description-text">EXPECTED WEIGHT (tons)</span>--}}
{{--                                        <h5 class="description-header">{{$data['expected_weight']}}</h5>--}}
{{--                                    </div>--}}

{{--                                </div>--}}

                            </div>


                            <input type="hidden" id="count_id"  name="count_id"  value="{{$data['count_id']}}">
                            <input type="hidden" id="code"  name="code"  value="{{$data['new_code']}}">
                            <input type="hidden" id="procurement_id"  name="procurement_id"  value="{{$data['procurement_id']}}">


                            <div class="form-group">
                                <label>Check-In Date:</label>
                                <div class="input-group date" id="checkin-date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="checkin_date" id="checkin_date" placeholder="Check-In Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#checkin-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="arrival_time">Arrival Time</label>
                                <input type="text" class="form-control form-control-border" id="arrival_time" name="arrival_time" placeholder="Arrival Time" >
                            </div>

                            <div class="form-group">
                                <label for="vehicle_no">Vehicle Number</label>
                                <input type="text" class="form-control form-control-border" id="vehicle_no" name="vehicle_no"  placeholder="Enter vehicle registration number">
                            </div>

                            <div class="form-group">
                                <label for="driver">Driver</label>
                                <input type="text" class="form-control form-control-border" id="driver" name="driver" placeholder="Driver's Name">
                            </div>

                            <div class="form-group">
                                <label for="bags">Number of Bags</label>
                                <input type="number" class="form-control form-control-border" id="bags" name="bags" placeholder="Number of Bags" >
                            </div>


                            <div class="form-group">
                                <label for="security">Checked by</label>
                                <input type="text" class="form-control form-control-border" id="security" name="security" placeholder="Name of Security" >
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
                    checkin_date: {
                        required: true,
                    },
                    vehicle_no: {
                        required: true,
                    },
                    driver: {
                        required: true,
                    },
                    bags: {
                        required: true,
                        number: true
                    },
                    arrival_time: {
                        required: true,
                     },
                    security: {
                        required: true,
                    },
                },
                messages: {
                    checkin_date: {
                        required: "Enter Date",
                    },
                    vehicle_no: {
                        required: "Enter Vehicle Number",
                    },
                    driver: {
                        required: "Enter Driver's name",
                    },
                    bags: {
                        required: "Enter Number of Bags",
                        number: "Enter number only"
                    },
                    arrival_time: {
                        required: "Enter arrival time",
                    },
                    security: {
                        required: "Enter Security Name",
                    },
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
                };

                //submit
                var formData = $('#newform').serializeArray();
                $.ajax({
                    type: "POST",
                    url: '{{ route('procurement.security.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('procurement.security.index')}}';
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
        $('#checkin_date').datepicker({
            format: "dd-mm-yyyy",
            toggleActive: false,
            autoclose: true,
            todayHighlight: true
        }).datepicker("setDate", new Date());

        $('#arrival_time').timepicker({
            timeFormat: 'h:mm:ss p',
            dropdown: false,
        });




    </script>


@stop

@section('plugins.Timepicker', true)
{{--@section('plugins.Datatables', true)--}}
{{--@section('plugins.DatatablesPlugins', true)--}}
{{--@section('plugins.Sweetalert2', true)--}}
{{--@section('plugins.jQueryValidation', true)--}}
{{--@section('plugins.Select2', true)--}}
