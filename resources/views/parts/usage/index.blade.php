@extends('adminlte::page')

@section('title', 'Parts Usage   | Dashboard')

@section('content_header')
    <h1>Enter Details for Part Usage</h1>
@stop

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div id="errorBox"></div>
            <div class="col-3">
                <form method="POST" action="{{route('parts.usage.store')}}" id="newform">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add Part Usage </h5>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="form-group">
                                <label>Collection Date</label>
                                <div class="input-group date" id="usage_date" data-target-input="nearest">
                                    <input type="text" class="form-control " name="usage_date" id="usage_date"
                                           placeholder="Collection Date" value="" readonly required>
                                    <div class="input-group-append" data-target="#usage_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Part <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-12 pr-0">
                                        <select class="form-control form-control-border" id="parts_id" name="parts_id" >
                                            <option value='' selected="selected">- Select Part -</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="quantity" id="quantity"
                                       placeholder="Enter quantity" min="1">
                            </div>


                            <div class="form-group">
                                <label for="collected_by" class="form-label">Collected by <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="collected_by" id="collected_by"
                                       placeholder="Collected By" >
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
                            <h5>Usage List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--DataTable-->
                        <div class="table-responsive">
                            <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>Part Code</th>
                                    <th>Part Name</th>
                                    <th>Collection Date</th>
                                    <th>Quantity</th>
                                    <th>Collected By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>

{{--                                <tfoot>--}}
{{--                                <tr>--}}
{{--                                    <th></th>--}}
{{--                                    <th></th>--}}
{{--                                    <th></th>--}}
{{--                                    <th></th>--}}
{{--                                    <th></th>--}}
{{--                                    <th></th>--}}
{{--                                </tr>--}}
{{--                                </tfoot>--}}
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
                    parts_id: {
                        required: true,
                    },
                    quantity: {
                        required: true,
                        number: true
                    },
                    collected_by: {
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
                    url: '{{ route('parts.usage.store') }}',
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


            $('#parts_id').select2({
                //minimumInputLength: 1,
                ajax: {
                    url: "{{route('parts.getlist')}}",
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


            // DATATABLE

            var table = $('#tblData').DataTable({
                reponsive: true, processing: true, serverSide: true, autoWidth: false,
                ajax: "{{route('parts.usage.index' )}}",
                columns: [
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'usage_date', name: 'usage_date'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'collected_by', name: 'collected_by'},
                    {data: 'action', name: 'action', bSortable: false, className: "text-center"},

                ],
                order: [[0, "asc"]],


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

            //Date picker
            $('#usage_date').datepicker({
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
