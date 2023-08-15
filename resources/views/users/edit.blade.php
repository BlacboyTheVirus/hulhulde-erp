@extends('adminlte::page')

@section('title', 'Update Users | Dashboard')

@section('content_header')
    <h1>Update Users</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div id="errorBox"></div>
        <div class="col-3">
            <form method="POST" action="{{route('users.update', $user->id)}}" id="editform">
                @method('patch')
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
                            <input type="text" class="form-control" name="name" id="name"  placeholder="Enter Full Name" value="{{$user->name}}">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Users Email" value="{{$user->email}}">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="New password or leave blank to keep old" value="">
                        </div>
                        <div class="form-group">
                            <label for="roles" class="form-label">Roles</label>
                            <select class="form-control select2" multiple="multiple" id="roles" data-placeholder="Select Roles" name="roles[]">
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{$user->id ? (in_array($role->name, $userRole)? 'selected': ''):''}}>{{ucfirst($role->name)}}</option>
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
            var formvalidator = $('#editform').validate({
                    rules: {
                        name: {
                            required: true,
                        }, 
                        email: {
                            required: true,
                            email: true
                        },
                                    
                    },
                    messages: {
                        name: {
                            name: "Please enter a valid User name"
                        },
                        email: {
                            email: "Please enter a valid email"
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
                    type: "PATCH",
                    url: '{{ route('users.update', $user->id) }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                                if (response.success){
                                    $('#editform').trigger('reset');
                                    sweetToast('', response.message, 'success', true);
                                    setTimeout(() => {
                                         window.location.href = '{{route('users.index')}}';
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

         }); // end document ready

</script>
@stop


{{-- @section('plugins.Datatables', true) --}}
@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)
@section('plugins.Select2', true)




