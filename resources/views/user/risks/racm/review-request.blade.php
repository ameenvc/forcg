@extends('pages.datatables')
@section('exportadd')
    <div class="input-group input-group-sm">
        <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
            <i class="fa fa-print  fa-1x" style="color:grey"></i>
        </a>
    </div>
    <div class="input-group input-group-sm">
        <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
            <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
        </button>
        <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
            <li>
                <!--change route as below-->
                <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                    @csrf
                    <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                    <!--change hidden input type id as below-->
                    <input type="hidden" id="tvalue3" name="tvalue3" />
                </form>
            </li>
            <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
            <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>

        </ul>
    </div>
@endsection

@section('content')
@include('shared.forms.risk-assets')

@once
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
@endonce

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <style>
        .select2-results__options,
        .select2 {
            font-size: 13px;
        }
        .blank-star {
            color: #F4F5F6 !important;
            text-shadow: 0 0 1px #000;
        }
        .colored-star {
            color: #e5ae67 !important;
        }
        [data-star] {
            text-align:left;
            font-style:normal;
            display:inline-block;
            position: relative;
            unicode-bidi: bidi-override;
            font-size:20px;
        }
        [data-star]::before {
            display:block;
            content: '★';
            /* color: #eee; */
            color: #C0C0C0;
        }
        [data-star]::after {
            position: absolute;
            top: 0;
            content: '★';
            width: 0;
            color: orange;
            overflow: hidden;
            height: 100%;
            display: flex;
        }

        [data-star^="1.9"]::after{width:38%}
        [data-star^="2"]::after{width:40%}
        [data-star^="2.5"]::after{width:50%}
        [data-star^="2.9"]::after{width:58%}
        [data-star^="3"]::after{width:60%}
        [data-star^="3.8"]::after{width:76%}
        [data-star^="5"]::after{width:100%}

        .headingselect {
            white-space: break-spaces;
        }
        .fa-book-reader {
            background: conic-gradient(from -45deg, #8a2be2 110deg,
            #0047AB 90deg 180deg, #34a853 180deg 270deg,
            #fbbc05 270deg) 73% 55%/150% 150% no-repeat;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }
        .label-invalid {
            color: red;
        }
        .status{
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .status-blue{
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-red{
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .status-warning{
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .status-secondary{
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .status-success{
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        #firstColumn::after{
            content: '' !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    @if(count($errors) >0 )
        @foreach($errors->all() as $error)
            <div class='alert alert-warning'>
                {{ $error }}
            </div>
        @endforeach
    @endif
    @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-warning">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('error')!!}
        </div>
    @endif
    <div class="content" style="width:100%;">
        <!-- heading part starts -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <form class="risk-module-form" action="{{route('racm.reviewed')}}" method="post">
                    @csrf
                    <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                        <!-- approved documents listing starts -->
                        <table class="risk-module-table table table-borderless table-striped table-vcenter" id="tabledata">
                            <thead>
                            <tr>
                                <th class="text-center" data-orderable="false" style="font-size:11px;width:1%;text-transform:capitalize;position:sticky;vertical-align: middle">#</th>
                                <th class="text-center" data-orderable="false" style="width:15%;text-transform:capitalize;position:sticky;font-size: 13px;top:2px;font-weight:600;vertical-align: middle;">
                                    Process name
                                </th>
                                <th class="text-center" data-orderable="false" style="width:20%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Risk factor title</th>
                                <th class="text-center" data-orderable="false" style="width:20%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Risk factor description</th>
                                <th class="text-center" data-orderable="false" style="width: 8%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    <span class="p-2" style="background: #ff686a; color: white">High</span>
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 8%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    <span class="p-2" style="background: #ffbf00; color: white">Moderate</span>
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 8%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    <span class="p-2" style="background: #349967; color: white">Low</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php 
                                $count = 1;
                            @endphp
                            @foreach($riskAcms as $riskAcm)
                                @php
                                    $unit = '';
                                    if ($riskAcm->unit == 2) {
                                        $unit = '%';
                                    }
                                    if ($riskAcm->unit == 3) {
                                        $unit = 'No sign';
                                    }
                                    if ($riskAcm->unit == 4) {
                                        $unit = $riskAcm->unit_other ? $riskAcm->unit_other : 'Other';
                                    }
                                @endphp
                                <tr>
                                    <input type="hidden" name="racm[]" value="{{$riskAcm->id}}">
                                    <td class="text-center" style="font-size:12px;">{{$count}}</td>
                                    <td style="font-size: 13px;" class="text-center">
                                        <span>{{ $riskAcm->department ? $riskAcm->department->departmentname : '--' }}</span>
                                    </td>
                                    <td style="font-size: 13px;" class="text-center">
                                        <span>{{ $riskAcm->risk_factor_title }}</span>
                                    </td>
                                    <td style="font-size: 11px;" class="text-left">
                                        <span>{{ $riskAcm->risk_factor_description }}</span>
                                    </td>
                                    <td style="font-size: 13px;" class="text-center">
                                        @if($riskAcm->high)
                                            {{ $riskAcm->high . $unit }}
                                        @endif
                                    </td>
                                    <td style="font-size: 13px;" class="text-center">
                                        @if($riskAcm->moderate)
                                            {{ $riskAcm->moderate . $unit }}
                                        @endif
                                    </td>
                                    <td style="font-size: 13px;" class="text-center">
                                        @if($riskAcm->low)
                                            {{ $riskAcm->low . $unit }}
                                        @endif
                                    </td>
                                </tr>
                                @php $count++ @endphp
                            @endforeach
                            </tbody>
                        </table>
                        <!-- Ends approved documents listing -->
                    </div>
                    <br>
                    <div class="text-right">
                        <button class="btn btn-danger" type="button" style="font-size: 13px;" data-toggle="modal" data-target="#rejection-reason">Reject</button>
                        <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px">Reviewed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="rejection-reason" class="risk-module-form modal fadebd-example-modal-sm" aria-labelledby="exampleModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header bg-primary-darker">
                      <h3 class="block-title text-white">Reason for Rejection</h3>
                      <div class="block-options">
                        <button type="button" class="btn-block-option" aria-label="Close" data-dismiss="modal">
                          <i class="fa fa-fw fa-times"></i>
                        </button>
                      </div>
                    </div>
                <form class="risk-module-form" id="reason-form">
                    <div class="block-content fs-sm">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Reason for Rejection <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" style="font-size:13px;" name="reason" id="reason" cols="30" rows="5" placeholder="Reason for Rejection..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body text-right">
                        <button type="button" class="btn btn-sm btn-secondary me-1" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="save-request">Submit</button>
                    </div>
                </form>
                </div>
                
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "pageLength": -1,
                "paging": false,
                "lengthChange": false,
                "info": false,
                "searching" : false
            });
            $('#DataTables_Table_0_filter').hide();
            $('body').on('submit','#reason-form',function(event){
                event.preventDefault();
                const url = `${window.location.origin}/user/riskmgmt/racm/review-reject-reason`;
                const reason = $('#reason').val();
                const recordids = $('input[name="racm[]"]').map(function () {
                    return $(this).val();
                }).get();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    url,
                    type: 'POST',
                    data: {
                        reason,
                        recordids
                    },
                    success: function (data) {
                        if (data.success) {
                            window.location.assign(`${window.location.origin}/user/riskmgmt/racm`);
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (response) {
                        const status = response.status;
                        const data = response.responseJSON;
                        if (status === 422) {
                            $.each(data.errors, function (field, object) {
                                const errorString = object.join(',')
                                $('.error-' + field).html(errorString);
                            })
                        } else {
                            alert('Process failure!')
                        }
                    }
                })
            });
        });
    </script>
@endsection

