@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .titleheading {
            font-size: 10px !important;
            text-transform: capitalize !important;
            position: sticky !important;
            position: relative !important;
            top: 2px !important;
            font-weight: 600 !important;
        }

        .td-class {
            text-align: center !important;
            font-size: 10px !important;
        }

        .breadcrumb>li+li:before {
            display: none !important;
        }

        .breadcrumb {
            font-size: 15px;
            padding: 0.75rem 1.25rem;
            background: #F6F7F9;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .risk-span-class {
            display: block;
            font-weight: bold;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 13px;
        }

        .high-rank {
            background: #F8D7D6;
            color: #E93A2D;
        }

        .moderate-rank {
            background: #FDF3D1;
            color: #F5C342;
        }

        .other-rank {
            background: #E5F0DB;
            color: #4EAD5B;
        }

    </style>

</head>

<body>
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a href="/user/corrective/corrissues" class="text-black">
                            NCR & Corrective Actions
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Edit Responsibility
                    </li>
                </ol>
            </nav>

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" enctype="multipart/form-data" action="{{url('/user/corrective/submitresp')}}" method="post" files="true">
                            {{csrf_field()}}

                            @if(count($data)>0)
                            @foreach($data as $dat)
                            <?php $rr=$dat->id ;
                            $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');?>
                            <input type="hidden" name="id" value="{{$dat->id}}">
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                                <div class="col-sm-12">
                                    @if($dat->category=='Legal Requirement')
                                    <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$dat->category}}" readonly="">
                                    @elseif($dat->category=='Management Review')
                                    <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$dat->category}}" readonly="">
                                    @elseif($dat->category=='Incident Investigation')
                                    <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$dat->category}}" readonly="">
                                    @else
                                    <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$dat->cat ? $dat->cat->categoryname : '' }}" readonly="">
                                    @endif
                                </div>
                            </div>
                            @php
                                $category = App\Categories::where('id', $dat->category)->first();
                                $enable_source = $category ? $category->enable_source : null;
                            @endphp

                            @if($enable_source)
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="source" style="font-size:13px;">Source</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="source" name="source" style="font-size:13px;" value="{{$dat->source}}">
                                    </div>
                                </div>
                            @endif

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Issue id</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{$dat->issueid}}" readonly="">
                                </div>
                            </div>
                            {{-- <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="department" id="department" style="font-size:13px;" required="true" placeholder="Choose a Department" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" style="font-size:13px;" {{ $dep->id == $dat->correction_department ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
                            @endforeach
                            @endif
                            </select>
                    </div>
                </div> --}}
                {{-- <input type="hidden" id="division_val" value="{{ $dat->division_id }}" />
                <input type="hidden" id="area_val" value="{{ $dat->area_id }}" />

                <div class="form-group d-inline-block" style="width:49%;" id="division">
                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                    <div class="col-sm-12">
                        <select class="form-control" id="division_select" name="division" style="font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                            <option value="0" disabled>Choose Division</option></br>
                        </select>
                    </div>
                </div>

                <div class="form-group d-inline-block" style="width:49%;" id="area">
                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                    <div class="col-sm-12" id="action_number" name="action">
                        <select class="form-control" id="area_select" name="area" style="font-size: 13px;" placeholder="Sub Division">
                            <option value="0" disabled>Choose Sub Division</option></br>
                        </select>
                    </div>
                </div> --}}
                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$dat->title}}" readonly>
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:49%;">
                    <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                    <div class="col-sm-12">
                        @if($dat->type==''||$dat->type=='-')
                        <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$dat->type ? $dat->type : '' }}" readonly="">
                        @else
                        <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$dat->typ ? $dat->typ->name : '' }}" readonly="">
                        @endif
                    </div>
                </div>

                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <div class="table-responsive ml-3 mt-4">

                        <table class="table table-bordered table-striped table-vcenter">
                            <thead class="bg-white">
                                <tr>
                                    <th class="text-center" style="font-size:11px;width:40%;">Correction</th>
                                    <th class="text-center" style="font-size:11px;width:19%;">Department<span class="text-danger">*</span></th>
                                    <th class="text-center" style="font-size:11px;width:19%;" id="division_head">Division</th>                                                
                                    <th class="text-center" style="font-size:11px;width:19%;" id="area_head">Sub Division</th>    
                                    <th class="text-center" style="font-size:11px;width:19%;">Responsibility<span class="text-danger">*</span></th>
                                    <th class="text-center" style="font-size:11px;width:15%;">Target date</th>
                                </tr>
                            </thead>
                            @php
                                $responsibilities = explode(',', $dat->responsibility);
                                $correction_responsibilities = explode(',', $dat->correction_responsibility);
                                $depts = explode(',', $dat->correction_department);
                                $dept_text_array = [];
                                foreach($depts as $department) {
                                $dept_text_array[] = App\Departments::where('id', $department)->value('departmentname');
                                }
                                $resp_text_array = [];
                                foreach($responsibilities as $responsibility) {
                                $resp_text_array[] = App\User::where('email', $responsibility)->value('name');
                                }
                                $target_date = explode(',', $dat->target_date);
                            @endphp
                            <tbody>
                                <tr>
                                    <td class="td-class px-1">
                                        <textarea rows="3" cols="50" class="form-control" name="correction" style="font-size:13px;" readonly>{{$dat->correction}}</textarea>
                                    </td>
                                    <td class="td-class px-1">
                                        <select class="form-control" style="font-size:13px;" required="true" placeholder="Choose Department" name="correction_department" onchange="chooseDivision($(this).val())">
                                            <option value="0" disabled style="font-size:13px;">Choose Department</option>
                                            @if(count($departments)> 0 )
                                            @foreach($departments as $item)
                                            <option value="{{$item->id}}" style="font-size:13px;" {{ $dat->correction_department == $item->id ? 'selected' : '' }}>{{ $item->departmentname }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td class="td-class px-1" id="division_col">
                                        <select class="form-control" id="division_select" name="division" style="font-size: 13px;width:100%;" onchange="chooseArea($(this).val())">
                                        </select>
                                    </td>                                                        
                                        
                                    <td class="td-class px-1" id="area_col">
                                        <select class="form-control" id="area_select" name="area" style="font-size: 13px;width:100%;">
                                        </select>
                                    </td>
                                    <td class="td-class px-1">
                                        <select class="form-control js-select2" name="correction_responsibility[]" style="font-size:13px;" required="true" placeholder="Choose Responsibility" multiple>
                                            <option value="0" disabled style="font-size:13px;">Choose Responsibility</option>
                                            @if(count($user)> 0 )
                                            @foreach($user as $item)
                                            <option value="{{$item->email}}" style="font-size:13px;" {{ in_array($item->email, $correction_responsibilities) ? 'selected' : '' }}>{{ $item->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>

                                    <td class="td-class px-1">
                                        <input type="date" id="target_date{{ $dat->id }}" value="{{ $dat->correction_target_date }}" name="correction_target_date" class="js-datepicker form-control" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" readonly />
                                    </td>
                                    <input type="hidden" name="correction_status" id="cor_status_val{{ $dat->id }}" value="{{ $dat->correction_status }}" />

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @php
                    $rootcause = explode('$', $dat->rootcause);
                    $cnt = count($rootcause) + 1;
                    $correctiveaction = explode('$', $dat->correctiveaction);
                    $responsibility = explode(',', $dat->responsibility);
                    $department = explode(',', $dat->department);
                    $percent_status = explode(',', $dat->percent_status);
                    $count = 1;
                @endphp
                 @if($dat->is_req_rc_analysis_management_review=="yes" && !in_array($dat->processstage,['Awaiting actions','rejected','Entry Under Review','Correction Proposed- RC Awaiting']))
                <div class="form-group" style="display:inline-block;width:98.5%;">
                    <div class="table-responsive ml-3 mt-4">

                        <table class="table table-bordered table-striped table-vcenter" id="root_cause_table{{ $dat->id }}">
                            <thead class="bg-white">
                                <tr>
                                    <th class="text-center titleheading">S #</th>
                                    <th class="text-center titleheading" style="width:420px;">Root Cause</th>
                                    <th class="text-center titleheading" style="width:420px;">Corrective Action</th>
                                    <th class="text-center titleheading" style="width:270px;">Responsibility</th>
                                    <th class="text-center titleheading">Target Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rootcause as $item)
                                <tr id="tr{{ $dat->id }}{{ $count }}">
                                    @php
                                    $dept = isset($department[$count - 1]) ? App\Departments::where('id', $department[$count - 1])->value('departmentname') : '';
                                    $resp = isset($responsibility[$count - 1]) ?App\User::where('email', $responsibility[$count - 1])->value('name') : '';
                                    @endphp
                                    <td class="td-class" id="first_td{{ $dat->id }}{{ $count }}">{{ $count }}</td>
                                    <td class="td-class"><textarea class="form-control" cols="70" name="rootcause[]" id="rootcause{{ $count - 1}}{{ $dat->id }}" rows="5" style="font-size:13px;" readonly>{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : '' }}</textarea></td>
                                    <td class="td-class"><textarea class="form-control" cols="70" name="correctiveaction[]" id="correctiveaction{{ $count - 1 }}{{ $dat->id }}" rows="5" style="font-size:13px;" readonly>{{ isset($correctiveaction[$count - 1] ) ? $correctiveaction[$count - 1] : ''}}</textarea></td>
                                    <td class="td-class">
                                        <select class="form-control" name="department[]" style="font-size:13px;" required="true" placeholder="Choose Department">
                                            <option value="0" disabled selected ="font-size:13px;">Choose Department</option>
                                             <!-- Include active departments -->
                                                @if (count($departments) > 0)
                                                    @foreach ($departments as $dep)
                                                        <option value="{{ $dep->id }}" style="font-size:13px;" 
                                                            {{ isset($department[$count - 1]) && intval($department[$count - 1]) === $dep->id ? 'selected' : '' }}>
                                                            {{ $dep->departmentname }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                                <!-- Include inactive department if already selected -->
                                                @if (isset($department[$count - 1]))
                                                    @php
                                                        $inactiveDept = App\Departments::where('id', $department[$count - 1])
                                                            ->where('is_active', false)
                                                            ->first();
                                                    @endphp
                                                    @if ($inactiveDept)
                                                        <option value="{{ $inactiveDept->id }}" style="font-size:13px;" selected>
                                                            {{ $inactiveDept->departmentname }}
                                                        </option>
                                                    @endif
                                                @endif
                                        </select>
                                        <select class="form-control mt-2" name="responsibility[]" style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                                            <option value="0" disabled selected style="font-size:13px;">Choose Responsibile Person</option>
                                            @if(count($user)> 0 )
                                            @foreach($user as $item)
                                            <option value="{{$item->email}}" style="font-size:13px;" {{ isset($responsibility[$count - 1]) && $responsibility[$count - 1] == $item->email ? 'selected' : '' }}>{{ $item->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>

                                    </td>

                                    <td class="td-class">
                                        <input type="date" id="target_date{{ $count - 1}}{{ $dat->id }}" class="js-datepicker form-control" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ isset($target_date[$count - 1]) ? $target_date[$count - 1] : ''}}" name="target_date[]" readonly />
                                    </td>


                                </tr>
                                @php
                                $count++;
                                @endphp
                                @endforeach
                                <input type="hidden" name="percent_status" id="percent_status" value="{{ $dat->percent_status }}" />
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="form-group row">
                    <div class="col-sm-8 ml-auto">
                        <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:82.5%;" onclick="return confirm('Do you want to proceed ? ')">Submit</button>
                    </div>
                </div>
                @endforeach
                @endif
                </form>
                <!-- END Form Horizontal - Default Style -->

            </div>
        </div>
    </div>
    </div>
    <!-- END Horizontal -->

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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
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
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-square fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' style="margin-top:6px">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sel').select2({});
        $('#sel_freq').select2({});
    });
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function() { 
        let divisionValue =  '{{ $dat->division_id }}';   
        let areaValue = '{{ $dat->area_id }}';
        let deptValue = '{{ $dat->correction_department }}'
        $('#division_head, #division_col').addClass('d-none');
        $('#area_head,#area_col').addClass('d-none');
        if(typeof divisionValue !== 'undefined' && divisionValue != 0){
            chooseDivision(deptValue);    
        }       
    });
    
    function chooseDivision(id) { 
        $('#division_select').val("");
        $('#area_select').val("");   
        $('#area_head,#area_col').addClass('d-none');
        let divisionselectElement = $('#division_select');
        divisionselectElement.empty(); 
        divisionselectElement.append(`<option value="">Choose Divisions</option>`);      
        let divisionValue =  '{{ $dat->division_id }}';  
        let deptValue = '{{ $dat->correction_department }}' 
        let areaValue = '{{ $dat->area_id }}';
        if(id==deptValue){
            if (typeof areaValue !== 'undefined' && areaValue != 0) {                                        
                chooseArea(divisionValue);                                                 
            }
        }           
        $.ajax({
            data: {
                    "depts_id_value": id
                    , _token: CSRF_TOKEN
                }          
            , url: "{{ route('admin.selectdivisiondetails')}}"
            , type: "POST"
            , success:function(response) {
                if(response.status == 200){
                    let divisiondatalist = response.divisions;    
                        if(divisiondatalist.length>0){ 
                            $('#division_head, #division_col').removeClass('d-none');                         
                            $.each(divisiondatalist, function (index, option) {
                                divisionselectElement.append(
                                    $('<option>', { value: option.id, text: option.name })
                                );
                            }); 
                            if(id==deptValue){                                     
                                if (typeof divisionValue !== 'undefined' && divisionValue != 0) {                                        
                                    divisionselectElement.val(divisionValue);                                                
                                }
                            }
                        }else{
                            $('#division_head, #division_col').addClass('d-none');
                        }                        
                            
                }
            },
            error: function(xhr, status, error) {
                    console.error('Error fetching divisions:', error);
                    alert('Failed to fetch division details. Please try again.');
            }
        });
    }

    function chooseArea(divid){
        let subdivisionselectElement = $('#area_select');
        subdivisionselectElement.empty(); 
        subdivisionselectElement.append(`<option value="">Choose Sub Division</option>`);  
        $('#area_select').val("");
        let areaValue =  Number('{{ $dat->area_id }}');
        $.ajax({
            data: {
                    "division_id_value": divid
                    , _token: CSRF_TOKEN
                }          
            , url: "{{ route('admin.selectsubdivisiondetails')}}"
            , type: "POST"
            , success:function(response) {
                if(response.status == 200){
                    let subdivisiondatalist = response.subdivisions;    
                        if(subdivisiondatalist.length>0){ 
                            $('#area_head, #area_col').removeClass('d-none');                          
                            $.each(subdivisiondatalist, function (index, option) {
                                subdivisionselectElement.append(
                                    $('<option>', { value: option.id, text: option.name })
                                );
                            });                                      
                            if (typeof areaValue !== 'undefined' && areaValue != 0) {                                        
                                subdivisionselectElement.val(areaValue);                                               
                            }
                        }else{
                            $('#area_head, #area_col').addClass('d-none');
                        }                                                       
                }
            },
            error: function(xhr, status, error) {
                    console.error('Error fetching divisions:', error);
                    alert('Failed to fetch division details. Please try again.');
            }
        });
    }
</script>
</html>
@endsection
