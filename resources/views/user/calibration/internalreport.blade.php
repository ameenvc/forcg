@extends('layouts.backend')
@section('content')
<style>
    .bor {
    border: 1px solid black;
    border-collapse: collapse;

    }

    .outlined {
    border: 1px solid white;
    }
    table{

    border-collapse: collapse;
    }
    td{
        border:1px solid #D0D0D0;
    }
</style>
<!-- Hero -->
<div class="bg-body-light">
<div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">

        <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item">Calibration Management</li>
                <li class="breadcrumb-item" aria-current="page">
                    <a class="link-fx" href="">Internal Calibration Report</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a class="link-fx" href="/user/calibrationmgmt?rowId={{request()->get('val')}}">Back</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
</div>
<!-- END Hero -->
<div class="content">
    <div class="block block-rounded" style="height:500px">
        <div class="block-content block-content-full" align="center">
            <div class="row">
                <div class="col-md-12">
                    <table width="80%">
                        @if($report!="novalue")
                        <?php $no=0;?>
                        <tr>
                            <td  style="width:100px;height:40px;font-size:13px;"class="bg-success text-white"><center><b>Sl No</b></center></td>
                            <td  style="width:130px;height:35px;font-size:13px;"class="bg-success text-white"><center><b>Report Number</b></center></td>
                            <td  style="width:120px;height:35px;font-size:13px;"class="bg-success text-white">&nbsp;&nbsp;&nbsp;</td>

                        </tr>
                        <?php $no = 1; ?>
                        @foreach($data1 as $value)
                        @php
                            $repno = $value->Report_no ;
                            $ins_no=$value->Instrument_no ;
                        @endphp
                        @if($value-> Report_no !="novalue")
                        <tr>
                            <td class="text-center" style="font-size:14px;width: 50px;height:30px">{{$no++}}</td>
                            <td class="text-center" style="font-size:14px;width: 50px;">
                                {{$value-> Report_no}}
                            </td>
                            <td class="text-center "style="font-size:14px;width: 50px;">
                                <a href="{{url('user/calibration/disprep?val='.$ins_no.'&repno='.$repno)}}">Generate Report</a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" style="font-size:20px;"><center>No Reports Available</center></Td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">OPTIONS</h3>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/calibrationmgmt">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Register
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


