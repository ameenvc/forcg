@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>

<style>
.header{
    margin-left:10%;
    margin-bottom:-5.7%;
}
</style>
</head> 
    <body>
                 <!-- Hero -->
                 <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Vendor Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add Vendor</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/vendor/register">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content">
                  

                   <!-- Horizontal -->
                    <div class="block block-rounded">
                       
                        <div class="block-content block-content-full">
                            <div class="row">
                                
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <form name="form" class="mb-5" action="{{url('/user/vendor/submit')}}" onsubmit="return validation()" method="POST">
                                        {{csrf_field()}}
                                        @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                    {{ $error }}
                            </div>
                            @endforeach
                            @endif	
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif
                                         <div class="form-group row">
                                             <label class="col-sm-4 col-form-label" for="vendorname" style="font-size:13px;">Vendor name</label>
                                              <div class="col-sm-8">
                                                <input type="text" style="font-size:13px;" class="form-control" id="vendorname" name="vendorname" placeholder="Enter vendor name" required>
                                              </div>
                                         </div>
                     
                        </div>
                        <div class="form-group row">
                                            <div class="col-sm-8 ml-auto">
                                                <button type="submit" style="margin-left:550px;" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                 @endforeach
            @endif
        </form>
                    </div>
                   
                    <!-- END Dynamic Table Full -->

                   
                </div>
                <!-- END Page Content -->
            {{-- </main> --}}
            <!-- END Main Container -->

          
            <!-- END Footer -->

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Options</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">
                                    <!-- <div class="col-6">
                                        
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/vendor/addvendor')}}" >
                                            <div class="block-content text-center"style="height:130px" >
                                             <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-4">
                                                Add Vendor
                                                </p>
                                            </div>
                                        </a>
                                       
                                    </div> -->
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/vendor/register">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/vendor/report">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Vendor report
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->
      
    
    </body>
</html>
@endsection
