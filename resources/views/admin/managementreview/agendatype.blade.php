@extends('admin.datatable')
@section('content')
<div class="content">


    @if(Session::has('message'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('message')!!}
    </div>
    @endif

    @if(Session::has('msg'))
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif

    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.20.01 &nbsp;Agenda Types Table</h3>
            
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        Management Review
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
                        <th class="d-sm-table-cell text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Agenda Type</th>
                        <!-- <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($types as $type)
                    {{csrf_field()}}

                    <?php $id = $type->id ?>
                    <tr>
                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span>{{$type->name}}</span>
                        </td>
                        <?php $number++; ?>
                        <!-- <td style="font-size:10px" class="text-left">
                            
                            <div class="btn-group">
                                <a href="{{url('/admin/management/editagendatypes/'.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                                <a href="" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                        </td> -->
                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/management/addagendatypes')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" style="font-size: 13px;" id="name" name="name" placeholder="Enter Agenda Types" required >
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
