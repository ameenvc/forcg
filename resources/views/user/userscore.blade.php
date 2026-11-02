@extends('layouts.backend')
@section('css_after')
<link rel="stylesheet" href="{{ asset('css/user/style.css') }}">
@endsection
@section('content')
<!doctype html>
<html lang="en">
<body>
    {{-- <div>
        <p>if_legal_requirements_identified_count : {{ $if_legal_requirements_identified_count }}</p>
        <p>if_zero_legal_requirements_identified_count : {{ $if_zero_legal_requirements_identified_count }}</p>
        <p>if_legal_requirements_fulfilled_count : {{ $if_legal_requirements_fulfilled_count }}</p>
        <p>if_legal_requirements_not_fulfilled_count : {{ $if_legal_requirements_not_fulfilled_count }}</p>
        <p>if_legal_evaluation_not_completed_count : {{ $if_legal_evaluation_not_completed_count }}</p>
        <p>if_periodical_evaluation_due_that_year_count : {{ $if_periodical_evaluation_due_that_year_count }}</p>
        <p>if_periodical_evaluation_completed_that_year_count : {{ $if_periodical_evaluation_completed_that_year_count }}</p>
        <p>if_periodical_evaluation_did_after_due_that_year_count : {{ $if_periodical_evaluation_did_after_due_that_year_count }}</p>
    </div> --}}
    <div class="content">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-12">
                <div class="bg-pink p-1 global-height">
                    <p class="text-white text-center font-shape mt-4">Global CG Score</p>
                    <h1 class="text-white text-center cg-score-style">CG 10.1373</h1>
                </div>
                <div class="bg-white pr-1 pl-1 last-month-style">
                    <p class="text-center mt-3 font-shape">Last Month: CG 10.1166</p>
                </div>
                <div class="bg-pink mt-1 p-2">
                    <p class="text-white text-center font-shape mt-4 module-score-style">Module Scores</p>
                </div>
                {{-- Start: Modules --}}
                @foreach($modules as $key => $item)
                <div class="p-1 {{$item[1]}} ">
                    <div class="text-white text-center mr-3 ml-3 font-shape">
                        <div class="row">
                            <div class="col-9 text-left text-font">{{ $key }}</div>
                            <div class="col-3 text-right text-font">{{ $item[0] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-4 col-md-12 col-12"></div>
            <div class="col-lg-4 col-md-12 col-12 mb-3" id="actions">
                <div class="dropdown">
                    <button type="button" class="btn btn-outline-danger dropdown-toggle" id="dropdown-default-outline-danger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-default-outline-danger">
                        <a class="dropdown-item" href="javascript:void(0)">Share your CG score</a>
                        <a class="dropdown-item" href="javascript:void(0)">Request CG score</a>
                    </div>
                </div>

            </div>

        </div>

    </div>


</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    isMobile = function() {
        const toMatch = [/Android/i, /webOS/i, /iPhone/i, /iPad/i, /iPod/i, /BlackBerry/i, /Windows Phone/i];

        return toMatch.some(toMatchItem => {
            return navigator.userAgent.match(toMatchItem);
        });
    };
    if (isMobile()) {
        $('#actions').addClass('text-center');
        $('#actions').removeClass('text-right');
    } else {
        $('#actions').addClass('text-right');
        $('#actions').removeClass('text-center');
    }

</script>

@endsection
