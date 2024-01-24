@extends('adminlte::page')

@section('title', 'Store Release Product | Dashboard')

@section('content_header')
    <h1>Store Release Product</h1>
@stop

@section('content')

        <div class="row">
            <div id="errorBox"></div>
            <div class="col-md-6">
                <form method="POST" action="{{route('marketing.store.store')}}" id="newform">
                    @csrf
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Release Invoice Good for {{ $data['invoice_code']  }}</h5>
                            </div>
                        </div>


                        <div class="card-body">

                            <div class="row mb-3">

                                @foreach($invoice->invoiceitems as $invoiceitem)
                                <div class="col-md-3 col-sm-3 border-right">
                                    <div class="description-block">
                                        <span class="description-text">{{$invoiceitem->product->name}}</span>
                                        <h5 id="description-header_{{$invoiceitem->product->id}}">{{$invoiceitem->quantity_left}}</h5>
                                    </div>
                                </div>
                                @endforeach



                            </div>


                            <input type="hidden" name="invoice_id" value="{{$data['invoice_id']}}" >


                            <div id="product-list">
                                @foreach($products as $product)
                                    @if ($invoice->invoiceitems->contains('product_id', $product->id))
                                    <button class="btn btn-app add-product"
                                            id="productbtn_{{ $product->id }}"
                                            data-id="{{ $product->id }}"
                                            data-name="{{$product->name}}"
                                            data-price="{{$product->price}}"
                                            data-available="{{$product->bags}}"
                                            data-bagweight={{ $product->bag_weight }}

                                    >
                                        <span class="badge bg-danger text-sm">{{$product->bags}}</span>
                                        <i class="fas fa-barcode"></i> {{$product->name}}
                                    </button>
                                    @endif
                                @endforeach
                            </div>


                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="released-date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="released_date" id="released_date" placeholder="Release Date" value="" readonly required style="background: #fff !important">
                                    <div class="input-group-append" data-target="#released-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <label style="font-weight: bold; margin-bottom: 0rem !important;">No. of Bags</label>

                            <div id="output-items" >

                            </div>


                            <div class="form-group">
                                <label for="released_by" class="form-label">Released by <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="released_by" id="released_by" placeholder="Released by" value="">
                            </div>

                        </div> <!-- Card body -->

                        <div class="card-footer">
                            <button id="save" class="btn btn-primary" >Save</button>
                        </div>
                    </div>
                </form>
            </div>








<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h5>Products Release History</h5>
            </div>
        </div>
        <div class="card-body">
            <!--DataTable-->
            <div class="table-responsive">
                <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Quantity Released</th>
                        <th>Released by</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($releases as $release)


                        <tr>
                            <td>{{\Carbon\Carbon::parse($release->released_date)->format('d-M-Y')}}</td>
                            <td>{{\App\Models\Product::where('id','=', $release->product_id)->value('name')}}</td>
                            <td>{{$release->quantity}}</td>
                            <td>{{$release->released_by}}</td>
                        </tr>

                    @endforeach

                    </tbody>


                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th>{{$releases->sum('quantity')}}</th>
                        <th></th>

                    </tr>
                    </tfoot>


                </table>
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


            var count=0;

            $('#product-list').on('click', '.add-product', function (e) {

                e.preventDefault();
                count++;
                console.log (count);
                var output_id = $(this).attr('data-id');
                var output_name = $(this).attr('data-name');
                var output_bag_weight = $(this).attr('data-bagweight');
                var available = $(this).attr('data-available');
                var quantity_left = $('#description-header_'+ output_id).text();



                // insert the input field
                var output_item = `

                            <div class="form-group outlist" id="product_${output_id}" >
                                    <div class="input-group"  >

                                    <div class="input-group-prepend" >
                                        <div class="input-group-text">${output_name}</div>
                                    </div>

                                    <input type="number" class="form-control col-md-12" name="quantity[]" id="quantity_${count}" placeholder="Bags" value="" required data-available=${available} data-product= "${output_id}" max=${quantity_left}  min=1>

<input type="hidden"  name="bag_weight[]"  value=${output_bag_weight}  id="bag_weight_${output_id}" >

                                    <input type="hidden"  name="product_id[]"   value="${output_id}" >

                                    <div class="input-group-append"  >
                                        <a class="btn btn-danger btn-sm remove-output"  onclick="removeoutput(${output_id})"><i class="fa fa-times"></i></a>
                                    </div>

                                </div>
                            </div>
                `;


                if (quantity_left > 0){
                    $('#output-items').append(output_item);

                    //disable button
                    //$(this).prop('disabled', true);
                    $(this).hide();
                }

            });



            //VALIDATE FORM
            var formvalidator = $('#newform').validate({
                rules: {

                    released_by: {
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

                var errors = false;

                $("[name^=bags]").each(function(){
                    if( ($(this).val() === '')
                        || ($(this).val() === '0')
                        || ($(this).val() > $(this).attr('data-available'))
                    ){
                        $(this).addClass('is-invalid');
                        errors = true;
                    }

                    var product = $(this).attr('data-product')

                    if( ($(this).val() >  $('#description-header_'+ product).text()   ) ){
                        $(this).addClass('is-invalid');
                        errors = true;
                    }



                });



                 if(errors) {
                     sweetToast('', 'Sorry, invalid value in field marked red.', 'error', true);
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
                    url: '{{ route('marketing.store.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{route('marketing.store.index')}}';
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
            $('.add-product[data-id="'+id+'"]').show();
            $('#product_'+id).remove();

        }


        //Date picker
        $('#released_date').datepicker({
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
