@extends('admin.datatable')
@section('content')
<div class="content">
    {{-- @if(Session::has('message'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('message')!!}
    </div>
    @endif --}}

    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.01.02 Standards Table</h3>
        </div>
        <div class="block-content block-content-full" style="width:80%;">
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Standards</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($standards as $sc)

                    {{csrf_field()}}

                    <?php $id = $sc->id ?>
                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">
                            <span>{{$sc->name}}</span>
                        </td>

                        <?php $number++; ?>

                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addstandard')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" style="font-size: 13px" id="example-text-input" placeholder="Enter standard" required name="standard">
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

