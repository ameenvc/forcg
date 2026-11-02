@extends('admin.nav')
@section('content')
<!doctype html>
<html lang="en">
    <body>
        <!-- Page Content -->
        <div class="content" style="">
            <!-- Full Table -->
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
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">Add Employee Details</h3>
                </div>

                <div class="block-content block-content-full" >
                    <form method="post"  action="{{url('/admin/upload/empdetails')}}" enctype="multipart/form-data" class="row">
                        {{csrf_field()}}
                        <div class="form-group col-sm-4">
                            <input type='file' class="form-control" id="example-text-input" style="font-size:13px;" required  name="emp_details"/>
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;"/>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-warning" data-id="note" style="font-size:10px;" data-target="#note"  data-toggle="modal" title="View">
                                    Note (*important)
                            </button>

                        </div>
                    </form>
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
                                            <br/><br/><span style="" style="font-size:10px;">2)All Dates Entered Should Be in d/m/Y format. For example : 31/12/2020</span>
                                            <br/><br/><span style="" style="font-size:10px;">3)Employee Details Should Be In Single Excel Sheet.</span>
                                            <br/><br/><span style="" style="font-size:10px;">4)Employee Name and Employee Number are mandatory fields.</span>
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
                </div>
                <br><br><br>
                <div class="block-header">
                    <h3 class="block-title">Add Employees</h3>
                </div>
                <div class="block-content block-content-full">
                    <form  method="post" action="{{url('/admin/addmore/employeedetails')}}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable" >
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Employee ID</th>
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
                                        <td class="text-center">
                                            <button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add employees"><i class="fa fa-fw fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right" style="margin-top:10px;">
                            <input type="submit" value="Add" class="btn btn-primary" style=""/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
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
