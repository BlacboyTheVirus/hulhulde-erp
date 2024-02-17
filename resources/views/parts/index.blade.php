@extends('adminlte::page')

@section('title', 'Parts  | Dashboard')

@section('content_header')
    <h1>Enter Details for New Parts {{$data['new_code']}}</h1>
@stop

@section('content')
    <div class="container-fluid">





        <div class="row">
            <div id="errorBox"></div>
            <div class="col-3">
                <form method="POST" action="{{route('parts.store')}}" id="newform">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add New | {{ $data['new_code']}} </h5>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $data['count_id']}}" name="count_id" >
                        <input type="hidden" value="{{ $data['new_code']}}" name="code" >

                        <div class="card-body">

                            <div class="form-group">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                       placeholder="Enter Part Name">
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Description </label>
                                <input type="text" class="form-control" name="description" id="description"
                                       placeholder="Enter Description">
                            </div>


                            <div class="form-group">
                                <label for="quantity" class="form-label">Initial Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="quantity" id="quantity"
                                       placeholder="Enter Initial Qunatity" min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="unit" id="unit"
                                       placeholder="Pieces, Ltrs, Bags, Kg, Dozens etc)">
                            </div>

                            <div class="form-group">
                                <label for="restock_level" class="form-label">Restock Level <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="restock_level" id="restock_level"
                                       placeholder="Enter Initial Qunatity" min="1" value="1">
                            </div>

                            <div class="form-group">
                                <label for="note">Remarks</label>
                                <textarea class="form-control form-control-border" id="note" name="note"
                                          placeholder="Remarks"></textarea>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button id="save" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>Parts List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--DataTable-->
                        <div class="table-responsive">
                            <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>Parts Code</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Restock Level</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>

                                <tfoot>
                                <tr>
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
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .select2-container--default .select2-selection--single {
            background: none !important;
        }

        .form-control:disabled, .form-control[readonly] {
            background: none !important;
        }
    </style>
@stop

@section('js')
    <script>


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })


        $(document).ready(function () {
            //validate
            var formvalidator = $('#newform').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    unit: {
                        required: true,
                    },
                    quantity: {
                        required: true,
                        number: true,

                    },
                    restock_level: {
                        required: true,
                        number: true,

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

            //submit form

            $('#save').click(function (e) {
                e.preventDefault();


                if (!formvalidator.form()) {
                    return false;
                }


                //submit
                var formData = $('#newform').serializeArray();

                $.ajax({
                    type: "POST",
                    url: '{{ route('parts.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success: function (response) {
                        if (response.success) {
                            // $("#tblData").DataTable().ajax.reload();
                            // $('#newform').trigger('reset');
                            sweetToast('', response.message, 'success', true);
                            setTimeout(() => {
                                window.location.href = '{{url()->full()}}';
                            }, 1000);

                        } else {
                            sweetToast('', response.message, 'error', true);
                        }
                    },
                    error: function (response) {
                        sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                    }

                });

            });


            // DATATABLE

            var table = $('#tblData').DataTable({
                reponsive: true, processing: true, serverSide: true, autoWidth: false,
                ajax: "{{route('parts.index' )}}",
                columns: [
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'unit', name: 'unit'},
                    {data: 'restock_level', name: 'restock_level'},
                    {data: 'action', name: 'action', bSortable: false, className: "text-center"},

                ],
                order: [[0, "asc"]],

                // drawCallback: function (json) {
                //     var api = this.api();
                //     var sum = 0;
                //     var formated = 0;
                //     //to show first th
                //     $(api.column(0).footer()).html('Total');
                //
                //     sum = api.column(1, {page: 'current'}).data().sum();
                //     //to format this sum
                //     unformated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits: 0});
                //     formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits: 2});
                //     $(api.column(1).footer()).html('₦ ' + formated);
                //
                //     // $('#invoice_amount').html(unformated);
                //
                //     // $('#invoice_count').html(table.data().count())
                //
                //
                // },
            });


            $('body').on('click', '#btnDel', function () {
                //confirmation
                var id = $(this).data('id');
                if (confirm('Delete Data ' + id + '?') == true) {
                    var route = "{{route('users.destroy', ':id')}}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url: route,
                        type: "delete",
                        success: function (response) {
                            console.log(response);
                            if (response.success) {
                                $("#tblData").DataTable().ajax.reload();
                                sweetToast('', response.message, 'success', true);
                            } else {
                                sweetToast('', response.message, 'error', true);
                            }
                        },
                        error: function (response) {
                            sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true);
                        }
                    });
                } else {
                    //do nothing
                }
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });



        }); // end document ready

    </script>
@stop


{{--@section('plugins.Datatables', true)--}}
{{--@section('plugins.Sweetalert2', true)--}}
{{--@section('plugins.jQueryValidation', true)--}}
{{--@section('plugins.Select2', true)--}}
