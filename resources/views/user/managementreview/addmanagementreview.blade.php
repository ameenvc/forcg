@extends('layouts.multiselect')
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
                            <a class="link-fx" href="">Add Management Review Schedule</a>
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

        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">
                   <div class="col-md-12">
                        <label class="col-sm-12 col-form-label text-right" style="font-size:13px;">All fields marked with <span class="text-danger">*</span> are mandatory</label>
                     </div>

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form name="form" class="mb-5" action="{{route('insertmanagementreview')}}" method="POST">
                            {{csrf_field()}}

                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                             <a class="close" data-dismiss="alert">×</a>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif

                            @if(Session::has('msg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                                                

                        <div class="col-lg-10">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="date" style="font-size:13px;">Date Of Management Review<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="date" style="font-size:13px;width:350px;" class="js-datepicker form-control" id="date" name="date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="" required>
                                </div>
                            </div>                         

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="time" style="font-size:13px;">Time<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                     <input type="time" class="form-control" name="time" required style="font-size: 13px;width:150px;" placeholder="Time">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="location" style="font-size:13px;">Location<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                  <select class="form-control" name ="location" id="location" required  style="font-size: 13px;width:350px;">
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                           <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="agendatype" style="font-size:13px;">Agenda Type<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                   @foreach($types as $type)
                                      <input style="margin-right:4px;margin-left:10px;"  type="checkbox" value="{{$type->name}}" id="agenda_type"   name="agenda_type[]" onclick="return displayagenda()">
                                      <label style="font-size: 13px;">{{$type->name}}</label>                                  
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group row" id="points">
                               
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="participants" style="font-size:13px;">Participants<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name = "participants[]" id="participants" required  style="font-size: 13px;width:350px;" multiple data-placeholder="Select Participants">
                                        @foreach($users as $user)
                                           <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                    

                             <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="managementparticipants" style="font-size:13px;">Participation from management<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                <select class="js-select2 form-control" name="management_participants[]" id="management_participants" required style="font-size: 13px;width:350px;" multiple data-placeholder="Choose Participants from Management" onchange="displayothermanagementpartcipants()">
                                        @foreach($users as $user)
                                           <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="management_participants_other" style="display:none;">
                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                   <input type="text"style="font-size: 13px;width:350px;" class="form-control" id="management_participants_oth" name="management_participants_oth" placeholder="Please specify Name">
                                </div>
                            </div> 

                            <div class="form-group row" id="management_participants_other_email" style="display:none;">
                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                   <input type="email"style="font-size: 13px;width:350px;" class="form-control" id="management_participants_oth_email" name="management_participants_oth_email" placeholder="Please specify Email">
                                </div>
                            </div> 
                        
                       
                          <div style="margin-top:50px;margin-bottom:50px;">
                            <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;margin-left:550px;" onclick="return validateform();">Submit</button>
                          </div>
                        </div> 
                        </form>
                        <!-- END Form Horizontal - Default Style -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->


        <!-- edit agenda starts-->
<div class="modal fade" id="modifyagendaModal" tabindex="-1" role="dialog" aria-labelledby="modifyagendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyagendaModalLabel">Modify Agenda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update_agenda" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">                   
                    <div class="form-group">
                       
                    </div>
                    
                </div>         
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="updateAgendaPoints()">Update</button>
                </div>
            </form>
            </div>
        </div>
    </div>

<!-- edit  agenda  ends-->

@include('user.managementreview.header_options')      


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  
  let CSRF_TOKEN = "";
        window.onload = function() {
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function(){
                $('#points').hide();       
            });
        }

    function displayagenda()
    {
        if ($('input:checkbox:checked').length > 0)
        {
            var agendatypevalues = [];
            $("#agenda_type:checked").each(function(){
                agendatypevalues.push($(this).val());
            });

           $.ajax({
                    data: {
                            "type_value": agendatypevalues
                            , _token: CSRF_TOKEN
                        }          
                    , url: "{{ route('selectagenda')}}"
                    , type: "POST"
                    , success:function(response) {
                        if(response.status == 200)
                           {
                                var userhtmldatas='';
                                userhtmldatas+='<div class="col-sm-10 text-right col-form-label" style="font-size:13px;">\
                                                <a href="#" data-toggle="modal"  data-target="#modifyagendaModal" style="font-size:11px;" onclick="return modifyagenda(\''+response.agenda_id+'\');">Click here to Modify Agenda</a></div>\
                                                <label class="col-sm-4 col-form-label" for="agenda" style="font-size:13px;">Agenda Required</label>\
                                                <div class="col-sm-6 bg-gray-lighter" style="padding-top:15px;padding-bottom:7px;">\
                                                <span style="font-size: 13px;">'+response.data+'</span>\
                                                <input type="hidden" name="description" id="description" value="'+response.data+'"/>\
                                                <input type="hidden" name="agenda" value="'+response.agenda_id+'"/>\
                                                </div>';
                                
                                $('#points').show(); 
                                $('#points').html(userhtmldatas); 
                                
                            }
                        }
                            
                    });
            }
            else
            {
                $(document).ready(function() {
                    document.getElementById('description').value = '';
                    $('#points').hide();       
                });
            }
    }
    

    function modifyagenda(pointid)
    {  
        var id = pointid;             
        $.ajax({
                        data: {
                                "datasid": id
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('selectagendamodifydata')}}"
                        , type: "POST"
                        , success:function(response) {
                            console.log(response.data);
                            if(response.status == 200)
                              {
                                   var points = response.data;
                                   var agendahtmldatas='';

                                   agendahtmldatas+='<table class="table table-borderless" width="100%">';
                                   
                                for(let i=0;i<points.length;i++)
                                 {
                                   agendahtmldatas+='<div class="alert alert-success" id="successBtntitle" style="display:none">Agenda Updated Successfully</div>\
                                   <tr><td><input type="text" class="form-control"  style="font-size:13px;" id="points" name="points[]"  value="'+points[i]['points']+'"></td></tr>\
                                   <input type="hidden" name="agendaid[]"  value="'+points[i]['id']+'">\
                                   <input type="hidden" name="detail_type"  value= "'+response.detail_type+'">\
                                   <input type="hidden" name="agenda_points_id"  id="agenda_points_id"  value="'+response.main_point_id+'"/>';                                                       
                                                       
                                  
                                 }
                                 agendahtmldatas+='</table>';
                                 $("#modifyagendaModal .modal-body").html(agendahtmldatas);
                                 }
                        }
                    });
        

    }

      function updateAgendaPoints() 
        {
            var formData = $('#update_agenda').serialize();
            $.ajax({
                url: '{{ route("updateagendaajax") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
                    if(response.status == 200)
                        {
                            $('#successBtntitle').css('display', 'block');
                            displayagenda();
                        }                                                  
                    }                         
               });
        }


    function displayothermanagementpartcipants()
       {
           if(document.getElementById('management_participants').value =='other')
            {
                document.getElementById('management_participants_other').style.display="";
                document.getElementById('management_participants_oth').required= true;
                document.getElementById('management_participants_other_email').style.display="";
                document.getElementById('management_participants_oth_email').required= true;
            }
           else
           {
                document.getElementById('management_participants_other').style.display="none";
                document.getElementById('management_participants_oth').required= false;
                document.getElementById('management_participants_other_email').style.display="none";
                document.getElementById('management_participants_oth_email').required= false;
           }
       }

    function validateform()
    {
        if ($('input:checkbox:checked').length > 0){
        }
        else{
            alert("Please Select Agenda Type");
            return false;
        }
    }
</script>

</html>
@endsection