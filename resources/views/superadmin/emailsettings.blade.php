@extends('superadmin.datatable')

@section('content')


                
                  <!-- END Hero -->
                  <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Email Settings</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->


    <!-- Page Content -->
    <div class="content" style="padding-top:30px;width:93%;margin-left:40px;">
                  <!-- Horizontal -->
                   <div class="block block-rounded">                       
                       <div class="block-content block-content-full">
                           <div class="row">                                
                               <div class="col-lg-10">
                                   <!-- Form Horizontal - Default Style -->
                           <form class="mb-5" method="POST" action="{{ route('updateemailsettings') }}" enctype="multipart/form-data" >
                               {{csrf_field()}}
                                   @if(Session::has('emailmsg'))
                                       <div class="alert alert-primary">
                                           <a class="close" data-dismiss="alert">×</a>
                                           {!!Session::get('emailmsg')!!}
                                       </div>
                                   @endif
                                   <div class="col-lg-10">
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Mail Driver</label>
                                           <div class="col-sm-8">
                                            <input type="text" name="driver" style="font-size: 13px;" value="{{$oldsettings['driver']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail Driver">
                                           </div>
                                       </div>
                                      
                                       <div class="form-group row">
                                           <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="title" >Mail Host</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="host" style="font-size: 13px;" value="{{$oldsettings['host']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail Host">
                                           </div>
                                       </div>

                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Mail Port</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="port" style="font-size: 13px;" value="{{$oldsettings['port']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail Port">
                                           </div>
                                       </div>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Mail User_Name</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="uname" style="font-size: 13px;"  value="{{$oldsettings['username']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail User_Name" >
                                           </div>
                                       </div>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Mail Password</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="password" style="font-size: 13px;" value="{{$oldsettings['password']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail Password" >
                                           </div>
                                       </div>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Mail From_Address</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="faddress" style="font-size: 13px;" value="{{$oldsettings['from_address']}}"  class="form-control form-control-lg form-control-alt"  placeholder="Mail From_Address" >
                                           </div>
                                       </div>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Mail From_Name</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="fname" style="font-size: 13px;" value="{{$oldsettings['from_name']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail From_Name" >
                                           </div>
                                       </div>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Mail Encryption</label>
                                           <div class="col-sm-8">
                                           <input type="text" name="encryption" style="font-size: 13px;" value="{{$oldsettings['encryption']}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail Encryption" >
                                           </div>
                                       </div>
                                   </div>
                                   <br>
                                   <div class="form-group row">
                                        <div class="col-md-6  col-xl-2" style="margin-left:50%">
                                            <button type="submit" class="btn btn-block btn-alt-success" style="font-size: 13px;">
                                                <i class=""></i> Update
                                            </button>
                                        </div>
                                    </div>
                               </form> 
                           
                                                
                   </div>
               </div>
           </div>
       </div>
    <!-- END Page Content -->

<!-- END Main Container -->


@endsection