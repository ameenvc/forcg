@extends('layouts.multiselect')

@section('content')
    <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Context Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add More Party</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/context/interestparty">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
               
               
                 <!-- Page Content -->
                 <div class="content" style="width:95%;">
                   <!-- Horizontal -->
                    <div class="block block-rounded">                       
                        <div class="block-content block-content-full">
                            <div class="row">                                
                                <div class="col-lg-12">
                                    <!-- Form Horizontal - Default Style -->
                                    <?php $f=0; ?>               
                            <form class="mb-5" action="{{url('/user/context/insertmoreparty')}}" enctype="multipart/form-data"  method="post" files="true" >
                                {{csrf_field()}}                                
                                @if(count($data)> 0 )
                                    @foreach($data as $dat)
                                        <input type="hidden" name="posted" value="{{Auth::user()->email}}"  > 
                                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}"  > 
                            
                                    
                                        <div class="col-lg-10">

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" style="font-size: 12px;" for="intr_party">Interested Party</label>
                                                <div class="col-sm-12">
                                                    <input type="text" readonly class="form-control" id="intr_party" name="intr_party" value="{{ $dat->intr_party }}" style="font-size: 13px;">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="type" style="font-size: 12px;" >Type</label>
                                                <div class="col-sm-12">
                                                    <input type="text" readonly class="form-control" id="type" name="type" value="{{ $dat->type }}" style="font-size: 13px;">    
                                                </div>
                                            </div>   

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="purpose" style="font-size: 12px;">Purpose Of Interested Party</label>
                                                <div class="col-sm-12">
                                                    <input type="text" readonly class="form-control" id="purpose" name="purpose" value="{{ $dat->purpose }}" style="font-size: 13px;">    
                                                </div>
                                            </div>
                                            <input type="hidden" name="Cid" value="{{$dat->Cid}}"> 
                                            <input type="hidden" name="posted_by" value="{{$dat->posted_by}}"> 
                                        </div>
                                        
                                        <div class="col-lg-10"> 
                                            <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">
                                                <thead style="background-color:#FFFFFF">
                                                <tr>
                                                    <th style="width:300px;text-transform:capitalize"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspRequirement Of IP&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                                    <th style="width:300px;text-transform:capitalize"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspMonitoring&nbsp&nbspMethodology&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                                    <th style="width:300px;text-transform:capitalize"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspAccountability&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                                </tr>                                             
                                                </thead>
                                                <tbody >
                                                    <tr >                                                
                                                        <td><textarea type="text"  name="doc[0][reqip]"  class="form-control" style="font-size:12px"></textarea></td>  
                                                        <td><textarea type="text"  name="doc[0][monitoring]"  class="form-control" style="font-size:12px"></textarea></td> 
                                                        <td><textarea type="text"  name="doc[0][accountability]"  class="form-control" style="font-size:12px"></textarea></td> 
                                                    
                                                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
                                                        <td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add Party">
                                                                <i class="fa fa-fw fa-plus"></i>
                                                            </button>
                                                        </td>                                                  
                                                    </tr>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="text-align:center;">
                                        <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Add</button>
                                        <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="row justify-content-center">
                                        <h2>No Data</h2>
                                    </div>
                                @endif
                            </form>         
                    </div>
                </div>
            </div>
        </div>
    

        {{-- <footer id="page-footer" class="bg-body-light">
                <div class="content py-3">
                    <div class="row font-size-sm">
                        <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                           </div>
                        <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                            <a style="margin-left:35%;" class="font-w600" href="" target="_blank">SETILAS QuickQM 2.1.1</a> &copy; <span data-toggle="year-copy"></span>
                        </div>
                    </div>
                </div>
            </footer> --}}
                  <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
             <!-- Opens from the modal toggle button in the header -->
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
             </div>
        <!-- END Page Container -->
        
       


<script type="text/javascript">   
            var i = 0;
            $("#add").click(function(){
                ++i;
                $("#dynamicTable").append('<tr><td><textarea type="text" name="doc['+i+'][reqip]" class="form-control" style="font-size:12px" ></textarea></td><td><textarea type="text" name="doc['+i+'][monitoring]"  class="form-control" style="font-size:12px"></textarea></td><td><textarea type="text" name="doc['+i+'][accountability]"  class="form-control" style="font-size:12px"></textarea></td> <td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
                });
            $(document).on('click', '.remove-tr', function(){  
            $(this).parents('tr').remove();
            });
        </script>    

@endsection