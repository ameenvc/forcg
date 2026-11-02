@extends('layouts.backend')

@section('back')
<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="/user/objectives/listofobjectives/{{Session::get('objdept')}}"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>
@endsection
@section('mainpage')
<div class="input-group input-group-sm">
        <a href="{{ url('/user/objectives/folders') }}" class="btn btn-secondary"style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>
        </div>
@endsection
  <style>
     .innerheadings{
        background:#ededed;
        font-family:system-ui;
        font-size:13px;
     }
     .colheading{
        font-size:13px;
        font-family:system-ui;
        font-weight:bold;
    }
    .achievedborder{
        background:#e0effa;
        border-right:3px solid #f1f1f1;
        border-bottom:3px solid #f1f1f1;
    }
    .targetborder{
        background:#e0effa;
        border-right:3px solid #f1f1f1;
    }
    .headingsbg{
        background:#e0effa;
    }
 </style>
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
            <div class="flex-sm-fill">
                <h1 class="h3 font-w700 mb-2">
                     Objective Dashboard
                </h1>
                <h2 class="h6 font-w500 text-muted mb-0">
                    Welcome <a class="font-w600" href="javascript:void(0)">{{Auth::user()->name}}</a>, everything looks great.
                </h2>
                <br>

        </div>
            @if($objective->status=='Approved')
            <div class="mt-3 mt-sm-0 ml-sm-3">
               <a href="{{ url('/user/objectives/add/'. $type .'/?val='.$objective->Oid)}}" class="button btn-sm btn btn-secondary 
               font-weight-normal"  style="font-size:12px;width:140px;" data-toggle="tooltip" title="Click here to Add reports">   
                   Add Reports
               </a>
            </div>
           @endif
        </div>
    </div>
</div>

