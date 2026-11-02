@extends('admin.datatable')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">Frequency Table</h3>

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        Calibration Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            @if(Session::has('message'))
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert" style="cursor: pointer">×</a>
                    <span class="fas fa-check-circle"></span> {!!Session::get('message')!!}
                </div>
            @endif
            @if(Session::has('warning'))
                <div class="alert alert-warning">
                    <a class="close" data-dismiss="alert" style="cursor: pointer">×</a>
                    <span class="fas fa-exclamation-triangle"></span> {!!Session::get('warning')!!}
                </div>
            @endif
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left"
                            style="width: 5%;font-size:10px;text-transform:capitalize">Frequency
                        </th>
                        <th class="d-sm-table-cell text-left"
                            style="width: 5%;font-size:10px;text-transform:capitalize">Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($freq as $index => $c)
                        {{csrf_field()}}
                        <tr>
                            <td style="font-size:10px" class="text-left">
                                <span>{{ $index + 1 }}</span>
                            </td>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->frequency}}</span>
                            </td>
                            <td>
                                <a href="{{url('/admin/removefreq?val='. $c->id)}}" class="btn btn-sm btn-alt-primary"
                                    onclick="return confirm('Are you sure?')" style="font-size:10px;"
                                    data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-5"></div>
                <div class="col-sm-7 text-right">
                    <form method="post" action="{{url('/admin/addfreq')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="number" class="form-control @error('freq') is-invalid @enderror"
                                    style="font-size: 13px;" min="0"
                                    id="example-text-input"
                                    value="{{ old('freq') }}"
                                    placeholder="Enter frequency" required name="freq">
                                @error('freq')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                @enderror
                                <select class="form-control @error('freq_time') is-invalid @enderror" name="freq_time" required style="font-size: 13px">
                                    <option value="Year" {{ old('freq_time') == 'Year' ? 'selected' : '' }}>Years</option>
                                    <option value="Month" {{ old('freq_time') == 'Month' ? 'selected' : '' }}>Months</option>
                                    <option value="Day" {{ old('freq_time') == 'Day' ? 'selected' : '' }}>Days</option>
                                </select>
                                @error('freq_time')
                                    <span class="text-danger" style="font-size: 13px">{{ $message }}</span>
                                @enderror
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
<!-- END Page Content -->
@endsection