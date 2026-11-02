@php
    $thInlineStyles = 'font-size:11px;width: 150px;text-transform:capitalize';
    $thSpanInlineStyles = 'font-size:10px;text-transform:capitalize';
    $isNewDocument = isset($isNewDocument) ? $isNewDocument : false;
@endphp
<input type="hidden" value="a1" id="tabselected">
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter table1"
       id="headerTable1_{{ $isNewDocument ? 'new' : 'change' }}">
    <thead>
    <tr>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>S.No</b></span></th>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Drafted By</b></span></th>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Document Name</b></span></th>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Type</b></span></th>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Dated</b></span></th>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Owner</b></span></th>
        <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Status</b></span></th>
    </tr>
    </thead>
    @foreach($datas as $data)
        @php
            $drafted_name = \App\User::where('email', $data->drafted_by)->first();
            $reviwers_arr = explode(',',$data->reviewer);
        @endphp
        <tbody>
        <tr>
            <!-- S.No -->
            <td class="text-center" style="font-size:11px;width: 50px;"><span
                        style="font-size:10px;width: 50px;">{{ $loop->index + 1 }}</span></td>
            <!-- Drafted By -->
            <td class="text-center" style="font-size:11px;width: 50px;color:#0033cc">
                @if($drafted_name)
                    <span style="font-size:10px;width: 50px;">{{ $drafted_name->name  }}</span>
                @else
                    <span style="font-size:10px;width: 50px;">-</span>
                @endif
            </td>
            <!-- Document name -->
            <td class="text-center" style="font-size:11px;width: 50px;"><span
                        style="font-size:10px;width: 50px;">{{ ucfirst($data->document_name) }}</td>
            <!-- Document type -->
            <td class="text-center" style="font-size:11px;width: 50px;"><span
                        style="font-size:10px;width: 50px;">
                    {{ ucwords(str_replace("_", " ", $data->type)) }}
                    </span>
            </td>
            <!-- Dated -->
            <td class="text-center" style="font-size:11px;width: 50px;"><span
                        style="font-size:10px;width: 50px;">{{ date('d-m-Y',strtotime($data->date)) }}</span></td>
            <!-- Owner -->
            <td class="text-center" style="font-size:11px;width: 50px;"><span
                        style="font-size:10px;width: 50px;">
                        @if(is_numeric($data->owner))
                        {{ $data->department ? $data->department->departmentname : '--'}}
                    @else
                        {{$data->owner}}
                    @endif
                    </span></td>
            <!-- Status -->
            <td class="text-center" style="font-size:11px;width: 50px;">
                @if(($data->status=='Review Requested' || $data->status=='Draft Reviewed') && $data->reviewed_by=='yes')
                    @php
                        $total_reviewers= count(explode(',',$data->reviewer));
                        $total_reviewed_by = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                ->where('reviewed_status',true)
                                                                                ->get()
                                                                                ->count();

                    @endphp
                    <span style="color:#1fa98a;font-size:10px;width: 50px;">Draft Reviewed({{$total_reviewed_by}}/{{$total_reviewers}})</span>
                @elseif($data->status=='Draft Reviewed' && $data->reviewed_by==NULL)
                    <span style="color:#1fa98a;font-size:10px;width: 50px;">Draft Reviewed</span>
                @elseif($data->status=='Review Requested')
                    <span style="color:#800088;font-size:10px;width: 50px;">{{$data->status}}</span>
                @elseif($data->status=='Under Draft')
                    <span style="font-size:10px;width: 50px;"> {{$data->status}}</span>
                @elseif($data->status=='Draft Approved')
                    <span style="color:#007bff;font-size:10px;width: 50px;"> {{$data->status}}</span>
                @else
                    <span style="color:red;font-size:10px;width: 50px;">{{$data->status}}</span>
                @endif
            </td>
        </tr>
        </tbody>
    @endforeach
</table>