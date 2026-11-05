<div class="register-toolbar">
    <label class="register-search"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search risk register</span><input type="search" id="risk-register-search" placeholder="Search risks, IDs, or owners…" autocomplete="off"></label>
    <div class="register-toolbar-actions">
        <button type="button" id="risk-toggle-filters" class="btn register-secondary" aria-controls="risk-register-filters" aria-expanded="false"><i class="fa fa-filter mr-1" aria-hidden="true"></i>Filters <span id="risk-filter-count" hidden></span></button>
        <details class="register-menu"><summary>Columns <i class="fa fa-chevron-down ml-1" aria-hidden="true"></i></summary><div class="register-menu-body">
            @foreach([1 => 'Row number', 5 => 'Risk group', 6 => 'Risk area', 9 => 'Risk owner', 10 => 'Response', 11 => 'Process stage'] as $column => $label)
                <label><input type="checkbox" data-register-column="{{ $column }}" @if($column !== 1) checked @endif> {{ $label }}</label>
            @endforeach
        </div></details>
        <details class="register-menu"><summary><i class="fa fa-download mr-1" aria-hidden="true"></i>Export <i class="fa fa-chevron-down ml-1" aria-hidden="true"></i></summary><div class="register-menu-body">
            <small>Exports the matching risks.</small>
            @foreach(['pdf' => 'PDF document', 'excel' => 'Excel workbook', 'word' => 'Word document'] as $format => $label)
            <a href="{{ route('risk.exportData', $format) }}" data-export-base="{{ route('risk.exportData', $format) }}" class="export-link">{{ $label }}</a>
            @endforeach
        </div></details>
    </div>
</div>
<div id="risk-register-filters" class="register-filters" hidden>
    <label>Risk group<select id="register-filter-group" data-filter-column="5"><option value="">All groups</option>@foreach($data->pluck('riskgroup')->filter()->unique()->sort() as $group)<option value="{{ $group }}">{{ $group }}</option>@endforeach</select></label>
    <label>Risk area<select id="register-filter-area" data-filter-column="6"><option value="">All areas</option>@foreach($data->pluck('department.departmentname')->filter()->unique()->sort() as $area)<option value="{{ $area }}">{{ $area }}</option>@endforeach</select></label>
    <label>Rating<select id="register-filter-rating" data-filter-column="7"><option value="">All ratings</option><option>High</option><option>Moderate</option><option>Low</option></select></label>
    <label>Risk owner<select id="register-filter-owner" data-filter-column="9" data-match-owner="true"><option value="">All owners</option>@foreach($riskOwnerData as $owner)<option value="{{ $owner['employeeid'] }}">{{ $owner['name'] }} · {{ $owner['employeeid'] }}</option>@endforeach</select></label>
    <label>Response<select id="register-filter-response" data-filter-column="10"><option value="">All responses</option>@foreach($data->pluck('risk_response')->filter()->unique()->sort() as $response)<option value="{{ $response }}">{{ $response }}</option>@endforeach</select></label>
    <label>Process stage<select id="register-filter-stage" data-filter-column="11"><option value="">All stages</option>@foreach($data->pluck('action_status')->filter()->unique()->sort() as $stage)<option value="{{ $stage }}">{{ $stage }}</option>@endforeach</select></label>
</div>
<div class="register-view-options">
    <span id="risk-match-count" aria-live="polite">{{ count($data) }} risks in this view</span>
    <button type="button" id="risk-clear-filters" hidden>Clear search &amp; filters</button>
    @if(request('rank') || request('riskgroup'))<a href="{{ route('risk.index') }}">Clear page filters</a>@endif
    <label class="register-layout">Row layout<select id="risk-row-layout"><option value="standard">Standard · 2 lines</option><option value="compact">Compact · 1 line</option><option value="full">Full text · auto height</option></select></label>
    <label class="register-sort">Sort by<select id="risk-register-sort"><option value="default">Default order</option><option value="rating">Highest rating</option><option value="name">Risk name A–Z</option><option value="id">Latest risk ID</option></select></label>
    <label>Rows<select id="risk-register-length"><option>25</option><option>50</option><option>75</option><option>100</option><option>1000</option></select></label>
</div>
<div id="risk-selection-bar" class="register-selection-bar" hidden>
    <strong id="risk-selected-count">0 selected</strong><span id="risk-selected-note"></span>
    <button type="button" id="risk-clear-selection">Clear selection</button>
    @if(Gate::allows('edit-risk') || Gate::allows('edit-all'))<button type="button" id="edit" class="btn register-secondary"><i class="fa fa-edit mr-1" aria-hidden="true"></i>Request edit</button>@endif
    @if(Gate::allows('delete-risk') || Gate::allows('delete-all'))<button type="button" id="del" class="btn register-delete"><i class="fa fa-trash mr-1" aria-hidden="true"></i>Delete selected</button>@endif
</div>
