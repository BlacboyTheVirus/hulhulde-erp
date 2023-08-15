@extends('adminlte::page')

@section('title', 'Create Roles | Dashboard')

@section('content_header')
    <h1>Create Roles</h1>
@stop

@section('content')
   <div class="container-fluid">
        <div id="errorBox"></div>
        <form action="{{route('users.roles.store')}}" method="POST" id="newform">
            @csrf
            <div class="card">                
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label"> Name <span class="text-danger"> *</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="For e.g. Manager" value={{old('name')}}>
                    </div>
                    <label for="name" class="form-label"> Assign Permissions <span class="text-danger"> *</span></label>
                    <!--DataTable-->
                    <div class="table-responsive">
                        <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="all_permission" name="all_permission">
                                    </th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="save" class="btn btn-primary" >Save Role</button>
                </div>
            </div>
        </form>
   </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        //check uncheck all function 
        $('[name="all_permission"]').on('click', function(){
            if($(this).is(":checked"))
            {
                $.each($('.permission'), function(){
                    if($(this).val()!="dashboard")
                    {
                        $(this).prop('checked', true); 
                    }
                });
            }else{
                $.each($('.permission'), function(){
                    if($(this).val()!="dashboard")
                    {
                        $(this).prop('checked', false); 
                    }
                });
            }
        });


        //validate
             var formvalidator = $('#newform').validate({
                    rules: {
                        name: {
                            required: true,
                        },                        
                    },
                    messages: {
                        name: {
                            name: "Please enter a valid role name",
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
                };

                var permissions = [];
                $('.permission:checked').each(function(i){
                        permissions[i] = $(this).val();
                });
                
                //submit
                var formData = {
                    name: $("#name").val(),
                    permission: permissions,                    
                };

                $.ajax({
                    type: "POST",
                    url: '{{ route('users.roles.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                                if (response.success){
                                    $("#tblData").DataTable().ajax.reload();
                                    $('#newform').trigger('reset');
                                    sweetToast('', response.message, 'success', true);
                                    setTimeout(() => {
                                         window.location.href = '{{route('users.roles.index')}}';
                                     }, 1000);
                                } else {
                                    sweetToast('', response.message, 'error', true);
                                }
                               },
                    error:     function (response){
                                     sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true); 
                               }
                
                });
                
            });



        var table = $('#tblData').DataTable({
            reponsive:true, processing:true, serverSide:true, autoWidth:false, bPaginate:false, bFilter:false,
            ajax:"{{route('users.permissions.index')}}", 
            columns:[
                {data:'chkBox', name:'chkBox', orderable:false, searchable:false, className:'text-center'},
                {data:'name', name:'name'},
                {data:'guard_name', name:'guard_name'},
            ], 
            order:[[0, "desc"]]
        });
    });
    
   
</script>
@stop

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)
