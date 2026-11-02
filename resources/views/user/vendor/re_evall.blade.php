@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>
<!-- script for sending checkbox values to controller function -->
<script>
    $(function () {
        $('#reeval').submit(function (e) {
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
                              <li class="breadcrumb-item">Vendor Management</li>
                              <li class="breadcrumb-item" aria-current="page" id="l1">
                                  <a id="a1" onclick="" class="link-fx" href="/">Reevaluate</a>
                              </li>
                          </ol>
                      </nav>
                      </div>
                      <!--end of shift header to white background-->
                @if(count($data)>0)
				      @foreach($data as $dat)
                     <form class="mb-5" action="{{url('/user/vendor/send')}}"  method="POST">
                       {{csrf_field()}}
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded">
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->   <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <input type="hidden"  name="id" id="id"  value= "{{ $dat->id}}">
                           <thead>
                                 <tr>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:25px;" >Sl.no</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:50px;">Questions</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:35px;">Exceptional</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:30px;">Good</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:35px;">Satisfactory</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:35px;">UnSatisfactory</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize;width:25px;">N/A</th>
                                    <th  class="text-center" style="font-size:10px;text-transform:capitalize">Score</th>
                                  
                                </tr>  
                            </thead>
                            <tbody> 
                           
                           <?php $no=0; ?>
                           @foreach($eval as $e)
                           
                           <tr>
                               <td class="text-center font-size-sm"> {{ $no=$no+1}}</td>
                               <td class="text-center font-size-sm"><label  for="qp" style="font-size:10px;">{{ $e->question }}</label></td>
                               
                               <!-- <div class="col-sm-8"> -->
                               @if($e->score==10)
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="10"></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="7"> </td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="5"></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="2">  </td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn1[]" id="qstn" value="10" ></td>
                               @elseif($e->score==5)
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="5" ></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="3.5" ></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="2" ></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="0.5" ></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn2[]" id="qstn" value="5" ></td>
                                @else
                                 <td class="text-center font-size-sm" ><input type="checkbox" name="qstn[]" id="qstn" value="15" ></td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="12" > </td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="7" > </td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn[]" id="qstn" value="2" > </td>
                                 <td class="text-center font-size-sm"><input type="checkbox" name="qstn3[]" id="qstn" value="15" ></td>
                               @endif
                           <td class="text-center "style="font-size:10px;width: 50px;">  
                                <input type="hidden" id="score[]" name="score[]" value=" {{ $e->score }} ">
                                <label  for="score" style="font-size: 10px;" id="scoree" name="scoree"> {{ $e->score}}</label>
                           </td>
                   
                        </tr>
                 
                         @endforeach
                      
                          </tbody>
                         
                     </table>
                     <div class="form-group row">
                             <label class="col-sm-4 col-form-label" for="vbasis" style="font-size:12px;">Would you Recommend using this firm again?</label>
                                            <div class="col-sm-8" >
                                               <div class="form-check">
                                                   <label class="form-check-label" for="vendortype" id="lbltype1" style="font-size:13px;">
                                                     <input class="form-check-input" type="radio" name="recommend" id="yes" value="Yes" ><span></span>Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                  </label>
                                                  <label class="form-check-label" for="vendortype" id="lbltype2" style="font-size:13px;">
                                                     <input class="form-check-input" type="radio" name="recommend" id="no" value="No"><span></span>No
                                                  </label>
                                                
                                           </div>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/vendormgmt">
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
