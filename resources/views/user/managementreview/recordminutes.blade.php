@extends('layouts.backend')
@section('content')

<style>
   .agenda_style{
       margin:10px; 
   }
</style>

    <!-- Header -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Management Review</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Record Minutes</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('management.meetings') }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Header -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-content block-content-full">
               <div class="row" style="margin-left:15px;margin-right:15px;">                                
                   <div class="col-lg-12 col-md-6">

                      <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size:13px;"> 
                        <tr>
                            <th>Date</th>
                            <td style="text-transform:capitalize">{{ date('d-m-Y',strtotime($data->date))}}</td>

                            <th>Time</th>
                            <td style="text-transform:capitalize">{{ $data->time }}</td>
                        
                            <th >Location</th>
                            <td style="text-transform:capitalize">
                                    @if(is_numeric($data->location))
                                        {{ $data->managementLocation ? $data->managementLocation->name:'' }}
                                    @else
                                    {{ $data->location }}
                                    @endif
                            </td>
                        
                            <th >Agenda Type</th>
                            <td style="text-transform:capitalize">
                                    <?php
                                    $types = explode(',',$data->agenda_type);
                                    $types_arr= array();
                                    ?>
                                    @foreach($types as $type)
                                    <?php
                                        $typename = DB::table('management_agenda_types')
                                                                                    ->where('id',$type)->value('name');
                                        array_push($types_arr,$typename);
                                        $typedisplay = implode(', ',$types_arr);
                                    ?>
                                    @endforeach
                                {{$typedisplay}}
                            </td>
                        </tr>
                       
                        <tr><td colspan="8"  style="background:#f1f1f1;"></td></tr>
                                                
                         <tr>
                            <td style="text-transform:capitalize" colspan="8">
                                <?php
                                  $sl = 1;
                                ?>
                                <table class="table table-bordered">
                                    @foreach($agendapoints as $agendapoint)
                                        <tr>
                                            <td>{{$sl++}}</td>
                                            <td>{{$agendapoint->points}}</td>
                                            <td class="text-center">
                                            <div class="btn-group">
                                              <a href="#" class="btn btn-sm btn-alt-primary font-weight-normal" style="font-size:13px;" data-toggle="modal" title="Add Minutes" data-target="#addminuteModal" onclick="agendadisplay('{{$agendapoint->points}}');agendaidvalue('{{$data->id}},{{$agendapoint->id}}')">
                                                 <i class="fa fa-fw fa-plus"></i></i> 
                                              </a>
                                        
                                              <a href="#" class="btn btn-sm btn-alt-info font-weight-normal" style="font-size:13px;" title="View Minutes" data-toggle="modal"  data-target="#viewminuteModal" onclick="viewnotes('{{$data->id}},{{$agendapoint->id}}')">
                                                 <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                              </a>
                                              
                                            </div> 
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>                                                                                     
                    </table>

                   </div>         
                </div>
            </div>
        </div>
    </div>

    <!-- add minutes starts-->
    <div class="modal fade" id="addminuteModal" tabindex="-1" role="dialog" aria-labelledby="addminuteLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="addminuteLabel"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form id="add_minutes_notes" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="successBtntitle" style="display:none">
                            Notes Added Successfully
                        </div>

                        <div class="alert alert-danger" id="dangerValidationtitle" style="display:none">
                            Some server errors happened, we are trying to fix them soon
                        </div>         
                             
                    <div class="block block-rounded">               
                        <div class="block-content block-content-full">
                            <div class="form-group">
                               <textarea class="js-summernote" id="add_minutes" name="add_minutes" required>
                                  <p id="displaynotesinside">

                                  </p>
                               </textarea>
                            </div>
                        </div>
                    </div>
                      <input type="hidden" name="agendalistid" id="agendalistid" value=""/>
                      <input type="hidden" name="scheduleid" id="scheduleid" value="{{$data->id}}"/>
                    </div>
                    
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Save Note</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

