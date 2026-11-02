@extends('admin.datatable')

@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">  
        
        <div class="block-header">
            <h3 class="block-title">Edit Agenda</h3>
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
        <div class="block-content block-content-full" style="width:90%;">
            <div class="row">                                
                <div class="col-lg-12">
                    @if(count($agendalist) > 0)
                    
                    <div class="col-sm-12" align="right" style="padding-bottom:10px;">
                        <a href="#" data-toggle="modal"  data-target="#addagendaModal" class="btn btn-sm btn-alt-primary" style="font-size:11px;" onclick="showagenda('{{$agendas->agenda_type}}')">Click here to Add more points</a>
                    </div>     
                    <form  class="mb-5" method="POST" action="{{url('/admin/management/updateagenda')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                        {{csrf_field()}}
                        <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom:70px;">
                
                            <tr class="table-active">
                                <td class="col-sm-12" align="center">
                                    <span style="font-size:13px;vertical-align:top;"><b>{{$agendas->agenda_type}}</b></span> 
                                </td>
                            </tr>
                
                            <tr>
                                <td class="col-sm-12" colspan="2">
                                    <table class="table table-borderless">                       
                                            @foreach($agendalist as $item)
                                            <tr>
                                                <td width="95%">                                        
                                                    <input type="text" class="form-control"  style="font-size:13px;" id="points" name="points[]"  value= "{{$item->points}}">
                                                    <input type="hidden" name="agendaid[]"  value= "{{$item->id}}">
                                                </td>
                                                <td>
                                                    <a href="{{route('management.agendapointdelete',$item->id)}}" onclick="return delpoints()" class="btn btn-sm btn-alt-danger" style="font-size:10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach 
                                    </table>
                                </td>
                            </tr>
                                
                            <tr>
                                <td colspan="2"><input type="submit" name="update" class="btn btn-primary text-center" style="font-size:12px;margin-left:350px;" value="Update"></td>
                            </tr>
            
                        </table>
                        </div>

                        <input type="hidden" name="id"  value= "{{$agendas->id}}">
                        <input type="hidden" name="detail_type"  value= "{{$detail_type}}">
                    </form>                                             
                    @else
                    <div class="col-sm-12 text-danger" align="center" style="padding-bottom:10px;margin-left:10px;">
                            No matching records
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

<!-- add more agenda starts-->
<div class="modal fade" id="addagendaModal" tabindex="-1" role="dialog" aria-labelledby="modifyagendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="addagendaModalLabel">Add Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_agenda" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">                   
                    <div class="form-group">
                    <table class="table table-borderless" id="dynamicTable">
                        <tr>
                            <td>
                            <input type="text" class="form-control" style="font-size:12px;" name="agenda[]" id="agenda[]" value="" required>
                            </td>
                            <td>
                                <button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add More">
                                    <i class="fa fa-fw fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                    </div>
                    <input type="hidden" name="details_type" id="details_type" value="{{$detail_type}}">
                    <input type="hidden" name="agenda_type" id="agenda_type" value="{{$agendas->agenda_type}}">
                    <input type="hidden" name="mainagendaid"  value= "{{$agendas->id}}">

                </div>         
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="addAgendaPoints()">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- add more agenda  ends-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        var i = 0;
    $("#add").click(function(){
        ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="agenda[]" id="agenda[]" style="font-size: 13px;"class="form-control"></td><td><button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add More"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr> ');
    });
        $(document).on('click', '.remove-tr', function(){  
    $(this).parents('tr').remove();
    });
    </script>

       <script>
           let CSRF_TOKEN = "";
             window.onload = function() {
                 CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');       
              }

        function addAgendaPoints() 
          {
            var formData = $('#add_agenda').serialize();
            $.ajax({
                url: '{{ route("management.addagendaajax") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
                    if(response.status == 200)
                        {
                            $('#successBtntitle').css('display', 'block');
                            setTimeout(function() {
                                    window.location.reload();
                            }, 1000);
                        }                                                  
                    }                         
               });
            }

            function delpoints() 
            {
                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Agenda will be deleted permanently,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {

                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            
            function showagenda(details)
            {
                var modules = details;
                //var modules_array = modules.split(',');
                var heading = 'Add Agenda for';
                var heading_value = heading.concat(" ",modules);
                document.getElementById('addagendaModalLabel').innerHTML=heading_value;
                return false;
            }
        </script>
        
@endsection