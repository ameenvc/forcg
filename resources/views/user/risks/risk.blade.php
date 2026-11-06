@extends('pages.datatables')
@section('content')
@once
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
@endonce

@include('shared.forms.assets')
@include('user.risks.review-assets')
<link rel="stylesheet" href="{{ asset('css/risk-register.css') }}?v={{ filemtime(public_path('css/risk-register.css')) }}">
<script src="{{ asset('js/risk-register-ai.js') }}?v={{ filemtime(public_path('js/risk-register-ai.js')) }}" defer></script>
<script src="{{ asset('js/risk-register.js') }}?v={{ filemtime(public_path('js/risk-register.js')) }}" defer></script>
<div class="content cgbod-form risk-register" id="risk-register" data-density="compact"
     data-edit-url="{{ route('risk.editrisk') }}" data-delete-url="{{ route('risk.deleterisk') }}">
    @include('user.risks.register-header')
    <div class="block block-rounded risk-register-card">
        <div class="block-content block-content-full">
            @include('user.risks.notification-warning')
            @if(Session::has('errmsg'))
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
            @endif
            @if(Session::has('msg'))
                <div class="alert alert-primary">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('msg')!!}
                </div>
            @endif
            @include('user.risks.register-toolbar')
            @include('user.risks.register-ai')
            <div id="risk-register-feedback" role="status" aria-live="polite"></div>
            <input type="hidden" value="a1" id="tabselected">
            <div id="risk-register-empty" class="register-empty-state" @if(count($data)) hidden @endif role="status">
                <i class="fa fa-search" aria-hidden="true"></i><strong id="risk-empty-title">No risks in this view</strong>
                <p id="risk-empty-description">Add a risk or clear any page filters to see more records.</p>
            </div>
            <div class="register-table-hint" aria-hidden="true"><i class="fa fa-arrows-alt-h"></i>Swipe the table to see more columns</div>
            <div id="content" class="risk-table-scroll" role="region" aria-label="Risk register table; scroll horizontally for more columns" tabindex="0">
                <table class="risk-module-table table table-vcenter risk-register-table" id="tabledata">
                    <thead>
                    <tr>
                        <th data-orderable="false" data-searchable="false"><input type="checkbox" id="risk-select-page" aria-label="Select risks on this page"></th>
                        <th data-searchable="false">S.No</th>
                        <th class="d-none" data-searchable="false">Internal ID</th>
                        <th class="register-col-id">Risk ID</th>
                        <th>Risk name</th>
                        <th data-orderable="false" class="register-filter-heading"><select class="register-header-filter" aria-label="Filter by risk group" id="register-filter-group" data-filter-column="5"><option value="">Risk group</option>@foreach($data->pluck('riskgroup')->filter()->unique()->sort() as $group)<option value="{{ $group }}">{{ $group }}</option>@endforeach</select></th>
                        <th data-orderable="false" class="register-filter-heading"><select class="register-header-filter" aria-label="Filter by risk area" id="register-filter-area" data-filter-column="6"><option value="">Risk area</option>@foreach($data->pluck('department.departmentname')->filter()->unique()->sort() as $area)<option value="{{ $area }}">{{ $area }}</option>@endforeach</select></th>
                        <th data-orderable="false" class="register-filter-heading"><select class="register-header-filter" aria-label="Filter by rating" id="register-filter-rating" data-filter-column="7"><option value="">Rating</option><option>High</option><option>Moderate</option><option>Low</option></select></th>
                        <th class="d-none" data-orderable="false" data-searchable="false">Owner image</th>
                        <th data-orderable="false" class="register-filter-heading"><select class="register-header-filter" aria-label="Filter by risk owner" id="register-filter-owner" data-filter-column="9" data-match-owner="true"><option value="">Risk owner</option>@foreach($riskOwnerData as $owner)<option value="{{ $owner['employeeid'] }}">{{ $owner['name'] }} · {{ $owner['employeeid'] }}</option>@endforeach</select></th>
                        <th data-orderable="false" class="register-filter-heading"><select class="register-header-filter" aria-label="Filter by response" id="register-filter-response" data-filter-column="10"><option value="">Response</option>@foreach($data->pluck('risk_response')->filter()->unique()->sort() as $response)<option value="{{ $response }}">{{ $response }}</option>@endforeach</select></th>
                        <th data-orderable="false" class="register-filter-heading"><select class="register-header-filter" aria-label="Filter by process stage" id="register-filter-stage" data-filter-column="11"><option value="">Process stage</option>@foreach($data->pluck('action_status')->filter()->unique()->sort() as $stage)<option value="{{ $stage }}">{{ $stage }}</option>@endforeach</select></th>
                        <th data-orderable="false" data-searchable="false">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; ?>
                        @foreach($data as $value)
                        @php
                            $rr = $value->Rid;
                            $riskowners = explode(',', (string) $value->risk_owner);
                            $ownerSearch = [];
                            $rowOwners = [];
                            foreach ($riskowners as $ownerEmail) {
                                if (isset($riskOwnerData[$ownerEmail])) {
                                    $ownerSearch[] = $riskOwnerData[$ownerEmail]['name'].' '.$riskOwnerData[$ownerEmail]['employeeid'];
                                    $rowOwners[] = $riskOwnerData[$ownerEmail];
                                }
                            }
                        @endphp
                        <tr data-risk-id="{{ $value->Rid }}" data-risk-rank="{{ $value->rank }}" data-owner-assigned="{{ trim((string) $value->risk_owner) !== '' ? 'true' : 'false' }}">
                            <td><input class="risk-row-select" type="checkbox" id="risk-select-{{ $value->Rid }}" value="{{ $value->Rid }}" name="id[]" aria-label="Select risk {{ $value->grp_id }}"></td>
                            <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                            <td class="d-none">{{ $value->Rid }}</td>
                            <td class="text-center " style="font-size:10px;"><span class="register-id-text" title="{{ $value->grp_id }}">{{ $value->grp_id }}</span></td>
                            <td class="register-risk-name" style="font-size:10px;">
                                <a href="{{ route('risk.detail', $value->Rid) }}" title="{{ $value->riskname }}"><span class="register-name-text">{{ $value->riskname }}</span></a>
                            </td>
                            <td class="register-risk-group" data-search="{{ $value->riskgroup }}" style="font-size:10px;">
                                <span class="register-cell-text" title="{{ $value->riskgroup }}">{{ $value->riskgroup }}</span>
                            </td>
                            <td class="register-risk-area" data-search="{{ $value->department ? $value->department->departmentname : '-' }}" style="font-size:10px;">
                                <span class="register-cell-text" title="{{ $value->department ? $value->department->departmentname : '-' }}">{{ $value->department ? $value->department->departmentname : '—' }}</span>
                            </td>
                            <td class="register-risk-rating" data-search="{{ $value->rank }}" data-order="{{ $value->rank === 'High' ? 3 : ($value->rank === 'Moderate' ? 2 : 1) }}">
                                @if($value->rank=="High")
                                    <span class="status status-red" style="font-size:10px;">{{$value->rank }}</span>
                                @elseif($value->rank=="Moderate")
                                    <span class="status status-warning" style="font-size:10px;">{{$value->rank }}</span>
                                @else
                                    <span class="status status-success" style="font-size:10px;">{{$value->rank }}</span>
                                @endif
                            </td>
                            <td class="d-none"></td>
                            <td class="register-risk-owner" data-search="{{ implode(' ', $ownerSearch) }}">
                                @if(count($rowOwners))
                                    @php $riskOwnerItem = $rowOwners[0]; @endphp
                                    <div class="register-owner-wrap">
                                        <div class="register-owner">
                                            @if($riskOwnerItem['profile_picture'] && file_exists(public_path('/storage/profile/'.$riskOwnerItem['profile_picture'])))
                                                <img src="{{ asset('storage/profile/'.$riskOwnerItem['profile_picture']) }}" alt="" class="register-avatar">
                                            @else
                                                <span class="register-avatar register-avatar-initial" aria-hidden="true">{{ mb_substr($riskOwnerItem['name'], 0, 1) }}</span>
                                            @endif
                                            <span class="register-owner-label"><span title="{{ $riskOwnerItem['name'] }}">{{ $riskOwnerItem['name'] }}</span><small title="{{ $riskOwnerItem['employeeid'] }}">{{ $riskOwnerItem['employeeid'] }}</small></span>
                                        </div>
                                        @if(count($rowOwners) > 1)
                                        <details class="register-owner-more"><summary aria-label="Show {{ count($rowOwners) - 1 }} additional owners">+{{ count($rowOwners) - 1 }}</summary>
                                            <div class="register-owner-popover"><strong>Additional owners</strong><ul>@foreach(array_slice($rowOwners, 1) as $extraOwner)<li><span>{{ $extraOwner['name'] }}</span><small>{{ $extraOwner['employeeid'] }}</small></li>@endforeach</ul></div>
                                        </details>
                                        @endif
                                    </div>
                                @endif
                                @if(!count($ownerSearch))<span class="register-muted">{{ trim((string) $value->risk_owner) === '' ? 'Unassigned' : 'Owner unavailable' }}</span>@endif
                            </td>
                            <td data-search="{{ $value->risk_response }}"><span class="register-cell-text" title="{{ $value->risk_response }}">{{ $value->risk_response ?: '—' }}</span></td>
                            <td class="register-stage" data-search="{{ $value->action_status }}" title="{{ $value->action_status }}">
                                @if($value->action_status=='Active Risk')
                                    <span class="status status-success" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Action under review')
                                    <span class="status status-secondary" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Completed')
                                    <span class="status status-blue" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Rejected')
                                    <span class="status status-red"  style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Under review')
                                    <span class="status status-warning" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Awaiting Periodic Review')
                                    <span class="status status-warning" style="font-size:10px;">{{$value->action_status}}</span>
                                @endif
                            </td>
                            <td class="register-row-actions">
                                <div class="btn-group">
                                    <button type="button" class="btn register-action-menu" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" aria-label="Actions for risk {{ $value->grp_id }}" id="risk-actions-{{ $value->Rid }}">
                                        <i class="fa fa-fw fa-ellipsis-v" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="risk-actions-{{ $value->Rid }}">
                                        <!-- Status: Under review, Action under review -->
                                        @if(in_array($value->action_status, ['Under review', 'Action under review']))
                                            <!-- Action: Edit -->
                                            @if(Auth::user()->employeeid == $value->postedby)
                                                <a href="{{ route('risk.usereditrisk', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">
                                                    <i class="fas fa-edit" style="font-size:10px;"></i>
                                                    <span class="ml-1">Edit</span>
                                                </a>
                                            @endif
                                            <!-- Action: Initial Review -->
                                            @if ($value->riskreviewer_id && $value->riskreviewer)
                                                @if (Auth::user()->email == $value->riskreviewer->email)
                                                    <a href="{{ url('/user/risks/updaterisk?val='.$rr)}}"
                                                        class="dropdown-item"
                                                        style="font-size:10px;">
                                                        <i class="fas fa-eye" style="font-size:10px;"></i>
                                                        <span class="ml-1">Initial Review</span>
                                                    </a>
                                                @endif
                                            @else
                                                @if(count($email) > 0)
                                                    @foreach($email as $rep)
                                                        @if(Auth::user()->email == $rep)
                                                            <a href="{{ url('/user/risks/updaterisk?val='.$rr)}}"
                                                                class="dropdown-item"
                                                                style="font-size:10px;">
                                                                <i class="fas fa-eye" style="font-size:10px;"></i>
                                                                <span class="ml-1">Initial Review</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif

                                            <!-- Status: Completed -->
                                        @elseif($value->action_status == 'Completed')
                                            <!-- Action: Closure Review -->
                                            @foreach($email as $rep)
                                                @if(Auth::user()->email == $rep)
                                                    <a href="{{ url('/user/risks/closure?val='.$rr)}}"
                                                        class="dropdown-item"
                                                        style="font-size:10px;">Closure Review</a>
                                                @endif
                                            @endforeach
                                            <!-- Status: Active Risk -->
                                        @elseif($value->action_status == 'Active Risk' || $value->action_status == 'Awaiting Periodic Review')
                                            <!-- Action: Edit Action, Completed-100%, Partially
                                            Completed-50%, Progress-25%, Not Started-0% -->
                                            @foreach($riskowners as $riskowner)
                                                @if($riskowner == Auth::user()->email)
                                                    <a href="{{ url('/user/risks/riskreview?val='.$rr)}}"
                                                        class="dropdown-item"
                                                        style="font-size:10px;">
                                                        <i class="fas fa-eye" style="font-size:10px;"></i>
                                                        Periodic Review
                                                    </a>
                                                    {{--                                                        <a href="{{ url('/user/risks/ownrespo?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Edit Action</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/hundred?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Completed-100%</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/five?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Partially Completed-50%</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/two?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Progress-25%</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/zero?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Not Started-0%</a>--}}
                                                @endif
                                            @endforeach
                                            <!-- Status: Rejected -->
                                        @elseif($value->action_status == 'Rejected')
                                            <!-- Action: Resubmit Risk -->
                                            @if(Auth::user()->employeeid == $value->postedby)
                                                <a href="{{ url('/user/risks/resubmitrisk?val='.$rr)}}"
                                                    class="dropdown-item" style="font-size:10px;">
                                                    <i class="si si-reload" style="font-size:10px;"></i>
                                                    Resubmit Risk
                                                </a>
                                            @endif
                                        @endif
                                        <!-- Action: Add Opportunity -->
                                        @foreach($riskowners as $riskowner)
                                            @if($value->action_status == 'Active Risk' && $riskowner == Auth::user()->email)
                                                <a href="{{ url('/user/risks/addopportunity/'.$rr)}}"
                                                    class="dropdown-item" style="font-size:10px;">
                                                    <i class="fas fa-plus" style="font-size:10px;"></i>
                                                    Add Opportunity
                                                </a>
                                            @endif
                                        @endforeach
                                        <!-- Action: Risk History -->
                                        <a href="{{ url('/user/risks/riskhistory?val='.$rr)}}"
                                            class="dropdown-item" style="font-size:10px;">
                                            <i class="fas fa-history" style="font-size:10px;"></i>
                                            <span class="ml-1">Risk History</span>
                                        </a>
                                        <!-- Action: Risk History -->
                                        @if(Gate::allows('risk-change-riskowner') && $value->action_status != 'Under review' && $value->risk_owner != NULL)
                                            <a href="{{ url('/user/risks/changeriskowner/'.$rr)}}"
                                                class="dropdown-item" style="font-size:10px;">
                                                <i class="fas fa-sync" style="font-size:10px;"></i>
                                                <span class="ml-1">Change Risk Owner/area</span>
                                            </a>
                                        @endif
                                        <!-- Action: Add Mitigating Action -->
                                        @if(!in_array($value->action_status, ['Under review', 'Rejected']))
                                            <a href="javascript:void(0)"
                                                class="dropdown-item create-mitigation" style="font-size:10px;"
                                                data-id="{{ $value->Rid }}">
                                                <i class="fas fa-arrow-down" style="font-size:10px;"></i>
                                                <span class="ml-1">Add Mitigating Action</span>
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Action: View detail -->
                                    <button type="button" class="btn register-view-button show-detail" data-id="{{ $value->Rid }}" aria-label="View risk {{ $value->grp_id }}">
                                        <i class="far fa-eye" aria-hidden="true"></i><span>View</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="risk-table-footer"></div>
        </div>
        <!-- END Dynamic Table Full -->
    </div>

    <div class="modal" id="modal-learn-module" tabindex="-1" role="dialog" aria-label="Risk management module tutorial" aria-hidden="true">
        <div class="modal-dialog m-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body m-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- 16:9 aspect ratio -->

                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="about:blank" id="video" title="Risk management module tutorial" allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="risk-area"></div>
    {{-- --------delete request button for mr --}}

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/learn_module.js') }}"></script>
    <script>
        let video_selector = $('#modal-learn-module');
        let video_id_selector = $('#video');
        displayVideo(video_selector, video_id_selector);
        video_selector.on('hidden.bs.modal', function () { video_id_selector.attr('src', 'about:blank'); });
        let learn_selector = $('#learn');
        let learn_btn_selector = $('#learn_btn');

    </script>
    <!-- END Footer -->
    <script>
        $(function () {
            $('body').on('click', '.show-detail', function (e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url : window.location.protocol+"//"+window.location.host+`/user/risks/${id}/detail`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.risk-area').html(data.view);
                            const modal = $(`#viewModal${id}`);

                            if (modal.length) {
                                modal.modal('show');
                            }
                        } else {
                            alert('Data is not found')
                        }
                    }
                })
            }).on('click', '.create-mitigation', function (e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: window.location.protocol+"//"+window.location.host+`/user/risks/${id}/create-mitigation`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.risk-area').html(data.view);
                            const modal = $(`#modal-mitigating-${id}`);

                            if (modal.length) {
                                modal.modal('show');
                            }
                        } else {
                            alert('Data is not found')
                        }
                    }
                })
            }).on('click', '.add_resource_required', function (e) {
                e.preventDefault();
                const val = $(this).attr('data-value');
                $('#resource_required').val(val);
            });

        });
        $(document).ready(function () {
            // Parse query parameters
            const params = new URLSearchParams(window.location.search);
            const openModal = params.get('open_modal');
            const riskId = params.get('id');

            if (openModal === 'true' && riskId) {
                const url = `/user/risks/${riskId}/create-mitigation`; // Update URL as needed
                $.ajax({
                    url,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.risk-area').html(data.view);
                            const modal = $(`#modal-mitigating-${riskId}`);
                            if (modal.length) {
                                modal.modal('show');
                            }
                        } else {
                            alert('Data is not found');
                        }
                        window.history.replaceState(null, "", window.location.protocol + "//" + window.location.host + "/user/riskmgmt");

                    }
                });
            }
        });

    </script>

    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Options</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">
                            {{-- <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/addrisk">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Risk
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div> --}}
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Risks
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
</div>
@endsection
