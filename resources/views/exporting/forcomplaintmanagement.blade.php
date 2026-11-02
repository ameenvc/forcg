@if(isset($data) && count($data)>0)
<table id="headerTable1" class="table table-responsive table-bordered table-striped table-vcenter" style="display:none;">
    <thead >
        <tr>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                S.No
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Complaint ID
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;word-wrap: break-word;">
                Title
            </td>
            @foreach($table_columns_active as $active)
                @if($active->enabled_by_user!==0)
                    <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                        {{ucwords($active->column_name)}}
                    </td>
                @else
                    <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                        {{ucwords($active->column_name)}}
                    </td>
                @endif
            @endforeach
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Process Stage
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Action Status
            </td>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;?>
        @foreach($data as $value)
        <?php
            $rr=$value->id;
            $date = $value->tardate;
            $today = Date("Y-m-d");
            $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
            $customer = $customersData[$value->customer] ?? $value->customer;
            $depname = $departmentsData[$value->dept] ?? '--';
            $res = $responsibilitiesData[$value->responsibility]??"--";
            $raisedby = $raisedbyRecordsData[$value->addedby]??"--";
            $receivedby = $receivedbyRecordsData[$value->employee]??'--';
            $plantname = $plantsData[$value->plant]??"--";
            $subdiv = $subdivisionsData[$value->area_id]??"--";
            $div = $divisionsData[$value->division_id]??"--";
            $leadername =$usersData[$value->team_lead_id]??'--';
            $reviewedbyname = $usersData[$value->reviewed_by]??'--';
            $closedbyname = $usersData[$value->closed_by]??'--';
        ?>
        <tr>
            <td style="font-size:11px;text-align: center;">{{ $no = $no + 1 }}</td>
            <td style="font-size:11px;text-align: center;">
                {{$value->trackno}}
            </td>
            <td style="font-size:11px;word-wrap: break-word;text-align: center;">
                {{$value->title}}
            </td>
            @foreach($table_columns_active as $column_c)
                @switch($column_c->column_name)
                    @case('customer')
                        <td style="font-size:11px;text-align: center;">
                            {{$customer ? $customer : $value->customer}}
                        </td>
                        @break
                    @case('product')
                        <td style="font-size:11px;text-align: center;">
                            {{$product ? implode(',',$product) : $value->product}}
                        </td>
                        @break
                    @case('type')
                        <td style="font-size:11px;text-align: center;">
                            @if($value->type=="Low")
                            <span style="background-color:#30c78d;color:#000;">{{$value->type}}</span>
                            @elseif($value->type=="Moderate")
                            <span style="background-color:#e5ae67;color:#000;">{{$value->type}}</span>
                            @elseif($value->type=="High")
                            <span style="background-color:#e56767;color:#000;">{{$value->type}}</span>
                            @else
                            <span>--</span>
                            @endif
                        </td>
                    @break
                    @case('department')
                        <td style="font-size:11px;text-align: center;">
                            {{ $depname ? $depname : $value->dept}}
                        </td>
                    @break
                    @case('responsibility')
                        <td style="font-size:11px;text-align: center;">
                            {{$res??'--'}}
                        </td>
                    @break
                    @case('due date')
                        <td style="font-size:11px;text-align: center;max-width:fit-content;">
                            {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                        </td>
                        @break
                    @case('raisedby')
                        @if(in_array('raisedby',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$raisedby??'--' }}
                            </td>
                        @endif
                    @break
                    @case('complaint received by')
                        @if(in_array('complaint received by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $receivedby??"--" }}
                            </td>
                        @endif
                    @break
                    @case('complaint received date')
                        @if(in_array('complaint received date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->crdate ? date('d-m-Y', strtotime($value->crdate)) : '--'}}
                            </td>
                        @endif
                    @break
                    @case('mode of complaint')
                    @if(in_array('mode of complaint',$table_columns_names_active))
                        <td style="font-size:11px;word-wrap: break-word;text-align: center;">
                            {{ ucfirst($value->mode_of_complaint)??"--"  }}
                        </td>
                    @endif
                    @break
                    @case('customer phone')
                    @if(in_array('customer phone',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->customer_phone ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('customer email')
                    @if(in_array('customer email',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->customer_email??'--' }}
                        </td>
                    @endif
                    @break
                    @case('sale order no.')
                    @if(in_array('sale order no.',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->sale_order_no ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('delivery no.')
                    @if(in_array('delivery no.',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->delivery_no ??"--"}}
                        </td>
                    @endif
                    @break
                    @case('delivery date')
                    @if(in_array('delivery date',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->delivery_date ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('plant')
                    @if(in_array('plant',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $plantname ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('division')
                        @if(in_array('division',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $div ??"--" }}
                            </td>
                        @endif
                        @break
                    @case('subdivision')
                        @if(in_array('subdivision',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $subdiv ??"--" }}
                            </td>
                        @endif
                        @break
                    @case('financial loss')
                        @if(in_array('financial loss',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $value->financial_loss??"--" }}
                            </td>
                        @endif
                        @break
                    @case('potential cost saving')
                    @if(in_array('potential cost saving',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->potential_cost_savings??"--" }}
                        </td>
                    @endif
                    @break
                    @case('email reminder for action')
                        @if(in_array('email reminder for action',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->eremainder??"--"}}
                            </td>
                        @endif
                        @break
                    @case('investigation team lead')
                        @if(in_array('investigation team lead',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $leadername??"--" }}
                            </td>
                        @endif
                    @break
                    @case('investigation target date')
                        @if(in_array('investigation target date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $value->investigation_date ? date('d-m-Y', strtotime($value->investigation_date)):"--" }}
                            </td>
                        @endif
                        @break
                        @case('added date')
                        @if(in_array('added date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $value->created_at?date('d-m-Y',strtotime($value->created_at)):"--" }}
                            </td>
                        @endif
                    @break
                    @case('reviewed by')
                        @if(in_array('reviewed by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $reviewedbyname?$reviewedbyname:"--" }}
                            </td>
                        @endif
                    @break
                    @case('reviewed date')
                        @if(in_array('reviewed date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->reviewedat?date('d-m-Y',strtotime($value->reviewedat)):"--"}}
                            </td>
                        @endif
                    @break
                    @case('closure reviewed by')
                        @if(in_array('closure reviewed by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $closedbyname?$closedbyname:"--" }}
                            </td>
                        @endif
                    @break
                    @case('closure reviewed date')
                        @if(in_array('closure reviewed date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->closed_at?date('d-m-Y',strtotime($value->closed_at)):"--"}}
                            </td>
                        @endif
                    @break
                    @default
                    @break
                @endswitch
            @endforeach
            <td style="font-size:11px;text-align: center;">
                @if($value->status=='Under Review')
                <span style="background-color: #a2a4a5;">{{$value->status}}</span>
                @elseif($value->status=='Actions Proposed')
                <span style="background-color: #44b3ab;">{{$value->status}}</span>
                @elseif($value->status=='Action Reviewed, OK')
                <span style="background-color: #30c78d;">Action Approved</span>
                @elseif($value->status=='Action Reviewed, Not OK')
                <span style="background-color: #e56767;">Action Rejected</span>
                @elseif($value->status=='Need More Action')
                <span style="background-color:#e5ae67;">{{$value->status}}</span>
                @elseif($value->status=='Actions Completed')
                <span style="background-color: #5cace5;">{{$value->status}}</span>
                @elseif($value->status=='Approved')
                <span style="background-color: #b08fe5;">{{$value->status}}</span>
                @elseif($value->status=='Rejected')
                <span style="background-color: #e58f8f;">{{$value->status}}</span>
                @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                <span style="background-color: #d9e2f6;">{{$value->status}}</span>
                @endif
            </td>
            <td style="font-size:11px;text-align: center;">
                <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif


@if(isset($data1) && count($data1)>0)
<table class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable2" style="display:none;">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Customer Name</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Location</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Contact</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Product/Service</b></span></th>

        </tr>
    </thead>
    <tbody>
        <?php $no=0;?>
        @foreach($data1 as $value)
        <tr>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">
                    {{$value->customer}}</span>
            </td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->location}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"></span>
            </td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->product}}</span>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(isset($data2) && count($data2)>0)
<table class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable3" style="display:none;">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>&nbsp&nbspTitle&nbsp&nbsp</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Customer</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Product</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Responsible Department</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Process&nbspStage</b></span></th>

            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Target&nbspDate</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Complaint ID</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Type</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>&nbspAction&nbspStatus&nbsp</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Added By</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Complaint</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Recommended Action</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Responsibility</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Correction</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Rootcause</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Corrective Action</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Added Date</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Reviewed Date</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Last Reviewed By</b></span></th>
            <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Approved By</b></span></th>

        </tr>
    </thead>
    <tbody>
        <?php $no=0;?>
        @foreach($data2 as $value)
        <?php   $rr=$value->id;
                $date = $value->tardate;
                $today = Date("Y-m-d");?>

        <tr>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;color:blue">{{$value->title}}</span>
            </td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->customer}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->product}}</span>
            </td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->dept}}</span>
            </td>
            <td class="text-center " style="font-size:11px;width: 50px;">
                @if($value->status != 'Under Review' && $today <= $date) <span style="font-size:11px;width: 50px;background-color:grey">{{$value->status}}</span>
                    @elseif( $today > $date)
                    <span style="font-size:11px;width: 50px;background-color:red">{{$value->status}}</span>
                    @elseif($value->status == 'Under Review' && $today <= $date) <span style="font-size:11px;width: 50px;background-color:orange">{{$value->status}}</span>
                        @endif
            </td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->tardate}}<span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->trackno }}<span></td>
            <td class="text-center " style="font-size:11px;width: 50px;">
                @if($value->type=="Low")
                <span style="font-size:11px;width: 50px;background-color:green">{{$value->type}}</span>
                @elseif($value->type=="Moderate"||$value->type=="Medium")
                <span style="font-size:11px;width: 50px;background-color:orange">{{$value->type}}</span>
                @elseif($value->type=="High")
                <span style="font-size:11px;width: 50px;background-color:red">{{$value->type}}</span>
                @endif
            </td>



            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->actionstatus}}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->addedby }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->complaint)?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->action )?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">@foreach($user as $u)
                    @if( $value->responsibility == $u->email )
                    {{ $u->name }}
                    @endif
                    @endforeach</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->correction )?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->rootcause )?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->correctiveaction )?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->created_at }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->reviewedat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->reviewed_by}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->approved_by}}</span></td>

        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(isset($data3) && count($data3)>0)
<table class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable4" style="display:none;">
    <thead >
        <tr>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                S.No
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Complaint ID
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;word-wrap: break-word;">
                Title
            </td>
            @foreach($table_columns_active as $active)
                @if($active->enabled_by_user!==0)
                    <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                        {{ucwords($active->column_name)}}
                    </td>
                @else
                    <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                        {{ucwords($active->column_name)}}
                    </td>
                @endif
            @endforeach
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Process Stage
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Action Status
            </td>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;?>
        @foreach($data3 as $value)
        <?php
            $rr=$value->id;
            $date = $value->tardate;
            $today = Date("Y-m-d");
            $closedate=date('Y-m-d',strtotime($value->closed_at));
            $product = DB::table('products')->whereIn('id',explode(',',$value->product))->pluck('product')->toArray();
            $customer = DB::table('customer')->where('id',$value->customer)->value('customer');
            $depname = DB::table('departments')->where('id',$value->dept)->value('departmentname');
            $res = App\User::where('email',$value->responsibility)->value('name');
            $raisedby = App\User::where('employeeid',$value->addedby)->value('name');
            $receivedby = DB::table('employee_details')->where('emp_number',$value->employee)->value('emp_name');
            $plantname = DB::table('complaint_plants')->where('id',$value->plant)->value('plant_name');
            $subdiv = App\Area::where('id',$value->area_id)->value('name');
            $div = App\Division::where('id',$value->division_id)->value('name');
            $leadername = App\User::where('id',$value->team_lead_id)->value('name');
            $reviewedbyname = App\User::where('id',$value->reviewed_by)->value('name');
            $closedbyname = App\User::where('id',$value->closed_by)->value('name');
        ?>
        <tr>
            <td style="font-size:11px;text-align: center;">{{ $no = $no + 1 }}</td>
            <td style="font-size:11px;text-align: center;">
                {{$value->trackno}}
            </td>
            <td style="font-size:11px;word-wrap: break-word;text-align: center;">
                {{$value->title}}
            </td>
            @foreach($table_columns_active as $column_c)
                @switch($column_c->column_name)
                    @case('customer')
                        <td style="font-size:11px;text-align: center;">
                            {{$customer ? $customer : $value->customer}}
                        </td>
                        @break
                    @case('product')
                        <td style="font-size:11px;text-align: center;">
                            {{$product ? implode(',',$product) : $value->product}}
                        </td>
                        @break
                    @case('type')
                        <td style="font-size:11px;text-align: center;">
                            @if($value->type=="Low")
                            <span style="background-color:#30c78d;color:#000;">{{$value->type}}</span>
                            @elseif($value->type=="Moderate")
                            <span style="background-color:#e5ae67;color:#000;">{{$value->type}}</span>
                            @elseif($value->type=="High")
                            <span style="background-color:#e56767;color:#000;">{{$value->type}}</span>
                            @else
                            <span>--</span>
                            @endif
                        </td>
                    @break
                    @case('issue authenticity')
                    @if(in_array('issue authenticity',$table_columns_names_active))
                        <td class="text-center px-1" style="font-size:10px;">
                            {{ $value->issue_authenticity ??"--" }}
                        </td>
                    @endif
                    @break
                    @case('department')
                        <td style="font-size:11px;text-align: center;">
                            {{ $depname ? $depname : $value->dept}}
                        </td>
                    @break
                    @case('responsibility')
                        <td style="font-size:11px;text-align: center;">
                            {{$res??'--'}}
                        </td>
                    @break
                    @case('due date')
                        <td style="font-size:11px;text-align: center;max-width:fit-content;">
                            {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                        </td>
                        @break
                    @case('raised by')
                        @if(in_array('raised by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$raisedby??'--' }}
                            </td>
                        @endif
                    @break
                    @case('complaint received by')
                        @if(in_array('complaint received by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $receivedby??"--" }}
                            </td>
                        @endif
                    @break
                    @case('complaint received date')
                        @if(in_array('complaint received date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->crdate ? date('d-m-Y', strtotime($value->crdate)) : '--'}}
                            </td>
                        @endif
                    @break
                    @case('mode of complaint')
                    @if(in_array('mode of complaint',$table_columns_names_active))
                        <td style="font-size:11px;word-wrap: break-word;text-align: center;">
                            {{ ucfirst($value->mode_of_complaint)??"--"  }}
                        </td>
                    @endif
                    @break
                    @case('customer phone')
                    @if(in_array('customer phone',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->customer_phone ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('customer email')
                    @if(in_array('customer email',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->customer_email??'--' }}
                        </td>
                    @endif
                    @break
                    @case('sale order no.')
                    @if(in_array('sale order no.',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->sale_order_no ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('delivery no.')
                    @if(in_array('delivery no.',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->delivery_no ??"--"}}
                        </td>
                    @endif
                    @break
                    @case('delivery date')
                    @if(in_array('delivery date',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->delivery_date ?date('d-m-Y',strtotime($value->delivery_date)): "--"}}
                        </td>
                    @endif
                    @break
                    @case('plant')
                    @if(in_array('plant',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $plantname ?? "--"}}
                        </td>
                    @endif
                    @break
                    @case('division')
                        @if(in_array('division',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $div ??"--" }}
                            </td>
                        @endif
                        @break
                    @case('subdivision')
                        @if(in_array('subdivision',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $subdiv ??"--" }}
                            </td>
                        @endif
                        @break
                    @case('financial loss')
                        @if(in_array('financial loss',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $value->financial_loss??"--" }}
                            </td>
                        @endif
                        @break
                    @case('potential cost saving')
                    @if(in_array('potential cost saving',$table_columns_names_active))
                        <td style="font-size:11px;text-align: center;">
                            {{ $value->potential_cost_savings??"--" }}
                        </td>
                    @endif
                    @break
                    @case('email reminder for action')
                        @if(in_array('email reminder for action',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->eremainder??"--"}}
                            </td>
                        @endif
                        @break
                    @case('investigation team lead')
                        @if(in_array('investigation team lead',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $leadername??"--" }}
                            </td>
                        @endif
                    @break
                    @case('investigation target date')
                        @if(in_array('investigation target date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $value->investigation_date ? date('d-m-Y', strtotime($value->investigation_date)):"--" }}
                            </td>
                        @endif
                        @break
                        @case('added date')
                        @if(in_array('added date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $value->created_at?date('d-m-Y',strtotime($value->created_at)):"--" }}
                            </td>
                        @endif
                    @break
                    @case('reviewed by')
                        @if(in_array('reviewed by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $reviewedbyname?$reviewedbyname:"--" }}
                            </td>
                        @endif
                    @break
                    @case('reviewed date')
                        @if(in_array('reviewed date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->reviewedat?date('d-m-Y',strtotime($value->reviewedat)):"--"}}
                            </td>
                        @endif
                    @break
                    @case('closure reviewed by')
                        @if(in_array('closure reviewed by',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{ $closedbyname?$closedbyname:"--" }}
                            </td>
                        @endif
                    @break
                    @case('closure reviewed date')
                        @if(in_array('closure reviewed date',$table_columns_names_active))
                            <td style="font-size:11px;text-align: center;">
                                {{$value->closed_at?date('d-m-Y',strtotime($value->closed_at)):"--"}}
                            </td>
                        @endif
                    @break
                    @default
                    @break
                @endswitch
            @endforeach
            <td style="font-size:11px;text-align: center;">
                @if($closedate <= $date)
                <span style="background-color:#30c78d;">{{$value->status}}</span>
                @else
                <span style="background-color:#e5ae67;">{{$value->status}}</span>
                @endif
            </td>
            <td style="font-size:11px;text-align: center;">
                <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if(isset($data) && count($data)>0)
<table id="headerTable5" class="table table-responsive table-bordered table-striped table-vcenter" style="display: none;">
    <thead>
        <tr>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                S.No
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Complaint ID
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;word-wrap: break-word;width:90px;">
                Title
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Type
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Customer
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Responsibility
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Department
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;width:60px;">
                Due Date
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Cost Impact
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;word-wrap: break-word;">
                Complaint Categories
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Process Stage
            </td>
            <td style="background-color:#fff;font-size:11px;font-weight:bold !important;text-align: center;">
                Action Status
            </td>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;?>
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
                $rowCount = max($products->count(),1);
            ?>
            @if(!$products->isEmpty())
                @foreach($products as $index => $pd)
                <tr>
                    @if($index == 0)
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">{{ $no = $no + 1 }}</td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{$value->trackno}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;word-wrap: break-word;text-align: center;">
                        {{$value->title}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        @if($value->type=="Low")
                        <span style="background-color:#30c78d;color:#000;">{{$value->type}}</span>
                        @elseif($value->type=="Moderate")
                        <span style="background-color:#e5ae67;color:#000;">{{$value->type}}</span>
                        @elseif($value->type=="High")
                        <span style="background-color:#e56767;color:#000;">{{$value->type}}</span>
                        @else
                        <span>--</span>
                        @endif
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{$customer ? $customer : $value->customer}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{$res??'--'}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{ $depname ? $depname : $value->dept}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;max-width:fit-content;">
                        {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                    </td>
                    @endif
                    <td style="font-size:10px;text-align: center;">
                        {{ $pd->cost_impact ?? '--' }}
                    </td>
                    @php
                        $categories = collect(explode(',', $pd->complaint_category ?? ''))
                            ->map(function($id) use ($categoryNamesData) {
                                return trim($categoryNamesData[$id] ?? '');
                            })
                            ->filter()
                            ->implode(', ');
                        $categories = $categories ?: '--';
                    @endphp

                    <td style="font-size:10px;text-align: center;">
                        {{$categories}}
                    </td>
                    @if($index==0)
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        @if($value->status=='Under Review')
                        <span style="background-color: #a2a4a5;">{{$value->status}}</span>
                        @elseif($value->status=='Actions Proposed')
                        <span style="background-color: #44b3ab;">{{$value->status}}</span>
                        @elseif($value->status=='Action Reviewed, OK')
                        <span style="background-color: #30c78d;">Action Approved</span>
                        @elseif($value->status=='Action Reviewed, Not OK')
                        <span style="background-color: #e56767;">Action Rejected</span>
                        @elseif($value->status=='Need More Action')
                        <span style="background-color:#e5ae67;">{{$value->status}}</span>
                        @elseif($value->status=='Actions Completed')
                        <span style="background-color: #5cace5;">{{$value->status}}</span>
                        @elseif($value->status=='Approved')
                        <span style="background-color: #b08fe5;">{{$value->status}}</span>
                        @elseif($value->status=='Rejected')
                        <span style="background-color: #e58f8f;">{{$value->status}}</span>
                        @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                        <span style="background-color: #d9e2f6;">{{$value->status}}</span>
                        @endif
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
                    </td>
                    @endif
                </tr>
                @endforeach
            @else
                <tr>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">{{ $no = $no + 1 }}</td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{$value->trackno}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;word-wrap: break-word;text-align: center;">
                        {{$value->title}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        @if($value->type=="Low")
                        <span style="background-color:#30c78d;color:#000;">{{$value->type}}</span>
                        @elseif($value->type=="Moderate")
                        <span style="background-color:#e5ae67;color:#000;">{{$value->type}}</span>
                        @elseif($value->type=="High")
                        <span style="background-color:#e56767;color:#000;">{{$value->type}}</span>
                        @else
                        <span>--</span>
                        @endif
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{$customer ? $customer : $value->customer}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{$res??'--'}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        {{ $depname ? $depname : $value->dept}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;max-width:fit-content;">
                        {{$value->tardate ? date('d-m-Y', strtotime($value->tardate)) : '--'}}
                    </td>
                    <td style="font-size:10px;text-align: center;">
                        {{'--' }}
                    </td>
                    <td style="font-size:10px;text-align: center;">
                        {{'--'}}
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        @if($value->status=='Under Review')
                        <span style="background-color: #a2a4a5;">{{$value->status}}</span>
                        @elseif($value->status=='Actions Proposed')
                        <span style="background-color: #44b3ab;">{{$value->status}}</span>
                        @elseif($value->status=='Action Reviewed, OK')
                        <span style="background-color: #30c78d;">Action Approved</span>
                        @elseif($value->status=='Action Reviewed, Not OK')
                        <span style="background-color: #e56767;">Action Rejected</span>
                        @elseif($value->status=='Need More Action')
                        <span style="background-color:#e5ae67;">{{$value->status}}</span>
                        @elseif($value->status=='Actions Completed')
                        <span style="background-color: #5cace5;">{{$value->status}}</span>
                        @elseif($value->status=='Approved')
                        <span style="background-color: #b08fe5;">{{$value->status}}</span>
                        @elseif($value->status=='Rejected')
                        <span style="background-color: #e58f8f;">{{$value->status}}</span>
                        @elseif($value->status == 'Correction Proposed- RC Awaiting' || $value->status == 'Actions Proposed- Review Awaiting' || $value->status == 'RC Updated- Correction Awaiting')
                        <span style="background-color: #d9e2f6;">{{$value->status}}</span>
                        @endif
                    </td>
                    <td rowspan="{{ $rowCount }}" style="font-size:11px;text-align: center;">
                        <p class="fs-xs fw-semibold mb-0">{{$value->average_percent}}%</p>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script type="text/javascript">
    var tab_text;
    function fnCovertTable(type) {
        if (document.getElementById('tabselected').value == 'a1') {
            exptable = "headerTable1";
            pdfvalue = "tvalue1";
            x = 11;
        } else if (document.getElementById('tabselected').value == 'a2') {
            exptable = "headerTable2";
            pdfvalue = "tvalue2";
            x = 0;
        } else if (document.getElementById('tabselected').value == 'a3') {
            exptable = "headerTable3";
            pdfvalue = "tvalue3";
            x = 15;
        } else if (document.getElementById('tabselected').value == 'a4') {
            exptable = "headerTable4";
            pdfvalue = "tvalue4";
            x = 16;
        }else if (document.getElementById('tabselected').value == 'a5') {
            exptable = "headerTable5";
            pdfvalue = "tvalue1";
            x = 11;
        }
        tab_text = "<style>.text-center{ text-align: center }</style><table style='border-collapse:collapse;width:100%;' border='1' align='center' >";
        var textRange;
        var j = 0;
        var i = 0;
        tab = document.getElementById(exptable); // id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>";
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        }
        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        var user_name = document.getElementById('current_user_name').value;
        const orientation = document.getElementById('orientation').value;
        const paper = document.getElementById('paper').value;
        if (type == 'excel') {
            const tab = document.getElementById(exptable); // id of table
            tab_text = "<style>.text-center{ text-align: center }</style><table style='border-collapse:collapse;width:100%;' border='1' align='center' >";
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>";
                for (i = 0; i < tab.rows[j].cells.length; i++) {
                    tab_text = tab_text + "<td class='text-center'>";
                    tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
                }
                tab_text = tab_text + "</tr>";
            }
            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            if(exptable!="headerTable5"){
                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
                {
                    txtArea1.document.open("txt/html", "replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Setilas.xlsx");
                    return (sa);
                } else {
                    var blob = new Blob([tab_text], { type: "application/msexcel" });
                    var link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "ComplaintManagement_" + user_name + ".xls";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }else{
                var workbook = XLSX.utils.table_to_book(document.getElementById(exptable), {raw:true});
                XLSX.writeFile(workbook, "ComplaintManagement_" + user_name + ".xls");
            }

        } else if (type == 'word') {
            const html = "<style>@page portrait_A4_page{size:595.3pt 841.9pt;margin:72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin:35.4pt;mso-footer-margin:35.4pt;mso-paper-source:0;} " +
                " div.portrait_A4_page { page:portrait_A4_page;} " +
                " @page landscape_A4_page {size:841.9pt 595.3pt;mso-page-orientation:landscape;margin:72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin:35.45pt;mso-footer-margin:35.45pt;mso-paper-source:0;} " +
                " div.landscape_A4_page { page:landscape_A4_page;} " +
                " @page portrait_A3_page {size: 841.9pt 1190.55pt;mso-page-orientation: portrait;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.portrait_A3_page { page:portrait_A3_page;} " +
                " @page landscape_A3_page {size: 1190.55pt 841.9pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.landscape_A3_page { page:landscape_A3_page;} " +
                " @page portrait_A2_page {size: 1190.55pt 1683.78pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.portrait_A2_page { page:portrait_A2_page;} " +
                " @page landscape_A2_page {size: 1683.78pt 1190.55pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.landscape_A2_page { page:landscape_A2_page;} " +
                " @page landscape_A1_page {size: 2383.94pt 1683.78pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.landscape_A1_page { page:landscape_A1_page;} " +
                " @page portrait_A1_page {size: 1683.78pt 2383.94pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.portrait_A1_page { page:portrait_A1_page;} " +
                " .text-center{ align-content: center } " +
                "</style><div class=" + (orientation === "portrait" ? "portrait_" + (paper === "a2" ? "A2" : (paper === "a3" ? "A3": (paper === "a1" ? "A1": "A4"))) + "_page" : "landscape_" + (paper === "a2" ? "A2" : (paper === "a3" ? "A3": (paper === "a1" ? "A1": "A4"))) + "_page") + ">" + tab_text + "</div>"

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(html);
                txtArea1.document.close();
                txtArea1.focus();
                sa = div.innerHTML.document.execCommand("SaveAs", true, "ComplaintManagement.doc");
                return sa;
            } else {
                const blob = new Blob([html], { type: "application/msword" });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "ComplaintManagement_" + user_name + ".doc";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

        } else if (type == 'print') {
            var oldPage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            window.print();
            document.body.innerHTML = oldPage;
        } else if (type == "screenshot") {
            oldpage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            document.body.innerHTML = document.body.innerHTML + "<br/><button value='10' style='float:right'  class='btn btn-success' onclick='saveme()' id='save' download='file-name.png'>Save</button><span></span><button style='float:right' class='btn btn-success' onclick='back()'>Back</button><a id='blank'></a>";
        } else if (type == "pdf") {
            document.getElementById(pdfvalue).value = tab_text;
            return true;

        } else if (type == 'magnify') {
            tab_text = "<style>.text-center{ text-align: center }</style><table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById('mainTable'); // id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>";
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            oldpage = document.body.innerHTML;
            var div1 = document.createElement('div');
            div1.setAttribute("id", "div1");
            var div2 = document.createElement('div');
            div2.setAttribute("id", "div2");
            div1.innerHTML = tab_text;
            div2.innerHTML = "<button type='button' class='btn btn-success'  onclick='back()'>Reset</button>";
            document.body.innerHTML = '';
            document.body.appendChild(div1);
            document.body.appendChild(div2);

        }
    }

</script>
