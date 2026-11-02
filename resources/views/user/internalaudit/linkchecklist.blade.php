@extends('layouts.multiselect')
@section('content')
<style>
    .paragraph-style {
        font-weight: bold;
        font-size: 20px;
        color: #191e3b;
        margin-top: 30px;
        margin-left: -1px;
    }

    .table-custom thead th {
            font-weight: normal;
            font-size: 11px;
            text-transform: none;
            letter-spacing: 0;
            text-align: center;
        }
        .table-custom tbody td {
            text-align: center;
            vertical-align: middle;
        }
        .table-custom tbody td:nth-child(3) {
            text-align: left;
        }
        .table-custom {
            border-top: none;
            border-left: none;
            border-right: none;
        }
        .table-custom thead tr th {
            background: #252945;
            color: white;
            border: none;
        }
        .table-custom thead tr th:not(:last-child) {
            border-right: 1px solid #e1e6e9;
        }
        .table-custom thead tr th:first-child {
            border-radius: 10px 0 0 0;
        }
        .table-custom thead tr th:last-child {
            border-radius: 0 10px 0 0;
        }

</style>

<div class="bg-body-light">
         <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Audit Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Link and Unlink Checklist</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ url('/user/internalaudit/auditschedule/'.$audit_record->year) }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
<!-- Page Content -->
<div class="content">  
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
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
                        <div class="alert alert-danger">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('error')!!}
                        </div>
                        @endif
                        @if(count($total_checklists_combined)>0)
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Audit ID</label>
                                        <div class="col-sm-12">                                  
                                            <input type="text" style="font-size: 13px;" class="form-control" 
                                                value="{{ $audit_record->aud_id }}" readonly>                      
                                        </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Department</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" 
                                            value="{{$audit_record->departmentObj ? $audit_record->departmentObj->departmentname:'--'}}" readonly>
                                    </div>
                                </div>

                                @if($audit_record->division_id!=null)
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                                        <div class="col-sm-12">
                                            <input type="text" style="font-size: 13px;" class="form-control"   
                                                value="{{$audit_record->divisiondetails ?$audit_record->divisiondetails->name:'--' }}" readonly>
                                        </div>
                                    </div>
                                @elseif($audit_record->division_id==0 && count($division_lists)>0)
                                    @php
                                        $getdivisiondetails = \App\AuditScheduleDivision::where('audit_id',$audit_record->id)->get();
                                        $all_div_names_array = [];
                                        foreach($getdivisiondetails as $getdivisiondetail){
                                            $div_name = \App\Division::where('id',$getdivisiondetail->division_id)->value('name');
                                            array_push($all_div_names_array,$div_name);
                                        }
                                        $all_div_names = implode(',',$all_div_names_array);
                                    @endphp
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                                        <div class="col-sm-12">
                                            <input type="text" style="font-size: 13px;" class="form-control"   
                                                value="{{$all_div_names ?$all_div_names:'--' }}" readonly>
                                        </div>
                                    </div>                  
                                @endif

                                @if($audit_record->area_id!=null)
                                    <div class="form-group  col-sm-12">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">Sub Division</label>
                                        <div class="col-sm-12">
                                            <input type="text" style="font-size: 13px;" class="form-control"   
                                                value="{{$audit_record->areadetails ?$audit_record->areadetails->name:'--' }}" readonly>
                                        </div>
                                    </div>
                                @elseif($audit_record->area_id==0 && count($area_lists)>0)
                                     @php
                                        $getareadetails = \App\AuditScheduleArea::where('audit_id',$audit_record->id)->get();
                                            $all_sub_div_names_array = [];
                                            foreach($getareadetails as $getareadetail){
                                                $sub_div_name = \App\Area::where('id',$getareadetail->area_id)->value('name');
                                                array_push($all_sub_div_names_array,$sub_div_name);
                                            }
                                            $all_sub_div_names = implode(',',$all_sub_div_names_array);
                                    @endphp
                                        <div class="form-group  col-sm-12">
                                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Sub Division</label>
                                            <div class="col-sm-12">
                                                <input type="text" style="font-size: 13px;" class="form-control"   
                                                    value="{{$all_sub_div_names ?$all_sub_div_names:'--' }}" readonly>
                                            </div>
                                        </div>                  
                                @endif

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Auditors</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control"  
                                            value="{{ $audit_record->auditors }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Auditee</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control"  
                                            value="{{ $audit_record->contact }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">From date</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control"  
                                        value="{{ date('d-m-Y',strtotime($audit_record->date)) }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">To date</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control"  
                                        value="{{ date('d-m-Y',strtotime($audit_record->enddate)) }}" readonly>
                                    </div>
                                </div>
                                 
                                @if(count($checklist_created)>0 && (count($records)>0))
                                    <div class="form-group col-sm-6">
                                       <div class="d-flex justify-content-between align-items-center">
                                            <label class="col-sm-7 col-form-label" style="font-size: 13px;">Linked Checklist</label>
                                            <!-- <button class="btn btn-sm btn-alt-primary" style="font-size: 13px;" onclick="">Add Checklist</button> -->
                                                <a href="" class="text-primary" style="font-size: 13px;" href="#" data-toggle="modal" data-target="#linkchecklistModal">
                                                   <b>Click here to Link more checklist</b>
                                                </a>
                                        </div>
                                            <table class="table table-bordered table-custom">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Checklist Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $checklist_ids = $checklist_created->pluck('checklist_id');
                                                $checklist_names = \App\AuditChecklist::whereIn('id', $checklist_ids)
                                                                                    ->pluck('checklist_name', 'id');
                                                @endphp
                                                @foreach($checklist_created as $index => $item)
                                                @php
                                                    $checklist_name = $checklist_names->get($item->checklist_id, '--'); 
                                                @endphp
                                                    <tr style="font-size:12px;">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="text-left">
                                                            {{ucfirst($checklist_name)}}
                                                        </td>
                                                        <td class="text-left">
                                                            <a class="btn btn-sm btn-alt-secondary" onclick="return confirmdelete('{{$item->checklist_id}},{{$audit_id}}')" style="font-size:5px" 
                                                                data-toggle="tooltip" title="Click here to remove/unlink Checklist">
                                                                <i class="fa fa-fw fa-times text-danger" style="font-size:10px"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                @elseif((count($records)==0) && count($checklist_created)>0)
                                    <div class="form-group col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="col-sm-7 col-form-label" style="font-size: 13px;">Linked Checklist</label>
                                                <a href="" class="text-primary" style="font-size: 13px;" href="#" data-toggle="modal" data-target="#linkchecklistModal">
                                                   <b>Click here to Link more checklist</b>
                                                </a>
                                        </div>
                                            <table class="table table-bordered table-custom">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Checklist Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $checklist_ids = $checklist_created->pluck('checklist_id');
                                                $checklist_names = \App\AuditChecklist::whereIn('id', $checklist_ids)
                                                                                    ->pluck('checklist_name', 'id');
                                                @endphp
                                                @foreach($checklist_created as $index => $item)
                                                @php
                                                    $checklist_name = $checklist_names->get($item->checklist_id, '--'); 
                                                @endphp
                                                    <tr style="font-size:12px;">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="text-left">
                                                            {{ucfirst($checklist_name)}}
                                                        </td>
                                                        <td class="text-left">
                                                            <a class="btn btn-sm btn-alt-secondary" onclick="return confirmdelete('{{$item->checklist_id}},{{$audit_id}}')" style="font-size:5px" 
                                                                data-toggle="tooltip" title="Click here to remove/unlink Checklist">
                                                                <i class="fa fa-fw fa-times text-danger" style="font-size:10px"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                @elseif((count($records)>0) && count($checklist_created)==0)
                                    <div class="form-group col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="col-sm-7 col-form-label" style="font-size: 13px;">Linked Checklist</label>
                                                <a href="" class="text-primary" style="font-size: 13px;" href="#" data-toggle="modal" data-target="#linkchecklistModal">
                                                   <b>Click here to Link more checklist</b>
                                                </a>
                                        </div>
                                            <table class="table table-bordered table-custom">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Checklist Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $checklist_ids = $records->pluck('checklist_id');
                                                $checklist_names = \App\AuditChecklist::whereIn('id', $checklist_ids)
                                                                                    ->pluck('checklist_name', 'id');
                                                @endphp
                                                @foreach($records as $index => $item)
                                                @php
                                                    $checklist_name = $checklist_names->get($item->checklist_id, '--'); 
                                                @endphp
                                                    <tr style="font-size:12px;">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="text-left">
                                                            {{ucfirst($checklist_name)}}
                                                        </td>
                                                        <td class="text-left">
                                                            <a class="btn btn-sm btn-alt-secondary" onclick="return confirmdelete('{{$item->checklist_id}},{{$audit_id}}')" style="font-size:5px" 
                                                                data-toggle="tooltip" title="Click here to remove/unlink Checklist">
                                                                <i class="fa fa-fw fa-times text-danger" style="font-size:10px"></i>
                                                            </a>                                                
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                    @else
                                    <div class="form-group col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="col-sm-7 col-form-label" style="font-size: 13px;">Linked Checklist</label>
                                                <a href="" class="text-primary" style="font-size: 13px;" href="#" data-toggle="modal" data-target="#linkchecklistModal">
                                                   <b>Click here to Link more checklist</b>
                                                </a>
                                        </div>                           
                                            <table class="table table-bordered table-custom" >
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Checklist Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr style="font-size:12px;">
                                                    <td colspan="3" align="center" class="text-danger">No Checklist Linked</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                    </div>                      
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label class="col-sm-12 col-form-label text-danger text-center" style="font-size: 15px;">
                                No checklists exist under this department, division or subdivision
                                </label>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
 