<!-- add minutes  ends-->

 <!-- view minutes starts-->
 <div class="modal fade" id="viewminuteModal" tabindex="-1" role="dialog" aria-labelledby="viewminuteLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="viewminuteLabel">View Minutes</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                                <div class="form-group"  style="border:1px solid #f1f1f1;">
                                     <div id="shownotes"></div> 
                                </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    </div>
                   
                </div>
            </div>
        </div>

<!-- view minutes  ends-->
<!-- view minutes  ends-->
@include('user.managementreview.header_options')      


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
  
  let CSRF_TOKEN = "";
        window.onload = function() {
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        }

function agendadisplay(details)
{
    document.getElementById('addminuteLabel').innerHTML=details;   
}

function agendaidvalue(bothids)
{
    //document.getElementById('agendalistid').value=id;
        var allids = bothids;
        var id_array = allids.split(',');
        var schedule_id = id_array[0];
        var agenda_list_id = id_array[1];
        document.getElementById('agendalistid').value=agenda_list_id;

        $.ajax({
                    data: {
                            "schedule_id": schedule_id,
                            "agenda_list_id":agenda_list_id,
                            _token: CSRF_TOKEN
                        }          
                    , url: "{{ route('management.displayminutenotesajax')}}"
                    , type: "POST"
                    , success:function(response) {
                        //console.log(response.data)
                        if(response.status == 200)
                                {
                                    var noteslist = response.data;        
                                    var noteshtmldatas='';
                                    if(noteslist.length>0)
                                     { 
                                        for(let i=0;i<noteslist.length;i++)
                                          {  
                                            noteshtmldatas+='<div>'+noteslist[i]['minute_notes']+'</div>';
                                                            
                                          }
                                     }
                                    else
                                    {
                                        noteshtmldatas+='<div></div>';
                                    }                                   
                                    $('#displaynotesinside').html(noteshtmldatas); 
                                
                                }
                        
                        }

                    });

}

    $(document).on('submit','#add_minutes_notes',function(e){
    e.preventDefault();

    var agendalistid = $('#agendalistid').val();
    var scheduleid = $('#scheduleid').val();
    var add_minutes = $('#add_minutes').val(); 
      
    $.ajax({
              data: {
                   "agendalistid_value" : agendalistid,
                   "scheduleid_value" : scheduleid,
                   "add_minutes_value": add_minutes
                   , _token: CSRF_TOKEN
              }
            , url: "{{ route('management.addminutesajax')}}"
            , type: "POST"
            , success: function(response) {
                //console.log(response.data);
                if(response.status == 200)
                {
                    $('#successBtntitle').css('display', 'block');
                    setTimeout(function() 
                        {
                           window.location.reload();
                        }, 1000);
                }
                else if(response.status == 500)
                {
                    $('#dangerValidationtitle').css('display', 'block');
                    setTimeout(function() 
                        {
                           window.location.reload();
                        }, 1000);
                }                
            }          
        });
    });


function viewnotes(ids)
{
        var allids = ids;
        var id_array = allids.split(',');
        var schedule_id = id_array[0];
        var agenda_list_id = id_array[1];
         
        $.ajax({
                    data: {
                            "schedule_id": schedule_id,
                            "agenda_list_id":agenda_list_id,
                            _token: CSRF_TOKEN
                        }          
                    , url: "{{ route('management.getminutenotesajax')}}"
                    , type: "POST"
                    , success:function(response) {
                        console.log(response.data)
                        if(response.status == 200)
                                {
                                    var noteslist = response.data;        
                                    var noteshtmldatas='';
                                    if(noteslist.length>0)
                                     { 
                                        for(let i=0;i<noteslist.length;i++)
                                          {  
                                            noteshtmldatas+='<div class="col-12" style="margin-top:5px;">'+noteslist[i]['minute_notes']+'</div>';
                                          }
                                     }
                                    else
                                    {
                                        noteshtmldatas+='<div class="col-12 text-danger" style="font-size:12px;">No Minutes Added</div>';
                                    }                                
                                    $('#shownotes').html(noteshtmldatas); 
                                
                                }
                        
                        }

                    });
}
</script>

@endsection
