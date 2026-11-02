<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style>
    
table{
  
  border-collapse: collapse;
}
td{
    border:1px solid #D0D0D0;
}
</style>
</head>
<body>

<table style="width:715px;" border="0">
<tr>
   <td colspan="4" class="bg-success text-white"  style="font-size:20px;height:35px;font-family:Arial, Helvetica, sans-serif;"><center><b>Calibration Log</b></center></td>
</tr>

@if($status > 0)


                                         
                                                    
                                                 <tr>
                                                    <td style="width:100px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Instrument Name</td>
                                                    <td style="width:205px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->Instrument_desc}}</td>
                                                    <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Instrument Manufacturer</td>
                                                    <td style="width:185px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;
                                                    @if($data2->Instru_Manufacturer=="no value")
                                                      &nbsp;&nbsp;&nbsp;
                                                    @else
                                                        {{$data2->Instru_Manufacturer}}
                                                    @endif
                                                   </td>
                                                  </tr>

                                                   <tr>
                                                      <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Instrument serial No</td>
                                                      <td   style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->Instrument_no}}</td>
                                                      <td   style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Instrument Number</td>
                                                      <td   style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->Instrument_no}}</td>
                                                   </tr>
                                                
                                                   <tr>
                                                      <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Calibration frequency</td>
                                                      <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->Frequency}}</td>
                                                      <td  style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Capacity</td>
                                                      <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->Capacity}}</td>
                                                   </tr>

                                                   <tr>
                                                      <td style="width:160px;height:35px;font-size:13px;background:#ededed;">&nbsp;&nbsp;&nbsp;Instrument Location</td>
                                                      <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->Holder}}</td>
                                                      <td  style="width:160px;height:35px;font-size:13px; background:#ededed;">&nbsp;&nbsp;&nbsp;Date of First Use</td>
                                                      <td  style="width:150px;height:35px;font-size:13px">&nbsp;&nbsp;&nbsp;{{$data2->date_of_first_use?date('d-m-Y',strtotime($data2->date_of_first_use)):''}}</td>
                                                   </tr>
                                                   @foreach($CustomFields->chunk(2) as $fieldPair)
                                                      <tr>
                                                         @foreach($fieldPair as $CustomField)
                                                            @php 
                                                                  $fieldname = Str::slug($CustomField->field_name, '_'); 
                                                                  $rawValue = $data3->{$fieldname} ?? '';
                                                                  $displayValue = null;
                                                                  $isFile = $CustomField->field_type === 'file';
                                                                  $isFileMultiple = $CustomField->field_type === 'file_multiple';

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
                                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;">
                                                                  &nbsp;&nbsp;&nbsp;{{ $CustomField->field_name }}
                                                            </td>
                                                            <td style="width:150px;height:35px;font-size:13px;">
                                                                  &nbsp;&nbsp;&nbsp;
                                                                  @if(count($fileArray))
                                                                     <!-- View button opens modal -->
                                                                     <button type="button" class="btn btn-secondary btn-sm" style="font-size:12px;">
                                                                        View Attachments
                                                                     </button>
                                                                  @else
                                                                     {{$displayValue??'--'}}
                                                                  @endif
                                                            </td>
                                                            
                                                         @endforeach
                                                         @if($fieldPair->count() < 2)
                                                            <td style="width:160px;height:35px;font-size:13px;background:#ededed;"></td>
                                                            <td style="width:150px;height:35px;font-size:13px;"></td>
                                                         @endif
                                                      </tr>
                                                   @endforeach
                                                   
                                                    <tr style="border-top:3px solid #28a745;">     
                                                      <td  style="width:100px;height:35px;font-size:13px;background:#ededed;"><center><b>Report No</b></center></td>
                                                      <td  style="width:130px;height:35px;font-size:13px;background:#ededed;"><center><b>Date of Calibration</b></center></td>
                                                      <td  style="width:120px;height:35px;font-size:13px;background:#ededed;"><center><b>Calibration Status</b><center></td>
                                                      <td  style="width:135px;height:35px;font-size:13px;background:#ededed;"><center><b>Calibration Due Date</b></center></td>  
                                                    </tr>          
                     
                                              @foreach($data1 as $value)
                                                 @php
                                                   $caldt = $value->Dateofcal;
                                                   $cdate = date('Y-m-d',strtotime($caldt));
                                                   $caldat = explode('-',$cdate);
                                                   $datecalval = $caldt == null ? null : $caldat[2]."-".$caldat[1]."-".$caldat[0];
                                                        
                                                  $ddt = $value->Duedate;
                                                  $dudate = date('Y-m-d',strtotime($ddt));
                                                  $dudat = explode('-',$dudate);
                                                  $duedateval = $ddt == null ? null : $dudat[2]."-".$dudat[1]."-".$dudat[0];
  
                                                  $neartoexpiry=date('Y-m-d',strtotime($ddt. ' - 30 days'));
                                                  $today_dt=date('Y-m-d');
                                                  
                                                  $report_val = $value->Report_no == "novalue" ? null : $value->Report_no;
                                                
                                                 @endphp

                                                 @if($value->Status!=NULL)
                                                       <tr> 
                                                      
                                                       <td class="text-center" style="font-size:14px;width: 50px;height:30px">&nbsp;&nbsp;&nbsp;{{$report_val}}</td>
                                                       <td class="text-center" style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                       @if($value->Dateofcal=="1970-01-01")
                                                              &nbsp;&nbsp;&nbsp;
                                                          @else
                                                             {{$datecalval}}
                                                          @endif
                                                        </td>
                                                       
                                                       <td class="text-center "style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                          @if($value->Duedate<$today_dt || $value->Status=="Expired")
                                                             <span class="badge badge-danger"> Expired </span>
                                                          @elseif( $neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
                                                             <span class="badge badge-warning"> Near to expiry </span>
                                                          @elseif($value->Status=="Calibrated")
                                                             <span class="badge badge-success">Calibrated</span>
                                                          @endif
                                                      </td>
                                                       <td class="text-center "style="font-size:14px;width: 50px;">&nbsp;&nbsp;&nbsp;
                                                        @if($value->Duedate=="1970-01-01")
                                                              &nbsp;&nbsp;&nbsp;
                                                          @else
                                                          {{$duedateval}}
                                                          @endif
                                                       </td>

                                                       
                                                       </tr>
                                                       @endif
                                                    @endforeach
                                           




                                           

                   
                   @else
                   <tr>
                     <td colspan="4" style="font-size:20px;"><center>No reports</center></Td>
                   </tr>
                   @endif
                </table>
</body>       

</html>

                                                    



                                           
                                  
                           
                   
                  
                    


                    