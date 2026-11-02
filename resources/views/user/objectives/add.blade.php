@extends('layouts.backend')
@section('mainpage')
<div class="input-group input-group-sm">
    <a href="{{ url('/user/objectives/folders') }}" class="btn btn-secondary"
        style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>
</div>
@endsection
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"
            style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Objective</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Add Reports</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx"
                            href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{$type}}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="content">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            @if(Session::has('msg'))
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('msg')!!}
                </div>
            @endif
            @if(Session::has('errmsg'))
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
            @endif
            <div class="row">
                <div class="col-lg-10">
                    <form class="mb-5" action="{{url('/user/objectives/add/'. $type)}}" method="post">
                        {{csrf_field()}}
                        @foreach($data1 as $dat)
                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="objid" style="font-size: 13px;">Objective
                                ID</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="objid" value="{{$dat->objective_id}}"
                                    style="font-size: 13px;" name="objid" readonly>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="objective_type"
                                style="font-size: 13px;">Objective type</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="objective_type" name="objective_type"
                                    style="font-size: 13px;" readonly
                                    value="{{$dat->objectiveTypes ? $dat->objectiveTypes->pluck('name')->implode(', ') : ''}}">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="objectives" style="font-size: 13px;">Objective
                                name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="objectives" name="objectives"
                                    style="font-size: 13px;" readonly value="{{$dat->objectives}}">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        @if($type== 'divisional' || $type == 'subdivisional')
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" readonly
                                    value="{{$dat->division ? $dat->division->name : ''}}">
                            </div>
                        </div>
                        @endif
                        @if($type == 'subdivisional')
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Sub-Division</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" readonly
                                    value="{{$dat->area ? $dat->area->name : ''}}">
                            </div>
                        </div>
                        @endif
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="responsibility"
                                style="font-size: 13px;">Responsibility</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="responsibility" style="font-size: 13px;"
                                    readonly name="responsibility" value="{{$dat->responsibility}}">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="frequency" style="font-size: 13px;">Frequency
                                of montitoring</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="frequency" style="font-size: 13px;" readonly
                                    name="frequency" value="{{$dat->frequency}}">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Objective Nature</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" readonly
                                    value="{{$dat->target_nature}}">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Baseline</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" name="baseline"
                                  value="{{$dat->baseline}}">
                            </div>
                        </div>
                        </div>
                        @endforeach
                        <br><br>
                        <table class="table table-bordered table-vcenter" style="width:900px" id="frequencyTable">
                            <thead style="background-color:#FFFFFF">
                                <tr>
                                    <th></th>
                                    <th>
                                        <label class="text-danger" style="font-size: 13px;">
                                            @if($dat->frequency === 'Quarterly')
                                            Choose the quarter
                                            @elseif($dat->frequency === 'Half yearly')
                                            Choose half
                                            @elseif($dat->frequency === 'Yearly')
                                            Choose year
                                            @elseif($dat->frequency === 'Monthly')
                                            Choose month
                                            @endif
                                        </label>
                                    </th>
                                    <th><label style="font-size: 13px;width:200px">Target</label></th>
                                    <th><label style="font-size: 13px;">Achieved</label></th>
                                    <th class="text-center">
                                        @if($dat->responsibility==Auth::user()->email)
                                        <a href="{{route('objective.deletequarters',['id'=>$dat->Oid])}}"
                                         style="font-size:13px;"onclick="return confirmquarterdelete()"
                                         data-toggle="tooltip" title="Click here to delete all quarter details">
                                           <i class="fas fa-1x fa-trash" style="color:red;"></i>
                                        </a>
                                        @else

                                           <i class="fas fa-1x fa-trash" style="color:#C8C8C8;" style="font-size:13px;"></i>

                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $value = null;
                                $data_value = null;
                                switch($dat->frequency){
                                case 'Quarterly':
                                $value = 'First Quarter';
                                $data_value = 'first_quarter';
                                break;
                                case 'Half yearly':
                                $value = 'First Half';
                                $data_value = 'first_half';
                                break;
                                case 'Yearly':
                                $value = 'Yearly';
                                $data_value = 'yearly';
                                break;
                                case 'Monthly':
                                $value = 'Monthly';
                                $data_value = 'monthly';
                                break;
                                }
                                $report_exists = DB::table('objreport')
                                                            ->where('Oid',$Oid)
                                                            ->whereNotNull('year')
                                                            ->get();

                                $latest_year = DB::table('objreport')
                                                            ->where('Oid',$Oid)
                                                            ->where('frequency','Yearly')
                                                            ->latest('slno')
                                                            ->value('year');

                                $quarterly_data = DB::table('objreport')->where('Oid',$Oid)
                                                                        ->where('frequency','Quarterly')
                                                                        ->whereNotNull('year')
                                                                        ->latest('slno')
                                                                        ->value('quarter');

                                $half_yearly_data = DB::table('objreport')->where('Oid',$Oid)
                                                                        ->where('frequency','Half yearly')
                                                                        ->whereNotNull('year')
                                                                        ->latest('slno')
                                                                        ->value('quarter');

                                $monthly_data = DB::table('objreport')->where('Oid',$Oid)
                                                                        ->where('frequency','Monthly')
                                                                        ->whereNotNull('year')
                                                                        ->latest('slno')
                                                                        ->value('quarter');
                                $record_date = explode('-',$dat->fromdate);
                                $monthly_start_year = $record_date[0];
                                $firstslno = 1;
                                @endphp
                                <input type="hidden" id="freq" value="{{ $dat->frequency }}" />
                                @if(count($report_exists) > 0)
                                    @foreach($report_exists as $report_exist)
                                        <tr>
                                            <td><input type="text" class="form-control" id="state" name="state[]"
                                                    style="font-size: 13px;" value="{{$report_exist->state}}"
                                                    readonly></td>
                                            <td>
                                                @if($dat->frequency === 'Quarterly')
                                                <input type="text" class="form-control text-danger font-weight-bold" id="db_quarter"
                                                    name="db_quarter[]" style="font-size: 13px;" value="{{ $report_exist->quarter }}" readonly>

                                                <input type="hidden" class="form-control text-danger font-weight-bold"
                                                    name="quarter_year_db[]" style="font-size: 13px;" value="{{ $report_exist->year }}" readonly>

                                                @elseif($dat->frequency === 'Half yearly')
                                                <input type="text" class="form-control text-danger font-weight-bold" id="db_half_year"
                                                    name="db_half_year[]" style="font-size: 13px;" value="{{ $report_exist->quarter }}" readonly>

                                                <input type="hidden" class="form-control text-danger font-weight-bold"
                                                    name="half_year_db[]" style="font-size: 13px;" value="{{ $report_exist->year }}" readonly>

                                                @elseif($dat->frequency === 'Yearly')
                                                <input type="text" class="form-control text-danger font-weight-bold" id="db_year" name="db_year[]"
                                                    style="font-size: 13px;" value="{{ $report_exist->quarter }}" readonly>

                                                @elseif($dat->frequency === 'Monthly')
                                                <input type="text" class="form-control text-danger font-weight-bold" id="db_month"
                                                    name="db_month[]" style="font-size: 13px;" value="{{ $report_exist->quarter }}" readonly>

                                                <input type="hidden" class="form-control text-danger font-weight-bold"
                                                    name="month_year_db[]" style="font-size: 13px;" value="{{ $report_exist->year }}" readonly>
                                                @endif
                                            </td>
                                            <td><input type="text" class="form-control" id="target" name="target[]"
                                                    style="font-size: 13px;" value="{{ $report_exist->target }}"></td>
                                            <td><input type="text" class="form-control" id="achieved" name="achieved[]"
                                                    style="font-size: 13px;" value="{{ $report_exist->achieved }}"></td>

                                            <td class="text-center">
                                             @if($firstslno == 1)
                                                <button type="button" name="add" id="add" class="btn btn-sm btn-light"
                                                    data-toggle="tooltip" title="Add">
                                                    <i class="fa fa-fw fa-plus"></i>
                                                </button>
                                            @endif
                                            </td>
                                            <?php $firstslno++; ?>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td><input type="text" class="form-control" id="state_display" name="state[]"
                                                style="font-size: 13px;" value="{{$value}}" data-value="{{ $data_value }}"
                                                readonly></td>
                                        <td>
                                            @if($dat->frequency === 'Quarterly')
                                                <select class="form-control text-danger font-weight-bold" id="db_quarter_disable" name="db_quarter[]"
                                                    style="font-size: 13px;" onchange="changeQuarter(this.value)">
                                                    <?php
                                                        $quarter_start_year = 2022;
                                                        $quarter_end_year = Date('Y') + 3;
                                                        for($quarter_year_value=$quarter_start_year;$quarter_year_value<= $quarter_end_year;$quarter_year_value++){
                                                    ?>
                                                      <option value='{{$quarter_year_value}} / Jan-Mar'>{{$quarter_year_value}} / Jan-Mar</option>
                                                      <option value='{{$quarter_year_value}} / Apr-Jun'>{{$quarter_year_value}} / Apr-Jun</option>
                                                      <option value='{{$quarter_year_value}} / Jul-Sep'>{{$quarter_year_value}} / Jul-Sep</option>
                                                      <option value='{{$quarter_year_value}} / Oct-Dec'>{{$quarter_year_value}} / Oct-Dec</option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="db_quarter[]" id="selected_quarter_value" value="2022 / Jan-Mar" />
                                                <input type="hidden" name="quarter_year_db[]" id="selected_quarter_year_db" value="2022" />
                                            @elseif($dat->frequency === 'Half yearly')
                                            <select class="form-control text-danger font-weight-bold" id="db_half_year_disable" name="db_half_year[]"
                                                style="font-size: 13px;width:190px;" onchange="changeHalf(this.value)">
                                                <?php
                                                    $half_start_year = 2022;
                                                    $half_end_year = Date('Y') + 3;
                                                    for($half_year_value=$half_start_year;$half_year_value<=$half_end_year;$half_year_value++) {
                                                ?>
                                                <option value="Jan {{$half_year_value}} - Jun {{$half_year_value}}">Jan {{$half_year_value}} - Jun {{$half_year_value}}</option>
                                                <option value="Jul {{$half_year_value}} - Dec {{$half_year_value}}">Jul {{$half_year_value}} - Dec {{$half_year_value}}</option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="db_half_year[]" id="selected_half_year_value" value="Jan 2022 - Jun 2022" />
                                            <input type="hidden" name="half_year_db[]" id="selected_half_year_db" value="2022" />
                                            @elseif($dat->frequency === 'Yearly')
                                            <select class="form-control text-danger font-weight-bold" id="db_year_disable" name="db_year[]" style="font-size: 13px;"
                                                onchange="changeYear(this.value)">
                                                <?php
                                                  $start_year = 2022;
                                                  $end_year = Date('Y') + 5;
                                                  for($year_value=$start_year;$year_value<= $end_year;$year_value++){
                                                ?>
                                                 <option value='{{$year_value}}'>{{$year_value}}</option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="db_year[]" id="selected_year" value="2022" />
                                            @elseif($dat->frequency === 'Monthly')
                                                <select class="form-control text-danger font-weight-bold" id="db_month_disable" name="db_month[]" style="font-size: 13px;"
                                                    onchange="changeMonth(this.value)">
                                                    <?php
                                                        $month_start_year =  $record_date[0];
                                                        $month_end_year = $month_start_year + 1;
                                                        $month_list = [
                                                                        'Jan','Feb','Mar','Apr','May','Jun',
                                                                        'Jul','Aug','Sep','Oct','Nov','Dec'
                                                                      ];
                                                        for($month_year_value=$month_start_year;$month_year_value<= $month_end_year;$month_year_value++){
                                                            for($s =0 ; $s<count($month_list);$s++){
                                                    ?>
                                                    <option value='{{$month_year_value}} / {{$month_list[$s]}}'>{{$month_year_value}} / {{$month_list[$s]}}</option>
                                                    <?php }} ?>
                                                </select>
                                                <input type="hidden" name="db_month[]" id="selected_month_value" value="{{$month_start_year}} / Jan" />
                                                <input type="hidden" name="month_year_db[]" id="selected_month_year_db" value="{{$month_start_year}}" />
                                            @endif
                                        </td>
                                        <td><input type="text" class="form-control" id="target" name="target[]"
                                                style="font-size: 13px;" required></td>
                                        <td><input type="text" class="form-control" id="achieved" name="achieved[]"
                                                style="font-size: 13px;" required></td>
                                        <td class="text-center">
                                            <button type="button" name="add" id="add" class="btn btn-sm btn-light"
                                                data-toggle="tooltip" title="Add">
                                                <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>

                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <table class="table table-bordered table-vcenter" style="width:900px" id="dynamicTable">
                            <thead style="background-color:#FFFFFF">
                                <tr>
                                    <th></th>
                                    <th><label style="font-size: 13px;width:200px">Target</label></th>
                                    <th><label style="font-size: 13px;">Achieved</label></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data1 as $dat)
                                <tr>
                                    <td style="font-size:13px;width:200px" class="text-center">Overall target and
                                        achieved</td>
                                    <td><input type="number" class="form-control" id="otarget" style="font-size:13px;"
                                            name="otarget" step="any" value="{{$dat->otarget}}"></td>
                                    <td><input type="number" class="form-control" id="oachieved" style="font-size:13px;"
                                            name="oachieved" step="any" value="{{$dat->oachieved}}"> </td>
                                </tr>
                                <tr>
                                    <td style="font-size:13px;width:200px" class="text-center">CAR, if any?</td>
                                    <td> <input type="text" class="form-control" id="CAR" style="font-size:13px;"
                                            name="CAR" value="{{$dat->CAR}}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="form-group">
                            <input type="hidden" name="posted" value="{{$Oid}}">
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right form-group">
                            <input class="btn btn-white text-primary border border-primary" type="reset"
                                   style="font-size:13px;"  value="Cancel"/>
                                <input class="btn btn-primary" type="submit" onclick="return confirm('Are you sure to submit the request?')"
                                    value="Submit" data-toggle="tooltip" style="font-size:13px;"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
    aria-hidden="true">
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
                            <a class="block block-rounded block-link-shadow bg-body"
                                href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Register
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
@endsection
@section('js_after')
<script>

    let freq = $('#freq').val();
    let add_count = 0;
    let quarterly_exist_data = '<?php echo $quarterly_data;?>';
    let half_yearly_exist_data = '<?php echo $half_yearly_data;?>';
    let monthly_exist_data = '<?php echo $monthly_data;?>';

    /**---------------Yearly starts---------------- */
        let latest_year_record = Number('<?php echo $latest_year; ?>');
        let current_year =  Number("{{ date('Y') }}");
        let start_year = (latest_year_record == '') ? 2022 : latest_year_record;
        let end_year =  current_year + 5;
    /**---------------Ends yearly---------------- */

     /**---------------Quarter starts---------------- */
         let quarter_start_value = 2022;
         let quarter_end_value = Number("{{ date('Y') }}") + 3;
         let quarter_months = ['Jan-Mar','Apr-Jun','Jul-Sep','Oct-Dec'];
         let quarter_add_value = [];
         let quarter_state_display_value = [];
         let quarter_year_value = [];

         for(let i= quarter_start_value;i<= quarter_end_value;i++){
            let firstquarter  = `${i} / ${quarter_months[0]}`;
            let secondquarter = `${i} / ${quarter_months[1]}`;
            let thirdquarter  = `${i} / ${quarter_months[2]}`;
            let forthquarter  = `${i} / ${quarter_months[3]}`;
            quarter_add_value.push(firstquarter);
            quarter_state_display_value.push('First Quarter');
            quarter_year_value.push(`${i}`);
            quarter_add_value.push(secondquarter);
            quarter_state_display_value.push('Second Quarter');
            quarter_year_value.push(`${i}`);
            quarter_add_value.push(thirdquarter);
            quarter_state_display_value.push('Third Quarter');
            quarter_year_value.push(`${i}`);
            quarter_add_value.push(forthquarter);
            quarter_state_display_value.push('Forth Quarter');
            quarter_year_value.push(`${i}`);
         }

        if(quarterly_exist_data){
            let find_count_quat = quarter_add_value.indexOf(quarterly_exist_data);
            add_count = find_count_quat;
        }

     /**---------------Ends Quarter---------------- */

     /**---------------Half yearly starts---------------- */
         let half_year_start_value = 2022;
         let half_year_end_value = Number("{{ date('Y') }}") + 3;
         let half_year_add_value = [];
         let half_year_state_display_value = [];
         let half_year_main_value = [];

         for(let j= half_year_start_value;j<= half_year_end_value;j++){
            let firsthalf  = `Jan ${j} - Jun ${j}`;
            let secondhalf = `Jul ${j} - Dec ${j}`;
            half_year_add_value.push(firsthalf);
            half_year_state_display_value.push('First Half');
            half_year_main_value.push(`${j}`);
            half_year_add_value.push(secondhalf);
            half_year_state_display_value.push('Second Half');
            half_year_main_value.push(`${j}`);
         }

         if(half_yearly_exist_data){
            let find_count_quat_half = half_year_add_value.indexOf(half_yearly_exist_data);
            add_count = find_count_quat_half;
        }

    /**--------------- Ends Half yearly---------------- */

    /**---------------Monthly starts------------------- */
         let month_start_value = Number('<?php echo $monthly_start_year;?>');
         let month_end_value = month_start_value + 1;
         let month_list = [
                            'Jan','Feb','Mar','Apr','May','Jun',
                            'Jul','Aug','Sep','Oct','Nov','Dec'
                          ];
         let month_add_value = [];
         let month_year_main_value = [];

        for(let k= month_start_value;k<= month_end_value;k++){
            for(let s =0;s<month_list.length;s++){
                let month_values  = `${k} / ${month_list[s]}`;
                month_add_value.push(month_values);
                month_year_main_value.push(k);
            }
        }

        if(monthly_exist_data){
            let find_count_quat_month = month_add_value.indexOf(monthly_exist_data);
            add_count = find_count_quat_month;
        }

    /**---------------Ends Monthly--------------------- */

    if(freq === 'Yearly') {
        if(start_year == end_year) {
                $('#add').attr('disabled', 'disabled');
            } else {
                $('#add').attr('disabled', false);
            }
    }


 /**---------------Add button starts---------------- */
    $("#add").on('click', this, function(event){
        if(freq === 'Yearly') {
            $('#db_year_disable').attr('disabled', 'disabled');
            add_count++;
            start_year++;

            let text_to_append_to_table = `<tr id="newrow">
                <td><input type="text" name="state[]" class="form-control" id="state${add_count}" style="font-size: 13px;" value="Yearly" readonly></td>
                <td><input type="text" class="form-control text-danger font-weight-bold" id="db_year" name="db_year[]" style="font-size: 13px;" value="${start_year}" readonly></td>
                <td><input type="text" name="target[]" style="font-size: 13px;" class="form-control">
                <td><input type="text" name="achieved[]" style="font-size: 13px;" class="form-control"></td>
                <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light"
                data-toggle="tooltip" title="Remove" onclick="remove_tr(${add_count})">
                <i class="fa fa-fw remove-tr fa-times" id="remove${add_count}"></i></button></td>
            </tr>`;

            if(start_year == end_year) {
                $('#add').attr('disabled', 'disabled');
            } else {
                $('#add').attr('disabled', false);
            }

            $("#frequencyTable").append(text_to_append_to_table);
        }
        else if(freq === 'Quarterly'){
            $('#db_quarter_disable').attr('disabled', 'disabled');
            add_count++;

            let text_to_append_to_table = `<tr id="newrow">
                <td><input type="text" name="state[]" class="form-control" id="state${add_count}" style="font-size: 13px;" value="${quarter_state_display_value[add_count]}" readonly></td>
                <td><input type="text" class="form-control text-danger font-weight-bold" id="db_quarter" name="db_quarter[]" style="font-size: 13px;" value="${quarter_add_value[add_count]}" readonly></td>
                <td><input type="text" name="target[]" style="font-size: 13px;" class="form-control">
                <td><input type="text" name="achieved[]" style="font-size: 13px;" class="form-control"></td>
                <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light"
                data-toggle="tooltip" title="Remove" onclick="remove_tr(${add_count})">
                <i class="fa fa-fw remove-tr fa-times" id="remove${add_count}"></i></button></td>
                <input type="hidden" name="quarter_year_db[]" value="${quarter_year_value[add_count]}">
            </tr>`;

            if(quarter_year_value[add_count] == quarter_end_value && quarter_state_display_value[add_count]=='Forth Quarter') {
                $('#add').attr('disabled', 'disabled');
            } else {
                $('#add').attr('disabled', false);
            }

            $("#frequencyTable").append(text_to_append_to_table);

        }
        else if(freq === 'Half yearly'){
            $('#db_half_year_disable').attr('disabled', 'disabled');
            add_count++;

            let text_to_append_to_table = `<tr id="newrow">
                <td><input type="text" name="state[]" class="form-control" id="state${add_count}" style="font-size: 13px;" value="${half_year_state_display_value[add_count]}" readonly></td>
                <td><input type="text" class="form-control text-danger font-weight-bold" id="db_half_year" name="db_half_year[]" style="font-size: 13px;" value="${half_year_add_value[add_count]}" readonly></td>
                <td><input type="text" name="target[]" style="font-size: 13px;" class="form-control">
                <td><input type="text" name="achieved[]" style="font-size: 13px;" class="form-control"></td>
                <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light"
                data-toggle="tooltip" title="Remove" onclick="remove_tr(${add_count})">
                <i class="fa fa-fw remove-tr fa-times" id="remove${add_count}"></i></button></td>
                <input type="hidden" name="half_year_db[]" value="${half_year_main_value[add_count]}">
            </tr>`;

            if(half_year_main_value[add_count] == half_year_end_value && half_year_state_display_value[add_count]=='Second Half') {
                $('#add').attr('disabled', 'disabled');
            } else {
                $('#add').attr('disabled', false);
            }

            $("#frequencyTable").append(text_to_append_to_table);

        }
        else if(freq === 'Monthly'){
            $('#db_month_disable').attr('disabled', 'disabled');
            add_count++;

            let text_to_append_to_table = `<tr id="newrow">
                <td><input type="text" name="state[]" class="form-control" id="state${add_count}" style="font-size: 13px;" value="Monthly" readonly></td>
                <td><input type="text" class="form-control text-danger font-weight-bold" id="db_month" name="db_month[]" style="font-size: 13px;" value="${month_add_value[add_count]}" readonly></td>
                <td><input type="text" name="target[]" style="font-size: 13px;" class="form-control">
                <td><input type="text" name="achieved[]" style="font-size: 13px;" class="form-control"></td>
                <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light"
                data-toggle="tooltip" title="Remove" onclick="remove_tr(${add_count})">
                <i class="fa fa-fw remove-tr fa-times" id="remove${add_count}"></i></button></td>
                <input type="hidden" name="month_year_db[]" value="${month_year_main_value[add_count]}">
            </tr>`;

            if(month_year_main_value[add_count] == month_end_value && month_add_value[add_count].includes('Dec')) {
                $('#add').attr('disabled', 'disabled');
            } else {
                $('#add').attr('disabled', false);
            }

            $("#frequencyTable").append(text_to_append_to_table);

        }

    });
 /**---------------Ends Add button---------------- */
    function remove_tr(index_param) {
          $('#remove' + add_count).parents('tr').remove();
            add_count = index_param -1;
            start_year--;

          $('#add').attr('disabled', false);
          let row_count = $('#frequencyTable >tbody >tr').length;
          if(row_count === 1) {
            $('#db_year_disable').attr('disabled', false);
            $(`#db_quarter_disable`).attr('disabled', false);
            $(`#db_half_year_disable`).attr('disabled', false);
            $(`#db_month_disable`).attr('disabled', false);
          }
    }

    function changeYear(selectedvalue){
        start_year = selectedvalue;
        $(`#selected_year`).val(selectedvalue);
    }

    function changeQuarter(selectedquarter){
        let find_count = quarter_add_value.indexOf(selectedquarter);
        add_count = find_count;
        quartervalues = selectedquarter.split(" ");
        quartervaluesyear = quartervalues[0];
        quartervaluesmonths =quartervalues[2];
        $(`#selected_quarter_value`).val(selectedquarter);
        $(`#selected_quarter_year_db`).val(quartervaluesyear);
        if(quartervaluesmonths=='Jan-Mar'){
            $(`#state_display`).val('First Quarter');
        }
        else if(quartervaluesmonths=='Apr-Jun'){
            $(`#state_display`).val('Second Quarter');
        }
        else if(quartervaluesmonths=='Jul-Sep'){
            $(`#state_display`).val('Third Quarter');
        }
        else if(quartervaluesmonths=='Oct-Dec'){
            $(`#state_display`).val('Forth Quarter');
        }
    }

    function changeHalf(selectedhalfyear){
        let find_half_year_count = half_year_add_value.indexOf(selectedhalfyear);
        add_count = find_half_year_count;
        halfyearvalues = selectedhalfyear.split(" ");
        halfyearmonths = `${halfyearvalues[0]}-${halfyearvalues[3]}`;
        halfyearselectedyearvalue = halfyearvalues[1];
        $(`#selected_half_year_value`).val(selectedhalfyear);
        $(`#selected_half_year_db`).val(halfyearselectedyearvalue);
        if(halfyearmonths=='Jan-Jun'){
            $(`#state_display`).val('First Half');
        }
        else if(halfyearmonths=='Jul-Dec'){
            $(`#state_display`).val('Second Half');
        }
    }

    function changeMonth(selectedmonth){
        let find_month_count = month_add_value.indexOf(selectedmonth);
        add_count = find_month_count;
        monthvalues = selectedmonth.split(" ");
        monthvaluesyear = monthvalues[0];
        monthvaluesmonth =monthvalues[2];
        $(`#selected_month_value`).val(selectedmonth);
        $(`#selected_month_year_db`).val(monthvaluesyear);
    }
</script>
<script>
    function confirmquarterdelete(){
        message1 = "Are you sure you want to delete this quarter details?";
            check = confirm(message1);
            if (check == true) {
                message2 = 'This details will be deleted permanently,Press OK to continue.';
                confirm_check = confirm(message2);
                if (confirm_check == true) {
                } else {
                    return false;
                }
            } else {
                return false;
            }
    }

</script>
@endsection
