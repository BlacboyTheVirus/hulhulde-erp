@extends('adminlte::page')

@section('title', 'Permissions | Dashboard')

@section('content_header')
    <h1>Permissions</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div id="errorBox"></div>
        <div class="col-3">
            <form method="POST" id="newform" action="{{route('users.permissions.store')}}" >
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
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Permission Name" value="{{old('name')}}">
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
                        <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline table-condensed">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Guard</th>
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

            $('#save').click(function(e){
                e.preventDefault();
 
                if(!formvalidator.form()){
                    return false;
                };
                
                //submit
                var formData = {
                    name: $("#name").val(),
                };

                $.ajax({
                    type: "POST",
                    url: '{{ route('users.permissions.store') }}',
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
                    ajax:"{{route('users.permissions.index')}}", 
                    columns:[
                        {data:'id', name:'id'},
                        {data:'name', name:'name'},
                        {data:'guard_name', name:'guard_name'},
                        {data:'action', name:'action'},
                    ], 
                    order:[[0, "desc"]]
             });
            


            $('body').on('click', '#btnDel', function(){
                //confirmation
                var id = $(this).data('id');
                if(confirm('Delete Data '+id+'?')==true)
                {
                    var route = "{{route('users.permissions.destroy', ':id')}}"; 
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


