@extends('adminlte::page')

@section('title', 'Payments  | Dashboard')

@section('content_header')
    <h1>Payments for Procurement {{$data['procurement_code']}}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-3">
                <form method="POST" action="{{route('procurement.payment.store')}}" id="newform">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add New | {{ $data['new_code']}} </h5>
                            </div>
                        </div>

                        <input type="hidden" id="count_id"  name="count_id"  value={{$data['count_id']}}>
                        <input type="hidden" id="code"  name="code"  value="{{$data['new_code']}}">
                        <input type="hidden" id="procurement_id"  name="procurement_id"  value="{{$data['procurement_id']}}">


                        <div class="card-body">
                            <div class="form-group">
                                <label>Payment Date</label>
                                <div class="input-group date" id="payment_date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="payment_date" id="payment_date" placeholder="Payment  Date" value="" readonly required >
                                    <div class="input-group-append" data-target="#payment_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_type" class="form-label">Payment Type</label>
                                <select class="form-control select2" id="payment_type" data-placeholder="Payment Type" name="payment_type">
                                    <option value="">--Select Payment Type--</option>
                                    @foreach(\App\Enums\PaymentType::getValues() as $payment_type)
                                        <option value="{{$payment_type}}">{{ucfirst($payment_type)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter amount" >
                            </div>
                            <div class="form-group">
                                <label for="note">Remarks</label>
                                <textarea class="form-control form-control-border" id="note" name="note" placeholder="Remarks" ></textarea>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button id="save" class="btn btn-primary" >Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--DataTable-->
                        <div class="table-responsive">
                            <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                                <thead>
                                    <tr>
                                        <th>Payment Code</th>
                                        <th>Amount</th>
                                        <th>Payment Type</th>
                                        <th>Payment Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>

                                <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .select2-container--default .select2-selection--single{
            background: none !important;
        }

        .form-control:disabled, .form-control[readonly] {
            background: none !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(function (){
            $('#payment_type').select2();
        });

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })


        $(document).ready(function(){
            //validate
            var formvalidator = $('#newform').validate({
                rules: {
                    payment_type: {
                        required: true,
                    },
                    amount: {
                        required: true,
                        number: true
                    },
                    note: {
                        required: true,
                    },
                },
                messages: {
                    payment_type: {
                        required: "Please select a payment type."
                    },
                    amount: {
                        required: "Please enter a valid amount."
                    },
                    note: {
                        required: "Please further details concerning this payment."
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

            //submit form

            $('#save').click(function(e){
                e.preventDefault();

                if(!formvalidator.form()){
                    return false;
                }

                //submit
                var formData = $('#newform').serializeArray();

                $.ajax({
                    type: "POST",
                    url: '{{ route('procurement.payment.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            $("#tblData").DataTable().ajax.reload();
                            $('#newform').trigger('reset');
                            sweetToast('', response.message, 'success', true);
                        } else {
                            sweetToast('', response.message, 'error', true);
                        }
                    },
                    error: function (response){
                        sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                    }

                });

            });




            // DATATABLE

            var table = $('#tblData').DataTable({
                reponsive:true, processing:true, serverSide:true, autoWidth:false,
                ajax:"{{route('procurement.payment.index',['procurement_id'=>$data['procurement_id']] )}}",
                columns:[
                    {data:'code', name:'code'},
                    {data:'amount', name:'amount',
                        "render": function ( data, type, row, meta ) {
                            return ( parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2}) );
                        }
                    },
                    {data:'payment_type', name:'payment_type'},
                    {data:'payment_date', name:'payment_date'},
                    {data:'action', name:'action', bSortable:false, className:"text-center"},

                ],
                order:[[0, "asc"]],

                drawCallback: function (json) {
                    var api = this.api();
                    var sum = 0;
                    var formated = 0;
                    //to show first th
                    $(api.column(0).footer()).html('Total');

                    sum = api.column(1, {page:'current'}).data().sum();
                    //to format this sum
                    unformated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:0});
                    formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:2});
                    $(api.column(1).footer()).html('₦ '+ formated);

                    // $('#invoice_amount').html(unformated);

                   // $('#invoice_count').html(table.data().count())



                },
            });



            $('body').on('click', '#btnDel', function(){
                //confirmation
                var id = $(this).data('id');
                if(confirm('Delete Data '+id+'?')==true)
                {
                    var route = "{{route('users.destroy', ':id')}}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url:route,
                        type:"delete",
                        success:function(response){
                            console.log(response);
                            if (response.success){
                                $("#tblData").DataTable().ajax.reload();
                                sweetToast('', response.message, 'success', true);
                            } else {
                                sweetToast('', response.message, 'error', true);
                            }
                        },
                        error:function(response){
                            sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                        }
                    });
                }else{
                    //do nothing
                }
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            //Date picker
            $('#payment_date').datepicker({
                format: "dd-mm-yyyy",
                toggleActive: false,
                autoclose: true,
                todayHighlight: true
            }).datepicker("setDate", new Date());

        }); // end document ready

    </script>
@stop


{{--@section('plugins.Datatables', true)--}}
{{--@section('plugins.Sweetalert2', true)--}}
{{--@section('plugins.jQueryValidation', true)--}}
{{--@section('plugins.Select2', true)--}}
