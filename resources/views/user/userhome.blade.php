@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .wrap-content {
            padding: 1.875rem 3.875rem 1px;
            justify-content: space-between;
            display: flex;
        }
        .wrap-content .textwc {
            width: 65%;
        }
        .profile-img {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            margin-left: 1rem;
            margin-right: 1rem;
        }
        .profile-btn-wrap {
            display: inline-block;
            margin-bottom: 10px;
        }
        .update-profile-btn {
            padding: 10px 12px;
            border-radius: 8px;
            background-color: #252945;
            color: white;
            font-size: 14px;
            border-color: #252945;
        }
        .update-profile-btn:hover {
            color: #fff;
            background-color: #3261cf;
            border-color: #2f5cc5;
        }
        .search-input {
            background-image: url({{ asset('images/icons/search-normal.png') }});
            background-repeat: no-repeat;
            text-indent: 25px;
            padding: 9px;
            border-radius: 10px;
            border: 1px solid #D7D7D7;
            background-position: left;
            background-origin: content-box;
            font-size: 14px;
        }
        .search-input:focus {
            outline: 1px solid #ccc;
        }
        .search-input::placeholder {
            color: #B0B0B0;
            font-size: 12px;
        }
        .tab-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        .tab-menu a {
            color: #878787;
            font-weight: 500;
        }
        .tab-menu a {
            margin-right: 1rem;
        }
        .tab-menu a.active {
            color: #25499C;
            border-bottom: 1px solid #25499C;
        }
        .module-item {
            background: #F6F7F9;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 30px;
            width: 31%;
            margin-right: 20px;
            align-items: center;
        }
        .module-item h5 {
            margin-bottom: 0;
            font-size: 16px;
        }
        .module-item .module-desc {
            font-size: 12px;
            color: grey;
        }
        .module-item .module-icon {
            padding: 7px 5px;
            border-radius: 8px;
            margin-right: 10px;
            width: 42px;
            height: 42px;
            text-align: center;
        }
        .module-item.disabled {
            background: #ccc;
            opacity: 0.5;
            pointer-events: none;
        }
        .separate-div {
            display: none;
        }
        .content-section {
            position: relative;
            width: 100%;
            padding-right: 14px;
            padding-left: 14px;
            flex: 0 0 83.3333333333%;
            max-width: 83.3333333333%;
            margin-left: 8.3333333333%;
            margin-top: 3rem;
        }
        .textwc h5 {
            font-size: 16px
        }
        .textwc p {
            font-size: 14px;
        }
        .menu-btn {
            display: none;
            background-color: #ccccccb3;
            border: none;
            color: #555;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .menu-btn.left-btn {
            margin-right: 0.5rem
        }
        .menu-btn.right-btn {
            margin-left: 0.5rem
        }
        @media screen and (min-width: 1217px) and (max-width: 1400px) {
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(14px, 0.25vw, 16px);
            }
            .module-item .module-desc {
                font-size: clamp(10px, 0.25vw, 12px);
            }
        }
        @media screen and (min-width: 851px) and (max-width: 1217px) {
            .tab-menu {
                display: block;
            }
            .tab-menu a {
                line-height: 2;
            }
            .search-input {
                width: 100%;
                margin-top: 20px;
            }
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(14px, 0.25vw, 16px);
            }
            .module-item .module-desc {
                font-size: clamp(10px, 0.25vw, 12px);
            }
            .module-item {
                width: 45%;
            }
        }
        @media screen and (min-width: 779px) and (max-width: 880px) {
            .content-section {
                flex: 0 0 95%;
                max-width: 95%;
                margin-left: 2%;
            }
            .wrap-content .textwc {
                width: 60%;
            }
            .module-item {
                width: 45%;
            }
            .tab-menu {
                display: block;
            }
            .tab-menu a {
                line-height: 2;
            }
            .search-input {
                width: 100%;
                margin-top: 20px;
            }
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(14px, 0.25vw, 16px);
            }
            .module-item .module-desc {
                font-size: clamp(10px, 0.25vw, 12px);
            }
        }
        @media screen and (min-width: 660px) and (max-width: 778px) {
            .wrap-content {
                padding: 1.875rem 0.75rem 1px;
            }
            .wrap-content .textwc {
                width: 55%;
            }
            .content-section {
                flex: 0 0 95%;
                max-width: 95%;
                margin-left: 2%;
            }
            .tab-menu {
                display: block;
            }
            .tab-menu a {
                line-height: 2;
                margin-right: 0.75rem;
            }
            .search-input {
                width: 100%;
                margin-top: 20px;
            }
            .profile-img {
                width: 60px;
                height: 60px;
            }
            .module-item {
                width: 47.85%;
                padding: 10px;
                margin-right: 10px;
            }
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(12px, 0.25vw, 14px);
            }
            .module-item .module-desc {
                font-size: clamp(8px, 0.25vw, 12px);
            }
        }
        @media screen and (min-width: 599px) and (max-width: 659px) {
            .wrap-content {
                padding: 1.875rem 0.75rem 1px;
            }
            .content-section {
                flex: 0 0 95%;
                max-width: 95%;
                margin-left: 2%;
            }
            .tab-menu {
                display: block;
            }
            .tab-menu a {
                line-height: 2;
            }
            .search-input {
                width: 100%;
                margin-top: 20px;
            }
            .profile-img {
                width: 60px;
                height: 60px;
                display: block;
                margin: 0 auto 0.5rem;
            }
            .module-item {
                width: 47.85%;
                padding: 10px;
                margin-right: 10px;
            }
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(12px, 0.25vw, 14px);
            }
            .module-item .module-desc {
                font-size: clamp(8px, 0.25vw, 12px);
            }
            .menu-btn.right-btn {
                display: block;
            }
            .menu-wrap {
                display: flex;
            }
            .menu-items {
                display: flex;
                gap: 15px;
                overflow: hidden; /* Hide overflow */
                white-space: nowrap; /* Prevent wrapping */
                position: relative;
            }
            .tab-menu a {
                flex: 0 0 auto;
            }
        }
        @media screen and (min-width: 421px) and (max-width: 599px) {
            .wrap-content {
                padding: 1.875rem 0.75rem 1px;
            }
            .content-section {
                flex: 0 0 95%;
                max-width: 95%;
                margin-left: 2%;
            }
            .module-item {
                width: 45%;
            }
            .tab-menu {
                display: block;
            }
            .tab-menu a {
                line-height: 2;
                /*margin-right: 0.5vw;*/
            }
            .search-input {
                width: 100%;
                margin-top: 20px;
            }
            .wrap-content .textwc {
                width: 50vw;
            }
            .profile-img {
                width: 48px;
                height: 48px;
                display: block;
                margin: 0 auto 0.5rem;
            }
            .update-profile-btn {
                padding: 8px 10px;
            }
            .module-item {
                width: 100%;
            }
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(12px, 0.25vw, 14px);
            }
            .module-item .module-desc {
                font-size: clamp(10px, 0.25vw, 12px);
            }
            .menu-btn.right-btn {
                display: block;
            }
            .menu-wrap {
                display: flex;
            }
            .menu-items {
                display: flex;
                gap: 15px;
                overflow: hidden;
                white-space: nowrap;
                position: relative;
            }
            .tab-menu a {
                flex: 0 0 auto;
            }
        }
        @media screen and (max-width: 420px) {
            .wrap-content {
                padding: 1.875rem 0.5rem 1px;
            }
            .content-section {
                flex: 0 0 95%;
                max-width: 95%;
                margin-left: 2%;
            }
            .module-item {
                width: 45%;
            }
            .tab-menu {
                display: block;
            }
            .tab-menu a {
                line-height: 2;
            }
            .search-input {
                width: 100%;
                margin-top: 20px;
            }
            .profile-img {
                width: 48px;
                height: 48px;
                display: block;
                margin: 0 auto 0.5rem;
            }
            .update-profile-btn {
                display: inline-block;
                padding: 8px 10px;
            }
            .module-item {
                width: 100%;
                margin-right: 0;
            }
            .profile-btn-wrap {
                margin-bottom: 20px;
            }
            .module-item h5, .textwc h5, .update-profile-btn, .tab-menu a, .textwc p {
                font-size: clamp(12px, 0.25vw, 14px);
            }
            .module-item .module-desc {
                font-size: clamp(8px, 0.25vw, 10px);
            }
            .menu-btn.right-btn {
                display: block;
            }
            .menu-wrap {
                display: flex;
            }
            .menu-items {
                display: flex;
                gap: 15px;
                overflow: hidden; /* Hide overflow */
                white-space: nowrap; /* Prevent wrapping */
                position: relative;
            }
            .tab-menu a {
                flex: 0 0 auto;
            }
            .module-item .module-icon {
                padding: 3px 5px;
                border-radius: 8px;
                margin-right: 10px;
                width: 32px;
                height: 32px;
            }
            .module-icon img {
                width: 20px;
                height: 20px;
            }
        }
        .module-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #e3e8f0;
        }
    </style>
