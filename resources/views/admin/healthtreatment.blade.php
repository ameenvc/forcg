@extends('admin.datatable')
@section('content')
<div class="content">

    @if(Session::has('msg'))
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif

    @if(Session::has('message'))
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('message')!!}
    </div>
    @endif

    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.14.02  Treatments Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        Occupational Health
                    </li>
                </ol>
            </nav>                
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Treatment Provided</th>

                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>


                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($treatment as $treat)

                    {{csrf_field()}}

                    <?php $id = $treat->id ?>
                    <tr>
                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span>{{$treat->name}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <?php $number++; ?>
                            <div class="btn-group">
                                <a href="{{url('/admin/health/edittreatment/'.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                <a href="{{url('/admin/health/deletetreatment/'.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/health/addtreatment')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" style="font-size: 13px;" name="treatment" id="treatment" placeholder="Enter Treatment" required>
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
