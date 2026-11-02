@extends('admin.datatable')

@section('content')

<main id="main-container">
         <!-- Services -->

                <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:88%;margin-left:70px;margin-top:-75px">
                  <!-- Horizontal -->
                   <div class="block block-rounded">

                   <div class="block-header">
                       <h3 class="block-title">Edit Agenda Type</h3>
                       <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                                <ol class="breadcrumb breadcrumb-alt">

                                    <li class="breadcrumb-item" aria-current="page">

                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{route('management.agendatypes')}}">Back</a>
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
                       <div class="block-content block-content-full" style="width:90%;margin-left:100px">
                           <div class="row">
                               <div class="col-lg-6">

                                   <!-- Form Horizontal - Default Style -->
                           <form class="mb-5" method="POST" action="{{url('/admin/management/updateagendatype')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                               {{csrf_field()}}


                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="case">Agenda Types</label>
                                           <div class="col-sm-8">
                                               <input type="text" class="form-control"  style="font-size: 13px;"   id="name"  name="name" value= "{{$types->name}}" >
                                            </div>
                                       </div>

                                        <div class="form-group row"  style="margin-right:50%">
                                            <div class="col-md-6  col-xl-2" style="margin-top:-55px" >
                                            <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;margin-left:480px" value="Update">
                                            </div>
                                        </div>
                                    <input type="hidden" name="id" class = "xlarge" value= "{{$types->id}}">
                            </form>
                        </div>
               </div>
           </div>
       </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->

@endsection