</head>

<div class="wrap-content" style="background-color: #F6F7F9">
    <div class="textwc">
        <h5 class="mb-0">All Modules</h5>
        <p>Welcome, <a href="{{ route('user.profile') }}" class="font-w600">{{ auth()->user()->name }}</a>! Explore and access essential modules for streamlined corporate governance and management.</p>
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
            <a href="{{ route('profile.edit', ['g' => 'user']) }}" class="update-profile-btn">Update Profile</a>
        </div>
    </div>
</div>
<div class="content-section">
    @if(session('msg'))
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            {{ session('msg') }} <span></span>
        </div>
    @endif
    <div class="tab-menu">
        <div class="menu-wrap">
            <button class="menu-btn left-btn" id="leftBtn"><span class="fa fa-arrow-circle-left"></span></button>
            <div class="menu-items">
                <a class="active js-tab-click" data-id="all" href="javascript:void(0)">All</a>
                <a class="js-tab-click" data-id="governance_compliance" href="javascript:void(0)">Governance & Compliance</a>
                <a class="js-tab-click" data-id="quality" href="javascript:void(0)">Quality</a>
                <a class="js-tab-click" data-id="hse_sustainability" href="javascript:void(0)">HSE & Sustainability</a>
                <a class="js-tab-click" data-id="people_culture" href="javascript:void(0)">People & Culture</a>
                <a class="js-tab-click" data-id="operations" href="javascript:void(0)">Operations</a>
            </div>
            <button class="menu-btn right-btn" id="rightBtn">
                <span class="fa fa-arrow-alt-circle-right"></span>
            </button>
        </div>
        <div>
            <input type="text" class="search-input" placeholder="Search Modules...">
        </div>
    </div>
    <div class="mt-4 mb-4" style="background: white; padding: 24px 23px; border-radius: 8px">
        <div class="d-flex flex-wrap">
            @foreach($modules as $key => $item)
                <a href="{{ $item[4] ? route($item[4]) : 'javascript:void(0)' }}"
                   class="d-flex module-item {{ $item[4] ? '' : 'disabled' }}" data-parent="{{ isset($item[6]) ? $item[6] : '' }}"
                   style="transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;">
                    <div class="module-icon" style="background: {{ $item[0] }};"><img src="{{ asset('images/icons/' . $item[1]) }}" alt="{{ $key }}"></div>
                    <div>
                        <h5>{{ $item[2] }}</h5>
                        <span class="module-desc">{{ $item[3] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        const $items = $('.js-tab-click'); // Select all menu items
        const $leftBtn = $('#leftBtn');
        const $rightBtn = $('#rightBtn');
        let visibleTabs = 4;
        let currentIndex = 0;

        function updateMenu() {
            // Slide visible items
            $items.each(function (index) {
                if (index >= currentIndex && index < currentIndex + visibleTabs) {
                    $(this)
                        .css({
                            transform: 'translateX(0)',
                            opacity: 1,
                        })
                        .show();
                } else {
                    $(this).css({
                        transform: 'translateX(-100%)',
                        opacity: 0,
                    });
                }
            });

            // Toggle button visibility
            $leftBtn.css('display', currentIndex === 0 ? 'none' : 'block');
            $rightBtn.css(
                'display',
                currentIndex + visibleTabs >= $items.length ? 'none' : 'block'
            );
        }

        // Navigation function for left and right buttons
        function navigate(direction) {
            currentIndex += direction;

            // Keep index in bounds
            if (currentIndex < 0) currentIndex = 0;
            if (currentIndex + visibleTabs > $items.length)
                currentIndex = $items.length - visibleTabs;

            // Apply sliding animation
            $items.parent().stop(true, true).animate(
                { scrollLeft: direction > 0 ? '+=200px' : '-=200px' },
                300
            );

            updateMenu();
        }

        // Event listeners
        $leftBtn.on('click', function () {
            navigate(-1); // Move left
        });

        $rightBtn.on('click', function () {
            navigate(1); // Move right
        });

        // Update menu on window resize
        // $(window).on('resize', function () {
        //     updateMenu();
        // });

        // Initialize the menu
        // updateMenu();
    });
    $(function () {
        $('body').on('click', '.js-tab-click', function () {
            const id = $(this).attr('data-id');
            $('.js-tab-click').removeClass('active');
            $(this).addClass('active');
            if (id !== 'all') {
                $(".module-item").addClass('d-none').removeClass('d-flex');
                $("[data-parent=" + id + "]").removeClass('d-none').addClass('d-flex');
            } else {
                $(".module-item").removeClass('d-none').addClass('d-flex');
            }
        }).on('input', '.search-input',function(e){
            const searchValue = $(this).val();
            if(searchValue) {
                $(".module-item").addClass('d-none').removeClass('d-flex');
                $(".module-item").each(function(i) {
                    const itemTitle = $(this).find('h5').html();
                    const itemDesc = $(this).find('.module-desc').html();
                    if(itemTitle.toLowerCase().indexOf(searchValue.toLowerCase()) != -1) {
                        $(this).removeClass('d-none').addClass('d-flex');
                    }
                    if(itemDesc.toLowerCase().indexOf(searchValue.toLowerCase()) != -1) {
                        $(this).removeClass('d-none').addClass('d-flex');
                    }
                });
            } else {
                $(".module-item").removeClass('d-none').addClass('d-flex');
            }

        });
    });
</script>
</html>
@endsection
