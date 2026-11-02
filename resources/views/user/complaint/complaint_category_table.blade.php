<table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata_1">
    <thead>
        <tr style="position: sticky; top: 0;z-index:5;">
            <td class="text-center px-1" data-orderable="false" style="background-color:#fff;"></td>
            <td class="text-center titleheading px-1" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" draggable="false" data-orderable="false">S.No</td>
            <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Complaint ID</td>
            <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Title</td>
            <td data-column-name="type" data-orderable="false" id="types101" class="text-center titleheading titleheading2 px-1" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;">Type</td>
            <td data-column-name="customer"class="text-center titleheading px-1 titleheading2" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Customer</td>
            <td data-column-name="responsibility" id="responsibilities101" class="text-center titleheading px-1 titleheading2" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Responsibility</td>
            <td data-column-name="department" id="departments101" class="text-center titleheading px-1 titleheading2" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Department</td>
            <td data-column-name="due date" id="duedates101" class="text-center titleheading px-1 titleheading2" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Due Date</td>
            <td data-column-name="cost impact" class="text-center titleheading px-1 titleheading2" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Cost Impact</td>
            <td data-column-name="product complaint category" data-column-label="Complaint Categories"  id="complaintcategories101" class="text-center titleheading px-1 titleheading2" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;width:" data-orderable="false">Complaint Categories</td>
            <td data-column-name="Process Stage" id="processstageshead101" class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">Process Stage</td>
            <td class="text-center titleheading titleheading2 px-1" draggable="false" style="background-color:#fff;font-size:10px;font-weight:bold !important;cursor:pointer;" data-orderable="false">
                Action Status
            </td>
            <td class="text-center px-1 titleheading" style="background-color:#fff;cursor:pointer;" data-orderable="false" draggable="false">Actions</td>
        </tr>
    </thead>
    <tbody>
        <?php $no=0; ?>
        @foreach($data as $value)
            <?php
                $rr = $value->id;
                $date = $value->tardate;
                $today = date("Y-m-d");
                $customer = $customersData[$value->customer] ?? $value->customer;
                $depname = $departmentsData[$value->dept] ?? '--';
                $res = $responsibilitiesData[$value->responsibility]??"--";
                $raisedby =$raisedbyRecordsData[$value->addedby]??"--";
                $receivedby = $receivedbyRecordsData[$value->employee]??'--';
                $plantname = $plantsData[$value->plant]??"--";
                $subdiv = $subdivisionsData[$value->area_id]??"--";
                $div = $divisionsData[$value->division_id]??"--";
                $leadername =$usersData[$value->team_lead_id]??'--';
                $reviewedbyname = $usersData[$value->reviewed_by]??'--';
                $closedbyname = $usersData[$value->closed_by]??'--';
                $products = $productDetailsData[$value->id] ?? collect();
                $rowColor = $no%2==0 ? '#f1f4f5' : '#fff';
                $cost_impact = $products->pluck('cost_impact')->toArray();
                $complaint_category = $products->pluck('complaint_category')->toArray();
            ?>
            <tr data-id="{{$value->id}}">
                <td><input class="text-center " style="font-size:10px;" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                <td class="text-center px-1" style="font-size:10px;">{{ $no = $no + 1 }}</td>
                <td class="text-center px-1" style="font-size:10px;">
                    {{$value->trackno}}
                </td>
                <td class="text-center font-w600 px-1" style="font-size:10px;word-wrap: break-word;">
                    <a href="{{ route('complaint.detail', $value->id) }}"  data-toggle="tooltip" title="Click to view details" data-position="top">{{$value->title}}</a>
                </td>
                <td class="text-center px-1" style="font-size:12px;">
                    @if($value->type=="Low")
                    <span class="badge badge-success">{{$value->type}}</span>
                    <span style="display: none;">{{ "e_".$value->type. "_f" }}</span>
                    @elseif($value->type=="Moderate")
                    <span class="badge badge-warning">{{$value->type}}</span>
                    <span style="display: none;">{{ "e_".$value->type. "_f" }}</span>
                    @elseif($value->type=="High")
                    <span class="badge badge-danger">{{$value->type}}</span>
                    <span style="display: none;">{{ "e_".$value->type. "_f" }}</span>
                    @else
                    <span>--</span>
                    @endif
                </td>
                <td class="text-center px-1" style="font-size:10px;">
                    {{$customer ? $customer : $value->customer}}
                </td>
                <td class="text-center px-1" style="font-size:10px;">
                    {{$res}}
                    <span style="display: none;">{{ "e_".$res . "_f" }}</span>
                </td>
                <td class="text-center px-1" style="font-size:10px;">
                    <em class="text-muted">{{ $depname ? $depname : $value->dept}}</em>
                    <span style="display: none;">{{ $depname ? "e_".$depname."_f" : "e_".$value->dept."_f" }}</span>
                </td>
                <td class="text-center px-1" style="font-size:10px;">
                    {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                </td>
                <td class="text-center p-0" style="font-size:10px;">
                    @if(count($cost_impact) > 0)
                        <table class="table m-0 p-0 table-borderless" style="background-color:inherit;">
                            @foreach ($cost_impact as $c)
                            <tr style="background-color: inherit;">
                                <td style="font-size:10px; background-color:inherit;{{count($cost_impact)>1 && $loop->iteration<count($cost_impact) ?'border-bottom: 1px solid #e8ecee':''}};">
                                    {{ $c ?? '--' }}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    @else
                        {{ '--' }}
                    @endif
                </td>
                <td class="text-center p-0" style="font-size:10px;">
                    @if(count($complaint_category) > 0)
                        <table class="table m-0 p-0 table-borderless" style="background-color:inherit;border-collapse: collapse;">
                            @foreach($complaint_category as $cc)
                                @php $cat = DB::table('complaint_categories')->whereIn('id',explode(',',$cc))->pluck('category_name')->toArray(); @endphp
                                <tr style="background-color: inherit;">
                                    <td style="font-size:10px; background-color:inherit;{{count($complaint_category)>1 && $loop->iteration<count($complaint_category)?'border-bottom: 1px solid #e8ecee':''}}">
                                        @foreach($cat as $c)
                                            <span style="display: none;">{{"e_".$c."_f"}}</span>
                                        @endforeach
                                        {{ $cat ? implode(',',$cat):'--' }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        {{ '--' }}
                    @endif
                </td>
                <td class="text-center px-1" style="font-size:12px;">
                    @if($value->status=='Under Review')
                    <span class="badge badge-secondary">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @elseif($value->status=='Actions Proposed')
                    <span class="badge badge bg-flat" style="color:white">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @elseif($value->status=='Action Reviewed, OK')
                    <span class="badge badge-success">Action Approved</span>
                    <span style="display: none;">e_Action Approved_f</span>
                    @elseif($value->status=='Action Reviewed, Not OK')
                    <span class="badge badge-danger">Action Rejected</span>
                    <span style="display: none;">e_Action Rejected_f</span>
                    @elseif($value->status=='Need More Action')
                    <span class="badge badge-warning">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @elseif($value->status=='Actions Completed')
                    <span class="badge badge-info">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @elseif($value->status=='Approved')
                    <span class="badge badge bg-amethyst-light" style="color:white">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @elseif($value->status=='Rejected')
                    <span class="badge badge bg-city-light" style="color:white">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                    <span class="status status-blue">{{$value->status}}</span>
                    <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                    @endif
                </td>
                <td class="text-center px-1" style="font-size:10px;">
                    <div class="progress mb-1" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$value->average_percent}}%;" aria-valuenow="{{$value->average_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
                </td>
                <div class="dropdown font-size-sm">
                    <td>
                        <div class="btn-group m-auto d-flex justify-content-center">
                            <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                            </a>
                        </div>
                    </td>
                </div>
            </tr>
        @endforeach
    </tbody>
</table>
