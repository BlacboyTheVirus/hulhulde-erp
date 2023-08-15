@extends('adminlte::page')

@section('title', 'Users | Dashboard')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div id="errorBox"></div>
        <div class="col-3">
            <form method="POST" action="{{route('users.store')}}" id="newform">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>Add New</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name"  placeholder="Enter Full Name" value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Users Email" value="{{old('email')}}">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Users Password" value="{{old('password')}}">
                        </div>
                        <div class="form-group">
                            <label for="roles" class="form-label">Roles</label>
                            <select class="form-control select2" multiple="multiple" id="roles" data-placeholder="Select Roles" name="roles[]">
                            @foreach ($roles as $role)
                                <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                            @endforeach
                            </select>
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Roles</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
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
@stop

@section('js')
    <script>
         $(function (){
             $('#roles').select2();
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
                        name: {
                            required: true,
                        }, 
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 6
                        },                       
                    },
                    messages: {
                        name: {
                            name: "Please enter a valid User name"
                        },
                        email: {
                            email: "Please enter a valid email"
                        },
                        password: {
                            password: "Please enter a valid password with minimum of 6 characters"
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
                
                //submit
                var formData = {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    roles: $("#roles").val(),
                     
                };

                $.ajax({
                    type: "POST",
                    url: '{{ route('users.store') }}',
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
                    error:     function (response){
                                     sweetToast('', 'Sorry, something went wrong! Please try again.', 'error', true); 
                               }
                
                });
                
            });







            // DATATABLE

            var table = $('#tblData').DataTable({
                    reponsive:true, processing:true, serverSide:true, autoWidth:false, 
                    ajax:"{{route('users.index')}}", 
                    columns:[
                        {data:'id', name:'id'},
                        {data:'name', name:'name'},
                        {data:'email', name:'email'},
                        {data:'date', name:'date'},
                        {data:'roles', name:'roles'},
                        {data:'action', name:'action', bSortable:false, className:"text-center"},
                    ], 
                    order:[[0, "desc"]]
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
            
         }); // end document ready

</script>
@stop


@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)
@section('plugins.Select2', true)




