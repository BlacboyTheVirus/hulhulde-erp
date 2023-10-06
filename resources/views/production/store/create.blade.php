@extends('adminlte::page')

@section('title', 'Store Receive | Dashboard')

@section('content_header')
    <h1>Receive Products</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-6">
                <form method="POST" action="{{route('production.store.store')}}" id="newform">
                    @csrf
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add Outputs for {{ $data['production_code']  }}</h5>
                            </div>
                        </div>


                        <div class="card-body">


                            <div class="row mb-3">

                                @foreach($millout as $milled)
                                <div class="col-md-3 col-sm-3 border-right">
                                    <div class="description-block">
                                        <span class="description-text">{{$milled->name}}</span>
                                        <h5 class="description-header">{{$milled->bags}}</h5>
                                    </div>
                                </div>
                                @endforeach



                            </div>


                            <input type="hidden" name="production_id" value="{{$data['production_id']}}" >

                            <div id="output-list">
                                @foreach($outputs as $output)
                                    <a class="btn btn-app add-output" id="outputbtn_{{ $output->id }}" data-id="{{ $output->id }}"  data-name="{{$output->name}}" data-bagweight="{{$output->bag_weight}}">
                                        <i class="fas fa-barcode"></i> {{$output->name}}
                                    </a>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="received-date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="received_date" id="received_date" placeholder="Received Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#received-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <label style="font-weight: bold; margin-bottom: 0rem !important;">No. of Bags</label>

                            <div id="output-items" >

                            </div>


                            <div class="form-group">
                                <label for="requested_weight" class="form-label">Received by <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="received_by" id="received_by" placeholder="Received by" value="">
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




            var count=0;

            $('#output-list').on('click', '.add-output', function (e) {

                e.preventDefault();
                count++;
                console.log (count);
                var output_id = $(this).attr('data-id');
                var output_name = $(this).attr('data-name');
                var output_bag_weight = $(this).attr('data-bagweight');


                // insert the input field
                var output_item = `

                            <div class="form-group outlist" id="output_${output_id}">
                                    <div class="input-group"  >

                                    <div class="input-group-prepend" >
                                        <div class="input-group-text">${output_name}</div>
                                    </div>

                                    <input type="number" class="form-control col-md-12" name="bags[]" id="bags_${count}" placeholder="Bags" value="" required>

<input type="hidden"  name="bag_weight[]"  value="${output_bag_weight}"  id="bag_weight_${output_id}" >

                                    <input type="hidden"  name="output_id[]"   value="${output_id}" >

                                    <div class="input-group-append"  >
                                        <a class="btn btn-danger btn-sm remove-output"  onclick="removeoutput(${output_id})"><i class="fa fa-times"></i></a>
                                    </div>

                                </div>
                            </div>
                `;

                $('#output-items').append(output_item);

                //disable button
                //$(this).prop('disabled', true);
                $(this).hide();
            });



            //VALIDATE FORM
            var formvalidator = $('#newform').validate({
                rules: {

                    weight: {
                        required: true,
                        number: true,
                    },

                },
                messages: {

                    weight: {
                        required: "Please enter expected weight in tonnes",
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

                var errors = false;

                $("[name^=bags]").each(function(){
                    if($(this).val() === ''){
                        $(this).addClass('is-invalid');
                        // $(this).closest('.form-group').find('span').remove();
                        // $(this).closest('.form-group').append('<span class="text-sm text-danger">Please fill</span>');
                        errors = true;
                       }
                });

                 if(errors) {
                     sweetToast('', 'Sorry, you must fill all field marked red.', 'error', true);
                     return false;
                 }


                 if(!formvalidator.form()) {
                     return false;
                 }

                // check if any item has been added
                var count = $('.outlist ').length;
                if(count < 1){
                    sweetToast('', 'Sorry, you must add at least a product.', 'error', true);
                    return false;
                }

                //submit
                var formData = $('#newform').serializeArray();

                $.ajax({
                    type: "POST",
                    url: '{{ route('production.store.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('production.store.index')}}';
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


        function removeoutput(id){
            //$('.add-output[data-id="'+id+'"]').prop('disabled', false);
            $('.add-output[data-id="'+id+'"]').show();
            $('#output_'+id).remove();

        }


        //Date picker
        $('#received_date').datepicker({
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
