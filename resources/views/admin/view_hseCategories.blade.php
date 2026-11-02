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
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
    @endif
    <div class="block block-rounded"  >
        <div class="block-header">
            <h3 class="block-title"> Categories Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        HSE Observations
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                          
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full"  >
                <thead>
                    <tr>
                        <th class="text-left" style="width:13%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th  class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Category Name</th>
                        <th class="text-left"style="width:10px;font-size:10px;text-transform:capitalize" >Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $number=1; ?>
                    @foreach($hsecategories as $c)
                    {{csrf_field()}}  
                    <?php $id = $c->id;?>
                    <tr>
                        <td style="font-size:10px;" class="text-left">
                            <span  style="width:5px">{{ $number }}</span>
                        </td>
                        <td  style="font-size:10px;width:10px" class="text-left">
                                <span>{{$c->categoryname}}</span>
                        </td>
                        <td style="font-size:10px;width:10px" class="text-left">
                            <?php $number++; ?>
                            <div class="btn-group"> 
                                <a href="{{url('/admin/edithseCategory?val='.$id)}}" class="btn btn-sm btn-alt-primary"  style="font-size:10px"data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                <a href="{{url('/admin/removehseCategory?val='.$id)}}" class="btn btn-sm btn-alt-primary"onclick="return confirm('Are you sure?')"style="font-size:10px"  data-toggle="tooltip" title="Delete" ><i class="fa fa-fw fa-times"></i></a>

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
                    <form method="post" action="{{url('/admin/addhseCategory')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"style="font-size: 13px;" id="example-text-input" placeholder="Enter HSE Category" required  name="category" >
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
       
    </div>                                                                             
    <div class="block block-rounded" >
        <div class="block-header">
            <h3 class="block-title">Types Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        HSE Observations
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
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Type</th>
                            <th class="text-left"style="width: 5%;font-size:10px;text-transform:capitalize" >Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($hsetypes as $c)
                            {{csrf_field()}}  
                            <?php $id = $c->id ?>
                            <tr>

                                <td style="font-size:10px" class="text-left">
                                        <span>{{ $number }}</span>
                                </td>
                                <td  style="font-size:10px" class="text-left">
                                        <span>{{$c->hsetype}}</span>
                                </td>
                                
                                <td style="font-size:10px" class="text-left">
                                    <?php $number++; ?>
                                    <div class="btn-group"> 
                                        <a href="{{url('/admin/edithseType?val='.$id)}}" class="btn btn-sm btn-alt-primary"  style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="{{url('/admin/removehseType?val='.$id)}}" class="btn btn-sm btn-alt-primary"onclick="return confirm('Are you sure?')" style="font-size:10px"  data-toggle="tooltip" title="Delete" ><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            <div class="text-danger font-weight-bold" style="font-size:12px">
                Notes:<br>
                1- Please classify your observations in the HSE module into three categories: High, Medium, and Low.<br>
                2- Add any types, the first type will be considered the major type, the second as the moderate and the third as the minor category.<br>
                Each category carries a distict weight within the overall scoring alghorithm.
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    @php $disableTypeAdd = count($hsetypes) >= 3; @endphp
                    <form method="post" action="{{url('/admin/addhseType')}}" onsubmit="return validation()" @if($disableTypeAdd) onsubmit="return false;" @endif>
                        {{csrf_field()}}
                        <div class="input-group mb-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                style="font-size: 13px;" 
                                id="example-text-input" 
                                placeholder="Enter type" 
                                required  
                                name="type"
                                @if($disableTypeAdd) 
                                    disabled 
                                    data-toggle="tooltip" 
                                    title="You can add only three types"
                                @endif
                            >
                            <div class="input-group-append">
                                <input 
                                    type="submit" 
                                    name="update" 
                                    class="btn btn-primary" 
                                    style="font-size:12px" 
                                    value="Add"
                                    @if($disableTypeAdd) 
                                        disabled 
                                        data-toggle="tooltip" 
                                        title="You can add only three types"
                                    @endif
                                >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection