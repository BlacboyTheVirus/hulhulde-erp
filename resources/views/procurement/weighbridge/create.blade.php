@extends('adminlte::page')

@section('title', 'Weighbridge | Dashboard')

@section('content_header')
    <h1>Create Weighbridge Record</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-6">
                <form method="POST" action="{{route('procurement.weighbridge.store')}}" id="newform">
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
                                        <span class="description-text">EXP. BAGS</span>
                                        <h5 class="description-header">{{$data['expected_bags']}}</h5>

                                    </div>

                                </div>



                                <div class="col-md-3 col-sm-3 ">
                                    <div class="description-block">
                                        <span class="description-text">EXP. WEIGHT (tons)</span>
                                        <h5 class="description-header" id="exp_weight">{{$data['expected_weight']}}</h5>
                                    </div>

                                </div>

                            </div>


                            <input type="hidden" id="count_id"  name="count_id"  value="{{$data['count_id']}}">
                            <input type="hidden" id="code"  name="code"  value="{{$data['new_code']}}">
                            <input type="hidden" id="procurement_id"  name="procurement_id"  value="{{$data['procurement_id']}}">


                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>1st Weigh Date</label>
                                    <div class="input-group date" id="first_date" data-target-input="nearest">
                                        <input type="text" class="form-control " name="first_date" id="first_date" placeholder="Date" value="" readonly required style="background: #fff !important">
                                        <div class="input-group-append" data-target="#first_date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="first_time">1st Weigh Time</label>
                                    <input type="text" class="form-control form-control-border" id="first_time" name="first_time"  placeholder="Time">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="first_weight">1st Weight (tons)</label>
                                    <input type="number" class="form-control form-control-border" id="first_weight" name="first_weight" placeholder="1st Weight">
                                </div>
                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>2nd Weigh Date</label>
                                    <div class="input-group date" id="second_date" data-target-input="nearest">
                                        <input type="text" class="form-control " name="second_date" id="second_date" placeholder="Date" value="" readonly required style="background: #fff !important">
                                        <div class="input-group-append" data-target="#second_date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="second_time">2nd Weigh Time</label>
                                    <input type="text" class="form-control form-control-border" id="second_time" name="second_time"  placeholder="Time">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="second_weight">2nd Weight (tons)</label>
                                    <input type="number" class="form-control form-control-border" id="second_weight" name="second_weight" placeholder="2nd Weight">
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label for="weight">Weight (tons)</label>
                                    <input type="number" class="form-control form-control-border" id="weight" name="weight" placeholder="Weight in Tonnes" >
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="bags">Bags</label>
                                    <input type="number" class="form-control form-control-border" id="bags" name="bags"  placeholder="Number of Bags">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="operator">Scale Operator</label>
                                    <input type="text" class="form-control form-control-border" id="operator" name="operator" placeholder="Weighbridge Operator" >
                                </div>
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

            $.validator.addMethod("validFirstWeight", function(value, element) {
                return this.optional(element) || value > parseFloat($('#exp_weight').html());
            }, "Must be greater than expected weight");

            //VALIDATE FORM
            var formvalidator = $('#newform').validate({

                rules: {
                    first_date: {
                        required: true,
                    },
                    first_time: {
                        required: true,
                    },
                    first_weight: {
                        required: true,
                        validFirstWeight: true
                    },
                    weight: {
                        required: true,
                        number: true
                    },
                    operator: {
                        required: true,
                    },
                },
                messages: {
                    first_date: {
                        required: "Enter Date",
                    },
                    first_time: {
                        required: "Enter a proper time",
                    },
                    first_weight: {
                        required: "Enter weight in tonnes",
                    },
                    weight: {
                        required: "Enter the final weight",
                        number: "Enter number only"
                    },
                    operator: {
                        required: "Enter Weight operator's Name",
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
            $('#save').click(function(e) {
                e.preventDefault();

                if(!formvalidator.form()){
                    return false;
                }

                //submit
                var formData = $('#newform').serializeArray();
                $.ajax({
                    type: "POST",
                    url: '{{ route('procurement.weighbridge.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('procurement.weighbridge.index')}}';
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


        $("#first_weight").on("input", function(){
            $("#second_weight").val( ($('#first_weight').val() - $('#exp_weight').text()).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 }) );
            $("#weight").val($('#first_weight').val() - $('#second_weight').val());
        });

        $("#second_weight").on("input", function(){
            $("#weight").val(($('#first_weight').val() - $('#second_weight').val()).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 }));
        });

        //(Math.round($('#first_weight').val() - $('#exp_weight').val() * 100) / 100).toFixed(2)

        //Date picker
        $('#first_date, #second_date').datepicker({
            format: "dd-mm-yyyy",
            toggleActive: false,
            autoclose: true,
            todayHighlight: true,
        });

        $('#first_date, #second_date').datepicker("setDate", new Date());

        $('#first_time, #second_time').timepicker({
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
