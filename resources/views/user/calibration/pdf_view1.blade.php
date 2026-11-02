<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }
</style>
</head>
<body>
@if( count($data1) > 0)
<div style="margin-left:-35px;">
  <table>
    <tr>
      <td colspan="6" style="background:grey;color:white;font-size:25px;"><center>INTERNAL CALIBRATION REPORT</center></td>
    </tr>
    <?php $no=0;?>
    <?php
        $d=date("Y-m-d");
        $t=date("Y-m-d",strtotime("-14 days"));
    ?>
    <tbody class="bor">
      @foreach($data1 as $value) 
      @php 
          $caldt = $value->Dateofcal;
          $cdate = date('Y-m-d',strtotime($caldt));
          $caldat = explode('-',$cdate);
          $datecalval = $caldt == null ? null : $caldat[2]."-".$caldat[1]."-".$caldat[0];
      @endphp
      <tr class="bor">
        <td class="bor" style="width:20px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Instrument<br>&nbsp;&nbsp;Description</td>
        <td class="bor" style="width:100px;font-size:10px">&nbsp;&nbsp;&nbsp;{{$value->Instrument_desc}}</td>
        <td  class="bor" style="width:100px;height:45px;font-size:10px;background:#ededed">&nbsp;&nbsp;&nbsp;Instrument<br>&nbsp;&nbsp;Number</td>
        <td class="bor" style="width:130px;height:35px;font-size:10px">&nbsp;&nbsp;&nbsp;{{$value->Instrument_no}}</td>

        <td  class="bor" style="width:5px;height:15px;font-size:10px;background:#ededed">&nbsp;&nbsp;&nbsp;Calibration<br>&nbsp;&nbsp;Procedure</td>
        <td class="bor" style="width:70px;height:15px;font-size:10px">&nbsp;&nbsp;&nbsp;
        @if($value->Calib_procedure==NULL)
            &nbsp;&nbsp;&nbsp;
          @else
            {{$value->Calib_procedure}}
          @endif
        </td>
      </tr>
      <tr class="bor"><td class="bor"style="width:110px;height:35px;font-size:10px;background:#ededed">&nbsp;&nbsp;&nbsp;Acceptable<br>&nbsp;&nbsp;Error</td>
            <td class="bor"  style="width:120px;height:35px;font-size:10px">&nbsp;&nbsp;&nbsp;{{$value->Acc_error}}</td>
            <td class="bor"  style="width:120px;height:35px;font-size:10px;background:#ededed">&nbsp;&nbsp;&nbsp;Master instrument</td>
            <td class="bor"  style="width:120px;height:35px;font-size:10px">&nbsp;&nbsp;&nbsp;{{$value->M_instru}}</td>
            <td class="bor"  style="width:130px;height:35px;font-size:10px;background:#ededed">&nbsp;&nbsp;&nbsp;Master Instrument<br> Certificate Number</td>
            <td class="bor"  style="width:130px;height:35px;font-size:10px">&nbsp;&nbsp;&nbsp;{{$value->Certificate_no}}</td>
      </tr>
      <tr>                                       
        <td class="bor"  style="width:80px;height:40px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Report Number</td>
        <td class="bor" style="width:90px;height:40px;font-size:10px;">&nbsp;&nbsp;&nbsp;{{$value-> Report_no}}</td>
        <td class="bor"  style="width:90px;height:40px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Calibration Status</td>
        <td class="bor" style="width:90px;height:40px;font-size:10px;">&nbsp;&nbsp;&nbsp;{{$value-> Status}}</td>
        <td class="bor"  style="width:80px;height:40px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Adjustment Made</td>
        <td class="bor" style="width:80px;height:40px;font-size:10px;">&nbsp;&nbsp;&nbsp;{{$value-> Adj_made}}</td>
      </tr>
      <tr>                                             
        <td class="bor"  style="width:80px;height:40px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Date of calibration</td>
        <td class="bor" style="width:90px;height:40px;font-size:10px;">&nbsp;&nbsp;&nbsp;
            @if($value->Dateofcal=="1970-01-01")
                  &nbsp;&nbsp;&nbsp;
              @else
                  {{$datecalval}}
              @endif
        </td>                
        <td class="bor"  style="width:90px;height:40px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Calibrated By</td>
        <td class="bor" style="width:90px;height:40px;font-size:10px;">&nbsp;&nbsp;&nbsp;{{$value-> Calibrated_by}}</td>
        <td class="bor"  style="width:90px;height:40px;font-size:10px;background:#ededed;">&nbsp;&nbsp;&nbsp;Verified By</td>
        <td class="bor" style="width:90px;height:40px;font-size:10px;">&nbsp;&nbsp;&nbsp;{{App\User::where('email',$value-> Verified_by)->value('name')}}</td>
      </tr>
        @foreach($CustomFields->chunk(3) as $fieldPair)
            <tr>
                @foreach($fieldPair as $CustomField)
                    @php 
                        $fieldname = Str::slug($CustomField->field_name, '_'); 
                        $rawValue = $data4->{$fieldname} ?? '';
                        
                        $isFile = $CustomField->field_type === 'file';
                        $isFileMultiple = $CustomField->field_type === 'file_multiple';
                        $displayValue = null;
                        $fileArray = [];
                        if($isFile || $isFileMultiple){
                            $decoded = json_decode($rawValue, true);
                            if (is_array($decoded)) {
                                $fileArray = $decoded;
                            }
                        }elseif (is_string($rawValue) && is_array(json_decode($rawValue, true))) {
                              $decoded = json_decode($rawValue, true);
                              if(in_array($CustomField->field_type,['multiselect','checkbox'])){
                                $decoded = App\DropdownOption::whereIn('id',$decoded)->pluck('option')->toArray();
                              }
                              $displayValue = implode(', ', $decoded);
                          } else {
                              if(in_array($CustomField->field_type,['radio','select'])){
                                    $rawValue = App\DropdownOption::where('id',$rawValue)->value('option');
                              }
                              $displayValue = $rawValue;
                          }
                    @endphp
                    <td class="bor"  style="width:90px;height:40px;font-size:10px;background:#ededed;">
                        &nbsp;&nbsp;&nbsp;{{ $CustomField->field_name }}
                    </td>
                    <td class="bor" style="width:90px;height:40px;font-size:10px;">
                        &nbsp;&nbsp;&nbsp;
                        @if(count($fileArray))
                            <!-- View button opens modal -->
                            <button type="button" class="btn btn-secondary btn-sm" style="font-size:12px;" data-toggle="modal" data-target="#fileModal">
                                View Attachments
                            </button>
                        @else
                            {{$displayValue??'--'}}
                        @endif
                    </td>
                @endforeach
                @if($fieldPair->count() < 3)
                    <td class="bor"  style="width:90px;height:40px;font-size:10px;background:#ededed;"></td>
                    <td class="bor" style="width:90px;height:40px;font-size:10px;"></td>
                @endif
            </tr>
        @endforeach
      @break
      @endforeach
    </tbody>
  </table>
  <table class="bor">
    <tr class="bor">
      <td class="bor"  style="width:70px;height:50px;font-size:10px;align:center;background:#ededed"><center>Slno</center></td>
      <td class="bor"  style="width:160px;height:40px;font-size:10px;align:center;background:#ededed"><center>Master Reading </center></td>
      <td class="bor" style="width:160px;height:35px;font-size:10px;background:#ededed"><center>Instrument Reading</center></td>
      <td class="bor" style="width:90px;height:35px;font-size:10px;background:#ededed"><center>Error<center></td>
      <td class="bor" style="width:264px;height:35px;font-size:10px;background:#ededed"><center>Remarks</center></td>
      <!-- <td class="bor" style="width:254px;height:45px;font-size:10px;background:#ededed"><center><br><br>Report Number</center></td> -->
      <!-- <td class="bor" style="width:254px;height:45px;font-size:10px;background:#ededed"><center><br><br></center></td> -->
    </tr>
    <?php $no=1;?>
    @foreach($data1 as $value) 
      <tr class="bor"> 
        <td class="bor" style="font-size:10px;width: 50px;height:30px">&nbsp;&nbsp;&nbsp;{{$no++}}</td>
        <td class="bor" style="font-size:10px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value-> M_reading}}</td>
        
        <td class="bor"style="font-size:10px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value->Instrument_reading}}</td>
        <td class="bor"style="font-size:10px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value-> Error}}</td>

        <td class="bor"style="font-size:10px;width: 50px;">&nbsp;&nbsp;&nbsp;{{$value->Remarks}}</td>
      </tr>
    @endforeach
  </table> 
</div>
@else
  <div class="row justify-content-center">
      <h2>No Calibration log available</h2>
  </div>
@endif                     
</body>
</html>




                   
                   



                                                    



                                           
                                  
                           
                   
                  
                    


                    