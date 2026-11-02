<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checklist</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<style>
    .innercontent{
        font-size:12px;
        font-family:'Times New Roman', Times, serif;
    }

    .innercontenthd{
        font-size:13px;
        font-family:'Times New Roman', Times, serif;
    }
    .meter { 
  height: 5px;  
  position: relative;
  background:#28a745;
  border-radius: 2px;
  padding: 2px;
  box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
}

.headcontent
{
    font-size:13px;
    font-family:'Times New Roman', Times, serif;
}
.headcontentvalues{
        font-size:12px;
        font-family:'Times New Roman', Times, serif;
    }

</style>
</head>
<body>                    
        
            <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">                                                                           
                <tr>
                  <td colspan="7" class="bg-secondary text-white" style="font-size:15px;height:15px;font-family:'Times New Roman', Times, serif;"><center><b> {{$data->audit_checklist_id}}</b></center></td>
               </tr>
                             
                <tr><td colspan="7">
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">
                   <tr>
                        <td  class="headcontent" style="background:#ededed;">Audit Checklist #</td>
                        <td class="headcontentvalues text-info">{{$data->audit_checklist_id}}</td>
                        <td  class="headcontent" style="background:#ededed;">Checklist Name</td>
                        <td class="headcontentvalues text-info">{{ucfirst($checklist->checklist_name)}}</td>

                        <td  class="headcontent" style="background:#ededed;">{{$category}}</td>
                        <td class="headcontentvalues text-info">{{ucfirst($listing_name)}}</td>                       
                    </tr>

                    <tr>                      
                        <td  class="headcontent" style="background:#ededed;">Audit ID</td>
                        <td class="headcontentvalues text-info">{{$audits->aud_id}}</td>
                        <td  class="headcontent" style="background:#ededed;">Auditor </td>
                        <td class="headcontentvalues text-info">{{$audits->auditors}}</td>
                        <td  class="headcontent" style="background:#ededed;">Auditee</td>
                        <td class="headcontentvalues text-info">{{$audits->contact}}</td>                       
                    </tr>
                    <tr>
                        <td  class="headcontent" style="background:#ededed;">Audit Date From </td>
                        <td class="headcontentvalues text-info">{{date('d-m-Y',strtotime($audits->date))}}</td>
                        <td  class="headcontent" style="background:#ededed;">Audit Date To  </td>
                        <td class="headcontentvalues text-info">{{date('d-m-Y',strtotime($audits->enddate))}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
              </td></tr>

              <tr><td colspan="7">
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">
                    <tr> 
                        <td  class="headcontent" style="background:#ededed;">Audit Progress</td>
                        <td class="d-none d-sm-table-cell ">
                            <div class="meter">
                                <span style="width:{{$data->completed_status}};"></span>
                            </div>
                            <p class="fs-xs fw-semibold mb-0 headcontentvalues text-info">{{$data->completed_status}}</p>
                        </td>
                        <td  class="headcontent" style="background:#ededed;">Total Check points</td>
                        <td class="headcontentvalues text-info">{{$data->total_checklist_qns}}</td>

                        <td  class="headcontent" style="background:#ededed;">Completed </td>
                        <td class="headcontentvalues text-info">{{$data->completed_qns}}</td>

                        <td  class="headcontent" style="background:#ededed;">Remaining</td>
                        <td class="headcontentvalues text-info">{{$data->remaining_qns}}</td>
                    </tr>
                </table>
                </td></tr>

                        <tr><td colspan="7" class="text-white" style="font-size:15px;height:2px;background:#c1c1c1;"></td></tr>                       
                             <tr style="background:#ededed;">
                                <td class="text-center innercontenthd">#</td>
                                <td class="text-center innercontenthd">Control Area</td>
                                <td class="text-center innercontenthd" >Purpose </td>
                                <td class="text-center innercontenthd" >Items to be Verified</td>
                                <td class="text-center innercontenthd" >Item Verified</td>
                               <td class="text-center innercontenthd" >Conformance</td>
                               <td class="text-center innercontenthd" >Remarks</td> 
                             </tr>
                                <?php
                                   $sno = 1;          
                                ?>
                           
                           @foreach($pointstitle as $pttitle)
                           <tr>
                                <td colspan="7" class="font-weight-bold" style="font-size:14px;height:5px;font-family:'Times New Roman', Times, serif;">
                                  <span style="margin-left:10px;margin-right:10px;">Section {{$sno++}} : {{ucfirst($pttitle->title_name)}}</span>
                                  
                               </td>
                            </tr>                        
                                <?php
                                    $mainnum = $sno - 1;
                                    $no = 1;
                                    $newpoints = DB::table('audit_checklist_new_record_details')
                                                                            ->where('title_name',$pttitle->title_name)
                                                                            ->where('audit_checklist_id_year',$data->audit_checklist_id_year) 
                                                                            ->where('is_deleted',0)
                                                                            ->get();
                                   
                                ?>                        
                        @foreach($newpoints as $point)                                 
                                <tr>                                   
                                    <td class="innercontent font-weight-normal">{{$mainnum}}.{{$no++}}</td>
                                    <td class="font-weight-normal innercontent">
                                       <span style="vertical-align: top;padding-left:0px;"><?php echo nl2br ($point->pointsname)?></span>
                                    </td>
                                    <td class="innercontent font-weight-normal"><?php echo nl2br ($point->purpose)?></td>
                                    <td class="innercontent font-weight-normal"><?php echo nl2br ($point->items_verified)?></td>                                   
                                    <td class="innercontent font-weight-normal">
                                        @if($point->items_verified_records!=NULL)
                                         <?php echo nl2br ($point->items_verified_records)?>
                                        @else
                                         &nbsp;
                                        @endif
                                    </td>
                                     <td class="innercontent font-weight-normal">
                                      @if($point->conformance_id!=NULL)
                                        <?php
                                          $conformancevalues = DB::table('audit_checklist_conformances')->where('id',$point->conformance_id)->first();              
                                        ?>
                                        <input type="hidden" name="conformance[]" id="conformance" value="{{$conformancevalues->id}}"/>
                                         @if($conformancevalues->stage=='OK')
                                         <span class="badge badge-success">OK</span>
                                         @elseif($conformancevalues->stage=='NC A')
                                         <span class="badge badge-danger">NC A</span>
                                         @elseif($conformancevalues->stage=='NC B')
                                         <span class="badge badge-warning">NC B</span>
                                         @elseif($conformancevalues->stage=='PI')
                                         <span class="badge badge-primary">PI</span>
                                         @elseif($conformancevalues->stage=='NA')
                                         <span class="badge badge-secondary">NA</span>
                                         @endif                                      
                                      @else
                                        &nbsp;
                                      @endif                                       
                                    </td>
                                   <td class="innercontent font-weight-normal">
                                       @if($point->remarks!=NULL) 
                                       {{$point->remarks}}
                                       @else
                                        &nbsp;
                                       @endif                                      
                                    </td> 
                                </tr>                              
                                @endforeach     
                        @endforeach
                </table> 
                    
</body>
</html>

                              









