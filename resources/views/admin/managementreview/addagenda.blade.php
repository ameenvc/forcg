@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">  
                   
            <div class="block-header">
                <h3 class="block-title">Add Agenda</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{route('management.agenda')}}">Back</a>
                        </li>
                    </ol>
                </nav>                    
            </div> 
            @if(Session::has('msg'))
            <div class="alert alert-danger">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('msg')!!}
            </div>
            @endif                       
            <div class="block-content block-content-full" style="width:85%;">
                <div class="row">                                
                    <div class="col-lg-12">      
                        <form  class="mb-5" method="POST" action="{{url('/admin/management/insertagenda')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-sm-6" >
                                    <select class="js-select2 form-control" name ="type[]" style="font-size: 13px;width:100%;" required="true" multiple data-placeholder="Select Agenda Type">                        
                                        @foreach($types as $value)
                                            <option value="{{$value->name}}"> {{$value->name}}</option>
                                        @endforeach               
                                    </select>                           
                                </div> 
                            </div>
                            <div class="table-responsive">    
                                <table class="table table-borderless" id="maintable">                        
                                    <tr>
                                        <td class="col-sm-1">
                                            <label class=" col-form-label " style="font-size:13px;" for="type">Agenda</label>
                                        </td>
                                        <td class="col-sm-11">
                                            <table class="table table-borderless" id="innertable">                                              
                                                    <tr>
                                                        <td width="70%">                                        
                                                            <input type="text" class="form-control"  style="font-size:13px;width:500px;" id="points" name="points[]"  value= "">                        
                                                        </td> 
                                                        <td>&nbsp;</td>                                   
                                                    </tr>

                                                <tr>
                                                    <td>                                        
                                                        <input type="text" class="form-control"  style="font-size:13px;width:500px;" id="points" name="points[]"  value= "">                        
                                                    </td>
                                                    <td>&nbsp;</td>                                    
                                                </tr>

                                                <tr>
                                                    <td>                                        
                                                        <input type="text" class="form-control"  style="font-size:13px;width:500px;" id="points" name="points[]"  value="">                        
                                                    </td> 
                                                    
                                                    <td>
                                                        <button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add More">
                                                            <i class="fa fa-fw fa-plus"></i>
                                                        </button>
                                                    </td>   
                                                                                        
                                                </tr>                        
                                                
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>   
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="submit" name="update" class="btn btn-primary text-center" style="font-size:12px;" value="Add">
                                </div>
                            </div>  
                            <div class="mt-2" style="color:red;font-size:13px;">
                                Note: Click on the  <i class="fa fa-fw fa-plus" style="color:black;"></i> button to add more agenda
                            </div>
                        </form>                                             
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        var i = 0;
    $("#add").click(function(){
        ++i;
            $("#innertable").append('<tr id="newrow"><td><input type="text" name="points[]" id="points" style="font-size: 13px;width:500px;"class="form-control"></td><td><button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add More"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr> ');
    });
    
    $(document).on('click', '.remove-tr', function(){  
    //$(this).parents('tr').remove();
    //$('#dynamicTable tr:eq(1)').remove();
    $('#newrow').addClass('to-remove');
    $('#innertable .to-remove').last().remove();
    });
    </script>
  
@endsection