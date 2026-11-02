@extends('admin.nav')

@section('content')



<!doctype html>
<html lang="en">
   

            <!-- Main Container -->
            <main id="main-container">
                <!-- Hero -->
               
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content" style="width:92%;margin-left:50px;margin-top:-80px">
                    <!-- Full Table -->
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">MS.10.01 Employee Details</h3>
                            <ol class="breadcrumb breadcrumb-alt" style="margin-left:53%;">
                                <li class="breadcrumb-item">Occupational Health</li>
                        </div>
                        @if (session('error'))
                                            <div class="alert alert-danger" role="alert">
                                            <a class="close" data-dismiss="alert" style="curson:pointer">×</a>
                                                {{ session('error') }} 
                                            </div>    
                        @endif 
                        @if (session('success'))
                                            <div class="alert alert-success" role="alert" >
                                            <a class="close" data-dismiss="alert" style="curson:pointer">×</a>
                                                {{ session('success') }} 
                                            </div>    
                        @endif 
                        <div class="block-content block-content-full"  style="width:80%;margin-left:90px;" >
                        
                            <div class="table-responsive" style="height:350px;overflow:auto;">
                            @if(count($empdetails)>0)
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead >
                                        <tr>
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize" >Sl.no</th>
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Employee.No</th>
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>  
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Position</th>  
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Department</th>  
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Date of Joining</th>  
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Date of Birth</th>  
                                        </tr>

                           
                                    </thead>
                                    <tbody >

                                    <?php $number = 1; ?>
                                    @foreach($empdetails as $emp)
                            <tbody>
                            <tr>                                        
                            <td style="font-size:10px" class="text-center">{{ $number}}</td>
                            <?php $number=$number+1; ?>
                                             <td style="font-size:10px">{{$emp->emp_number}}</td>
                                             <td style="font-size:10px">{{$emp->emp_name}}</td>
                                             <td style="font-size:10px">{{$emp->emp_position}}</td>
                                             <td style="font-size:10px">{{$emp->emp_department}}</td>
                                             <td style="font-size:10px">{{$emp->joining_date}}</td>
                                             <td style="font-size:10px">{{$emp->emp_dob}}</td>
                                         </tr> 
                                     @endforeach
                                </table>
                               
                                @else
                                <h2 style="font-size:13px;">No Employee Details Uploaded</h2>
                                <form method="post"  action="{{url('/admin/upload/empdetails')}}" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="col-lg-8 col-xl-5">
                                        <div class="form-group" style="margin-top:50px">
                                        <input type='file' class="form-control" id="example-text-input" style="font-size:13px;margin-left:-10px" required  name="emp_details">
                                        </div>
                                        <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;margin-left:350px;margin-top:-108px"> </input>
                                    </div>
                                </form>
                                <button class="btn  btn-warning" data-id="note" style="font-size:10px;margin-left:490px;margin-top:-156px" data-target="#note"  data-toggle="modal" title="View">
                                            Note (*important)
                                </button>

            <div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">*Important</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                        <div class="table-responsive">
                        <span style="" style="font-size:10px;">1) The Employee Details Excel Sheet Should have the Headings ' emp_number, emp_name, position, department, doj, dob ' respectively and should be in the same sequence.</span>
                        <br/><br/><span style="" style="font-size:10px;">2)Employee Details Should Be In Single Excel Sheet.</span>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                                @endif
                            </div>
                        </div>
                        <div class="block-header" style="margin-top:-170px;">
                            <h3 class="block-title">Add More Details</h3>
                            <ol class="breadcrumb breadcrumb-alt" style="margin-left:53%;">
                               
                            </div>
                            <div class="block-content block-content-full"  style="width:80%;margin-left:90px" >
                            <div class="table-responsive">
                                <form  method="post" action="{{url('/admin/addmore/employeedetails')}}">
                                @csrf
                                <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable" style="width:148%" >
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Employee No.</th>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Name</th>
                                         <th class="text-center" style="font-size:13px;width: 240px;text-transform:capitalize">Position</th>
                                         <th class="text-center"  style="font-size:13px;width: 140px;text-transform:capitalize">Department</th>
                                         <th class="text-center"  style="font-size:13px;width: 140px;text-transform:capitalize">DOJ</th>
                                         <th class="text-center"  style="font-size:13px;width: 140px;text-transform:capitalize">DOB</th>
                                         <th class="text-center"  style="font-size:13px;width: 140px;text-transform:capitalize"></th>
                                 
                                    </tr>
                                </thead>
                                <tbody> 
                                        <tr> 
                                        <td>
                                        <input type="text" name="doc[0][number]" style="font-size: 13px;"class="form-control"  >
                                             </td>
                                             <td>
                                        <input type="text" name="doc[0][name]" style="font-size: 13px;"class="form-control">
                                             </td>
                                             <td>
                                        <input type="text"name="doc[0][position]" style="font-size: 13px;"class="form-control">
                                             </td>
                                             <td>
                                        <input type="text" name="doc[0][department]" style="font-size: 13px;"class="form-control">
                                             </td>
                                             <td>
                                        <input  type="date"  class="js-datepicker form-control" style="font-size: 13px;"class="form-control"  name="doc[0][doj]" data-week-start="1" data-autoclose="true"  data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" >
                                             </td>
                                             <td>
                                        <input  type="date"  class="js-datepicker form-control" style="font-size: 13px;"class="form-control"  name="doc[0][dob]" data-week-start="1" data-autoclose="true"  data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" >
                                             </td>
                                            
                                    <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add witness">
                                            <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td> 
                                        </tr> 
                                </tbody>
                                  
                            </table>
                            
                            
                </div>
                                    <div class="text-right" style="margin-top:10px;">  
                                        <input type="submit" value="Add" class="btn btn-primary" style=""> </input>
                                    </div>
                                    </form>
                </div>
                                       
                </div>
               
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

         

        <!--</div>-->
        <!-- END Page Container -->
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var i = 0;
    $("#add").click(function(){
        ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="doc['+i+'][number]" style="font-size: 13px;"class="form-control"  ></td><td><input type="text" name="doc['+i+'][name]" style="font-size: 13px;"class="form-control"></td><td><input type="text"name="doc['+i+'][position]" style="font-size: 13px;"class="form-control"></td><td><input type="text" name="doc['+i+'][department]" style="font-size: 13px;"class="form-control"></td><td><input  type="date"  class="js-datepicker form-control" style="font-size: 13px;"class="form-control"  name="doc['+i+'][doj]" data-week-start="1" data-autoclose="true"  data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" ></td><td><input  type="date"  class="js-datepicker form-control" style="font-size: 13px;"class="form-control"  name="doc['+i+'][dob]" data-week-start="1" data-autoclose="true"  data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" ></td><td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add witness"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr> ');
    });
        $(document).on('click', '.remove-tr', function(){  
    $(this).parents('tr').remove();
    });
    </script>
</html>
@endsection