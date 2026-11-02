@extends('layouts.multiselect')
@section('content')
<link rel="stylesheet" href="{{asset('css/user/myboard.css')}}">
<div class="wrap-content" style="background-color: #F6F7F9">
    <div class="textwc">
        <h4 class="mb-2" style="font-size:16px;">My Board</h4>
        <p>Welcome <a href="{{ route('user.profile') }}" class="font-w600">{{ auth()->user()->name }}</a>, this is your dashboard where you can view all your pending actions and action history.</p>
    </div>
    <div>
        <div class="profile-btn-wrap">
            @php
                $user_details= App\User::where('email',Auth::user()->email)->first();
                $profilePicture =  $user_details ? $user_details->profile_picture : '';
            @endphp
            @if ($profilePicture && file_exists(public_path('/storage/profile/'. $profilePicture)))
                <img class="profile-img"
                     src="{{ asset("storage/profile/". $profilePicture) }}"
                     alt="{{ auth()->user()->name }}">
            @else
                <img class="profile-img"
                     style="border: 1px solid #ccc;"
                     src="{{ asset('/images/avatar10.jpg') }}"
                     alt="{{ auth()->user()->name }}">
            @endif
            <a href="{{ route('user.profile') }}" class="update-profile-btn">Update Profile</a>
        </div>
    </div>
