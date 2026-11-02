@extends('admin.nav')
@section('content')




            <!-- Main Container -->
            <main id="main-container">
            <!-- Page Content -->
                <div class="content"  style="width:100%;margin-top:-75px">
                                 @if(Session::has('message'))
                                       <div class="alert alert-success">
                                           <a class="close" data-dismiss="alert">×</a>
                                           {!!Session::get('message')!!}
                                       </div>
                                   @endif
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                       <div class="block-header">
                            <h3 class="block-title">Calibration Table</h3>
                            <ol class="breadcrumb breadcrumb-alt" style="margin-left:53%;">
                                <li class="breadcrumb-item"></li>
                       </div>

                       <div class="block-content block-content-full">
                       <form method="post" action="{{url('/admin/multiplecalaction')}}">
		                  {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 col-lg-12">
                                    <table width="100%" style="margin-bottom:20px;">
                                        <tr>
                                           <td align="right" colspan="2">
                                             <input class="btn btn-primary btn-sm"   type="submit" onclick="return chkedit()" name="submit" value="Edit"/>

                                             <input class="btn btn-danger btn-sm"  type="submit" onclick="return delins()" name="submit" value="Delete"/>
                                           </td>
                                       </tr>
                                    </table>
                            </form>     
                                </div>

                                 
                                <div class="col-md-6 col-lg-12">
                                    <table width="100%" style="margin-bottom:20px;" border="0">
                                        <tr>
                                            <td width="50%">
                                                <form>
                                                    <select id="pagination" style="border-color:#cccccc;font-size:14px;width:60px;height:30px;">
                                                        <option value="10" @if($items == 10) selected @endif >10</option>
                                                        <option value="25" @if($items == 25) selected @endif >25</option>
                                                        <option value="50" @if($items == 50) selected @endif >50</option>
                                                        <option value="75" @if($items == 75) selected @endif >75</option>
                                                        <option value="100" @if($items == 100) selected @endif >100</option>
                                                        <option value="1000" @if($items == 1000) selected @endif >1000</option>
                                                    </select>
                                                </form>
                                            </td>
                                     <form method="get" action="{{url('/admin/calibrationlist')}}"> 
                                         <td align="right" width="49%">
                                              <input type="text" class="form-control" name="search" placeholder="Search.." value="{{$serchval}}" style="font-size:14px;width:210px;height:30px;">
                                          </td>
                                          <td align="left" width="1%">
                                           <span class="input-group-btn">
                                                <button type="submit"class="btn btn-default-sm">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                             </span>
                                          </td>  </form>
                                       </tr>
                                    </table>
                                </div>
                               

                                <div class="col-md-12 col-sm-6 table-responsive">
                                <table width="100%" class="table table-bordered table-striped table-vcenter">
                                <thead>

                                        <tr>
                                            <th class="text-left" style="width:1%;"><input type="checkbox" id="checkAll"></th>
                                            <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize">Sl No</th>
                                            <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize">Instrument Number</th>
                                            <th class="text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Description</th>
                                            <th class="text-left"  style="width: 5%;font-size:10px;text-transform:capitalize">Department</th>
                                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize" >Holder</th>
                                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize" >Capacity</th>
                                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize" >Type</th>
                                            <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize" >Frequency</th>
                                            <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize" >Standard Edition</th>
                                            <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize" >Acceptance Criteria</th>
                                            <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize" >Agency</th>
                                            <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize" >Date of Cal</th>
                                            <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize" >Due Date</th>
                                            <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize" >Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                @if(count($caldetails)>0)
                                    @foreach($caldetails as $key => $cal)
                                    @php
									   $dalofcal = $cal->dateofcal;
									   $date = date('Y-m-d',strtotime($dalofcal));
									   $dt = explode('-',$date);
									   $caldateval = $dalofcal == null ? null :  $dt[2]."-".$dt[1]."-".$dt[0];

									   $duedt = $cal->Duedate;
									   $ddate = date('Y-m-d',strtotime($duedt));
									   $dudat = explode('-',$ddate);
									   $dudateval = $duedt == null ? null : $dudat[2]."-".$dudat[1]."-".$dudat[0];

                                       $status = $cal->Status;
                                       $status_val = $status == null ? "Not Calibrated" : $cal->Status;


                                        $neartoexpiry=date('Y-m-d',strtotime($duedt. ' - 30 days'));
                                        $today_dt=date("Y-m-d");

                                     @endphp

                                    <?php $id=$cal->Instrument_no;?>
                                        <tr>
                                          <td><input name='id[]' type="checkbox" id="checkItem" value="{{ $cal->Instrument_no }}"></td>
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{ $caldetails->firstItem() + $key }}</span>
                                            </td>                    
                                            
                                            
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Instrument_no}}</span>
                                            </td>
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Instrument_desc}}</span>
                                            </td>

                                            <td  style="font-size:10px" class="text-left">
                                            @if(is_numeric($cal->Department))
                                                @foreach($departments as $dep)
                                                @if($cal->Department==$dep->id)
                                                    {{$dep->departmentname}}
                                                    @break
                                                @endif
                                                @endforeach
                                              @else
                                                    {{$cal->Department }}
                                              @endif             
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Holder}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Capacity}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Type}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Frequency}}</span>
                                            </td>
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->standard_edition?$cal->standard_edition:'--' }}</span>
                                            </td>
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->acceptance_criteria?$cal->acceptance_criteria:'--'}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$cal->Agency}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                            @if($cal->dateofcal=="1970-01-01")
                                            <span>&nbsp;&nbsp;</span>
                                            @else
                                                <span>{{$caldateval}}</span>
                                            @endif
                                             </td>

                                            <td style="font-size:10px" class="text-left">
                                            @if($cal->Duedate=="1970-01-01")
                                            <span>&nbsp;&nbsp;</span>
                                            @else
                                                <span>{{$dudateval}}</span>
                                            @endif
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                            @if($cal->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                                                <span class="badge badge-primary" style="font-size:10px">Under Calibration</span>
                                            @else
                                                @if($cal->Duedate==NULL)
                                                <span class="badge badge-secondary" style="font-size:10px">Not Calibrated</span>
                                                @elseif($cal->Duedate<$today_dt || $cal->Status=="Expired")
                                                <?php
                                                // echo App\Http\Controllers\CalibrationController::statusExpired($cal->Duedate); ?>
                                                <span  class="badge badge-danger" style="font-size:10px">Expired</span>
                                                @elseif( $neartoexpiry<=$today_dt || $cal->Status=="Near to expiry" )
                                                <?php
                                                // echo App\Http\Controllers\CalibrationController::statusNeartoexpiry($cal->Duedate); ?>
                                                <span class="badge badge-warning" style="font-size:10px">Near to expiry</span>
                                                @elseif($cal->Duedate>$today_dt || $cal->Status=="Calibrated")
                                                <?php
                                                // echo App\Http\Controllers\CalibrationController::statusCalibrated($cal->Duedate); ?>
                                                <span  class="badge badge-success" style="font-size:10px">Calibrated</span>
                                                @endif
                                            @endif
                                            </td>

                                        </tr>

                                    @endforeach
                                    @else
                                    <tr>
                                      <td colspan="13" align="center" style="font-size:16px">No data available in table</td>
                                    </tr>
                                    @endif
                                    </tbody>
                            </table>
                            <table width="100%">
           <tr>
              <td width="70%" align="left" style="margin-left:5px;">Page <b>{{$caldetails->currentPage()}}</b> of <b>{{$caldetails->lastPage()}}</b></td>
              <td width="20%" align="right">
                 {{ $caldetails->appends(compact('items'))->links() }}
               </td> 
           </tr>
       </table>
                                </div>
                            </div>
                       </div>
                       <!--</form>-->
                    </div>
                    <!-- END Dynamic Table Full -->

                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>      
        document.getElementById('pagination').onchange = function() { 
            window.location = "{!! $caldetails->url(1) !!}&items=" + this.value; 
        }; 
    </script>

        <script language="javascript">
			$("#checkAll").click(function () {
				$('input:checkbox').not(this).prop('checked', this.checked);
			});
		</script>
        <script>
             function chkedit() {
                if ($('input:checkbox:checked').length > 0) {} else {
                    alert('Please select a record to edit');
                    return false;
                }
              }

            function delins()
            {
                if ($('input:checkbox:checked').length > 0) {} else {
                    alert('Please select a record to delete');
                    return false;
                }
                mess1= "Are you sure?";
                x=confirm(mess1);
                if(x==true){
                    mess2='Instrument Details will be deleted permanently,Press OK to continue.';
                    y=confirm(mess2);
                    if(y==true){
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return false;
                }
            }
         </script>



    </body>
</html>
@endsection
