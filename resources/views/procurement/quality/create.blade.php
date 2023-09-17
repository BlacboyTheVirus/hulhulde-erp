@extends('adminlte::page')

@section('title', 'Quality | Dashboard')

@section('content_header')
    <h1>Create Quality Record</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-8">
                <form method="POST" action="{{route('procurement.quality.store')}}" id="newform">
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
                                <label>Analysis Date</label>
                                <div class="input-group date" id="analysis_date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="analysis_date" id="analysis_date" placeholder="Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#analysis_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">

                                <div class="form-group  col-md-4">
                                    <label for="moisture">Moisture %</label>
                                    <input type="number" class="form-control form-control-border" id="moisture" name="moisture"  placeholder="Moisture %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="broken">Broken %</label>
                                    <input type="number" class="form-control form-control-border" id="broken" name="broken"  placeholder="Broken %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="crackness">Crackness %</label>
                                    <input type="number" class="form-control form-control-border" id="crackness" name="crackness" placeholder="Crackness %">
                                </div>

                            </div>



                            <div class="form-row">

                                <div class="form-group  col-md-4">
                                    <label for="immature">Immature %</label>
                                    <input type="number" class="form-control form-control-border" id="immature" name="immature"  placeholder="Immature %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="red_grain">Red Grain %</label>
                                    <input type="number" class="form-control form-control-border" id="red_grain" name="red_grain"  placeholder="Red grain %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="green_grain">Green grain %</label>
                                    <input type="number" class="form-control form-control-border" id="green_grain" name="green_grain" placeholder="Green grain %">
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group  col-md-4">
                                    <label for="yellow_grain">Yellow Grain %</label>
                                    <input type="number" class="form-control form-control-border" id="yellow_grain" name="yellow_grain"  placeholder="Yellow Grain %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="discolour">Discolour %</label>
                                    <input type="number" class="form-control form-control-border" id="discolour" name="discolour"  placeholder="Discolour %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="short_grain">Short grain %</label>
                                    <input type="number" class="form-control form-control-border" id="short_grain" name="short_grain" placeholder="Short grain %">
                                </div>

                            </div>



                            <div class="form-row">

                                <div class="form-group  col-md-4">
                                    <label for="paddy_length">Paddy Length</label>
                                    <input type="number" class="form-control form-control-border" id="paddy_length" name="paddy_length"  placeholder="Paddy Length ">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="bran_length">Bran Length</label>
                                    <input type="number" class="form-control form-control-border" id="bran_length" name="bran_length"  placeholder="Bran Length">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="milled_length">Milled Length</label>
                                    <input type="number" class="form-control form-control-border" id="milled_length" name="milled_length" placeholder="Milled Length">
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group  col-md-4">
                                    <label for="impurity">Impurities</label>
                                    <input type="number" class="form-control form-control-border" id="impurity" name="impurity"  placeholder="Impurities %">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="rejected_bags">Rejected Bags </label>
                                    <input type="number" class="form-control form-control-border" id="rejected_bags" name="rejected_bags"  placeholder="Rejected Bags">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="rejected_weight">Weight of Rejected </label>
                                    <input type="number" class="form-control form-control-border" id="rejected_weight" name="rejected_weight"  placeholder="Weight of Rejected">
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group  col-md-4">
                                    <label for="recommended_price">Recommended Price/ton (N)</label>
                                    <input type="number" class="form-control form-control-border" id="recommended_price" name="recommended_price" placeholder="Recommended Price/ton">
                                </div>

                                <div class="form-group  col-md-4">
                                    <label for="analyst">Name of Analyst</label>
                                    <input type="text" class="form-control form-control-border" id="analyst" name="analyst" placeholder="Analyst">
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

            //VALIDATE FORM
            var formvalidator = $('#newform').validate({
                rules: {
                    analysis_date: {
                        required: true,
                    },
                    moisture: {
                        required: true,
                        number: true
                    },
                    broken: {
                        required: true,
                        number: true
                    },
                    crackness: {
                        required: true,
                        number: true
                    },
                    immature: {
                        required: true,
                        number: true
                    },
                    recommended_price: {
                        required: true,
                        number: true
                    },
                    analyst: {
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
                    url: '{{ route('procurement.quality.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('procurement.quality.index')}}';
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
        $('#analysis_date').datepicker({
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
