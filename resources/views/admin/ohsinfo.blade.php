@extends('admin.datatable')
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS 05.01 OHS info Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        Health and Safety Risk
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full"  style="width:84%;">
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Attachments</th>
                        <th class="text-left"style="width: 5%;font-size:10px;text-transform:capitalize" >Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($ohsinfo as $c)
                        {{csrf_field()}}  
                        <?php $id = $c->id ?>
                        <tr>
                            <td style="font-size:10px" class="text-left">
                                    <span>{{ $number }}</span>
                            </td>
                            <td  style="font-size:10px" class="text-left">
                                    <span>{{$c->infoname}}</span>
                            </td>
                            <?php $number++; ?>
                            <td class="text-left">
                                <div class="btn-group">   
                                    <a href="{{url('/admin/removeohsinfo?val='.$id)}}" onclick="return confirm('The attachment will be deleted permanently,Press OK to continue')" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
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
                    <form method="post"  action="{{url('/admin/addohsinfo')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="example-file-input-multiple" style="font-size:13px;" name="file[]" required multiple>
                                <div class="input-group-append">
                                    <input type="submit" id="add" name="add" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>                     
<!-- END Page Content --> 
@endsection