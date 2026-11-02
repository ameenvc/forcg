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
                <h3 class="block-title">MS.20.02 &nbsp;Agenda Table</h3>
                
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                      <a href="{{route('management.addagenda')}}" class="btn btn-sm btn-alt-primary link-fx" style="font-size:12px;">Add Agenda</a>

                        </li>
                    </ol>
                </nav>
            </div>

            <!-- <div class="row"> 
                <div  style="width:80%;margin-left:90px">
                    <table class="table table-borderless">
                        <tr>
                            <td align="right">
                                <a href="{{route('management.addagenda')}}" class="btn btn-success btn-sm">Add</a>
                            </td>
                        </tr>
                    </table>
                </div> -->

            <div class="block-content block-content-full" style="width:90%;">
                <div class="table-responsive">         
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">#</th>
                            <th class="d-sm-table-cell text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Agenda</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Agenda Type</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($agendapoints as $agendapoint)
                        {{csrf_field()}}

                        <?php $id = $agendapoint->id ?>
                        <tr>
                            <td style="font-size:10px;" class="text-left"></td>

                            <td style="font-size:11px" class="text-left">
                                <?php 
                                    $points = $agendapoint->points;
                                    $point_arrays = explode("\n",$points);
                                ?>
                                 <ul>
                                    @foreach($point_arrays as $point_array)
                                        <li style="margin-bottom:2px;position:relative;left: -12px;">{{$point_array}}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="font-size:11px" class="text-left">
                                <span>{{$agendapoint->agenda_type}}</span>
                            </td>

                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">
                                
                                <div class="btn-group">
                                    <a href="{{url('/admin/management/editagenda/'.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <!-- <a href="" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a> -->
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
