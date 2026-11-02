@extends('admin.datatable')
@section('content')

        <style>
            .select2-results__options,
            .select2 {
                font-size: 13px;
            }
        </style>
        <style>
            #page-overlay {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                content: "";
                /* background-color: rgba(0, 0, 0, 0.6); */
                background-color: rgba(0, 0, 0, 0.1);
                z-index: 1033;
                opacity: 0;
                transition: opacity 0.28s ease-out;
                -webkit-transform: translateY(-100%);
                        transform: translateY(-100%);
            }
            .scrollable {
                height: 500px !important;
                overflow-x: scroll;
                overflow-y: scroll;
            }
        </style>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
        <div class="content">
            @if(Session::has('message'))
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('message')!!}
            </div>
            @endif
            <div class="block block-rounded">
                                <div class="block-header">
                                    <h3 class="block-title">Email Notifications Table</h3>
                                    
                                </div>
                                <div class="block-content block-content-full" style="width:92%;height:550px;"> 
                                    <div class="scrollable table-responsive"> 
                                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                        <table class="table  table-striped table-vcenter js-dataTable-full" id="tabledata">                        
                                            <thead style="position: sticky; top: 0;z-index:2;">
                                                <tr>                                
                                                    <th colspan="6" style="padding-left:0;">
                                                        <select class="form-control  justify-content-center" name ="module" id="module" style="font-size: 13px;width:250px;">
                                                            <option value="" class="bg-light text-black">Select Module</option>
                                                            @foreach($modulenames as $key => $value)
                                                            <option value="{{$key}}" class="bg-light text-black"> {{$value}}</option>
                                                            @endforeach               
                                                        </select> 
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize;">#</th>
                                                    <!-- <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize">Module</th> -->
                                                    <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize;">Item</th>
                                                    <th class="text-left" style="width: 15%;font-size:10px;text-transform:capitalize;">Description</th>
                                                    <th class="text-left" style="width: 15%;font-size:10px;text-transform:capitalize;">Email Content</th>
                                                    <!-- <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize">User Assigned</th> -->
                                                    <th class="text-left" style="width:2%;font-size:10px;text-transform:capitalize;">Actions</th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodydata">
                                                <?php $number = 0; ?>
                                                @foreach($emaildatas as $data)
                                                {{csrf_field()}}
                                                <?php  $id = $data->id; ?>
                                                @if($data->is_blocked==1)
                                                <tr class="text-danger" style="font-size:12px;">
                                                @endif
                                                    <td style="font-size:10px;" class="text-left">{{$number = $number+1}}</td> 
                                                    <td class="d-none">{{$data->module}}</td>
                                                    <td style="font-size:10px" class="text-left">
                                                        <span>{{$data->item}}</span>          
                                                    </td>

                                                    <td style="font-size:10px" class="text-left">
                                                        <span>{{$data->description}}</span>
                                                    </td>

                                                    <td style="font-size:10px" class="text-left">
                                                        <span>{{$data->email_content}}</span>
                                                    </td>
                                                
                                                    <td style="font-size:10px;" class="text-left">         
                                                        <div class="btn-group m-auto d-flex justify-content-center">
                                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                            </a>
                                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}">             
                                                                <!-- <a href="" class="dropdown-item" style="font-size:10px;color:#343a40;" onclick="return chkval('{{$data->item}}');" data-toggle="layout" data-action="side_overlay_toggle">Add/Remove Users</a> -->
                                                                <a href="" class="dropdown-item" style="font-size:10px;color:#343a40;" id="emailpass" onclick="return chkval('{{$data->id}},{{$data->item}}');" data-toggle="layout" data-action="side_overlay_toggle">Add/Remove Users</a>
                                                                <a href="{{url('/admin/emailnotification/edit/'.$id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;">Edit Email Notification</a>
                                                                @if($data->is_blocked==0)
                                                                <a href="{{url('/admin/emailnotification/blocked/'.$id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;" onclick="return chkblockemails()">Block</a>
                                                                @else
                                                                <a href="{{url('/admin/emailnotification/unblock/'.$id)}}" class="dropdown-item" style="font-size:10px;color:#343a40;" onclick="return chkunblockemails()">Unblock</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        <!-- email listing ends -->
                        <!-- side bar starts -->
                        <!-- Side Overlay-->
                        <aside id="side-overlay" class="font-size-sm">  
                            <div class="content-header border-bottom">                   
                                <div class="ml-2">
                                    <a class="text-dark font-w600 font-size-sm" href="javascript:void(0)" id="itemname"></a>
                                </div>
                            
                                <a class="ml-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                                    <i class="fa fa-fw fa-times"></i>
                                </a>         
                            </div>       
                            <div class="content-side">
                                <div class="block block-transparent pull-x pull-t">                
                                    <div class="block">
                                        <div class="block-header block-header-default">
                                            <h3 class="block-title" style="text-transform:capitalize;">Add Users</h3>
                                            <div class="block-options">
                                                <a class="btn-block-option" href="#" data-toggle="modal" data-target="#addusersModal">
                                                    <i class="fa fa-plus-square text-secondary" style="font-size:20px;"></i>                                   
                                                </a>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <input type="hidden" name='datasid' id="datasid" value="">
                                            <div class="row" id="getemaildetails"></div>
                                        </div>
                                    </div>        
                                </div>
                            </div>
                        
                        </aside>                   
                        <!-- side bar starts -->
                    
            <!-- add user modal starts-->
            <div class="modal fade" id="addusersModal" tabindex="-1" role="dialog" aria-labelledby="addusersModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="save_users" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name='dataid' id="dataid" value="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addusersModalLabel">Add Users</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="alert alert-success" id="successBtntitle" style="display:none">
                                Users added successfully 
                            </div>

                            <ul class="alert alert-danger" id="dangerValidationtitle" style="display:none"></ul>
                            <div class="form-group">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="users">Select Users for Email Notification</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control selectm select_user" name ="email_users[]" id="email_users" style="font-size: 13px;width:100%;"  multiple  required>                        
                                        @if(count($users)>0)
                                        @foreach($users as $value)
                                            <option value="{{$value->id}}"> {{$value->name}}</option>
                                        @endforeach 
                                        @endif              
                                    </select>                                                       
                                </div>           
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
            <!-- add user modal  ends-->
            <script>
                $(document).ready(function() {
                    $("#tabledata").one("preInit.dt", function() {
                        let table = $('#tabledata').DataTable();
                        $("#module").on('change', function() {
                            if ($(this).val()) {
                                table.columns(1).search($(this).val()).draw();
                            } else {
                                table.columns(1).search('').draw();
                            }
                        });
                    });
                });
            </script>
            <script>
                let CSRF_TOKEN = "";
                    window.onload = function() {
                        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    }

                function chkval(details)
                {  
                    var modules = details;
                    var modules_array = modules.split(',');
                    
                    document.getElementById('itemname').innerHTML=modules_array[1]; 
                    document.getElementById('dataid').value=modules_array[0];
                    document.getElementById('datasid').value=modules_array[0]; 
                    

                    $.ajax({
                                    data: {
                                            "datasid": $(datasid).val()
                                            , _token: CSRF_TOKEN
                                        }          
                                    , url: "{{ route('admin.selectemaildetails')}}"
                                    , type: "POST"
                                    , success:function(response) {
                                    if(response.status == 200)
                                            {
                                                var userdatalist = response.data; 
                                                var notificationid = response.id;               
                                                var userhtmldatas='';
                                                if(userdatalist.length>0)
                                                {                                                            
                                                    for(let i=0;i<userdatalist.length;i++)
                                                    {   
                                                        if(userdatalist[i]['profile_picture']!=null)
                                                        {
                                                            var image = '<img class="img-avatar img-avatar32" src="/storage/profile/'+userdatalist[i]['profile_picture']+'" alt="'+userdatalist[i]['profile_picture']+'"></div>';
                                                        }
                                                    else if(userdatalist[i]['profile_picture']==null)
                                                        {
                                                            var image = '<img class="img-avatar img-avatar32" src="{{asset("/images/avatar10.jpg")}}" alt=""></div>';
                                                        }
                                                    
                                                        var removeitem = '<a href="/admin/emailnotification/deleteemailusers?val='+userdatalist[i]['id']+'&value='+notificationid+'"  class="font-w600" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times text-danger"></i></a>';
                                                        
                                                        
                                                        userhtmldatas+='<div class="col-2 p-2 mb-2 bg-gray-lighter text-white">\
                                                                        '+image+'</div>\
                                                                        <div class="col-6 p-2 mb-2 bg-gray-lighter text-white">\
                                                                        <span class="font-size-sm font-w600 text-capitalize text-left" style="vertical-align:bottom;"><a href="">'+userdatalist[i]['name']+'</a></span></div>\
                                                                        <div class="col-4 text-right p-2 mb-2 bg-gray-lighter text-dark">'+removeitem+'</div>';
                                                    }
                                                } 
                                                else
                                                {
                                                    userhtmldatas+='<div class="col-12">&nbsp;</div>';
                                                }
                                                $('#getemaildetails').html(userhtmldatas); 
                                                
                                            }
                                    
                                    }

                                });



                    return false;
                    
                }

                


                $(document).on('submit','#save_users',function(e){
                e.preventDefault();

                var id = $('#dataid').val();
                var users = $('#email_users').val();
                
                $.ajax({
                        data: {
                            "dataid" : id,
                            "users_value" : users
                            , _token: CSRF_TOKEN
                        }
                        , url: "{{ route('admin.addemailnotificationajax')}}"
                        , type: "POST"
                        , success: function(response) {
                            if(response.status == 200)
                            {
                                $('#successBtntitle').css('display', 'block');
                                setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            }                
                        }          
                    });
                


                });




                function chkblockemails()
                {
                            
                    mess1= "Are you sure?";
                    x=confirm(mess1);
                    if(x==true)
                    {
                        mess2='Email Notification will be blocked,Press OK to continue.';
                        y=confirm(mess2);
                        if(y==true)
                        {

                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }


                function chkunblockemails()
                {
                            
                    message1= "Are you sure?";
                    x=confirm(message1);
                    if(x==true)
                    {
                        message2='Email Notification will be unblocked,Press OK to continue.';
                        y=confirm(message2);
                        if(y==true)
                        {

                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
                
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
            <script>
                $('.select_user').select2();
            </script>
        </div>
 
@endsection