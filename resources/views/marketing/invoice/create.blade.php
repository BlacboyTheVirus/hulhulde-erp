@extends('adminlte::page')

@section('title', 'Invoice | Dashboard')

@section('content_header')
    <h1>Create Invoice</h1>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{route('marketing.invoice.store')}}" id="newform">
            @csrf


            <div class="row">
                    <div class="col-md-3">

                            <div class="card card-info card-outline">

                                <div class="card-header">
                                    <div class="card-title">
                                        <h5>New Invoice | {{ $data['new_code']  }}</h5>
                                    </div>
                                </div>


                                <div class="card-body">

                                    <input type="hidden" name="count_id" value="{{$data['count_id']}}" >
                                    <input type="hidden" name="code" value="{{$data['new_code']}}" >


                                    <div class="form-group">
                                        <label>Invoice Date:</label>
                                        <div class="input-group date" id="invoice-date" data-target-input="nearest">
                                            <input type="text" class="form-control " name="date" id="date" placeholder="invoice Date" value="" readonly required style="background: #fff !important">
                                            <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label>Customer <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-10 pr-0">
                                                <select class="form-control form-control-border" id="customer_id" name="customer_id" >
                                                    <option value='1' selected="selected">CU-0001 | Walk-In Customer</option>
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
                                        <label for="note" class="form-label">Invoice Note </label>
                                        <textarea  class="form-control" name="note" id="note" placeholder="Note" ></textarea>
                                    </div>



                                </div> <!-- Card body -->

                            </div>

                    </div>



                <div class="col-md-9">

                    <div class="card card-info card-outline">

                        <div class="card-header">
                            <div class="card-title">
                                <h5>Invoice Items for {{ $data['new_code']  }}</h5>
                            </div>
                        </div>


                        <div class="card-body">

                            <div id="product-list">
                                @foreach($products as $product)
                                    <button class="btn btn-app add-product"
                                            id="productbtn_{{ $product->id }}"
                                            data-id="{{ $product->id }}"
                                            data-name="{{$product->name}}"
                                            data-price="{{$product->price}}"
                                            data-bagweight={{ $product->bag_weight }}
                                    >
                                        <span class="badge bg-danger text-sm">{{$product->bags}}</span>
                                        <i class="fas fa-barcode"></i> {{$product->name}}
                                    </button>
                                @endforeach
                            </div>



                            <div class="card-body table-responsive p-0 mb-3">

                                <table class="table table-hover text-nowrap ">

                                    <thead>
                                    <tr>
                                        <th width="25%">Product Name</th>
                                        <th width="20%">Unit Price</th>
                                        <th width="20%">Quantity</th>
                                        <th width="25%">Amount</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody id="invoice-items" >

                                    </tbody>


                                </table>
                            </div>



                            <div class="row" id="summary">

                                <div class="order-1 order-sm-2 p-2 col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <table class="col-md-9">
                                                    <tbody>
                                                    <tr>
                                                        <th class="text-right" >Subtotal</th>
                                                        <th class="text-right" >
                                                            <b id="subtotal_txt">0.00</b>
                                                            <input type="hidden" name="subtotal" id="subtotal" value=0.00>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right">Discount</th>
                                                        <th class="text-right">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control form-control-border text-right text-red text-bold p-0 numonly" id="discount" placeholder="" value="0.00" onchange="calculateTotal()" name="discount" >
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr style=" border-bottom: 2px solid #dee2e6;">
                                                        <th class="text-right">Grand Total</th>
                                                        <th class="text-right" >
                                                            <h5><b id="grandtotal_txt">0.00</b></h5>
                                                            <input type="hidden" name="grandtotal" id="grandtotal" value=0.00>
                                                        </th>
                                                    </tr>

                                                    <tr style=" border-bottom: 2px solid #dee2e6;">
                                                        <th colspan="2" class="text-right">
                                                            <span id="inwords" style="font-size: 0.8rem; font-weight:500"></span>
                                                        </th>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="order-2 order-sm-1 p-2 col-md-6"></div>

                            </div>






                        </div> <!-- Card body -->

                        <div class="card-footer">
                            <button id="save" class="btn btn-primary" >Save</button>
                        </div>
                    </div>

                </div>


            </div>


        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

    <script>


        $(document).ready(function(){

            $('#product-list').on('click', '.add-product', function (e) {

                e.preventDefault();

                var available = $(this).find('.badge').text();
                if (available == 0){
                    return false;
                }

                var product_id = $(this).attr('data-id');
                var product_name = $(this).attr('data-name');
                var product_bag_weight = $(this).attr('data-bagweight');
                var product_price =  $(this).attr('data-price');

                // Check if a row with the same product_id[] value already exists
                var existingRow = $(`tr[data-product-id="${product_id}"]`);

                if (existingRow.length > 0) {
                    // If the row exists, increment the quantity
                    var quantityInput = existingRow.find('.qty');
                    var currentQuantity = parseInt(quantityInput.val()) || 0;

                    // confirm that the quantity added is not more than available
                    if (currentQuantity == available ) {
                        return false;
                    }

                    quantityInput.val(currentQuantity + 1);

                } else {
                    // If the row does not exist, create a new row
                    // var count = $('#invoice-items tr').length + 1;

                    // insert the input field
                    var product_item = `
                            <tr data-product-id="${product_id}" >
                                <td>
                                    <label id="product-name">${product_name}</label>
                                    <input type="hidden" class="product_id" value="${product_id}" name="product_id[]">
                                    <input type="hidden" class="product_bagweight" value="${product_bag_weight}" name="product_bagweight[]">
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">

                                        <label id="price_label">${product_price}</label>
                                        <input type="hidden" name="unit_price[]" class="form-control form-control-border unitprice" placeholder="Price" value="${product_price}" class="numonly" data-default="${product_price}" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="quantity[]" min="1" class="form-control form-control-border text-center qty" value="1" required style="background: #fff !important" onChange="calculateItem(${product_id})">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                         <label id="unit_amount_label" style="width: 100%; text-align: right">${product_price}</label>
                                        <input type="hidden" name="unit_amount[]" class="form-control form-control-border text-right itemtotal" value="${product_price}" readonly>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-sm" id="remove-item" onclick="removeItem(${product_id})"><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>
                        `;

                    $('#invoice-items').append(product_item);

                }

                calculateItem(product_id);
            });



            //SUBMIT FORM
            $('#save').click(function(e){
                e.preventDefault();

                // check if any item has been added to invoice
                var item_count = $('#invoice-items tr').length;
                if(item_count < 1){
                    sweetToast('',"You must add at least one item", 'error', true);
                    return false;
                }

                //submit
                var formData = $('#newform').serializeArray();

                $.ajax({
                    type: "POST",
                    url: '{{ route('marketing.invoice.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href =  response.lastid ;
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

///////////////////////////////////////////////////////////////

        function calculateItem(id){
            var row = $(`tr[data-product-id="${id}"]`);
            var item_price = row.find('input[name="unit_price[]"]').val();
            var item_quantity = row.find('input[name="quantity[]"]').val();
            var item_amount = (item_price * item_quantity);
            row.find('#unit_amount_label').html(item_amount.toLocaleString("en-US", {minimumFractionDigits:2}));
            row.find('input[name="unit_amount[]"]').val(item_amount.toFixed(2));

            calculateTotal();
        }


        function calculateTotal(){
            var discount = parseFloat($('#discount').val());
            $('#discount').val( discount );

            var subtotal = 0;
            var grandtotal = 0;

            // calculate subtotal
            $("table").find("input.itemtotal").each(function(){
                subtotal += parseFloat($(this).val());
            });
            grandtotal = parseFloat(subtotal) - parseFloat(discount);


            $('#subtotal').val(subtotal);
            $('#subtotal_txt').html(subtotal.toLocaleString("en-US", {minimumFractionDigits:2}));

            $('#grandtotal').val(grandtotal);
            $('#grandtotal_txt').html('₦ '+ grandtotal.toLocaleString("en-US", {minimumFractionDigits:2}));
            $('#inwords').html(numToWordsDec(grandtotal));
        }


        function removeItem(product_id) {
            var rowToRemove = $(`tr[data-product-id="${product_id}"]`);
            rowToRemove.remove();
            calculateTotal();
        }

        //Date picker
        $('#date').datepicker({
            format: "dd-mm-yyyy",
            toggleActive: false,
            autoclose: true,
            todayHighlight: true
        }).datepicker("setDate", new Date());


        $('#customer_id').select2({
            //minimumInputLength: 1,
            ajax: {
                url: "{{route('customers.getlist')}}",
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


    </script>


@stop
