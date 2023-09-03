@extends('adminlte::page')

@section('title', 'Procurements | Dashboard')

@section('content_header')
    <h1>Create New Procurement</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-6">
                <form method="POST" action="{{route('procurement.store')}}" id="newform">
                    @csrf
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add New | {{$data['new_code']}}</h5>
                            </div>
                        </div>
                        <div class="card-body">

                            <input type="hidden" name="count_id" value="{{$data['count_id']}}" >
                            <input type="hidden" name="code" value="{{$data['new_code']}}" >


                            <div class="form-group">
                                <label>Item</label>
                                <select class="form-control" id="input_id" name="input_id" >
                                    @foreach ($inputs as $input )
                                        <option value={{$input->id}} >{{$input->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="procurement-date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="procurement_date" id="procurement_date" placeholder="Procurement Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#procurement-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Supplier <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-10 pr-0">
                                        <select class="form-control form-control-border" id="supplier_id" name="supplier_id" >
                                            <option value='' selected="selected">- Select Supplier -</option>
                                        </select>
                                    </div>

                                    <div class="col-2">
                                        <a class="btn btn-block btn-primary btn-sm p-1" id="add-button" href="">
                                            <i class="fa fa-user-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="expected_weight" class="form-label">Expected Weight <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="expected_weight" id="expected_weight" placeholder="Expected Weight (tonnes)" value="">
                            </div>

                            <div class="form-group">
                                <label for="expected_bags" class="form-label">Expected Bags <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="expected_bags" id="expected_bags" placeholder="Expected bags " value="">
                            </div>

                            <div class="form-group">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location" id="location" placeholder="Location " value="">
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
                    supplier_id: {
                        required: true,
                    },
                    expected_weight: {
                        required: true,
                        number: true,
                    },
                    expected_bags: {
                        required: true,
                        number: true
                    },
                    location: {
                        required: true,
                    },
                },
                messages: {
                    supplier_id: {
                        required: "Please Select a Supplier"
                    },
                    expected_weight: {
                        required: "Please enter expected weight in tonnes",
                        number: "Please enter only number"
                    },
                    expected_bags: {
                        required: "Please enter expected number of bags",
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
                    url: '{{ route('procurement.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('procurement.index')}}';
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

        $('#supplier_id').select2({
            //minimumInputLength: 1,
            ajax: {
                url: "{{route('suppliers.getlist')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        //Date picker
        $('#procurement_date').datepicker({
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