<div class="content">
    <div class="text-muted mb-0">
        <table class="table table-striped">
        <tr>
            <th style="width:220px">Objectives description :  </th>
            <td><?php echo nl2br( $description )?></td>
    </tr></table>
    </div>
    <div class="row">
        <div class="form-group col-sm-4" style="padding-left:0;">
            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Objective Nature</label>
            <div class="col-sm-12">
                <input type="text" class="form-control bg-white" style="font-size: 13px;" readonly value="{{$data[0]->target_nature}}">
            </div>
        </div>
        <div class="form-group col-sm-4" style="padding-left:0;">
            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Baseline</label>
            <div class="col-sm-12">
                <input type="text" class="form-control bg-white" style="font-size: 13px;" readonly value="{{$data[0]->baseline}}">
            </div>
        </div>
        <div class="col-sm-4"></div>
    </div>

    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <dt class="font-size-h2 font-w700">{{$data[0]->otarget}}</dt>
                        <dd class="text-muted mb-0">Overall Target</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-user-clock" style="font-size:24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <dt class="font-size-h2 font-w700">{{$data[0]->oachieved}}</dt>
                        <dd class="text-muted mb-0">Overall Achieved</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-spinner"style="font-size:24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

      @php
        $month_start_year =  $monthly_start_year;
        $month_end_year = $month_start_year + 1;
        $month_list = [
                        'Jan','Feb','Mar','Apr','May','Jun',
                        'Jul','Aug','Sep','Oct','Nov','Dec'
                      ];    
      @endphp

    <div class="row">
        <div class="col-xl-9 d-flex flex-column">
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Target and Achieved</h3>
                    @if($objective->frequency==='Monthly')
                        <div class="block-options">
                            <button type="button" class="btn-block-option">
                                <select class="form-control" name="month_frm_select" id="month_frm_select" 
                                  style="font-size: 13px;width:150px;" onchange="getfrommonth()">
                                    <option value="">From</option>
                                        <?php                                            
                                            for($month_year_value=$month_start_year;$month_year_value<= $month_end_year;$month_year_value++){
                                                for($s =0 ; $s<count($month_list);$s++){
                                        ?>
                                        <option value='{{$month_year_value}} / {{$month_list[$s]}}'>{{$month_year_value}} / {{$month_list[$s]}}</option>
                                        <?php }} ?>
                                </select>
                                <input type="hidden" name="month_from" id="month_from"/>
                                <input type="hidden" name="objective_id" id="objective_id" value="{{$objective->Oid}}"/>
                                
                            </button>
                            <button type="button" class="btn-block-option">
                                <select class="form-control" name="month_to_select" id="month_to_select" 
                                 style="font-size: 13px;width:150px;" onchange="gettomonth()">
                                    <option value="">To</option>
                                    <?php                                            
                                            for($month_year_value=$month_start_year;$month_year_value<= $month_end_year;$month_year_value++){
                                                for($s =0 ; $s<count($month_list);$s++){
                                        ?>
                                        <option value='{{$month_year_value}} / {{$month_list[$s]}}'>{{$month_year_value}} / {{$month_list[$s]}}</option>
                                        <?php }} ?>
                                </select>
                            </button>
                        </div>
                    @endif
                    @if($objective->frequency==='Quarterly')
                        <div class="block-options">
                            <button type="button" class="btn-block-option">
                                <select class="form-control" name="quarter_frm_select" id="quarter_frm_select" 
                                  style="font-size: 13px;width:150px;" onchange="getfromquarter()">
                                    <option value="">From</option>
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
                                <input type="hidden" name="quarter_from" id="quarter_from"/>
                                <input type="hidden" name="objective_id" id="objective_id" value="{{$objective->Oid}}"/>
                                
                            </button>
                            <button type="button" class="btn-block-option">
                                <select class="form-control" name="quarter_to_select" id="quarter_to_select" 
                                 style="font-size: 13px;width:150px;" onchange="gettoquarter()">
                                    <option value="">To</option>
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
                            </button>
                        </div>
                    @endif
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center" id="filter_graph">
                   <canvas class="js-chartjs-earnings" id="canvas1"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- monthly and quarterly tabular listings starts -->
    @if($objective->frequency==='Monthly' || $objective->frequency==='Quarterly')
    @if(count($datareports) > 0)
       <div class="row">
             <div class="col-xl-12 d-flex flex-column">
                <div class="block block-rounded flex-grow-1 d-flex flex-column">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            @if($objective->frequency==='Monthly')
                              Monthly Reports
                            @elseif($objective->frequency==='Quarterly')
                              Quarterly Reports
                            @endif
                        </h3>
                        <div class="block-options">
                            <select class="form-control" name="year_select" id="year_select" style="font-size: 13px;width:150px;" required>
                                <option value="">Choose Year</option>
                                    @foreach($year_datas as $year_data)
                                    <option value="{{$year_data}}">{{$year_data}}</option>
                                    @endforeach
                            </select>
                        </div>
                     </div>
                     <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                            <table class="table table-bordered">
                                @if($objective->frequency==='Monthly')   
                                    <tr>
                                        <td>
                                            <table class="table table-bordered" id="reportTable">
                                                <tr class="bg-info text-white colheading text-center">                                                
                                                <td>Monthly</td>
                                                    @foreach($report_collection->keys()->all() as $month)                                         
                                                    <td>{{$month}} {{$current_year}}</td>
                                                    @endforeach
                                                <td>Overall</td>
                                                </tr>
                                                <tr class="bg-info text-white colheading text-center">
                                                <td>Achieved</td>
                                                    <?php
                                                        $report_collection->each(function ($item, $key){
                                                        $achieved_datas = isset($item[1]) ? $item[1] : "";
                                                    ?>
                                                <td class="achievedborder text-info">{{$achieved_datas}}</td>
                                                    <?php
                                                    })
                                                    ?>
                                                <td class="headingsbg text-info">{{$overall_achieved}}</td>
                                                </tr>

                                                <tr class="bg-info text-white colheading text-center">
                                                    <td>Target</td>
                                                        <?php
                                                            $report_collection->each(function ($item, $key){
                                                            $target_datas = isset($item[0]) ? $item[0] : "";
                                                        ?>
                                                    <td class="targetborder text-info">{{$target_datas}}</td>
                                                        <?php
                                                        })
                                                        ?>
                                                    <td class="headingsbg text-info">{{$overall_target}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @elseif($objective->frequency==='Quarterly')
                                    <tr>
                                        <td>
                                            <table class="table table-bordered" id="reportTable">
                                                <tr class="bg-info text-white colheading text-center">                                                
                                                <td>Quarter</td>
                                                    @foreach($report_collection->keys()->all() as $quarter_value)                                                 
                                                     @if($quarter_value==$current_year.'/Jan-Mar')                                    
                                                        <td>Q1 {{$current_year}}</td>
                                                     @endif
                                                     @if($quarter_value==$current_year.'/Apr-Jun')                                    
                                                        <td>Q2 {{$current_year}}</td>
                                                     @endif
                                                     @if($quarter_value==$current_year.'/Jul-Sep')                                    
                                                        <td>Q3 {{$current_year}}</td>
                                                     @endif
                                                     @if($quarter_value==$current_year.'/Oct-Dec')                                    
                                                        <td>Q4 {{$current_year}}</td>
                                                     @endif
                                                    @endforeach
                                                <td>Overall</td>
                                                </tr>
                                                <tr class="bg-info text-white colheading text-center">
                                                <td>Achieved</td>
                                                    <?php
                                                        $report_collection->each(function ($item, $key){
                                                        $achieved_datas = isset($item[1]) ? $item[1] : "";
                                                    ?>
                                                <td class="achievedborder text-info">{{$achieved_datas}}</td>
                                                    <?php
                                                    })
                                                    ?>
                                                <td class="headingsbg text-info">{{$overall_achieved}}</td>
                                                </tr>

                                                <tr class="bg-info text-white colheading text-center">
                                                    <td>Target</td>
                                                        <?php
                                                            $report_collection->each(function ($item, $key){
                                                            $target_datas = isset($item[0]) ? $item[0] : "";
                                                        ?>
                                                    <td class="targetborder text-info">{{$target_datas}}</td>
                                                        <?php
                                                        })
                                                        ?>
                                                    <td class="headingsbg text-info">{{$overall_target}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                @endif
                            </table>
                     </div>
                </div>
             </div>
       </div>
    @endif
    @endif
    <!-- Ends monthly and quarterly tabular listings  -->



    <div class="row">
       <div class="col-xl-6 d-flex flex-column">
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Overall Target and Overall Achieved</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                <canvas class="js-chartjs-earnings" id="canvas"></canvas>
                </div>
            </div>
        </div> 

        <div class="col-xl-6 d-flex flex-column">
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h3 class="block-title">5 year Trend</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                <canvas class="js-chartjs-earnings" id="canvas6"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
        var otarget = <?php echo $otarget; ?>;
        var oachieved = <?php echo $oachieved; ?>;
        var target = <?php echo $target; ?>;
        var achieved = <?php echo $achieved; ?>;
        var names = <?php echo $names; ?>;
        var title = <?php echo $title; ?>;
        var year = <?php echo $year; ?>;


       var barChartDataover = {
        labels:['Overall'],
        datasets: [{
            label: 'Target',
            backgroundColor: "#6495ED",
            barThickness: 40,
            data: otarget

        },
        {
            label: 'Achieved',
            backgroundColor: "#99e6ff",
            barThickness: 40,
            data: oachieved
        }]
     };

    var barChartData = {
        labels:names,
        datasets: [{
            label: 'Target',
            backgroundColor: "#6495ED",
            barThickness: 20,
            data: target

        },
        {
            label: 'Achieved',
            // backgroundColor: "#E6E6FA",
            backgroundColor: "#99e6ff",
            barThickness: 20,
            data: achieved
        }]
    };
    var barChartData6= {
            labels: year,
            datasets: [{
            label: 'Overall target',
            backgroundColor: "#6495ED",
            fill: false,
            tension: 0,
            data: otarget

        },
        {
            label: 'Overall achieved',
            backgroundColor: "#99e6ff",
            fill: false,
            tension: 0,
            data: oachieved
        }]
        };


        window.onload = function() {
            var ctx= document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartDataover,
                options: {
                    scales: {
                        xAxes: [{
                            barPercentage: 0.4
                        }],
                    yAxes: [{

                        ticks: {
                        beginAtZero: true,
                        },

                    }]
                    },
     legend: { position: 'bottom' },
                    elements: {
                        rectangle: {
                            borderWidth: 0,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    responsive: true
                }
            });
        };
        var ctx = document.getElementById("canvas1").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    plugins: {
                            title: {
                                    display: true,
                                    text: 'Chart.js Bar Chart - Stacked'
                                },
                              },
                    responsive: true,
                    interaction: {
                        intersect: false,
                        },
                        scales: {

                            yAxes: [{

                                ticks: {
                                beginAtZero: true,
                                },

                            }]
                            },
                            legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: title
                    }
                }
            });
            var ctx= document.getElementById("canvas6").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData6,
                options: {
                    plugins: {
                            title: {
                                    display: true,
                                    text: 'Chart.js Bar Chart - Stacked'
                                },
                              },
                    responsive: true,
                    interaction: {
                        intersect: false,
                        },
                        scales: {

                            yAxes: [{

                                ticks: {
                                beginAtZero: true
                                },

                            }]
                            },
                            legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: title
                    }
                }
            });

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
        $("#year_select").on('change',function(){
            let year_value = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: "{{ route('user.objectiveanalysis.getreports',['id'=>$objective->Oid]) }}",
                type:"GET",
                data:{'selected_year':$(this).val()},
                success:function(response){
                        let alldatareports = response.datareports;
                        let allreports = response.report_collection;
                        let frequencyname = response.obj_frequency;
                        let frequencyheading='';
                        let quarterhead = '';
                        if(frequencyname=='Monthly'){
                             frequencyheading = 'Monthly';
                        }
                        else if(frequencyname=='Quarterly'){
                             frequencyheading = 'Quarter';
                        }
                        
                                            
                        let htmldatas='';
                        if(alldatareports.length > 0){                            
                            htmldatas+= `<tr class="bg-info text-white colheading text-center"><td>${frequencyheading}</td>`;
                                for (const key in allreports) {
                                   if(key.includes('Jan-Mar')){
                                     quarterhead = `Q1 ${response.current_year}`;
                                   }
                                   else if(key.includes('Apr-Jun')){
                                    quarterhead = `Q2 ${response.current_year}`;
                                   }
                                   else if(key.includes('Jul-Sep')){
                                    quarterhead = `Q3 ${response.current_year}`;
                                   }
                                   else if(key.includes('Oct-Dec')){
                                    quarterhead = `Q4 ${response.current_year}`;
                                   }
                                   
                                   if(frequencyname=='Monthly'){
                                     htmldatas+= `<td>${key} ${response.current_year}</td>`;
                                   }
                                   else if(frequencyname=='Quarterly'){
                                    htmldatas+= `<td>${quarterhead}</td>`;
                                   }
                                }
                            htmldatas+= `<td>Overall</td></tr>
                                         <tr class="bg-info text-white colheading text-center">
                                         <td>Achieved</td>`;
                                for (const [key, value] of Object.entries(allreports)) {
                                    if(value[1]===undefined){
                                       htmldatas+= `<td class="achievedborder text-info">&nbsp;</td>`;
                                    }
                                    else{
                                        htmldatas+= `<td class="achievedborder text-info">${value[1]} </td>`;
                                    }
                                   
                                }       
                            htmldatas+= `<td class="headingsbg text-info">${response.overall_achieved}</td>
                                         </tr>
                                         <tr class="bg-info text-white colheading text-center">
                                         <td>Target</td>`;
                                for (const [key, value] of Object.entries(allreports)) {
                                    if(value[0]===undefined){
                                        htmldatas+= `<td class="achievedborder text-info">&nbsp;</td>`;
                                    }
                                    else{
                                        htmldatas+= `<td class="achievedborder text-info">${value[0]} </td>`;
                                    }
                                   
                                }
                            htmldatas+= `<td class="headingsbg text-info">${response.overall_target}</td>
                                         </tr>`;                             
                        }
                        else{
                            htmldatas=`<tr class="bg-info colheading text-center">
                                       <td colspan="14" class="achievedborder text-danger font-weight-normal" style="font-size:14px;">
                                       There are no records created for this year</td></tr>`;
                        }
                        
                        $('#reportTable').html(htmldatas);

                }
            });

        });
    });

    /**---------------month-select Graph starts---------------- */

        function getfrommonth()
        {
            if($('#month_frm_select').val())
            {
              let curr_val = $('#month_frm_select').val();
              monthvalues = curr_val.split(" ");
              monthvaluesyear = Number(monthvalues[0]);
              monthvaluesmonth =monthvalues[2];
              var to_options = $('#month_to_select');
              to_options.empty();
              to_options.append($('<option></option>').val('').html('To'));
              
              let month_list = [
                                'Jan','Feb','Mar','Apr','May','Jun',
                                'Jul','Aug','Sep','Oct','Nov','Dec'
                               ];
              let month_index = month_list.indexOf(monthvaluesmonth);
              let next_month_index = month_index + 1;

              let start_value='';
              if(month_index==11){
                 start_value = 0;
                 monthvaluesyear++;
              }
              else{
                 start_value = next_month_index;
              }
              
              count = 1;
              for (let index = start_value; index < month_list.length; index++) {
                let to_display = `${monthvaluesyear} / ${month_list[index]}`;
                to_options.append($('<option></option>').val(to_display).html(to_display));
                if (index == month_list.length-1) {
                    index = -1;
                    monthvaluesyear++;
                }
                if (count == 12) {
                    break;
                }
                count++;
              }

              $('#month_from').val(curr_val);
              
            } 

        }
        function gettomonth(){
        
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                 });

                 $.ajax({
                    url: "{{ route('user.objectiveanalysis.selectmonthfreqgraph') }}",
                    type:"POST",
                    data:{'month_frm_select':$('#month_from').val(),
                          'month_to_select':$('#month_to_select').val(),
                          'objective_id':$('#objective_id').val()
                         },
                    success:function(response){
                            let graphreports = response.graph_collection;
                            let graph_display_months = response.graph_display_months;                          
                                                                             
                            let achieved_datas = [];
                            for (const [key, value] of Object.entries(graphreports)) {
                                if(value[1]===undefined){
                                    achieved_datas.push('');
                                }
                                else{
                                    achieved_datas.push(value[1]);
                                }
                               
                            }
                            
                            let target_datas=[];
                            for (const [key, value] of Object.entries(graphreports)) {
                                if(value[0]===undefined){
                                    target_datas.push('');
                                }
                                else{
                                    target_datas.push(value[0]);
                                }                            
                            }

                            var barChartData5 = {
                                                labels:graph_display_months,
                                                datasets: [{
                                                    label: 'Target',
                                                    backgroundColor: "#6495ED",
                                                    barThickness: 20,
                                                    data: target_datas

                                                },
                                                {
                                                    label: 'Achieved',
                                                    backgroundColor: "#99e6ff",
                                                    barThickness: 20,
                                                    data: achieved_datas
                                                }]
                                            };


                            $(function() {
                                var ctx = document.getElementById("canvas4").getContext("2d");
                                                window.myBar = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: barChartData5,
                                                    options: {
                                                        plugins: {
                                                                title: {
                                                                        display: true,
                                                                        text: 'Chart.js Bar Chart - Stacked'
                                                                    },
                                                                },
                                                        responsive: true,
                                                        interaction: {
                                                            intersect: false,
                                                            },
                                                            scales: {

                                                                yAxes: [{

                                                                    ticks: {
                                                                    beginAtZero: true,
                                                                    },

                                                                }]
                                                                },
                                                                legend: { position: 'bottom' },
                                                        title: {
                                                            display: true,
                                                            text: title
                                                        }
                                                    }
                                                });
                                          });

                                   let graphhtmldatas='';
                                   graphhtmldatas+=`<canvas class="js-chartjs-earnings" id="canvas4"></canvas>`
                                   $('#filter_graph').html(graphhtmldatas);
                        
                        
                       },
                       error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                         }		
                });
            

        }
    /**---------------Ends month-select Graph---------------- */

    /**---------------Quarter-select Graph starts ---------------- */

    function getfromquarter()
        {
            if($('#quarter_frm_select').val())
            {
              let selected_val = $('#quarter_frm_select').val();
              quartervalues = selected_val.split(" ");
              quartervaluesyear = Number(quartervalues[0]);
              quartervaluesmonth =quartervalues[2];
              var to_options = $('#quarter_to_select');
              to_options.empty();
              to_options.append($('<option></option>').val('').html('To'));
              
              let quarter_months = ['Jan-Mar','Apr-Jun','Jul-Sep','Oct-Dec'];

              let quarter_index = quarter_months.indexOf(quartervaluesmonth);
              let next_quarter_index = quarter_index + 1;
              let start_value='';
              if(quarter_index==3){
                 start_value = 0;
                 quartervaluesyear++;
              }
              else{
                 start_value = next_quarter_index;
              }
              
              count = 1;
              for (let index = start_value; index < quarter_months.length; index++) {
                let to_display = `${quartervaluesyear} / ${quarter_months[index]}`;
                to_options.append($('<option></option>').val(to_display).html(to_display));
                if (index == quarter_months.length-1) {
                    index = -1;
                    quartervaluesyear++;
                }
                if (count == 12) {
                    break;
                }
                count++;
              }
            
              $('#quarter_from').val(selected_val);
              
            } 

        }

        function gettoquarter(){
        
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
             });

             $.ajax({
                url: "{{ route('user.objectiveanalysis.selectquarterfreqgraph') }}",
                type:"POST",
                data:{'quarter_frm_select':$('#quarter_from').val(),
                      'quarter_to_select':$('#quarter_to_select').val(),
                      'objective_id':$('#objective_id').val()
                     },
                success:function(response){
                        let graphreports = response.graph_collection;
                        let graph_display_quarters = response.graph_display_quarters;                          
                                                                                           
                        let achieved_datas = [];
                        for (const [key, value] of Object.entries(graphreports)) {
                            if(value[1]===undefined){
                                achieved_datas.push('');
                            }
                            else{
                                achieved_datas.push(value[1]);
                            }
                           
                        }
                        
                        let target_datas=[];
                        for (const [key, value] of Object.entries(graphreports)) {
                            if(value[0]===undefined){
                                target_datas.push('');
                            }
                            else{
                                target_datas.push(value[0]);
                            }                            
                        }

                        var barChartData7 = {
                                            labels:graph_display_quarters,
                                            datasets: [{
                                                label: 'Target',
                                                backgroundColor: "#6495ED",
                                                barThickness: 20,
                                                data: target_datas

                                            },
                                            {
                                                label: 'Achieved',
                                                backgroundColor: "#99e6ff",
                                                barThickness: 20,
                                                data: achieved_datas
                                            }]
                                        };


                        $(function() {
                            var ctx = document.getElementById("canvas7").getContext("2d");
                                            window.myBar = new Chart(ctx, {
                                                type: 'bar',
                                                data: barChartData7,
                                                options: {
                                                    plugins: {
                                                            title: {
                                                                    display: true,
                                                                    text: 'Chart.js Bar Chart - Stacked'
                                                                },
                                                            },
                                                    responsive: true,
                                                    interaction: {
                                                        intersect: false,
                                                        },
                                                        scales: {

                                                            yAxes: [{

                                                                ticks: {
                                                                beginAtZero: true,
                                                                },

                                                            }]
                                                            },
                                                            legend: { position: 'bottom' },
                                                    title: {
                                                        display: true,
                                                        text: title
                                                    }
                                                }
                                            });
                                      });

                               let graphhtmldatas='';
                               graphhtmldatas+=`<canvas class="js-chartjs-earnings" id="canvas7"></canvas>`
                               $('#filter_graph').html(graphhtmldatas);
                    
                    
                   },
                   error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                     }		
            });
        

    }
     /**---------------End Quarter-select Graph---------------- */
  </script>
@endsection















