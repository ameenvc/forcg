@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>
<!-- script for sending checkbox values to controller function -->
<script>
    $(function () {
        $('#eval').submit(function (e) {
            // e.preventDefault()  // prevent the form from 'submitting'

            var url = e.target.action  // get the target
            var formData = $(this).serialize() // get form data
            $.post(url, formData, function (response) { // send; response.data will be what is returned
                //  alert('report sent')
            })
        })
    })
</script>
<style>
.header{
    margin-left:10%;
    margin-bottom:-5.7%;
}
</style>
</head> 
    <body>
                
                    <!-- Page Content -->
                 <div class="content"style="width:1100px;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        <div class="block-content block-content-full" >

                                       <!--shift header to white background-->
                 <div class="d-flex header">
                      
                      <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                          <ol class="breadcrumb breadcrumb-alt">
                              <li class="breadcrumb-item">Hr Management</li>
                              <li class="breadcrumb-item" aria-current="page" id="l1">
                                  <a id="a1" onclick="" class="link-fx" href="/">Evaluate</a>
                              </li>
                          </ol>
                      </nav>
                      </div>
                      <!--end of shift header to white background-->
                      @if(count($data)>0)
				      @foreach($data as $dat)
                     <form class="mb-5" action="{{url('/user/hr/evalqs')}}" method="POST">
                       {{csrf_field()}}
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded">
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->   <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            
                            <input type="hidden"  name="id" id="id"  value= "{{ $dat->id}}">
                            <!-- <p class="font-size-sm text-muted" style="font-weight:bold;font-size: 15px;">How satisfied were you with the following?</p> -->
                           <thead>
                                 <tr>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:25px;" >Sl.no</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:50px;">Items</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:35px;">Disappointed</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:30px;">Satisfied</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:35px;">Good</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:35px;">Outstanding</th>
            
                                  
                                </tr>  
                            </thead>
                            <tbody> 
                           
                            <?php $no=0; ?>
                            @foreach($q as $q1)
                            <tbody>
                             <tr>
                               <td class="text-center font-size-sm"> {{ $no=$no+1}}</td>
                               <td class="text-center font-size-sm"><label  for="qp" style="font-size:10px;">{{ $q1->item}}</label></td>
                               
                               <!-- <div class="col-sm-8"> -->
                              
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="0"></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="0.1"> </td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="0.2"></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="0.3"> </td>
                                 
                              	
                         </tr>
                    
                    </tbody>
                    @endforeach
                     </table>
                     <input type="hidden"  name="qn" id="qn"  value= "{{$no}}">
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
                                  
                                <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/register">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Job Description
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/trainingregister">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Training Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                   
                                    <div class="col-6">                    
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Employee List
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
      
    
    </body>
</html>
@endsection