<!-- link checklist starts-->
<div class="modal fade" id="linkchecklistModal" tabindex="-1" role="dialog" aria-labelledby="linkchecklistLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkchecklistLabel">Link Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearvalues()">
                        <span aria-hidden="true">&times;</span>
                    </button>              
                </div>
            <form id="link_checklist_ajax" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success" id="successchecklistlinked" style="display:none">
                        Checklist linked successfully
                    </div>

                    <ul class="alert alert-danger" id="dangerchecklistnotlinked" style="display:none">
                      Internal error ! Checklist not Linked
                    </ul>

                    <ul class="alert alert-danger" id="technical_error" style="display:none">
                      Unable to process the request
                    </ul>
                                       
                    <div class="form-group">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Select the checklist required for this audit <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-control" name="checklist[]" id="checklist_value" 
                                   style="font-size: 12px;width:100%;" required multiple>
                           
                                   @if(count($total_checklists)>0)
                                        @foreach($total_checklists as $total_checklist)
                                            <option value="{{$total_checklist->id}}">
                                                {{$total_checklist->checklist_name}}
                                            </option>
                                        @endforeach
                                   @else
                                      <option disabled>
                                             No checklists available to add
                                      </option>
                                   @endif
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="audit_id" value="{{$audit_record->id}}"/>
                         <input type="hidden" name="year" value="{{$audit_record->year}}"/>
                
                <div class="modal-footer">
                    @if(count($total_checklists)>0)
                        <button type="submit" class="btn btn-sm btn-primary" id="btn-linkcheck" style="font-size:12px;">
                           Submit
                        </button>
                    @else
                        <button disabled class="btn btn-sm btn-primary" id="btn-linkcheck" style="font-size:12px;">
                            Submit
                        </button>
                    @endif
                </div>
            </form>
            </div>
        </div>
    </div>

