@extends('layouts.backend')

@section('content')

<style>
    table{
  
  border-collapse: collapse;
}
td{
    border:1px solid #D0D0D0;
}
</style>

      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Occupational Health</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href=""> Health History</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/occupationhealth/register">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

        
                <!-- END Hero -->
               
                 <!-- Page Content -->
                 <div class="content">
                   <!-- Horizontal -->
                    <div class="block block-rounded">                       
                        <div class="block-content block-content-full">
                            <div class="row">                                
                                <div class="col-md-12">
                                   
                                            <table width="100%" cellpadding="8"> 
                                            <tr>
    <td colspan="4" class="bg-success text-white" style="font-size:20px;height:35px;font-family:Arial, Helvetica, sans-serif;"><center><b>Health History</b></center></td>
</tr> 
                                               <tr>
                                                 <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Employee Name</td>
                                                 <td style="font-size:13px;text-transform:capitalize;">{{$emp->emp_name}}</td>
                                                 <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Employee ID</td>
                                                 <td style="font-size:13px;text-transform:capitalize;">{{$emp->emp_number}}</td>
                                               </tr>

                                               <tr>
                                                 <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Position</td>
                                                 <td style="font-size:13px;text-transform:capitalize;">{{$emp->emp_position}}</td>
                                                 <td class="text-left col-form-label" style="font-size:15px;background:#ededed;font-family:Arial, Helvetica, sans-serif;">Department</td>
                                                 <td style="font-size:13px;text-transform:capitalize;">
                                                    @if(is_numeric($emp->emp_department))
                                                        @foreach($departments as $dep)
                                                            @if($emp->emp_department==$dep->id)
                                                                {{$dep->departmentname}}
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        @else
                                                          {{$emp->emp_department}}
                                                    @endif
                                                 </td>
                                               </tr>
                                           
                                                <tr><td colspan="4" class="bg-success text-white" style="font-size:15px;height:20px;font-family:Arial, Helvetica, sans-serif;">Routine Checkup</td></tr>
                                                 
                                                <tr style="background:#ededed;">
                                                 <td class="text-center" style="font-size:14px;">Date Of Checkup</td>
                                                 <td class="text-center" style="font-size:14px;">Blood Pressure (Diastolic)</td>
                                                 <td class="text-center" style="font-size:14px;">Blood Pressure (Systolic)</td>
                                                 <td class="text-center" style="font-size:14px;">Blood Sugar</td>
                                               </tr>

                                               @foreach($empmedrecord as $empl)
                                               <tr>
                                                 <td class="text-center" style="font-size:12px;text-transform:capitalize;">{{date('d-m-Y',strtotime($empl->date_of_medical))}}</td>
                                                 <td  class="text-center" style="font-size:12px;text-transform:capitalize;">{{$empl->bp_diastolic}}</td>
                                                 <td  class="text-center"style="font-size:12px;text-transform:capitalize;">{{$empl->bp_systolic}}</td>
                                                 <td  class="text-center"style="font-size:12px;text-transform:capitalize;">{{$empl->bs_level}}</td>
                                               </tr>
                                               @endforeach

                                               <tr><td colspan="4" class="bg-success text-white" style="font-size:15px;height:20px;font-family:Arial, Helvetica, sans-serif;">Occupational Injury Records</td></tr>
                                                
                                               <tr style="background:#ededed;">
                                                 <td class="text-center" style="font-size:14px;">Date Of Injury</td>
                                                 <td class="text-center" style="font-size:14px;">Report Number</td>
                                                 <td class="text-center" style="font-size:14px;"># of Medical leaves</td>
                                                 <td class="text-center" style="font-size:14px;">Status</td>
                                               </tr>
                                         
                                               @foreach($injury as $inj)
                                               <tr>
                                                 <td class="text-center" style="font-size:12px;text-transform:capitalize;">
                                                    @if($injury_row > 0)
                                                      {{$inj->dateofinjury}}
                                                    @else
                                                     {{NULL}}
                                                    @endif
                                                </td>
                                                 <td class="text-center" style="font-size:12px;text-transform:capitalize;">
                                                    @if($injury_row > 0)
                                                      {{$inj->report_num}}
                                                    @else
                                                      {{NULL}}
                                                     @endif
                                                 </td>
                                                 <td  class="text-center" style="font-size:12px;text-transform:capitalize;">
                                                     @if($injury_row > 0)
                                                       {{$inj->medical_leaves}}
                                                     @else
                                                       {{NULL}}
                                                     @endif
                                                 </td>
                                                 <td  class="text-center" style="font-size:13px;text-transform:capitalize;">
                                                    @if($injury_row > 0)
                                                    @if($inj->status=='Cured')
                                                        <span class="badge badge-success">{{$inj->status}}</span>
                                                        @elseif($inj->status=='In Progress')
                                                        <span class="badge badge-warning">{{$inj->status}}</span>
                                                        @elseif($inj->status=='Fatality')
                                                        <span class="badge badge-danger">{{$inj->status}}</span>
                                                        @else
                                                        <span class="badge badge-info">{{$inj->status}}</span>
                                                        @endif
                                                     @else
                                                       {{NULL}}
                                                     @endif
                                                 </td>

                                               </tr>
                                              @endforeach
                                               <tr><td colspan="4" class="bg-success text-white" style="font-size:15px;height:20px;font-family:Arial, Helvetica, sans-serif;">Occupational Illness Records</td></tr>

                                               <tr style="background:#ededed;">
                                                 <td class="text-center" style="font-size:14px;">Date Of Injury</td>
                                                 <td class="text-center" style="font-size:14px;">Report Number</td>
                                                 <td class="text-center" style="font-size:14px;"># of Medical leaves</td>
                                                 <td class="text-center" style="font-size:14px;">Status</td>
                                               </tr>
                                              @foreach($illness as $ill)
                                               <tr>
                                                 <td class="text-center" style="font-size:12px;text-transform:capitalize;">
                                                    @if($ill_row > 0)
                                                      {{$ill->dateofillness}}
                                                    @else
                                                     {{NULL}}
                                                    @endif
                                                 </td>
                                                 <td class="text-center" style="font-size:12px;text-transform:capitalize;">
                                                    @if($ill_row > 0)
                                                      {{$ill->report_num}}
                                                    @else
                                                     {{NULL}}
                                                    @endif
                                                 </td>
                                                 <td class="text-center" style="font-size:12px;text-transform:capitalize;">
                                                   @if($ill_row > 0)
                                                      {{$ill->medical_leaves}}
                                                    @else
                                                     {{NULL}}
                                                    @endif
                                                </td>
                                                 <td class="text-center" style="font-size:13px;text-transform:capitalize;">
                                                   @if($ill_row > 0)
                                                   @if($ill->status=='Cured')
                                                        <span class="badge badge-success">{{$ill->status}}</span>
                                                        @elseif($ill->status=='In Progress')
                                                        <span class="badge badge-warning">{{$ill->status}}</span>
                                                        @elseif($ill->status=='Fatality')
                                                        <span class="badge badge-danger">{{$ill->status}}</span>
                                                        @else
                                                        <span class="badge badge-info">{{$ill->status}}</span>
                                                        @endif
                                                    @else
                                                     {{NULL}}
                                                    @endif
                                                 </td>
                                               </tr>
                                              @endforeach

                                              </table>
                                           
                                </div>
                             </div>
                          </div>
                       </div>


              <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <!-- Opens from the modal toggle button in the header -->
              <!-- Opens from the modal toggle button in the header -->
              <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">More</h3>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/register">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Employee Health Record
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/injuryrecords">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Employee Injury Record
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/illnessrecords">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Employee Illness Record
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{ url('/user/occupationhealth/analysis') }}">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 " style="margin-bottom:60px">
                                                  Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->

  
   @endsection
