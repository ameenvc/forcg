@extends('layouts.backend')

@section('content')

<style>
    body {
        margin: 0px;
    }

    table {

        border-collapse: collapse;
        margin: 0px;
        padding: 0px;
    }

    td {
        border: 1px solid #D0D0D0;
    }

</style>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Enterprise Risk</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href=""> Risk History</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/riskmgmt">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-12">
                    @if(count($riskhistory) > 0)
                    <table width="100%" cellpadding="8">
                        <tr>
                            <td colspan="4" class="bg-info text-white" style="font-size:20px;height:35px;font-family:Arial, Helvetica, sans-serif;">
                                <center>Risk History</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" width="100%">
                                <table width="100%" cellpadding="8">

                                    <tr>
                                        <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;width:15%">Risk ID</td>
                                        <td style="font-size:13px;text-transform:capitalize;"><a href="#">{{$riskdata->grp_id}}</a></td>
                                        <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Risk Name </td>
                                        <td style="font-size:13px;text-transform:capitalize;">{{$riskdata->riskname}}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Risk Area</td>
                                        <td style="font-size:13px;text-transform:capitalize;">{{$riskdata->department ? $riskdata->department->departmentname : '-'}}</td>
                                        <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Risk group</td>
                                        <td style="font-size:13px;text-transform:capitalize;">
                                            {{$riskdata->riskgroup}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Risk Description</td>
                                        <td colspan="3" style="font-size:13px;text-transform:capitalize;">{{$riskdata->riskdec}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- <tr><td colspan="11" class="bg-gray text-white" style="font-size:15px;height:5px;font-family:Arial, Helvetica, sans-serif;"></td></tr> -->
                        <tr>
                            <td colspan="4" class="text-white" style="font-size:15px;height:5px;font-family:Arial, Helvetica, sans-serif;background:#c1c1c1;"></td>
                        </tr>

                        <tr style="background:#ededed;">
                            <td class="text-center" style="font-size:14px;width:0.5%">#</td>
                            <td class="text-center" style="font-size:14px;width: 15%">Description</td>

                            <td class="text-center" style="font-size:14px;width: 2%">Date</td>
                            <td class="text-center" style="font-size:14px;width: 2%">Action</td>
                        </tr>
                        <?php $no=0;?>
                        @foreach($riskhistory as $value)
                        <tr>
                            <td class="text-center" style="font-size:14px;">{{$no=$no+1}}</td>

                            <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                @if($value->impa==NULL && $value->like==NULL && $value->rank==NULL && $value->mitigating_action==NULL)
                                --
                                @endif
                                @if($value->impa!=NULL && $value->action!=NULL)
                                Impact changed from
                                @if($value->impa=="High")
                                <span class="badge badge-danger">{{$value->impa }}</span>
                                @elseif($value->impa=="Moderate")
                                <span class="badge badge-warning">{{$value->impa }}</span>
                                @else
                                <span class="badge badge-success">{{$value->impa }}</span>
                                @endif
                                to
                                @if($value->impa_updated=="High")
                                <span class="badge badge-danger">{{$value->impa_updated }}</span>
                                @elseif($value->impa_updated=="Moderate")
                                <span class="badge badge-warning">{{$value->impa_updated }}</span>
                                @else
                                <span class="badge badge-success">{{$value->impa_updated }}</span>
                                @endif

                                @elseif($value->impa!=NULL && $value->action==NULL)
                                Impact changed from
                                @if($value->impa=="High")
                                <span class="badge badge-danger">{{$value->impa }}</span>
                                @elseif($value->impa=="Moderate")
                                <span class="badge badge-warning">{{$value->impa }}</span>
                                @else
                                <span class="badge badge-success">{{$value->impa }}</span>
                                @endif

                                @endif

                                @if($value->like!=NULL && $value->action!=NULL)
                                <br>
                                Likelihood changed from
                                @if($value->like=="High")
                                <span class="badge badge-danger">{{$value->like }}</span>
                                @elseif($value->like=="Moderate")
                                <span class="badge badge-warning">{{$value->like }}</span>
                                @else
                                <span class="badge badge-success">{{$value->like }}</span>
                                @endif
                                to
                                @if($value->like_updated=="High")
                                <span class="badge badge-danger">{{$value->like_updated }}</span>
                                @elseif($value->like_updated=="Moderate")
                                <span class="badge badge-warning">{{$value->like_updated }}</span>
                                @else
                                <span class="badge badge-success">{{$value->like_updated }}</span>
                                @endif
                                @elseif($value->like!=NULL && $value->action==NULL)
                                <br>
                                Likelihood changed from
                                @if($value->like=="High")
                                <span class="badge badge-danger">{{$value->like }}</span>
                                @elseif($value->like=="Moderate")
                                <span class="badge badge-warning">{{$value->like }}</span>
                                @else
                                <span class="badge badge-success">{{$value->like }}</span>
                                @endif

                                @endif

                                @if($value->rank!=NULL && $value->action!=NULL)
                                <br>
                                Risk changed from
                                @if($value->rank == 3)
                                <span class="badge badge-danger">High</span>
                                @elseif($value->rank == 2)
                                <span class="badge badge-warning">Moderate</span>
                                @else
                                <span class="badge badge-success">Low</span>
                                @endif
                                to
                                @if($value->rank_updated == 3)
                                <span class="badge badge-danger">High</span>
                                @elseif($value->rank_updated == 2)
                                <span class="badge badge-warning">Moderate</span>
                                @else
                                <span class="badge badge-success">Low</span>
                                @endif
                                @elseif($value->rank!=NULL && $value->action==NULL)
                                <br>
                                Risk changed from
                                @if($value->rank == 3)
                                <span class="badge badge-danger">High</span>
                                @elseif($value->rank == 2)
                                <span class="badge badge-warning">Moderate</span>
                                @else
                                <span class="badge badge-success">Low</span>
                                @endif

                                @endif

                                @if($value->mitigating_action!=NULL && $value->action!=NULL)
                                <br>
                                Mitigating Action changed from <br>{{$value->mitigating_action}} <br>to <br>{{$value->mitigating_action_updated}}
                                @elseif($value->mitigating_action!=NULL && $value->action==NULL)
                                <br>
                                Mitigating Action changed from <br> {{$value->mitigating_action}}
                                @endif
                                <br>

                            </td>

                            <td class="text-center" style="font-size:12px;text-transform:capitalize;">{{date('d-m-Y',strtotime($value->created_at))}}</td>

                            <td class="text-center" style="font-size:12px;text-transform:capitalize;">
                                @if($value->action==NULL)
                                --
                                @else
                                <span class="text-danger"><b>{{$value->action}}</b></span>
                                @endif
                            </td>

                        </tr>
                        @endforeach

                    </table>
                    @else
                    <div class="text-danger text-center">No data</div>
                    @endif

                </div>
            </div>
        </div>
    </div>


    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <!-- Opens from the modal toggle button in the header -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">More</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">

                            <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/addrisk">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Add Risk
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                    <div class="block-content text-center">
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/closedrisks">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Risks
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>

                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/analysis">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 " style="margin-bottom:60px">
                                            Analysis
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


    @endsection
