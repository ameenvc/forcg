@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">


    <body>
        
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">HR Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Evaluation Questions</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/hrtmgmt">Back</a>
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
                                    <form name="form" class="mb-5" action="{{url('/user/hr/submitqstn')}}" method="POST">
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
                                    
                        <table> 
                                  <tr>
                                      <td>
                                            <label   for="periods"style="font-size: 13px;width:100px">Enter Question</label>
                                      </td>
                                         <td>
                                             <input type="text"style="font-size: 13px;width:600px" class="form-control" id="qstn" name="qstn"  required>
                                         </td>
                                         <td>&nbsp;&nbsp;&nbsp;</td>
                                         <td>
                                   
                                               <button type="submit" for="submit" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">Add</button>
                                         </td>     
                                   </tr>

                                   
                               </table>
                                    
                      
                     </form>
                     @if( count($data) > 0)
                     <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                         <thead>
                                <?php $no=0;?>
                                 <tr>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Questions</th>  
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Edit</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Delete</th> 
                                </tr>
                        </thead>
                        @foreach($data as $d)
                              <?php $rr=$d->id; ?>
                                  <tbody>
                                     <tr>
                                         <td class="text-center " style="font-size:10px;"> {{ $no=$no+1}} </td>
                                         <td class="text-center " style="font-size:10px;">{{ $d->item }}</td>
                                         <td class="text-center " style="font-size:10px;">  
                                         <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $d->id}}" data-target="#edit{{$d->id}}"><strong>Edit</strong> </button>
                                         <div id="edit{{$d->id}}" class="modal fadebd-example-modal-lg"  role="dialog" aria-hidden="false" tabindex="-1">
                                    <form name="form" class="mb-5" action="{{ url('/user/hr/editqstns?val='.$rr)}}"  method="POST">
                                       {{csrf_field()}}
                                                      <div class="modal-dialog">
                                                          <div class="modal-content">
                                                          <div class="modal-header">
                                                                   <!-- <h2 class="modal-title">Detailed View</h2> -->
                                                         </div>
                                                         <div class="block-content font-size-sm">
                                                               
                                                                <label   style="font-size:12px;margin-left:1px">Edit Question</label>
                                                                <!-- <input type="text" style="font-size:12px;" name="item" id="item"  value= "{{ $d->item}}"> -->
                                                                <textarea rows="3" type="text"style="font-size: 13px;" class="form-control" id="item" name="item" >{{ $d->item}}</textarea>
                                                                <input type="hidden"  name="id" id="id"  value= "{{ $d->id}}">
                                                                 
                                                       </div>
                                                               <div class="modal-footer">
                                                               <button type="submit" class="btn btn-dark style"style="font-size:10px;width: 60px;" >Submit</button>
                                                               <button type="button" class="btn btn-dark" data-dismiss="modal" style="font-size:10px;width: 60px;">Close</button>
                                                               </div>
                                                         </div>
                                                     </div>
                                                     </form>
                                                </div>
                                         <!-- <a href="{{ url('/user/hr/editqstns?val='.$rr)}}" class="btn btn-primary" style="font-size:10px;width: 60px;" >Edit</a> -->
                                          </td>
                                          <td class="text-center " style="font-size:10px;">
                                          <form class="delete" action="{{url('/user/hr/CarryQstnID?val='.$rr)}}" method="POST">
                                                             <input type="hidden" name="_method" value="DELETE">
                                                             {{ csrf_field() }}
                                                             {{ method_field('DELETE') }} 
                                                             <input class="btn btn-primary" style="font-size:10px;width: 60px;" type="submit" value="Delete" >
                                                      </form>
                                                   </td>
                                            </tr>
                                        </tbody>
                                        @endforeach
                                            </table>
                                        @else
                                    <div class="row justify-content-center">
                                        <h2>No Data</h2>
                                     </div>
                                  @endif
   
                                    </form>
                                    <!-- END Form Horizontal - Default Style -->
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Horizontal -->

               

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
    <script>
    $(".delete").on("submit", function(){
        mess1= "Are you sure?";
        x=confirm(mess1);
        if(x==true)
        {
            mess2='The Question will be deleted permanently,Press OK to continue.';
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
    });
</script>


</html>
@endsection
