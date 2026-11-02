@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
                            <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
                           
                                <i class="fa fa-print  fa-1x" style="color:grey"></i> 
                         
                            </a>
  </div>
<div class=" input-group input-group-sm">
<button type="button" class="btn btn-sm btn-dual btn-default dropdown-toggle mr-0 mb-0" style="margin-left:9px; " data-toggle="dropdown">
    <i class="fa fa-file-export fa-1x" style="color:grey;"></i> 
</button>
<ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">    
   
<li>
<!--change the route to pass hidden id of specific table-->
<form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
    @csrf
    <button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
    <!--change hidden input type id as below-->
    <input type="hidden"  id="tvalue2" name="tvalue2"/>
</form>
</li>
<li><button  onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
<li><button  onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
                                                            
</ul>
</div>

<div class="input-group input-group-sm">
<a type="button"  href="{{url('/user/context/addparty')}}" class="btn btn-sm btn-dual"  style="margin-left:8px;"  style="">
<i class="fa fa-1x fa-plus" style="color:grey;"></i>
</a>
</div>
@endsection
@section('content')
{{--<style>
.header{
    margin-left:7%;
    margin-bottom:-3.6%;
}
</style>--}}
<?php 
    $issues=$issues2=[];
    ?>
      <input type="hidden" value="a2" id="tabselected">  

                    <!-- Page Content -->
                
                 <div class="content" style="width:100%;">
                 @if(count($issues1)>0) 
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                    {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
                            <div class="scrollable" style="height:450px;" >
                                <table class="move" width="15%" style="margin-left:670px">
                                    <tr>
                                   
                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px;">
                                                <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Context Management</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="" >Interested Party Register</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                   
                                     <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Id</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Interested Party(IP)</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Type</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Purpose of IP</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Requirement of IP</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Monitoring Methodology</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Accountability</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                        <!-- <th class="text-center" style="font-size:10px;">View</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0;?>
                                @foreach($issues1 as $issue) 
                                <?php $rr=$issue->Cid;                               
                                //giving unique id to each + button
                                $btnid=$issue->partyid."1";
                                $spnid=$issue->partyid."2";
                                ?>  
                                    <tr>
                                        <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}<b><button style="font-weight:bold;color:blue;border:none;background:none;outline:none" id="{{$btnid}}" value="+" onclick="show_hide('{{$issue->partyid}}','{{$btnid}}','{{$spnid}}')"><span id="{{$spnid}}" class="fa fa-angle-right text-muted"></span></button></b></td>
                                        <td class="text-center " style="font-size:10px;">{{$issue->partyid}}</td>
                                        <td class="text-center" style="font-size:10px;">
                                          {{$issue->intr_party}}
                                        </td>
                                        <td class="d-none d-sm-table-cell text-center " style="font-size:10px;">
                                          {{$issue->type}}
                                        </td>
                                        <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                           {{$issue->purpose}}
                                        </td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>                                     
                                      
                                        <div class="dropdown">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary dropdown-toggle" style="font-size:10px;" id="dropdown-dropleft-outline-dark" data-toggle="dropdown" >Actions</button>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                    <a class='dropdown-item'  href="{{ url('/user/context/addmoreparty?val='.$rr)}}" style="font-size:10px;">Add Requirements</a>
                                                </div>
                                            </td>
                                        </div>  
                                      
                                    </tr>
                                   <tbody style="display:none;" id="{{$issue->partyid}}">
                                  
                                        @foreach($issues3 as $dat)                                          
                                            @if( $dat->Cid == $issue->Cid) 
                                                <tr>
                                                    <td class="text-center"><input class="checkbox"  type="checkbox" id="{{ $dat->id}}"   name="id[]" ></td>
                                                    <td ></td> 
                                                    <td ></td>  
                                                    <td ></td>
                                                    <td ></td>
                                                        
                                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;"> {{$dat->reqip}}</em></td>
                                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;"> {{$dat->monitoring}}</em></td>
                                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;"> {{$dat->accountability}}</em></td>
                                                    
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 50px;" data-toggle="modal" data-id="{{ $dat->id }}" data-target="#viewModal2{{$dat->id}}"><strong>View</strong> </button>
                                                        <div id="viewModal2{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h2 class="modal-title">Detailed View</h2>
                                                                    </div>
                                                                    <table class="table table-striped">
                                                                            <tr>
                                                                                <th class="text-left" style="width:70px;">Interested Party ID</th>
                                                                                <td class="text-left" >{{$dat->partyid}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Interested Party</th>
                                                                                <td class="text-left" >{{$dat->intr_party}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Type</th>
                                                                                <td class="text-left" >{{$dat->type}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Purpose</th>
                                                                                <td class="text-left" >
                                                                                <?php echo nl2br($dat->purpose)?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Requirement of IP</th>
                                                                                <td class="text-left" ><?php echo nl2br($dat->reqip)?></td>
                                                                            </tr>
                                                                        
                                                                            <tr>
                                                                                <th class="text-left">Monitoring Methodology</th>
                                                                                <td class="text-left" ><?php echo nl2br($dat->monitoring)?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Accountability</th>
                                                                                <td class="text-left" ><?php echo nl2br($dat->accountability)?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Posted By</th>
                                                                                <td class="text-left" >{{$dat->posted_by}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="text-left">Added Date</th>
                                                                            <td class="text-left" >{{date('M/d/Y',strtotime($dat->created_at))}}</td>
                                                                            </tr>
                                                                            
                                                                            
                                                                        </table>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>                                                                  
                                                </tr>                                                
                                            @endif  
                                        @endforeach                                                   
                                    </tbody>
                                    </tbody>
                                @endforeach  
                            </table>
                            @endif
                            </div>
                        
                    <!-- END Dynamic Table Full -->
                    
                    <!--Delete Button -->
                    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">More</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="si si-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <div class="row gutters-tiny">
                                        
                                            <div class="col-6">
                                                <!-- CRM -->
                                                <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/contextmgmt')}}">
                                                    <div class="block-content text-center">
                                                        <i class="fa fa-bug fa-2x text-primary"></i>
                                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                                        Issue Register
                                                        </p>
                                                    </div>
                                                </a>
                                                <!-- END CRM -->
                                            </div>
                                        <div class="col-6">
                                            
                                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/interestparty">
                                                    <div class="block-content text-center">
                                                        <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                                            InterestedParty Register
                                                        </p>
                                                    </div>
                                                </a>
                                        
                                            </div>
                                        <div class="col-6">
                                            
                                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/changelogreg">
                                                    <div class="block-content text-center">
                                                        <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                                            Change Log Register
                                                        </p>
                                                    </div>
                                                </a>
                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Apps Modal -->
             
                    <!--End of Delete Button -->
                    {{----------delete request button ----------}}  
                    
                    @section('sideitem')
                    @foreach($mr as $mrs)
                    @if(Auth::user()->email==$mrs)
                    <div class="input-group input-group-sm ml-2">
                    <button type="button"   id="editparty" value=id[] style="margin-left:393px;" class="btn btn-sm btn-dual" >
                    <i class="fa fa-1x fa-edit" style="color:grey;"></i>
                    </button>
                    </div>
                    <div class="input-group input-group-sm ml-2">
                    <button type="button"    id="deleteparty" value=id[]   name="delete" data-name="" class="btn btn-sm btn-dual">  
                    <i class="fas fa-1x fa-trash" style="color:red;"></i>
                    </button>      
                    </div>

                    @endif
                    @endforeach
                    @endsection     

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    
                        <script>
                               $("#deleteparty").click(function () {
                                   var dataArr = new Array();
                                   if($('input:checkbox:checked').length > 0){
                                       $('input:checkbox:checked').each(function(){ 
                                           dataArr.push($(this).attr('id'));
                                            console.log(dataArr);
                                           $.ajax({
                                               type    :'get',
                                               url     :"{{route('deleteparty')}}"+"?val="+$(this).attr('id'),
                                               success : function(response){
                                                       // alert(response);
                                                       window.location.href ="{{url('/user/context/interestparty')}}";
                                                       //  redirect('/user/doc/masterlist')->withData($data);
                                               },
                                               error   :function(response){
                                                       alert(errResponse);
                                               }
                                   });
                                       });
                           
                                   }
                                   else{
                                           alert('No Record Selected');
                                       }
                               });
                           </script>
                            
                            
                           
                              {{-----------edit  button ------------}}
                           
                           <script>
                                $("#editparty").click(function () {
                                    var dataArr = new Array();
                                    var group = new Array();
                                    var d;
                                    var all;
                                    if($('input:checkbox:checked').length > 0){
                                        $('input:checkbox:checked').each(function(){ 
                                            dataArr.push($(this).attr('id'));
                                        //  console.log(dataArr);
                                            
                                            all = ""+dataArr;
                                            group.push(all); // push all checkbox values into an array
                                        //  console.log(group);
                                            
                                            
                                    });
                                    $.ajax({
                                                type    :'get',
                                                url     :"{{route('editparty')}}"+"?val="+(all),
                                                success : function(response){
                                                        // alert(response);
                                                    
                                                        window.location.href ="{{route('editparty')}}"+"?val="+(all);
                                                                            // console.log(response);
                            
                                                },
                                                error   :function(response){
                                                        alert(errResponse);
                                                }
                                        });
                            
                                    }
                                    else{
                                            alert('No Record Selected');
                                        }
                                });
                                </script>
                <!-- End of Interested Party register-->

 <!-- Opens from the modal toggle button in the header -->
 
            @include('exporting.forcontextmanagement')
            <!-- END Apps Modal -->
            <script>
         ////Copy the below javascript function in listofaspects.blade
     //javascript function for showing and hiding ohs risks
     function show_hide(id,bid,sid){
             //for display the hidden elements
         if(document.getElementById(bid).value=="+")
         {
         document.getElementById(bid).value="-";
         document.getElementById(sid).className="fa fa-angle-down text-muted";
         document.getElementById(id).style.display="";
         }
          //for hide the tbody elements
         else{
             document.getElementById(bid).value="+";
             document.getElementById(sid).className="fa fa-angle-right text-muted";
         document.getElementById(id).style.display="none";
         }
     }  
    </script>
@endsection 


