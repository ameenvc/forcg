<div class="form-group" style="display:inline-block;width:25%;">
    <label class="col-sm-12 col-form-label"
           style="font-size: 13px;" for="linked_risk">
        {{ isset($isOtherLinkedRisk) ? 'Other Linked risk' : 'Linked risk' }}
    </label>
    <div class="col-sm-12">
        <select class="form-control" id="linked_risk" style="font-size: 13px;">
            @if($risk)
            <option value="{{ $risk->Rid }}">
                {{ $risk->riskname . ' - ' . $risk->grp_id }}
            </option>
            @else 
                <option value="{{$mitigation->risk_id}}">{{$mitigation->risk_id}} (Deleted)</option>
            @endif
        </select>
    </div>
</div>
@if($risk)
<div class="form-group" style="display:inline-block;width:16%;">
    <label class="col-sm-12 col-form-label"
           style="font-size: 13px;" for="linked_risk">Likelihood reduced by
    </label>
    <div class="col-sm-12">
        <select class="form-control js-likelihood"
                data-id="{{ $risk->Rid }}"
                style="font-size: 13px;">
            @for($index = 0; $index <= 100; $index += 5)
                <option value="{{ $index }}">
                    {{ $index }}%
                </option>
            @endfor
        </select>
    </div>
</div>
<div class="form-group" style="display:inline-block;width:16%;">
    <label class="col-sm-12 col-form-label"
           style="font-size: 13px;" for="linked_risk">Impact reduced by
    </label>
    <div class="col-sm-12">
        <select class="form-control js-impact"
                data-id="{{ $risk->Rid }}"
                style="font-size: 13px;">
            @for($index = 0; $index <= 100; $index += 5)
                <option value="{{ $index }}">{{ $index }}%</option>
            @endfor
        </select>
    </div>
</div>
<div class="form-group" style="display:inline-block;width:10%;">
    <label class="col-sm-12 col-form-label"
           style="font-size: 13px;">Existing L x I
    </label>
    <div class="col-sm-12 form-row" style="padding-right: 5px">
        @php
            if ($risk->like == 'High') {
                $likelihood = 3;
            } elseif ($risk->like == 'Moderate') {
                $likelihood = 2;
            } else {
                $likelihood = 1;
            }
            if ($risk->impa == 'High') {
                $impact = 3;
            } elseif ($risk->impa == 'Moderate') {
                $impact = 2;
            } else {
                $impact = 1;
            }
        @endphp
        <input type="text" readonly value="{{ $likelihood }}" class="form-control"
               id="org_likelihood{{ $risk->Rid }}"
               name="org_likelihood[{{ $risk->Rid }}]"
               style="width: 45%; margin-left: 5%; font-weight: bold; background: #D9D9D9; color: #347FC0">
        <input type="text" readonly value="{{ $impact }}" class="form-control"
               id="org_impact{{ $risk->Rid }}"
               name="org_impact[{{ $risk->Rid }}]"
               style="width: 45%; margin-left: 5%; font-weight: bold; background: #D9D9D9; color: #347FC0">
    </div>
</div>
<div class="form-group" style="display:inline-block;">
    <label class="col-sm-12 col-form-label"
           style="font-size: 13px;" for="linked_risk">Risk
    </label>
    @if($risk->rank == "High")
        <span class="risk-span-class high-rank">High</span>
    @elseif($risk->rank == "Moderate")
        <span class="risk-span-class moderate-rank">Moderate</span>
    @else
        <span class="risk-span-class other-rank">{{ $risk->rank }}</span>
    @endif
    <input type="hidden" name="org_ranks[{{ $risk->Rid }}]" value="{{ $risk->rank }}">
</div>
<div class="form-group" style="display:inline-block;width:12.5%; margin-left: 1.5rem">
    <label class="col-sm-12 col-form-label"
           style="font-size: 13px;">New L x I
    </label>
    <div class="col-sm-12 form-row" style="padding-right: 5px">
        <input type="text" readonly value="{{ $likelihood }}" class="form-control"
               id="new_likelihood{{ $risk->Rid }}"
               name="likelihoods[{{ $risk->Rid }}]"
               style="width: 45%; margin-left: 5%; font-weight: bold; background: #DCE3F1; color: #267CC3">
        <input type="text" readonly value="{{ $impact }}" class="form-control"
               id="new_impact{{ $risk->Rid }}"
               name="impacts[{{ $risk->Rid }}]"
               style="width: 45%; margin-left: 5%; font-weight: bold; background: #DCE3F1; color: #267CC3">
    </div>
</div>
<div class="form-group" style="display:inline-block;">
    <label class="col-sm-12 col-form-label" style="font-size: 13px;">New risk</label>
    <div id="new_rank{{ $risk->Rid }}">
        @if($risk->rank == "High")
            <span class="risk-span-class high-rank">High</span>
        @elseif($risk->rank == "Moderate")
            <span class="risk-span-class moderate-rank">Moderate</span>
        @else
            <span class="risk-span-class other-rank">{{ $risk->rank }}</span>
        @endif
    </div>
    <input type="hidden" name="ranks[{{ $risk->Rid }}]" id="ranks_input{{ $risk->Rid }}" value="{{ $risk->rank }}">
</div>
@else
<br>
@endif