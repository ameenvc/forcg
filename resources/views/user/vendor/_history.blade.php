@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">

<body>

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Vendor Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Evaluation History</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/vendormgmt">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <form class="mb-5" method="POST" id="eval">
            {{csrf_field()}}
            <!-- Dynamic Table Full -->
            <div class="block block-rounded">

                <div class="block-content block-content-full">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">

                        @if(count($data)>0)
                        <tr>
                            <th class="text-center" rowspan="2" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1"></th>
                            <th class="text-center" colspan="3" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation By Procurement</th>
                            <th class="text-center" colspan="3" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation By Hse</th>
                            <th class="text-center" colspan="3" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation By End user</th>
                            <th class="text-center" rowspan="2" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Total Score </th>
                            <th class="text-center" rowspan="2" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Percentage</th>

                        </tr>
                        <tr>

                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;width:40px;">User Id</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation Date</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;width:90px;">Score Given</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">User Id</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation Date</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;width:90px;">Score Given</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">User Id</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Evaluation Date</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;width:90px;">Score Given</th>


                        </tr>

                        </thead>
                        <tbody>
                            <?php $no=0;?>

                            @foreach($data as $dat)

                            <tr>

                                <td style="font-weight:bold;font-size:10px;text-transform:capitalize"> Evaluation No {{ $no=$no+1}}<br>({{$dat->type}})</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->proc_id}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->proc_eval_at}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->proc_score}} Out Of {{ $dat->proc_tot}}</td>
                                @if($dat->hse_eval_at!=NULL)
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->hse_id}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->hse_eval_at}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->hse_score}} Out Of {{ $dat->hse_tot}}</td>
                                @else
                                <td style="font-size:10px;text-transform:capitalize">-</td>
                                <td style="font-size:10px;text-transform:capitalize">-</td>
                                <td style="font-size:10px;text-transform:capitalize">-</td>
                                @endif
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->enduser_id}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->enduser_eval_at}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->enduser_score}} Out Of {{ $dat->enduser_tot}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->total_score}} Out Of {{ $dat->outofscore}}</td>
                                <td style="font-size:10px;text-transform:capitalize">{{ $dat->percentage}}%</td>
                            </tr>

                        </tbody>
                        @endforeach
                        @else


                        <h4>No Evaluation Data</h4>
                        @endif
                    </table>

        </form>
    </div>

    <!-- END Dynamic Table Full -->


    </div>
    <!-- END Page Content -->
    {{-- </main> --}}
    <!-- END Main Container -->


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

                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/vendormgmt">
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
