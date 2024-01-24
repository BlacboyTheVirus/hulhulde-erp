@extends('adminlte::page')

@section('title', 'Procurements | Dashboard')

@section('content_header')
    <h1>Procurements</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>


            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-md-12 ">Procurement List</div>

                                <div class="card-tools">
                                    <div class="btn-group ">

                                        <a href="{{ route('procurement.create') }}" class="btn btn-success" > <i class="fa fa-cart-plus nav-icon"></i> Create New</a>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--DataTable-->
                        <div class="table-responsive">
                            <table id="tblData" class="table table-hover dataTable dt-responsive  dtr-inline">
                                <thead>
                                    <tr>
                                        <th class="exportable">Code</th>
                                        <th class="exportable">Name</th>
                                        <th class="exportable">Item</th>
                                        <th class="exportable">Date</th>
                                        <th class="exportable">Bags</th>
                                        <th class="exportable">Weight (ton)</th>
                                        <th class="exportable">Location</th>
                                        <th class="exportable">Status</th>
                                        <th class="exportable">Approval</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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


    <!-- STATUS MODAL -->
    <div class="modal hide fade" tabindex="-1" id="modal-approval">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Approval  <span id="procurement_code_label" class="text-info"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <form action = "{{ route('procurement.approval.update') }}" id="approval_form" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="id"  name="id"  value="">
                        <input type="hidden" id="procurement_id"  name="procurement_id"  value="">

                        <div class="form-group">
                            <label for="approval_date">Approval Date:</label>
                            <div class="input-group date" id="approval_date" data-target-input="nearest">
                                <input type="text" class="form-control " name="approval_date" id="approval_date" placeholder="Approval Date" value="" readonly required style="background: #fff !important">
                                <div class="input-group-append" data-target="#approval_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="approved_by">Approved Price/ton</label>
                            <input type="text" class="form-control form-control-border" id="approved_price" name="approved_price"  placeholder="Approved Price" value="">
                        </div>

                        <div class="form-group">
                            <label for="approved_by">Approval by </label>
                            <input type="text" class="form-control form-control-border basicAutoComplete" id="approved_by" name="approved_by"  placeholder="Approved By">
                        </div>



                        <div class="form-group">
                            <label>Approval Status </label>
                            <div class="icheck-primary">
                                <input type="checkbox" id="status" name="status" value="approved" />
                                <label for="status" id="approval_label">Pending</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea class="form-control form-control-border" id="note"  name="note" placeholder="Remarks"></textarea>
                        </div>


                    </form>

                </div>

                <div class="modal-footer justify-content-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="approvalSave" >Save changes</button>
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

            $('#status').on('change', function(){
                $('#approval_label').html(( $('#status').is(':checked') ? "Approved" : "Pending"));
            })

            // DATATABLE
            var table = $('#tblData').DataTable({
                reponsive:true, processing:true, serverSide:true, autoWidth:false, info:true,
                ajax:"{{route('procurement.index')}}",
                columns:[
                    {data:'code', name:'code'},
                    {data:'supplier', name:'supplier.name'},
                    {data:'input', name:'input.name'},
                    {data:'procurement_date', name:'procurement_date'},
                    {data:'expected_bags', name:'expected_bags'},
                    {data:'expected_weight', name:'expected_weight'},
                    {data:'location', name:'location'},
                    {data:'status', name:'status'},
                    {data:'approval', name:'approval'},
                    {data:'action', name:'action', bSortable:false, className:"text-center"},
                ],
                order:[[0, "desc"]],
                buttons: [
                    {extend: "copy", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                    {extend: "csv", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                    {extend: "excel", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                    {extend: "pdfHtml5", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                    {extend: 'print', footer:true, exportOptions: {columns: [ '.exportable' ]} },
                    "colvis"
                ],
                dom: '<"row" <"col-md-3"l> <"#top.col-md-6">  <"col-md-3"f> > rt <"row"  <"col-md-6"i> <"col-md-6"p> ><"clear">',
                "initComplete": function(settings, json) {
                    $(this).DataTable().buttons().container()
                        .appendTo( ('#top'));
                },

                drawCallback: function (json) {
                    var api = this.api();
                    var sum = 0;
                    var formated = 0;
                    //to show first th
                    $(api.column(3).footer()).html('Total');

                    sum = api.column(4, {page:'current'}).data().sum();
                    //to format this sum
                    unformated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:0});
                    formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:0});
                    $(api.column(4).footer()).html(formated);


                    sum = api.column(5, {page:'current'}).data().sum();
                    //to format this sum
                    unformated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:0});
                    formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:2});
                    $(api.column(5).footer()).html( formated);

                    // $('#invoice_amount').html(unformated);

                    // $('#invoice_count').html(table.data().count())



                },



            });


            //DELETE BUTTON CLICKED
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




            //APPROVAL BUTTON CLICKED
            $('body').on('click', '.btnApproval', function(e){
                //confirmation
                e.preventDefault();
                var id = $(this).data('id');

                var route = "{{ route('procurement.approval.edit', ':id') }}" ;
                route = route.replace(':id', id);

                $.ajax({
                    type: "GET",
                    url: route,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        $('#id').val(response.id);
                        $('#procurement_id').val(response.procurement_id);
                        $('#approved_by').val(response.approved_by);
                        $('#approved_price').val(response.price);
                        $('#status').prop('checked', response.status);
                        $('#approval_label').html(response.status?"Approved" : "Pending");
                        $('#note').val(response.note);
                        $('#modal-approval').modal('show');
                    },
                    error: function (response){
                        sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                    }

                });


            });


            //VALIDATE FORM
            var formvalidator = $('#approval_form').validate({
                rules: {
                    approved_by: {
                        required: "#approval_status:checked"
                    },
                },
                messages: {
                    approved_by: {
                        required: "Please enter the Name of the Approver"
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

            //SUBMIT APPROVAL FORM
            $('#approvalSave').click(function(e){
                e.preventDefault();

                if(!formvalidator.form()){
                    return false;
                };

                //submit
                var formData = $('#approval_form').serializeArray();

                $.ajax({
                    type: "PUT",
                    url: '{{ route('procurement.approval.update') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                        if (response.success){
                            sweetToast('', response.message, 'success', true);
                            $("#tblData").DataTable().ajax.reload();
                            $('#modal-approval').modal('hide');
                        } else {
                            sweetToast('', response.message, 'error', true);
                        }

                    },
                    error: function (response){
                        sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                    }

                });

            });


            $('#approval_date').datepicker({
                format: "dd-mm-yyyy",
                toggleActive: false,
                autoclose: true,
                todayHighlight: true
            }).datepicker("setDate", new Date());


        }); // end document ready





    </script>


@stop


{{-- @section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)
@section('plugins.Select2', true) --}}
