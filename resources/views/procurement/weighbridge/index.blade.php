@extends('adminlte::page')

@section('title', 'Weighbridge | Dashboard')

@section('content_header')
    <h1>Weighbridge</h1>
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
{{--                                    <th class="exportable">Weight (ton)</th>--}}
                                    <th class="exportable">Location</th>
                                    <th class="exportable">Status</th>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

    <script>
        $(document).ready(function(){

            // DATATABLE
            var table = $('#tblData').DataTable({
                reponsive:true, processing:true, serverSide:true, autoWidth:false, info:true,
                ajax:"{{ route('procurement.weighbridge.index') }}",
                columns:[
                    {data:'code', name:'code'},
                    {data:'supplier', name:'supplier.name'},
                    {data:'input', name:'input.name'},
                    {data:'procurement_date', name:'procurement_date'},
                    {data:'expected_bags', name:'expected_bags'},
                    // {data:'expected_weight', name:'expected_weight'},
                    {data:'location', name:'location'},
                    {data:'status', name:'status'},
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


        }); // end document ready





    </script>


@stop


{{-- @section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)
@section('plugins.Select2', true) --}}
