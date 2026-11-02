@extends('layouts.backend')
@section('back')
<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="/user/occupationhealth/register"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Occupational Health</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="padding-top:30px;width:96%;margin-left:25px;">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="#">
                          <dt class="font-size-h2 font-w700">{{$totalcases}}</dt>
                        </a>
                        <dd class="text-muted mb-0">Total Injured and Illness</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-inbox font-size-h3 text-primary"></i>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                       <a href="{{ url('/user/occupationhealth/injuryrecords') }}">
                          <dt class="font-size-h2 font-w700">{{$totalinjured}}</dt>
                       </a>
                        <dd class="text-muted mb-0"># of Injury</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="{{ url('/user/occupationhealth/illnessrecords') }}">
                          <dt class="font-size-h2 font-w700">{{$totalillness}}</dt>
                        </a> 
                        <dd class="text-muted mb-0"># of Illness</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="#">
                          <dt class="font-size-h2 font-w700">{{$totalcategory}}</dt>
                        </a> 
                        <dd class="text-muted mb-0"># of Category</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3" style="color:#00cc99"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">

      <div class="col-xl-7 d-flex flex-column">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury and Illness-Department wise</h8>
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

      <div class="col-xl-5 d-flex flex-column">
        <div class="row row-deck flex-grow-1">

            <div class="col-md-6 col-xl-12" style="height:150px;">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-danger">{{$injurytotaltopcases}}</dt>
                            <dd class="text-muted mb-0">Injury - #Top Cases List </dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12" style="height:150px;">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-warning">{{$illnesstotaltopcases}}</dt>
                            <dd class="text-muted mb-0">Illness - #Top Cases List</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12" style="height:150px;">&nbsp;</div>
            <!--<div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">zerocount</dt>
                            <dd class="text-muted mb-0">Total Actions in Zero Progress</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>-->

        </div>
      </div>

    </div>

   
    <div class="row">
      <div class="col-xl-7 d-flex flex-column">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury-Department wise</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas1"></canvas>
                </div>
            </div>
      </div>

      <div class="col-xl-5 d-flex flex-column">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury-Total # of Category</h8>
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

    <div class="row">
       <div class="col-xl-7 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness-Department wise</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas2"></canvas>
                </div>
            </div>
       </div> 

       <div class="col-xl-5 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness-Total # of Category</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas4"></canvas>
                </div>
            </div>
       </div>    
   </div>

   <div class="row">
       <div class="col-xl-7 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury-Top Cases</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas5"></canvas>
                </div>
            </div>
       </div> 
       
       <div class="col-xl-5 d-flex flex-column mt-4">
         <div class="row row-deck flex-grow-1">

            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-danger">{{$injuryworktopcases}}</dt>
                            <dd class="text-muted mb-0">Work Related - Injury Top Cases</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-danger">{{$injuryworkcasesother}}</dt>
                            <dd class="text-muted mb-0">Other Work Related - Injury Cases</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-danger">{{$injury_nonworkcases}}</dt>
                            <dd class="text-muted mb-0">Non-Work Related - Injury Cases</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>

          </div>
      </div>
   </div>



   <div class="row">
       <div class="col-xl-7 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness-Top Cases</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas3"></canvas>
                </div>
            </div>
       </div> 
       
       <div class="col-xl-5 d-flex flex-column mt-4">
         <div class="row row-deck flex-grow-1">

            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-warning">{{$illnessnonworktopcases}}</dt>
                            <dd class="text-muted mb-0">Non-Work Related - Illness Top Cases</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-warning">{{$illnessnonworkcasesother}}</dt>
                            <dd class="text-muted mb-0">Other Non-Work Related - Illness Cases</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700 text-warning">{{$illness_workcases}}</dt>
                            <dd class="text-muted mb-0">Work Related - Illness Cases</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="fa fa-inbox font-size-h3 text-primary"></i>
                        </div>                  
                    </div>              
                </div>
            </div>

          </div>
      </div>

   </div>


   <div class="row">
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury Monthly Trend (Top Cases)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas7"></canvas>
                </div>
            </div>
       </div>
       
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury Yearly Trend (Top Cases)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas8"></canvas>
                </div>
            </div>
       </div>
   </div>
   
   <div class="row">
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness Monthly Trend (Top Cases)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas9"></canvas>
                </div>
            </div>
       </div>
       
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness Yearly Trend (Top Cases)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas10"></canvas>
                </div>
            </div>
       </div>
   </div>

   <div class="row">
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury Monthly Trend (Category)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas11"></canvas>
                </div>
            </div>
       </div>
       
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Injury Yearly Trend (Category)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas12"></canvas>
                </div>
            </div>
       </div>
   </div>
   
   <div class="row">
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness Monthly Trend (Category)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas13"></canvas>
                </div>
            </div>
       </div>
       
       <div class="col-xl-6 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Illness Yearly Trend (Category)</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas14"></canvas>
                </div>
            </div>
       </div>
   </div>


   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
        var depcode = <?php echo $depcode; ?>;
        var injured = <?php echo $injured; ?>;
        var illness = <?php echo $illness; ?>;
        var catcode = <?php echo $catcode; ?>;
        var catdata = <?php echo $catdata; ?>;
        var categoryname = <?php echo $categoryname;?>;
        var illcasename = <?php echo $illcasename; ?>;
        var illcasedata = <?php echo $illcasedata; ?>;
        var injurycasename = <?php echo $injurycasename;?>;
        var injurycasedata = <?php echo $injurycasedata;?>;
        var injurycatdata = <?php echo $injurycatdata;?>;
        var months = <?php echo $months;?>;
        var year = <?php echo $year;?>;
        var injurymonth = <?php echo $injurymonth;?>;
        var injuryyear = <?php echo $injuryyear;?>;
        var illnessmonth = <?php echo $illnessmonth;?>;
        var illnessyear = <?php echo $illnessyear;?>; 
        var injurycategorymonth = <?php echo $injurycategorymonth;?>; 
        var illnesscategorymonth = <?php echo $illnesscategorymonth;?>;
        var illnesscategoryyear = <?php echo $illnesscategoryyear;?>;
        var injuryyearlycategory = <?php echo $injuryyearlycategory;?>;
       
        
        
        
    var barChartData1 = {
        labels: depcode
        , datasets: [{
                label: 'Injury'
                , backgroundColor: "#ff6437"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: injured
            },
            {
                label: 'Illness'
                , backgroundColor: "#fff0b9"
                , barThickness: 40
                , data: illness
                , stack: 'Stack 0'
            }
        ]
    };
    var barChartData2 = {
        labels: depcode
        , datasets: [{
                label: 'Injury'
                , backgroundColor: "#ff6437"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: injured
            },
        ]
    };
    var barChartData3 = {
        labels: depcode
        , datasets: [
            {
                label: 'Illness'
                , backgroundColor: "#fff0b9"
                , data: illness
                , stack: 'Stack 0'
            },
            
        ]
    };
 
    var barChartData4 = {
        labels: illcasename
        , datasets: [
            {
                label: 'Illness Cases'
                , backgroundColor: "rgb(46,134,193)"
                , data: illcasedata
                , stack: 'Stack 0'
            },
            
        ]
    };

    var barChartData5 = {
        labels: catcode
        , datasets: [
            {
                label: "Total # of Category"
                //, backgroundColor: ("#ff8c00 ".repeat(catcode.length-1).trim() + " #32cd32").split(" ") 
                , backgroundColor: "#00cc99"
                , barThickness: 50
                , data: catdata
                , stack: 'Stack 0'
            },         
        ]
    };


    var barChartData6 = {
        labels: injurycasename
        , datasets: [
            {
                label: 'Injury Cases'
                , backgroundColor: "rgb(46,134,193)"
                , data: injurycasedata
                , stack: 'Stack 0'
            },
            
        ]
    };

    var barChartData7 = {
        labels: catcode
        , datasets: [
            {
                label: "Total # of Category"
                , backgroundColor: "#00cc99"
                , barThickness: 50
                , data: injurycatdata
                , stack: 'Stack 0'
            },         
        ]
    };

    var barChartData8 = {
        labels: months
        , datasets: [
            {
                label: "Injury Monthly Trend", 
                fill: false,
                lineTension: 0,
                backgroundColor: "#737000",
                //borderColor: "rgb(46,134,193)", 
                borderColor: "#737000",
                data: injurymonth, 
                stack: 'Stack 0'
            },         
        ]
    };
 
    var barChartData9 = {
        labels: year
        , datasets: [
            {
                label: "Injury Yearly Trend", 
                fill: false,
                lineTension: 0,
                backgroundColor: "#008b8b",
                borderColor: "#008b8b",
                data: injuryyear, 
                
            },         
        ]
    };

    var barChartData10 = {
        labels: months
        , datasets: [
            {
                label: "Illness Monthly Trend", 
                fill: false,
                lineTension: 0,
                backgroundColor: "#737000",
                borderColor: "#737000",
                data: illnessmonth, 
                stack: 'Stack 0'
            },         
        ]
    };


    var barChartData11 = {
        labels: year
        , datasets: [
            {
                label: "Illness Yearly Trend", 
                fill: false,
                lineTension: 0,
                backgroundColor: "#008b8b",
                borderColor: "#008b8b",
                data: illnessyear 
                
            },         
        ]
    };


    function colorGen() {
    const randomColorHex = Math.floor(Math.random()*16777215).toString(16);
    return "#" + randomColorHex;
   }


    let datasets_injmnth = [];
    let month_arr = [];
    for (const element in injurycategorymonth) {
        month_arr.push(element)
    }
    //console.log(month_arr);
    categoryname.forEach(
        catgyname => {
            let datacount = [];
            month_arr.forEach(
                month_item =>{
                    month_data = injurycategorymonth[month_item]; //fetching data for a particular month
                    cat_count = month_data[catgyname];
                    datacount.push(cat_count);
                } 
            );
            dataset = {
                label: catgyname,
                backgroundColor: colorGen(),
                stack: 'Stack 0',
                data: datacount
            }
            datasets_injmnth.push(dataset); 
        }
    )
   

    var barChartData12 = {
        labels: months
        , datasets: datasets_injmnth
    };


    let datasets_arr = [];
    let years_arr = [];
    for (const element in injuryyearlycategory) {
    years_arr.push(element)
    }
    //catcode.pop();
    categoryname.forEach(
        cat => {
            let data_count = [];
            years_arr.forEach(
                //year_item => data_count.push(test[year_item][cat])
                year_item =>{
                    year_data = injuryyearlycategory[year_item]; //fetching data for a particular year from injuryyearlycategory[]
                    cat_count = year_data[cat];
                    data_count.push(cat_count);
                } 
            );
            dataset = {
                label: cat,
                backgroundColor: colorGen(),
                stack: 'Stack 0',
                data: data_count
            }
            datasets_arr.push(dataset); 
        }
    )

    

    var barChartData13 = {
        labels: years_arr
        , datasets: datasets_arr
    };
  

    let datasets_illmnth = [];
    let ill_month_arr = [];
    for (const element in illnesscategorymonth) {
        ill_month_arr.push(element)
    }
    
    categoryname.forEach(
        catgyname => {
            let datacount = [];
            ill_month_arr.forEach(
                month_item =>{
                    month_data = illnesscategorymonth[month_item]; 
                    cat_count = month_data[catgyname];
                    datacount.push(cat_count);
                } 
            );
            dataset = {
                label: catgyname,
                backgroundColor: colorGen(),
                stack: 'Stack 0',
                data: datacount
            }
            datasets_illmnth.push(dataset); 
        }
    )
   

    var barChartData14 = {
        labels: months
        , datasets: datasets_illmnth
    };


    let datasets_illyr = [];
    let ill_years_arr = [];
    for (const element in illnesscategoryyear) {
        ill_years_arr.push(element)
    }
    categoryname.forEach(
        cat => {
            let data_count = [];
            ill_years_arr.forEach(
                year_item =>{
                    year_data = illnesscategoryyear[year_item]; 
                    cat_count = year_data[cat];
                    data_count.push(cat_count);
                } 
            );
            dataset = {
                label: cat,
                backgroundColor: colorGen(),
                stack: 'Stack 0',
                data: data_count
            }
            datasets_illyr.push(dataset); 
        }
    )

    

    var barChartData15 = {
        labels: years_arr
        , datasets: datasets_illyr
    };
  

    window.onload = function() {   
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData1
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Injury and Illness -Department wise'
            }
        }
    });
    var ctx = document.getElementById("canvas1").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData2
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Injury-Department wise'
            }
        }
    });
    var ctx = document.getElementById("canvas2").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData3
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Illness-Department Wise'
            }
        }
    });
    

    var ctx = document.getElementById("canvas3").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData4
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Illness Top Cases'
            }
        }
    });


    var ctx = document.getElementById("canvas4").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData5
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Illness-Total # of Category'
            }
        }
    });

    var ctx = document.getElementById("canvas5").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData6
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Injury-Top Cases'
            }
        }
    });

    var ctx = document.getElementById("canvas6").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData7
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Injury-Total # of Category'
            }
        }
    });

    
    var ctx= document.getElementById("canvas7").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData8,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Injury-Monthly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{
                                        
                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
            });

            var ctx= document.getElementById("canvas8").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData9,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Injury Yearly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{
                                        
                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
                });

                var ctx= document.getElementById("canvas9").getContext("2d");
                window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData10,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Illness Monthly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{
                                        
                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
                });

                var ctx= document.getElementById("canvas10").getContext("2d");
                window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData11,
                options: {
                    legend: {
                            display: true,
                            position: 'bottom'
                            },
                    plugins: {
                        filler: {
                            propagate: false,
                        },
                        title: {
                            display: true,
                            text: 'Illness Yearly Trend'
                        }
                        },
                        scales: {
                            xAxes : [ {
                                    gridLines : {
                                        display : false
                                    },
                                } ],
                            yAxes: [{
                                        
                                    gridLines: {
                                        drawBorder: false
                                        },
                                }],
                            },
                        interaction: {
                        intersect: false,
                        }
                    },
                });

                var ctx = document.getElementById("canvas11").getContext("2d");
                window.myBar = new Chart(ctx, {
                    type: 'bar'
                    , data: barChartData12
                    , options: {
                        plugins: {
                            title: {
                                display: true
                                , text: 'Chart.js Bar Chart - Stacked'
                            }
                        , }
                        , responsive: true
                        , interaction: {
                            intersect: false
                        , }
                        , scales: {
                            x: {
                                stacked: true
                            , }
                            , y: {
                                stacked: true
                            }
                        }
                        , legend: {
                            position: 'bottom'
                        }
                        , title: {
                            display: true
                            , text: 'Injury-Total # of Category'
                        }
                    }
                });


                var ctx = document.getElementById("canvas12").getContext("2d");
                window.myBar = new Chart(ctx, {
                    type: 'bar'
                    , data: barChartData13
                    , options: {
                        plugins: {
                            title: {
                                display: true
                                , text: 'Chart.js Bar Chart - Stacked'
                            }
                        , }
                        , responsive: true
                        , interaction: {
                            intersect: false
                        , }
                        , scales: {
                            x: {
                                stacked: true
                            , }
                            , y: {
                                stacked: true
                            }
                        }
                        , legend: {
                            position: 'bottom'
                        }
                        , title: {
                            display: true
                            , text: 'Injury-Total # of Category'
                        }
                    }
                });     

                var ctx = document.getElementById("canvas13").getContext("2d");
                  window.myBar = new Chart(ctx, {
                    type: 'bar'
                    , data: barChartData14
                    , options: {
                        plugins: {
                            title: {
                                display: true
                                , text: 'Chart.js Bar Chart - Stacked'
                            }
                        , }
                        , responsive: true
                        , interaction: {
                            intersect: false
                        , }
                        , scales: {
                            x: {
                                stacked: true
                            , }
                            , y: {
                                stacked: true
                            }
                        }
                        , legend: {
                            position: 'bottom'
                        }
                        , title: {
                            display: true
                            , text: 'Illness-Total # of Category'
                        }
                    }
                });

                var ctx = document.getElementById("canvas14").getContext("2d");
                  window.myBar = new Chart(ctx, {
                    type: 'bar'
                    , data: barChartData15
                    , options: {
                        plugins: {
                            title: {
                                display: true
                                , text: 'Chart.js Bar Chart - Stacked'
                            }
                        , }
                        , responsive: true
                        , interaction: {
                            intersect: false
                        , }
                        , scales: {
                            x: {
                                stacked: true
                            , }
                            , y: {
                                stacked: true
                            }
                        }
                        , legend: {
                            position: 'bottom'
                        }
                        , title: {
                            display: true
                            , text: 'Illness-Total # of Category'
                        }
                    }
                });    

    };
</script>

<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);
</script>
</div>




@endsection