</div>
<div class="row content-section">
    <div class="col-sm-12">
        <h5 style="font-size:15px;">User Profile Overview</h5>
        <div class="mt-3 mb-4" style="background: white; padding: 15px 15px; border-radius: 8px">
            <div class="d-flex flex-wrap">
                    <a href="javascript:void(0)" class="d-flex module-item" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Pending Reviews</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/pending_reviews.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="number-of-action" style="font-size:17px;">{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Pending Approvals</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/pending_approval.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="number-of-action" style="font-size:17px;">{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Pending Actions</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/pending_action.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span style="font-size:17px;" class="number-of-action">{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Total Actions</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/total_action.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="number-of-action" style="font-size:17px;">{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item mb-1 custom-bottom-margin" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Total Points Earned</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/total_points_earn.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="number-of-action" style="font-size:17px;">{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item mb-1 custom-bottom-margin" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Previous Redeemed Points</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/previous_redeemed_points.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="number-of-action" style="font-size:17px;">{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item mb-1 custom-bottom-margin" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                        <dl class="mb-0">
                            <dt class="fw-bold mb-2 action-name" style="font-size:13px;color:#000;">Reward Rank</dt>
                            <dd class="text-dark font-weight-bold mb-0">
                                <img src="{{ asset('images/icons/reward_rank.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="number-of-action" style="font-size:17px;">{{12}}/{{100}}</span>
                            </dd>
                        </dl>
                    </a>
                    <a href="javascript:void(0)" class="d-flex module-item mb-1 custom-bottom-margin" data-parent="" style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease; background-color:#252945; color:#fff; justify-content: center; align-items: center;">
                        <span class="action-name">Redeem Points</span>
                        <div class="module-icon">
                            <i class="fas fa-arrow-up" style="transform: rotate(45deg);"></i>
                        </div>
                    </a>
            </div>
        </div>
    </div>
</div>
<div class="row content-section mt-0">
    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">
        <h5 style="font-size:15px;">Recently Used Modules</h5>
        <div class="mt-3 mb-4" style="background: white; padding: 15px 15px; border-radius: 8px; max-height: 400px; overflow-y: auto;">
            <div class="flex-wrap">
                @forelse($modules as $key => $item)
                <a href="{{ $item[4] ? route($item[4]) : 'javascript:void(0)' }}"
                    class="d-flex module-item"
                    style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease; width: 100%;">
                    <div class="module-icon" style="background: {{ $item[0] }};"><img src="{{ asset('images/icons/' . $item[1]) }}" alt="{{ $key }}"></div>
                    <div>
                        <h5>{{ $item[2] }}</h5>
                        <span class="module-desc">{{ $item[3] }}</span>
                    </div>
                </a>
                @empty
                    <div class="text-danger pt-4" style="text-align: center;">
                        <p>No Activity Found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
        <h5 style="font-size:15px;">Pending Actions</h5>
        <div class="mt-3 mb-4" style="background: white; padding: 15px 15px; border-radius: 8px;max-height: 400px; overflow-y: auto;">
            <div class="flex-wrap">
                @forelse($items as $item)
                <a href='{{$item['redirectPage']}}'
                    class="d-flex module-item mb-0"
                    style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;width:100%;border-radius:0;border-left: 2px solid {{$item['borderColorCode']}};">
                    <dl>
                        <dd class="module-image">
                            <img src="{{asset($item['iconPath'])}}" class="mr-4 mt-4" style="width: 50px;height: 50px;border-radius: 5px;">
                        </dd>
                    </dl>
                    <dl class="mb-0" style="width:100%;">
                        <dt class="fw-bold mb-2 d-flex justify-content-between date-and-flag-status" style="font-size:12px;color:#3d3d3d;">
                            <span>{{$item['taskType']}}</span>
                            <span>{{$item['time']}}</span>
                        </dt>
                        <dt class="text-dark font-weight-bold mb-0 date-and-flag-status">
                           <span style="font-size:13px;">
                            {!!$item['message']!!}
                           </span>
                        </dt>
                        <dt class="mt-2 date-and-flag-status" style="font-size:12px;color:#3d3d3d;">
                            <span>
                                <i class="far fa-calendar-alt date-and-flag-status" style="font-size:13px;color:#af1e1e; visibility: {{$item['dueDate']=='dd/mm/yyyy' ? 'hidden':''}}"></i>
                                &nbsp;&nbsp;
                                <span style="font-weight:lighter;visibility: {{$item['dueDate']=='dd/mm/yyyy' ? 'hidden' : '' }};" class="date-and-flag-status mr-7">{{$item['dueDate']}}</span>
                                @if($item['priorityColor'] && $item['priorityType'])
                                <i class="fas fa-flag date-and-flag-status" style="font-size:13px;color:{{$item['priorityColor']}};"></i>
                                &nbsp;&nbsp;
                                <span style="font-weight:lighter;font-size:13px;" class="date-and-flag-status">{{$item['priorityType']}}</span>
                                @endif
                            </span>
                        </dt>
                    </dl>
                </a>
                @empty
                    <div class="text-danger pt-4" style="text-align: center;">
                        <p>No Pending Actions Found!</p>
                    </div>
                @endforelse
                {{-- <a href='javascript:void(0)'
                    class="d-flex module-item mb-0"
                    style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;width:100%;border-radius:0;border-left: 2px solid #176044;">
                    <dl>
                        <dd class="module-image">
                            <img src="{{ asset('images/icons/pending_approval.png') }}" class="mr-4 mt-4" style="width: 50px;height: 50px;border-radius: 5px;">
                        </dd>
                    </dl>
                    <dl class="mb-0" style="width:100%;">
                        <dt class="fw-bold mb-2 d-flex justify-content-between date-and-flag-status" style="font-size:12px;color:#3d3d3d;">
                            <span>Pending Approval </span>
                            <span>2 hours ago</span>
                        </dt>
                        <dt class="text-dark font-weight-bold mb-0 date-and-flag-status">
                           <span style="font-size:13px;">
                            Your approval is needed for a new risk entry <b style="color:#25499c;">APR021</b> in the <u>Risk Register</u>.
                           </span>
                        </dt>
                        <dt class="mt-2 date-and-flag-status" style="font-size:12px;color:#3d3d3d;">
                            <span>
                                <i class="far fa-calendar-alt date-and-flag-status" style="font-size:13px;color:#af1e1e;"></i>
                                &nbsp;&nbsp;
                                <span style="font-weight:lighter;" class="date-and-flag-status">{{date('d/m/Y', strtotime('2021-09-01'))}}</span>
                            
                                <i class="fas fa-flag ml-6 date-and-flag-status" style="font-size:13px;color:#176044;"></i>
                                &nbsp;&nbsp;
                                <span style="font-weight:lighter;font-size:13px;" class="date-and-flag-status">Low</span>
                            </span>
                        </dt>
                    </dl>
                </a> --}}
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
@endsection
