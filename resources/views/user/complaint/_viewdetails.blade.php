<style>
    .table thead th {
        vertical-align: middle;
    }
    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .status-red-light {
        color: #e27e7e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .status-warning {
        color: #af6f1e;
        background-color: #fbdece;
        border-color: #fbf4ea;
    }

    .status-gray {
        color: #737373;
        background-color: #e6e6e6;
        border-color: #fbf4ea;
    }

    .status-blue {
        color: #25499c;
        background-color: #d9e2f6;
        border-color: #d9e2f6;
    }

    .status-success {
        color: #176044;
        background-color: #e0edcf;
        border-color: #bff0dd;
    }

    .status-success-light {
        color: #488a71;
        background-color: #e0edcf;
        border-color: #bff0dd;
    }

    .status-amethyst-light {
        background-color: #f3e8ff;
        color: #6f42c1;
    }

    .status {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-size: 75%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

</style>
<div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="width:105%;">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th style="width: 20%">Complaint ID</th>
                        <td>{{$value->trackno }}</td>
                    </tr>
                    <tr>
                        <th>Process Stage</th>
                        <td>
                            @if($value->status=='Under Review')
                            <span class="status status-gray">{{$value->status}}</span>
                            @elseif($value->status=='Actions Proposed')
                            <span class="badge badge bg-flat">{{$value->status}}</span>
                            @elseif($value->status=='Action Reviewed, OK')
                            <span class="status status-success">Action Approved</span>
                            @elseif($value->status=='Action Reviewed, Not OK')
                            <span class="status status-red">Action Rejected</span>
                            @elseif($value->status=='Need More Action')
                            <span class="status status-warning">{{$value->status}}</span>
                            @elseif($value->status=='Actions Completed')
                            <span class="status status-blue">{{$value->status}}</span>
                            @elseif($value->status=='Approved')
                            <span class="status status-amethyst-light">{{$value->status}}</span>
                            @elseif($value->status=='Rejected')
                            <span class="status status-red">{{$value->status}}</span>
                            <span style="display: none;">{{ 'e_'.$value->status . "_f" }}</span>
                            @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                            <span class="status status-blue">{{$value->status}}</span>
                            @elseif($value->status == 'Closed')
                                <span class="status status-success">Closed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th> Complaint Received By</th>
                        <td>{{ DB::table('employee_details')->where('emp_number',$value->employee)->value('emp_name') ??  '---' }}</td>
                    </tr>
                    @if($value->crdate)
                        <tr>
                            <th>Complaint Received Date</th>
                            <td>{{$value->crdate ? date('d-m-Y', strtotime($value->crdate)) : ''}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Mode of Compliant</th>
                        <td>{{ $value->mode_of_complaint ?? '--' }}</td>
                    </tr>

                    @php
                        $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
                        $customer = DB::table('customer')->where('id',$value->customer)->first();
                        $complaintTemplateSectionFields = json_decode($value->complaint_template_section_field_rules, true)
                    @endphp
                    @if ($value->complaint_template_id == null)
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $customer ? $customer->customer : ($value->customer ?? '---') }}</td>
                        </tr>
                        @if($value->customer_phone)
                        <tr>
                            <th>Customer Phone</th>
                            <td>{{ $value->customer_phone ?? $customer && $customer->phone ? $customer->phone :  '---'}}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Customer Email</th>
                            <td>{{ $value->customer_email ?? '---'}}</td>
                        </tr>
                        @if($value->customer_address)
                            <tr>
                                <th>Customer Address</th>
                                <td>{{ $value->customer_address ?? (($customer && $customer->location) ? $customer->location :  '---') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Product / Service</th>
                            <td>{{$product ? implode(',',$product) : $value->product}}</td>
                        </tr>
                    @else
                        @if ($complaintTemplateSectionFields['customer']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['customer']['field_display_label']}}</th>
                                <td>{{ $customer ? $customer->customer : ($value->customer ?? '---')}}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['customerphone']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['customerphone']['field_display_label']}}</th>
                                <td>{{ $value->customer_phone ?? (($customer && $customer->phone) ? $customer->phone :  '---')}}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['customeremail']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['customeremail']['field_display_label']}}</th>
                                <td>{{ $value->customer_email ?? '---'}}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['customeraddress']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}</th>
                                <td>{{ $value->customer_address ?? (($customer && $customer->location) ? $customer->location : '---') }}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['product']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['product']['field_display_label']}}</th>
                                <td>{{$product ? implode(',',$product) : $value->product ?? '---'}}</td>
                            </tr>
                        @endif
                    @endif
                    @if ($value->complaint_template_id == null)
                        <tr>
                            <th>Sale Order Number</th>
                            <td>{{ $value->sale_order_no }}</td>
                        </tr>
                        <tr>
                            <th>Delivery Date</th>
                            <td>{{$value->delivery_date ? date('d-m-Y', strtotime($value->delivery_date)) : ''}}</td>
                        </tr>
                        <tr>
                            <th>Delivery Number</th>
                            <td>{{ $value->delivery_no }}</td>
                        </tr>
                    @elseif ($value->complaint_template_id == 1)
                        @if ($complaintTemplateSectionFields['saleorder']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['saleorder']['field_display_label']}}</th>
                                <td>{{ $value->sale_order_no ?? '---' }}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['deliveryno']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['deliveryno']['field_display_label']}}</th>
                                <td>{{ $value->delivery_no ?? '---' }}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['deliverydate']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['deliverydate']['field_display_label']}}</th>
                                <td>{{$value->delivery_date ? date('d-m-Y', strtotime($value->delivery_date)) : '---'}}</td>
                            </tr>
                        @endif
                    @elseif ($value->complaint_template_id == 2)
                        @if ($complaintTemplateSectionFields['job_order_no']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['job_order_no']['field_display_label']}}</th>
                                <td>{{ $value->job_order_no ?? '---' }}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['product_serial_no']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['product_serial_no']['field_display_label']}}</th>
                                <td>{{ $value->product_serial_no ?? '---' }}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['project_name']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['project_name']['field_display_label']}}</th>
                                <td>{{ $value->project_name ?? '---' }}</td>
                            </tr>
                        @endif
                        @if (isset($value->warranty_coverage))
                            <tr>
                                <th>Warranty Coverage</th>
                                <td>{{ $value->warranty_coverage }}</td>
                            </tr>
                        @endif
                        @if ($complaintTemplateSectionFields['job_history']['enable_field'])
                            <tr>
                                <th>{{$complaintTemplateSectionFields['job_history']['field_display_label']}}</th>
                                <td><?php echo $value->job_history ? nl2br($value->job_history) : '---'; ?></td>
                            </tr>
                        @endif
                    @endif
                    <tr>
                        <th>Type</th>
                        <td>
                            @if($value->type=="Low")
                            <span class="status status-success">{{$value->type}}</span>
                            @elseif($value->type=="Moderate")
                            <span class="status status-warning">{{$value->type}}</span>
                            @elseif($value->type=="High")
                            <span class="status status-red">{{$value->type}}</span>
                            @else
                                <span>---</span>
                            @endif</td>
                    </tr>
                    @if($value->plant)
                        <tr>
                            <th>Plant</th>
                            <td>{{ DB::table('complaint_plants')->where('id',$value->plant)->value('plant_name') }}</td>
                        </tr>
                    @endif
                    @if (isset($value->complaint_category_id))
                            <tr>
                                <th>Complaint Category</th>
                                <td>{{$projectComplaint->category_name}}</td>
                            </tr>
                        @endif
                    @if (isset($value->vendor_id))
                        <tr>
                            <th>Vendor</th>
                            <td>{{$vendor->vendor_name}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Complaint Title</th>
                        <td>{{ $value->title }}</td>
                    </tr>
                   <tr>
                        <th>Complaint Description</th>
                        <td><?php echo nl2br($value->complaint)?></td>
                    </tr>
                    @if (isset($value->previously_occured_complaint))
                        <tr>
                            <th>Is this a previously occurred Compliant?</th>
                            <td>{{ $value->previously_occured_complaint }}</td>
                        </tr>
                    @endif
                    @if (isset($value->no_of_occurrence))
                        <tr>
                            <th>No of Occurrence</th>
                            <td>{{ $value->no_of_occurrence }}</td>
                        </tr>
                    @endif
                    @if (isset($value->previous_complaint_reference) && $value->previous_complaint_reference != '')
                        <tr>
                            <th>Previous Complaint Reference</th>
                            @if ($value->previous_complaint_reference == '0')
                                <td>External {{ isset($value->external_reference) && $value->external_reference ? $value->external_reference : '' }}</td>
                            @else
                                <td style="align-items: center">
                                    @if ($value->previousComplaintRef)
                                        @if ($value->previousComplaintRef['deleted_at'])
                                            <button class="btn status-blue status-blue1 mr-3 px-2 py-2 br-5 tooltipClass" data-toggle="tooltip" data-placement="top" title="Click to view previous Customer Complaint" onclick="viewDetailsModal({{$value->previousComplaintRef['id']}})">{{ $value->previousComplaintRef['trackno'].' - '.$value->previousComplaintRef['title'].' (deleted complaint)' }}</button>
                                        @else
                                            <button class="btn status-blue status-blue1 mr-3 px-2 py-2 br-5 tooltipClass" data-toggle="tooltip" data-placement="top" title="Click to view previous Customer Complaint" onclick="viewDetailsModal({{$value->previousComplaintRef['id']}})">{{ $value->previousComplaintRef['trackno'].' - '.$value->previousComplaintRef['title']  }}</button>
                                        @endif
                                    @else
                                        <span>{{ $value->previous_complaint_reference.'(deleted complaint)' }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endif
                    <tr>
                        <th>Recommended Action</th>
                        <td><?php echo nl2br($value->action )?></td>
                    </tr>
                    @if($value->eremainder)
                        <tr>
                            <th>Email Reminder for Action</th>
                            <td>{{$value->eremainder }}</td>
                        </tr>
                    @endif
                    @if($value->sendnotif)
                        <tr>
                            <th>Send notifications to</th>
                            <td>{{DB::table('users')->whereIn('email', array_map('trim', explode(',', $value->sendnotif)))->pluck('name')->implode(', ')}}</td>
                        </tr>
                    @endif
                    @if($value->financial_loss)
                        <tr>
                            <th>Financial Loss</th>
                            <td>{{$value->financial_loss }}</td>
                        </tr>
                    @endif
                    @if($value->potential_cost_savings)
                        <tr>
                            <th>Potential Cost Savings</th>
                            <td>{{$value->potential_cost_savings}}</td>
                        </tr>
                    @endif
                </table>
                @php
                    $records = DB::table('complaint_product_details')->where('complaint_id',$value->id)->get()->toArray();
                @endphp
                @if($records)
                    @if ($value->complaint_template_id == null)
                        <table class="table table-bordered table-striped table-vcenter" >
                            <thead class="bg-white">
                                <tr>
                                    <th class="text-center" style="font-size:11px;">S #</th>
                                    <th class="text-center" style="font-size:11px;">Item Code</th>
                                    <th class="text-center" style="font-size:11px;">Product Description</th>
                                    <th class="text-center" style="font-size:11px;">Size of the Item</th>
                                    <th class="text-center" style="font-size:11px;">ID No.</th>
                                    <th class="text-center" style="font-size:11px;">Total Delivery Quantity</th>
                                    <th class="text-center" style="font-size:11px;">Affected Quantity</th>
                                    <th class="text-center" style="font-size:11px;">Unit</th>
                                    <th class="text-center" style="font-size:11px;">Cost Impact</th>
                                    <th class="text-center" style="font-size:11px;">Category</th>
                                    <th class="text-center" style="font-size:11px;">Sale Return Request</th>
                                    <th class="text-center" style="font-size:11px;">Rework Request</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for($i=0;$i<count($records);$i++){
                                ?>
                                <tr>

                                    <td style="font-size:11px;">{{ $i+1 }}</td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->item_code}}" readonly />
                                    </td>
                                    <td class="td-class"><textarea class="form-control" cols="30" rows="5" style="font-size:11px;" readonly>{{$records[$i]->product_description}}</textarea></td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->size_of_item}}" readonly />
                                    </td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->idno}}" readonly />
                                    </td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->total_delivery_quantity}}" readonly />
                                    </td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->affected_quantity}}" readonly />
                                    </td>
                                    @php
                                        $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$records[$i]->unit)->first();
                                    @endphp
                                    <td class="td-class">
                                        <input type="text" class="form-control" style="font-size:11px;" value="{{$oldunitforoldrecords ? $oldunitforoldrecords->name : $records[$i]->unit}}" readonly>
                                    </td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->cost_impact}}" readonly />
                                    </td>
                                    @php $catname = DB::table('complaint_categories')->whereIn('id',explode(',',$records[$i]->complaint_category))->pluck('category_name')->toArray() @endphp
                                    <td class="td-class">
                                        <textarea class="form-control" style="font-size:11px;" cols="30" rows="5" readonly>{{implode(',',$catname)}}</textarea>


                                    </td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->sale_return_request}}" readonly />

                                    </td>
                                    <td class="td-class">
                                        <input class="form-control" style="font-size:11px;" value="{{$records[$i]->rework_request}}" readonly />

                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    @elseif ($value->complaint_template_id == 1)
                        <table class="table table-bordered table-striped table-vcenter" >
                            <thead class="bg-white">
                                <tr>
                                    <th class="text-center" style="font-size:11px;width:2%;">S #</th>
                                    @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:8%;">{{$complaintTemplateSectionFields['itemcode']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:15%;">{{$complaintTemplateSectionFields['productdesc']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['sizeofitem']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['idno']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['totalquant']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:7%;">{{$complaintTemplateSectionFields['affectquant']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:6%;">{{$complaintTemplateSectionFields['unit']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['costimpact']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['complaintcategory']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['salereturnreq']['field_display_label']}} </th>
                                    @endif
                                    @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                        <th class="text-center" style="font-size:11px;width:9%;">{{$complaintTemplateSectionFields['reworkreq']['field_display_label']}} </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for($i=0;$i<count($records);$i++){
                                ?>
                                <tr>
                                    <td style="font-size:11px;">{{ $i+1 }}</td>
                                    @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->item_code ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                        <td class="td-class"><textarea class="form-control" cols="30" rows="5" style="font-size:11px;" readonly>{{$records[$i]->product_description ?? '---'}}</textarea></td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->size_of_item ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->idno ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->total_delivery_quantity ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->affected_quantity ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                        @php
                                            $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$records[$i]->unit)->first();
                                        @endphp
                                        <td class="td-class">
                                            <input type="text" class="form-control" style="font-size:11px;" value="{{$oldunitforoldrecords ? $oldunitforoldrecords->name : ($records[$i]->unit  ?? '---')}}" readonly>
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->cost_impact  ?? '---' }}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                        @php $catname = DB::table('complaint_categories')->whereIn('id',explode(',',$records[$i]->complaint_category))->pluck('category_name')->toArray() @endphp
                                        <td class="td-class">
                                            <textarea class="form-control" style="font-size:11px;" cols="30" rows="5" readonly>{{ $catname ? implode(',',$catname) : '---'}}</textarea>
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->sale_return_request ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                    @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                        <td class="td-class">
                                            <input class="form-control" style="font-size:11px;" value="{{$records[$i]->rework_request ?? '---'}}" readonly />
                                        </td>
                                    @endif
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    @endif
                @endif
                @php
                  $department_val = App\Departments::where('id',$value->dept)->value('departmentname');
                  $dept_name = $department_val?$department_val:'--';
                  $division_val = App\Division::where('id',$value->division_id)->value('name');
                  $division_name = $division_val?$division_val:'--';
                  $area_val = App\Area::where('id',$value->area_id)->value('name');
                  $area_name = $area_val?$area_val:'--';
                  $resp = App\User::where('email',$value->responsibility)->value('name');
                  $resp_name = $resp?$resp:'--';
                @endphp
                <table class="table table-bordered table-striped table-vcenter" >
                    <thead class="bg-white">
                        <tr>
                            <th class="text-center" style="font-size:11px;">Correction</th>
                            <th class="text-center" style="font-size:11px;">Department</th>
                            @if($value->division_id)
                              <th class="text-center" style="font-size:11px;">Division</th>
                            @endif
                            @if($value->area_id)
                              <th class="text-center" style="font-size:11px;">Sub Division</th>
                            @endif
                            <th class="text-center" style="font-size:11px;">Responsibility<span class="text-danger">*</span></th>
                            <th class="text-center" style="font-size:11px;">Target date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-class"><textarea class="form-control" cols="30"  rows="4" style="font-size:11px;" readonly>{{$value->correction ?? '---'}}</textarea></td>
                            <td class="td-class">
                                   <input type="text" value="{{$dept_name ?? '---'}}" class="form-control" style="font-size:11px;" readonly>
                            </td>
                            @if($value->division_id)
                            <td class="td-class">
                               <input type="text" value="{{$division_name ?? '---'}}" class="form-control" style="font-size:11px;" readonly>
                            </td>
                            @endif
                            @if($value->area_id)
                            <td class="td-class">
                              <input type="text" value="{{$area_name ?? '---'}}" class="form-control" style="font-size:11px;" readonly>
                            </td>
                            @endif
                            <td class="td-class">
                               <input type="text" value="{{$resp_name ?? '---'}}" class="form-control" style="font-size:11px;" readonly>
                            </td>
                            <td class="td-class">
                                <input type="text" class="form-control" style="font-size: 11px;"  readonly value="{{ $value->tardate ? date('d-m-Y',strtotime($value->tardate)) : '---' }}">
                            </td>

                        </tr>
                    </tbody>
                </table>
                @php
                    $records = DB::table('complaint_root_cause_corrective_action_details')->where('complaint_id',$value->id)->get()->toArray();
                @endphp
                @if($records)
                    <table class="table table-bordered table-striped table-vcenter" >
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center" style="font-size:11px;width:1%;">S #</th>
                                <th class="text-center" style="font-size:11px;width:38%;">Root Cause</th>
                                <th class="text-center" style="font-size:11px;width:38%;">Corrective Action</th>
                                <th class="text-center" style="font-size:11px;width:14%;">Responsibility</th>
                                <th class="text-center" style="font-size:11px;width:8%;">Target Date</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($i=0;$i<count($records);$i++){
                            ?>
                            <tr>

                                <td style="font-size:11px;">{{ $i+1 }}</td>
                                <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:11px;" readonly>{{$records[$i]->root_cause ?? '---'}}</textarea></td>
                                <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:11px;" readonly>{{$records[$i]->corrective_action ?? '---'}}</textarea></td>
                                <td class="td-class">
                                    <input class="form-control" style="font-size:11px;" value="{{DB::table('departments')->where('id',$records[$i]->department)->value('departmentname') ?? '---'}}" readonly>
                                    <input class="form-control" style="font-size:11px;" value="{{DB::table('users')->where('id',$records[$i]->responsibility)->value('name') ?? '---'}}" readonly>
                                </td>
                                <td class="td-class">
                                    <input type="date" value="{{$records[$i]->target_date ?? '---'}}" class="form-control" style="font-size:11px;" readonly>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                @endif
                <table class="table table-striped">
                    @if(count($docs) > 0)
                        <tr>
                            <th style="width:20%">Attachments(Complaint files)</th>
                            <td>
                                <div class="col-sm-8">
                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal4{{$value->id}}"><strong>View Attachments</strong> </button>
                                    <div id="viewModal4{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments(Complaint Files)</h3>
                                                </div>
                                                <div class="alert alert-success d-none w-100" id="success_complaint" style="font-size:12px;">

                                                </div>

                                                <ul class="alert alert-danger d-none w-100" id="error_complaint" style="font-size:12px;">
                                                    Internal server error, we are trying to fix them soon
                                                </ul>
                                                <div class="modal-body">
                                                    <table id="complaintAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        @if(count($docs) > 0)
                                                        <div class="row justify-content-center">

                                                            <thead>
                                                                <tr class="table-secondary">
                                                                    <th style="width:5%;">S.No</th>
                                                                    <th style="width:65%;">Attachments</th>
                                                                    <th style="width:30%;">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <?php $sno=0; ?>

                                                            @foreach($docs as $value1)
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                    <td style="font-size:13px;">{{ $value1->files}}</td>
                                                                    <td>
                                                                        <a style="font-size:11px;" class="btn btn-sm btn-success" href="{{asset("storage/complaint/".$value1->files)}}" download="{{$value1->files}}">Download</a>
                                                                        @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $value->addedby==Auth::user()->employeeid)
                                                                        <button class="btn btn-secondary btn-sm remove-complaint" data-fileid="{{ $value1->sno }}" data-filename="{{ $value1->files }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            @endforeach
                                                        </div>
                                                        @else
                                                        <div class="row justify-content-center">
                                                            <h2>No Files</h2>
                                                        </div>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th style="width:20%">Attachments(Issues)</th>
                        <td>
                            <div class="col-sm-8">
                                <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal10{{$value->id}}"><strong>View Attachments</strong> </button>
                                <div id="viewModal10{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Attachments(Issues)</h3>
                                            </div>
                                            <div class="alert alert-success d-none w-100" id="success_issue" style="font-size:12px;">

                                            </div>

                                            <ul class="alert alert-danger d-none w-100" id="error_issue" style="font-size:12px;">
                                                Internal server error, we are trying to fix them soon
                                            </ul>
                                            <div class="modal-body">
                                                <table id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    @if(count($issuedocs) > 0)
                                                    <div class="row justify-content-center">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th style="width:5%;">S.No</th>
                                                                <th style="width:65%;">Attachments</th>
                                                                <th style="width:30%;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <?php $sno=0; ?>

                                                        @foreach($issuedocs as $value1)
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                <td style="font-size:13px;">{{ $value1->issue_file}}</td>
                                                                <td>
                                                                    <a style="font-size:11px;" class="btn btn-sm btn-success" href="{{asset("storage/complaint/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a>
                                                                    @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $value->addedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary btn-sm remove-issues" data-fileid="{{ $value1->id }}" data-filename="{{ $value1->issue_file }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        @endforeach

                                                    </div>
                                                    @else
                                                    <div class="row justify-content-center">
                                                        <h2>No Files</h2>
                                                    </div>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <th style="width:20%">Attachments(Action)</th>
                        <td>
                            <div class="col-sm-8">
                                <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal5{{$value->id}}"><strong>View Attachments</strong> </button>
                                <div id="viewModal5{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Attachments(Action)</h3>
                                            </div>
                                            <div class="alert alert-success d-none w-100" id="success_action" style="font-size:12px;">

                                            </div>

                                            <ul class="alert alert-danger d-none w-100" id="error_action" style="font-size:12px;">
                                                Internal server error, we are trying to fix them soon
                                            </ul>
                                            <div class="modal-body">
                                                <table id="actionAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    @if(count($actiondocs) > 0)
                                                    <div class="row justify-content-center">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th style="width:5%;">S.No</th>
                                                                <th style="width:65%;">Attachments</th>
                                                                <th style="width:30%;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <?php $sno=0; ?>

                                                        @foreach($actiondocs as $value1)
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                <td style="font-size:13px;">{{ $value1->action_file}}</td>
                                                                <td>
                                                                    <a style="font-size:11px;" class="btn btn-sm btn-success" href="{{asset("storage/complaint/".$value1->action_file)}}" download="{{$value1->action_file}}">Download</a>
                                                                    @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $value->addedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary btn-sm remove-actions" data-fileid="{{ $value1->id }}" data-filename="{{ $value1->action_file }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        @endforeach

                                                    </div>
                                                    @else
                                                    <div class="row justify-content-center">
                                                        <h2>No Files</h2>
                                                    </div>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @if($value->team_lead_id)
                        <tr>
                            <th>Team leader</th>
                            <td>{{DB::table('users')->where('id',$value->team_lead_id)->value('name')}}</td>
                        </tr>
                    @endif
                    @php
                        $team_mem_ids = DB::table('complaint_investigators')->where('complaint_id',$value->id)->pluck('user_id')->toArray();
                        $team_mem_names = DB::table('users')->whereIn('id',$team_mem_ids)->pluck('name')->toArray();
                        $members = implode(',',$team_mem_names);
                    @endphp
                    @if($members)
                        <tr>
                            <th>Team Members</th>
                            <td>{{$members }}</td>
                        </tr>
                    @endif
                    @if($value->investigation_date)
                        <tr>
                            <th>Investigation Target Date</th>
                            <td>{{$value->investigation_date ? date('d-m-Y', strtotime($value->investigation_date)) : ''}}</td>
                        </tr>
                    @endif
                    @php
                        $step = 0;
                        switch ($value->status) {
                            case 'Under Review':
                                $step = 1;
                                break;
                            case 'Rejected':
                                $step = 2;
                                break;
                            case 'Approved':
                                $step = 3;
                                break;
                            case 'Correction Proposed- RC Awaiting':
                                $step = 3;
                                break;
                            case 'Actions Proposed- Review Awaiting':
                                $step = 5;
                                break;
                            case 'Action Reviewed, Not OK':
                                $step = 6;
                                break;
                        case 'Action Reviewed, OK':
                                $step = 7;
                                break;
                            case 'Actions Completed':
                                $step = 8;
                                break;
                            case 'Need More Action':
                                $step = 9;
                                break;
                            case 'Closed':
                                $step = 10;
                                break;
                            default:
                                # code...
                                break;
                        }
                    @endphp
                    @if ($value->reason || $step >= 3)
                        <tr>
                            <th>Entry Review Remarks</th>
                            <td>
                                @if ($value->reason)
                                    <span>{{$value->reason}}</span>
                                @else
                                    <span class="text-muted font-italic">No Remarks Added</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ($value->remarks || $step >= 7)
                        <tr>
                            <th>Action Review Remarks</th>
                            <td>
                                @if ($value->remarks)
                                    <span>{{$value->remarks}}</span>
                                @else
                                    <span class="text-muted font-italic">No Remarks Added</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ($value->needaction || $step >= 10)
                        <tr>
                            <th>Closure Review Remarks</th>
                            <td>
                                @if ($value->needaction)
                                    <span>{{$value->needaction}}</span>
                                @else
                                    <span class="text-muted font-italic">No Remarks Added</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ($value->issue_authenticity)
                        <tr>
                            <th>Issue Authenticity</th>
                            <td><span class="badge badge-success">{{$value->issue_authenticity}}</span></td>
                        </tr>
                    @endif
                    @if (isset($value->source_of_issue))
                        <tr>
                            <th>{{$complaintTemplateSectionFields['source_of_issue']['field_display_label']}}</th>
                            <td><span class="badge badge-success">{{$value->source_of_issue}}</span></td>
                        </tr>
                    @endif
                    @if ($value->addedby)
                        @php
                            $addedBy = DB::table('users')->where('employeeid',$value->addedby)->first();
                        @endphp
                        @if ($addedBy)
                            <tr>
                                <th style="width:20%">Added By & Date</th>
                                <td><span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $addedBy->email }}">{{ $addedBy->name }}</span> —  {{$value->created_at ? date('d-m-Y', strtotime($value->created_at)).' — '.date('h:i:s A', strtotime($value->created_at)) : ''}}</td>
                            </tr>
                        @endif
                    @endif

                    @if ($value->approved_by)
                        @php
                            $approvedBy = DB::table('users')->where('employeeid',$value->approved_by)->first();
                        @endphp
                        @if ($approvedBy)
                            <tr>
                                <th>Entry Reviewed By & Date</th>
                                <td><span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $approvedBy->email }}">{{ $approvedBy->name }}</span> —  {{$value->approved_at ? date('d-m-Y', strtotime($value->approved_at)).' — '.date('h:i:s A', strtotime($value->approved_at)) : ''}}</td>
                            </tr>
                        @endif
                    @endif

                    @if ($value->reviewed_by)
                        @php
                            $reviewedBy = DB::table('users')->where('id',$value->reviewed_by)->first();
                        @endphp
                        @if ($reviewedBy)
                            <tr>
                                <th>Action Reviewed By & Date</th>
                                <td><span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $reviewedBy->email }}">{{  $reviewedBy->name }}</span> — {{$value->reviewedat ? date('d-m-Y', strtotime($value->reviewedat)).' — '.date('h:i:s A', strtotime($value->reviewedat)) : ''}}</td>
                            </tr>
                        @endif
                    @endif

                    @if($value->status=="Closed")
                        @php
                            $closedBy = null;
                            $closedat = null;
                            if($value->status=="Closed" || $value->status=="Need More Action"){
                                // note: this first condition may create problems when employee id matches user id
                                if(App\User::where('employeeid',$value->reviewed_by)->first()){
                                    $closedBy = App\User::where('employeeid',$value->reviewed_by)->first();
                                    $closedat = $value->reviewedat ? date('d-m-Y', strtotime($value->reviewedat)).' — '.date('h:i:s A', strtotime($value->reviewedat)):null;
                                }else{
                                    $closedBy = App\User::where('id',$value->closed_by)->first();
                                    $closedat = $value->closed_at ? date('d-m-Y', strtotime($value->closed_at)).' — '.date('h:i:s A', strtotime($value->closed_at)):null;
                                }
                            }else{
                                $closedBy = App\User::where('id',$complaint->closed_by)->first();
                                $closedat = $value->closed_at ? date('d-m-Y', strtotime($value->closed_at)).' — '.date('h:i:s A', strtotime($value->closed_at)):null;
                            }
                        @endphp
                        @if ($closedBy)
                            <tr>
                                <th>Closure Reviewed By & Date</th>
                                <td><span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $closedBy->email }}">{{$closedBy ? $closedBy->name : ''}}</span> — {{$closedat ? $closedat : '' }}</td>
                            </tr>
                        @endif
                    @endif

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.tooltipClass').tooltip();

        const recordId = "{{$value->id}}";
        $('.remove-issues').on('click', function () {
            var filename = $(this).data('filename');
            var fileid = $(this).data('fileid');
            var btnId = this;
            $.ajax({
                url : window.location.protocol + "//" + window.location.host +`/user/complaint/${recordId}/delete-issue`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    fileid : fileid,
                },
                success: function (data) {
                    $(btnId).closest('tr').remove();
                    $('#issueAttachmentTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        $(this).find('td:first').css('font-size', '10px');
                    });
                    $('#error_issue').addClass('d-none');
                    $("#success_issue")
                        .stop(true, true)
                        .text(`Issue file name: ${filename} has been removed successfully!`)
                        .removeClass('d-none')
                        .fadeIn('slow')
                        .delay(3000)
                        .fadeOut('slow', function() {
                            $(this).addClass('d-none').text('');
                        });
                },
                error: function (response) {
                    $('#error_issue').stop(true, true).removeClass('d-none');
                }
            });
        });
        $('.remove-actions').on('click', function () {
            var filename = $(this).data('filename');
            var fileid = $(this).data('fileid');
            var btnId = this;
            $.ajax({
                url : window.location.protocol + "//" + window.location.host +`/user/complaint/${recordId}/delete-action`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    fileid : fileid,
                },
                success: function (data) {
                    $(btnId).closest('tr').remove();
                    $('#actionAttachmentTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        $(this).find('td:first').css('font-size', '10px');
                    });
                    $('#error_action').addClass('d-none');
                    $("#success_action")
                        .stop(true, true)
                        .text(`Action file name: ${filename} has been removed successfully!`)
                        .removeClass('d-none')
                        .fadeIn('slow')
                        .delay(3000)
                        .fadeOut('slow', function() {
                            $(this).addClass('d-none').text('');
                        });
                },
                error: function (error) {
                    $('#error_action').stop(true, true).removeClass('d-none');
                }
            });
        });
        $('.remove-complaint').on('click', function () {
            var filename = $(this).data('filename');
            var fileid = $(this).data('fileid');
            var btnId = this;
            $.ajax({
                url : window.location.protocol + "//" + window.location.host +`/user/complaint/${recordId}/delete-complaint`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    fileid : fileid,
                },
                success: function (data) {
                    $(btnId).closest('tr').remove();
                    $('#complaintAttachmentTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        $(this).find('td:first').css('font-size', '10px');
                    });
                    $('#error_complaint').addClass('d-none');
                    $("#success_complaint")
                        .stop(true, true)
                        .text(`Complaint file name: ${filename} has been removed successfully!`)
                        .removeClass('d-none')
                        .fadeIn('slow')
                        .delay(3000)
                        .fadeOut('slow', function() {
                            $(this).addClass('d-none').text('');
                        });
                },
                error: function (error) {
                    $('#error_complaint').stop(true, true).removeClass('d-none');
                }
            });
        });
    });
</script>
