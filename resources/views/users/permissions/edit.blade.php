@extends('adminlte::page')

@section('title', 'Edit Permissions | Dashboard')

@section('content_header')
    <h1>Edit Permission</h1>
@stop

@section('content')
   <div class="container-fluid">
    <div class="row">
        <div id="errorBox"></div>
        <div class="col-3">
            <form method="POST" action="{{route('users.permissions.update', $permission->id)}}"  id="updateform" >
                @method('patch')
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>Update</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" name="name" placeholder="Enter Permission Name" value="{{$permission->name}}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="update" class="btn btn-primary" >Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
   </div>
@stop

@section('js')
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })


        $(document).ready(function(){
            //validate
            var formvalidator = $('#updateform').validate({
                    rules: {
                        name: {
                            required: true,
                        },                        
                    },
                    messages: {
                        name: {
                            name: "Please enter a valid permission name"
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

            $('#update').click(function(e){
                e.preventDefault();

                if(!formvalidator.form()){
                    return false;
                };
                
                
                //submit
                var formData = {
                    name: $("#name").val(),
                    id:{{$permission->id}},
                };

               
                $.ajax({
                    type: "PUT",
                    url: '{{route('users.permissions.update', $permission)}}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                    success:  function (response) {
                                if (response.success){
                                    $('#updateform').trigger('reset');
                                    sweetToast('', response.message, 'success', true);
                                    setTimeout(() => {
                                         window.location.href = '{{route('users.permissions.index')}}';
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

@section('plugins.Sweetalert2', true)
@section('plugins.jQueryValidation', true)