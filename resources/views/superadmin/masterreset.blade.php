@extends('superadmin.datatable')

@section('content')


                       <!-- END Hero -->
                       <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Master Reset</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->
@if($reset=="")
<!--Page Content-->
<div class="content" style="padding-top:30px;width:94%;margin-left:40px;">
                  <!-- Horizontal -->
                   <div class="block block-rounded">
                       <div class="block-content block-content-full">
                           <div class="row">
                               <div class="col-lg-10">
                                   <!-- Form Horizontal - Default Style -->
                           <form class="mb-5" method="POST" action="{{ route('getcode') }}" >
                               {{csrf_field()}}
                                       <div class="form-group row" style="margin-top:30px;">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Email ID</label>
                                           <div class="col-sm-8">
                                           <input type="text" style="font-size: 13px;" name="email" value="{{$sadmin[0]->email}}" class="form-control form-control-lg form-control-alt"  placeholder="Mail From_Name" >
                                           </div>
                                       </div>

                                   <br>
                                   <div class="form-group row">
                                        <div class="col-md-6  col-xl-2" style="margin-left:50%">
                                            <button type="submit" class="btn btn-block btn-alt-success" style="font-size: 13px;">
                                                <i class=""></i> Get Code
                                            </button>
                                        </div>
                                    </div>
                               </form>
                               </div>
                   </div>
               </div>
           </div>
       </div>
    <!-- End Page Content -->
    <!-- END Page Content -->
    @else
    <!--Page COntent-->
    <div class="content" style="padding-top:30px;width:94%;margin-left:40px;">
                  <!-- Horizontal -->
                   <div class="block block-rounded">
                       <div class="block-content block-content-full">
                           <div class="row">
                               <div class="col-lg-10">
                               @if(Session::has('mastermsg'))
                                        <div class="alert alert-danger">
                                            <a class="close" data-dismiss="alert">×</a>
                                            {!!Session::get('mastermsg')!!}
                                        </div>
                                @endif
                                   <!-- Form Horizontal - Default Style -->
                           <form class="mb-5" method="POST" action="{{ route('master.masterreset') }}" >
                               {{csrf_field()}}
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Email ID</label>
                                           <div class="col-sm-8">
                                           <input style="font-size: 13px;" type="text" name="email" value="{{$sadmin[0]->email}}" class="form-control form-control-lg form-control-alt"  placeholder="" >
                                           </div>
                                       </div>
                                       <div class="form-group row" style="margin-top:30px;">
                                           @if(Session::get('vericode')=="")
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Enter Verificaton Code <span class="text-danger">*</span></label>
                                           @elseif(Session::get('vericode')!==Session::get('codeis'))
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Enter Verificaton Code</label>
                                           @else
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Enter Verificaton Code <span class="text-danger">*</span></label>
                                           @endif
                                           
                                           <div class="col-sm-8">
                                           @if(Session::get('vericode')=="")
                                                <input id="vcode" style="font-size: 13px;" type="password" class="form-control form-control-lg form-control-alt" name="vcode" required >
                                            @elseif(Session::get('vericode')!==Session::get('codeis'))
                                                <input id="vcode" style="font-size: 13px;"   type="password" class="form-control form-control-lg form-control-alt" name="vcode">
                                            @else
                                                <input id="vcode" style="font-size: 13px;"  value="{{Session::get('vericode')}}" type="password" class="form-control form-control-lg form-control-alt" name="vcode" required >
                                            @endif
                                           </div>
                                       </div>

                                   <br>
                                   <div class="form-group row">
                                        <div class="col-md-3 col-xl-4" >
                                            <button style="font-size: 13px;" style="display:inline" name="btn" value="1" type="submit" class="btn btn-block btn-alt-success">
                                                <i class=""></i> {{ __('Backup') }}<br/>{{ __('Database') }}
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-xl-4" >
                                            <button style="font-size: 13px;" style="display:inline" name="btn" value="2" type="submit" class="btn btn-block btn-alt-success">
                                                <i class=""></i> {{ __('Backup') }}<br/>{{ __('Files') }}
                                            </button>

                                        </div>
                                        <div class="col-md-3 col-xl-4" >
                                            <button style="font-size: 13px;" style="display:inline" name="btn" value="3" type="submit" class="btn btn-block btn-alt-success" onclick="return confirming()">
                                                <i class=""></i> {{ __('Confirm') }}<br/>{{ __('Reset') }}
                                            </button>
                                        </div>
                                    </div>
                               </form>
                               </div>
                   </div>
               </div>
           </div>
       </div>
    <!-- Page Content -->

        </div>
    </div>
    @endif

<!-- END Main Container -->
<script src="https://use.fontawesome.com/releases/v5.10.1/js/all.js" data-auto-replace-svg="nest"></script>

<script type="text/javascript">
function confirming(){

return confirm("Have you taken backup of both Database and Files? press 'Cancel' to take a backup of Files and Database or press 'Ok' to Confirm reset");
}
</script>
@endsection
