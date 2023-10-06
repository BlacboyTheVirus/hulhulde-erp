@extends('adminlte::page')

@section('title', 'Warehouse | Dashboard')

@section('content_header')
    <h1>Create New Warehouse Record</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-6">
                <form method="POST" action="{{route('production.warehouse.store')}}" id="newform">
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
                                        <span class="description-text">PRODUCTION CODE</span>
                                        <h5 class="description-header">{{$data['production_code']}}</h5>

                                    </div>

                                </div>


                                <div class="col-md-3 col-sm-3 ">
                                    <div class="description-block">
                                        <span class="description-text">REQUESTED WEIGHT (tons)</span>
                                        <h5 class="description-header">{{$data['requested_weight']}}</h5>
                                    </div>

                                </div>

                            </div>

                            <input type="hidden" name="count_id" value="{{$data['count_id']}}" >
                            <input type="hidden" name="code" value="{{$data['new_code']}}" >
                            <input type="hidden" id="production_id"  name="production_id"  value="{{$data['production_id']}}">

                            <div class="form-group">
                                <label>Release Date</label>
                                <div class="input-group date" id="release-date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="release_date" id="release_date" placeholder="Release Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#release-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="weight" class="form-label">Released Weight <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="weight" id="weight" placeholder="Released Weight (tonnes)" value="">
                            </div>


                            <div class="form-group">
                                <label for="released_by" class="form-label">Released By <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="released_by" id="released_by" placeholder="Released By" value="">
                            </div>



                            <div class="form-group">
                                <label for="note" class="form-label">Notes </label>
                                <textarea  class="form-control" name="note" id="note" placeholder="Notes" ></textarea>
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

                    weight: {
                        required: true,
                        number: true,
                    },
                    released_by: {
                        required: true,
                    },
                    note: {
                        required: true,
                    },

                },
                messages: {

                    weight: {
                        required: "Please enter released weight in tonnes",
                        number: "Please enter only number"
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
                    url: '{{ route('production.warehouse.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('production.warehouse.index')}}';
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
        $('#release_date').datepicker({
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
