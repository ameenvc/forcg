@extends('admin.datatable')
@section('content')
    <!-- Page Content -->
<div class="content">

    @if(Session::has('message'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('message')!!}
    </div>
    @endif
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.15.05 Disposal Methods Table</h3>

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        Waste Management
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
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Value</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($wastedispoalmethods as $c)
                    {{csrf_field()}}
                    <tr>
                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>
                        <?php $number++; ?>
                        <td style="font-size:10px" class="text-left">
                            <span>{{$c->value}}</span>
                        </td>
                        <td class="text-left">
                            <div class="btn-group">
                                <a href="{{url('/admin/editwasteDisposalMethod/'.$c->id)}}" class="btn btn-sm btn-alt-primary" style="font-size:13px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                                <a href="{{url('/admin/removewasteDisposalMethod/'.$c->id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{ route("admin.waste.addwastedisposalmethod")}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter disposal method" name="disposal_method" id="disposal_method" required>
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