<!-- link checklist  ends-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    function clearvalues(){
        window.location.reload();
    }
        
    function confirmdelete(details) {
        let audit_checklist = details;
        let audit_checklist_array = audit_checklist.split(',');
        let checklistId = audit_checklist_array[0];
        let auditid = audit_checklist_array[1];
        console.log(checklistId);
        console.log(auditid);
        let url = '{{ route("user.internalaudit.delete.linkchecklist", [":checklistId", ":auditid"]) }}';
        url = url.replace(':checklistId', checklistId).replace(':auditid', auditid);
        Swal.fire({
            title: 'Are you sure you want to unlink this checklist?'
            , text: "If you click on Unlink, checklist will be removed"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#3085d6'
            , cancelButtonColor: '#d33'
            , confirmButtonText: 'Unlink'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace(url);
            }
        })
    }

    window.onload = function() {
        $(document).ready(function() {
                $('#link_checklist_ajax').submit(function(event) {
                    $('.btn-linkcheck').attr('disabled', true);
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                            type: 'POST',
                            url: '{{ route("user.internalaudit.store.linkchecklist") }}',
                            data: formData
                            ,success: function(response) {
                                if(response.status == 200){ 
                                    $('#dangerchecklistnotlinked').hide();
                                    $('#technical_error').hide();
                                    $('#successchecklistlinked').css('display', 'block');
                                    window.location.reload();
                                }
                            }
                        ,error: function(err) {
                            $('.btn-linkcheck').attr('disabled', false);
                            if(err.status === 500) {
                                $('#successchecklistlinked').hide();
                                $('#technical_error').hide();
                                $('#dangerchecklistnotlinked').css('display', 'block');
                            }else{
                                $('#successchecklistlinked').hide();
                                $('#dangerchecklistnotlinked').hide();
                                $('#technical_error').css('display', 'block');
                            }                             
                        }                   
                    });              
                });
            });
    } 
    </script>

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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                    <div class="block-content text-center">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Auditors List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                    <div class="block-content text-center">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Audit Log
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                    <div class="block-content text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Audits
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection