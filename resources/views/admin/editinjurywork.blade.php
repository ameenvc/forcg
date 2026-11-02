@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">      
            <div class="block-header">
                <h3 class="block-title">Edit Case</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        
                        
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/health/injury">Back</a>
                        </li>
                    </ol>
                </nav>
                    
                
            </div>                        
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">                                
                    <div class="col-lg-12">       
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/health/updateinjurywork')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-2 mb-2">
                                <label class="col-form-label" style="font-size: 13px;" for="category">Work Related Case</label>

                                </div>
                                <div class="col-sm-6 mb-2">
                                    <input type="text" class="form-control"  style="font-size: 13px;"   id="case"  name="case" value= "{{$injurywork->name}}" >


                                </div>
                                <div class="col-sm-4 mb-2">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                                <input type="hidden" name="id" class = "xlarge" value= "{{$injurywork->id}}">
                            </div>
                        </form>                                             
                    </div>
               </div>
           </div>
       </div>
    </div>
    <!-- END Page Content -->
@endsection