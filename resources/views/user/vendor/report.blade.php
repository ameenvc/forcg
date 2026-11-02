@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
                            <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

                                <i class="fa fa-print  fa-1x" style="color:grey"></i>

                            </a>
                            </div>
<div class="input-group input-group-sm">
<button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
    <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
</button>
<ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
<li>
    <!--change route as below-->
        <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
            @csrf

            <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
            <!--change hidden input type id as below-->
            <input type="hidden"  id="tvalue1" name="tvalue1"/>
        </form>
    </li>
    <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
    <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
</ul>
</div>

<div class="input-group input-group-sm">
<a type="button"  href="{{url('/user/vendor/addvendor')}}" class="btn btn-sm btn-dual" style="margin-left:8px;"  style="">
<i class="fa fa-1x fa-plus" style="color:grey;"></i>
</a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
{{--<style>
.header{
    margin-left:6%;
    margin-bottom:-2.9%;
}
</style>--}}
        </head>
    <body>

    <input type="hidden" value="a1" id="tabselected">
                <!-- Page Content -->

                 <div class="content" style="width:100%;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" style="margin-left:-1%;">
                    {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
                            <div class="scrollable" id="content" >
                                <table class="move" width="15%" style="margin-left:670px">
                                    <tr>

                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px;">
                                                <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Vendor Management</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="/user/vendor/report" >Vendor Report</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                     <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <!-- <th style="width: 5%;position:sticky;top:-1"></th> -->
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                        <th class="text-center"style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Vendor &nbsp ID</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Vendor Name</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Purpose of Addition</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Year Of Establishment</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Vendor Type</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Frequency Of Evaluation</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Scope of work/product/service</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Address</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Location</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Contact</th>

                                        <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Status</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation Result</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action Comment</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Reevaluate</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th> -->
                                        <th class="d-none d-sm-table-cell"style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0;?>
                                <?php   $d=date("Y-m-d");
                                        $t=date("Y-m-d",strtotime("-14 days"));
                                ?>
                                    @foreach($data as $value)
                                    <?php $rr=$value->id; ?>
                                    <tr>
                                        <!-- <td><input class="text-center font-size-sm" type="checkbox" id="{{ $value->id}}" name="id[]" ></td> -->
                                        <td class="text-center " style="font-size:10px;height:30px;">{{$no=$no+1}}</td>

                                        <td class="text-center " style="font-size:10px;">VEN{{ $value->id }}</td>
                                        <td class="text-center " style="font-size:10px;text-transform:capitalize">{{ $value->vendor_name }}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->vendor_group }}</td>
                                        <td class="text-center" style="font-size:10px;">{{ $value->Yoe }}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->vendor_type }}</td>
                                        <td class="text-center" style="font-size:10px;">{{ $value->eval }}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->scope }}</td>
                                        <td class="text-center" style="font-size:10px;">{{ $value->address }}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->location }}</td>
                                        <td class="text-center" style="font-size:10px;">{{ $value->contact }}</td>
                                        <td class="text-center "style="font-size:10px;width: 50px;"><a href="{{ url('/user/vendor/viewreport?val='.$rr)}}" >View</a></td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                <!-- END Page Content -->
            {{-- </main> --}}
            <!-- END Main Container -->

            <script>
                  function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);
            </script>

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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/vendormgmt ">
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
