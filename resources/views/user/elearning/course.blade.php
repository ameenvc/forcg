@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .comment-padding {
            padding: 1.25rem;
        }

        .font-13 {
            font-size: 13px !important;
        }

        .blank-star {
            color: #F4F5F6 !important;
            text-shadow: 0 0 1px #000;
        }

        .fa-stack i.fa-star {
            color: transparent;
            -webkit-text-stroke-width: 1px;
            -webkit-text-stroke-color: #ccc;
        }

        .fa-stack i.fa-star-half {
            color: #e5ae67;
            -webkit-text-stroke-width: 1px;
            -webkit-text-stroke-color: #e5ae67;
            text-shadow: 0 0 1px #000;
        }
        .mb-12{
            margin-bottom: 12.5px;
        }

        .colored-star {
            color: #e5ae67 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

    </style>
</head>
<body>

    <!-- Horizontal -->
    <div id="page-container" class="/*sidebar-o*/ sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!-- Main Container -->
        <main id="main-container">

            <!-- Navigation -->
            <div class="bg-white">
                <div class="content content-boxed py-1">
                    <nav aria-label="breadcrumb">
                        <div class="row">
                            <div class="col">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">
                                        <a class="link-fx text-dark" href="be_pages_elearning_courses.html">Courses</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">{{ $course->name }}</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{ route('elearning.courses') }}">Back</a>
                                    </li>
                                </ol>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('elearning.add_content', ['id' => $id]) }}" class="link-fx btn text-white" style="background-color: #118987" {{
                                            Gate::allows('elearning-add-content') ? '' : 'disabled' }}>
                                            Add Content <i class="fa fa-fw fa-plus mr-1"></i>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                    </nav>
                </div>
            </div>
            <!-- END Navigation -->

            <!-- Page Content -->
            <div class="content content-boxed">
                <div class="row">
                    <div class="col-xl-8">
                        <!-- Lessons -->
                        @php
                        $i = 1;
                        $sum_of_topics = 0;
                        $course_headings = App\CourseHeading::where('is_deleted', false)->where('courses_id', $id)->get();
                        @endphp
                        @if(count($course_headings))
                        <div class="block block-rounded">
                            <div class="block-content font-size-sm">
                                @foreach($course_headings as $ch)
                                <div class="modal edit-modal fade" id="edit_heading{{ $ch->id }}" tabindex="-1" role="dialog" aria-labelledby="edit_heading{{ $ch->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edit_headingLabel{{ $ch->id }}">Edit Course
                                                    Heading
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-success text-left" id="successEditHeadingBtn{{ $ch->id }}" style="display:none;font-size:12px;">
                                                    Course heading edited successfully!
                                                </div>
                                                <ul class="alert alert-danger text-left" id="dangerEditHeadingBtn{{ $ch->id }}" style="display:none;font-size:12px;"></ul>
                                                <input type="hidden" id="heading_id{{ $ch->id }}" name="heading_id" value="{{ $ch->id }}" />
                                                <div class="form-group text-left">
                                                    <label for="edit_heading_name" class="col-form-label" style="font-size:12px;">Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="edit_heading_name" id="edit_heading_name{{ $ch->id }}" style="font-size:12px;" value="{{ $ch->heading }}" required />
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px">Cancel
                                                </button>
                                                <button type="submit" class="btn btn-primary save_heading" style="font-size:12px" data-id="{{ $ch->id }}">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-borderless table-vcenter">
                                    <tbody>
                                        <tr class="table-active">
                                            <th style="width: 50px;"></th>
                                            <th>{{ $i }}. {{ ucfirst($ch->heading) }}</th>
                                            <th class="text-right">
                                                <span class="text-muted">{{ round($ch->duration/3600, 1) }} hours
                                                    @if(Gate::allows('elearning-add-content'))
                                                    <span class="ml-2">
                                                        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit" onclick="showEditHeadingModal('{{ $ch->id }}')">
                                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Delete" onclick="return confirmdeleteheading('{{ $ch->id }}')">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </span>
                                                    @endif

                                                </span>
                                            </th>
                                        </tr>
                                        @php
                                        $j = 1;
                                        $course_heading_contents = App\CourseHeadingContent::where('is_deleted', false)->where('course_headings_id', $ch->id)->get();
                                        $sum_of_topics += count($course_heading_contents);
                                        @endphp
                                        @if(count($course_heading_contents))
                                        @foreach($course_heading_contents as $item)
                                        <div class="modal edit-modal fade" id="edit_content{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="edit_content{{ $item->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="edit_contentLabel{{ $item->id }}">
                                                            Edit Content Name
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-success text-left" id="successEditContentBtn{{ $item->id }}" style="display:none;font-size:12px;">
                                                            Course heading content edited successfully!
                                                        </div>
                                                        <ul class="alert alert-danger text-left" id="dangerEditContentBtn{{ $item->id }}" style="display:none;font-size:12px;"></ul>
                                                        <input type="hidden" id="content_id{{ $item->id }}" name="content_id" value="{{ $item->id }}" />
                                                        <div class="form-group text-left">
                                                            <label for="edit_content_name" class="col-form-label" style="font-size:12px;">Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="edit_content_name" id="edit_content_name{{ $item->id }}" style="font-size:12px;" value="{{ $item->name }}" required />
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px">Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-primary save_content" style="font-size:12px" data-id="{{ $item->id }}">Save</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <tr>
                                            <td class="table-white text-center">

                                                @if(in_array($item->id, $course_heading_content_users))
                                                <i class="fa fa-lg fa-fw fa-check-square text-success"></i>
                                                @else
                                                <i class="fa fa-lg fa-fw fa-minus-square text-secondary"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('elearning.rich_text', ['id' => $item->id ]) }}">{{ $i
                                                    }}.{{ $j }} {{ ucfirst($item->name) }}</a>
                                            </td>
                                            <td class="text-right">
                                                <em class="text-muted">{{ round($item->duration / 60) }} min
                                                    @if(Gate::allows('elearning-add-content'))
                                                    <span class="ml-2">
                                                        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit" onclick="showEditContentModal('{{ $item->id }}')">
                                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Delete" onclick="return confirmdeletecontent('{{ $item->id }}')">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </span>
                                                    @endif

                                                </em>
                                            </td>
                                        </tr>
                                        @php $j++; @endphp
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                @php $i++; @endphp
                                @endforeach
                            </div>
                            <ul class="nav nav-tabs nav-tabs-alt align-items-center comment-padding" data-toggle="tabs" role="tablist" id="myTab">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#ecom-product-comments" id="comment_tab" data-toggle="tab">Comments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ecom-product-reviews" id="review_tab" data-toggle="tab">Reviews</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">

                                <!-- Comments -->
                                <div class="tab-pane pull-x active font-size-sm comment-padding" id="ecom-product-comments" role="tabpanel">
                                    @foreach($course->comments as $comment)
                                    <div class="media push">
                                        <a class="mr-3" href="javascript:void(0)">
                                            @if($comment->admin)
                                            <img class="img-avatar img-avatar32" src="{{ ($comment->admin && $comment->admin->profile_picture) ? '/storage/profile/' . $comment->admin->profile_picture : asset('media/avatars/avatar3.jpg') }}" alt="">
                                            @else
                                            <img class="img-avatar img-avatar32" src="{{ ($comment->user && $comment->user->profile_picture) ? '/storage/profile/' . $comment->user->profile_picture : asset('media/avatars/avatar3.jpg') }}" alt="">
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <a class="font-w600" href="javascript:void(0)">
                                                @if($comment->user)
                                                {{ $comment->user->name}}
                                                @elseif($comment->admin)
                                                {{ $comment->admin->name }}
                                                @endif
                                            </a>
                                            <p class="my-1">{{ $comment->comment }}</p>
                                            <a class="mr-1" href="javascript:void(0)">Like</a>
                                            <a class="mr-1" href="javascript:void(0)" onclick="deleteComment('{{ $comment->id }}')">Delete</a>
                                            <a class="mr-1" href="#reply" onclick="reply('{{ $comment->id }}')">Reply</a>
                                            <span class="text-muted"><em>{{ Carbon\Carbon::createFromDate($comment->created_at)->diffForHumans()}}</em></span>
                                            @foreach($comment->replies as $reply)
                                            <div class="media mt-3">
                                                <a class="mr-3" href="javascript:void(0)">
                                                    @if($reply->admin)
                                                    <img class="img-avatar img-avatar32" src="{{ ($reply->admin && $reply->admin->profile_picture) ? '/storage/profile/' . $reply->admin->profile_picture : asset('media/avatars/avatar3.jpg') }}" alt="">
                                                    @else
                                                    <img class="img-avatar img-avatar32" src="{{ ($reply->user && $reply->user->profile_picture) ? '/storage/profile/' . $reply->user->profile_picture : asset('media/avatars/avatar3.jpg') }}" alt="">
                                                    @endif
                                                </a>
                                                <div class="media-body">
                                                    <a class="font-w600" href="javascript:void(0)">
                                                        @if($reply->user)
                                                        {{ $reply->user->name}}
                                                        @elseif($reply->admin)
                                                        {{ $reply->admin->name }}
                                                        @endif
                                                    </a>
                                                    <p class="my-1">{{ $reply->comment }}</p>
                                                    <a class="mr-1" href="javascript:void(0)">Like</a>
                                                    <a class="mr-1" href="javascript:void(0)" onclick="deleteReply('{{ $reply->id }}')">Delete</a>
                                                    <span class="text-muted"><em>{{ Carbon\Carbon::createFromDate($reply->created_at)->diffForHumans()}}</em></span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="form-group" id="reply">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-alt" id="comment" placeholder="Write a comment...">
                                            <div class="input-group-append" style="cursor:pointer" onclick="storeComment()">
                                                <span class="input-group-text input-group-text-alt">
                                                    <i class="fas fa-paper-plane"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Comments -->

                                @php
                                $course_review = App\CourseReview::where('email', Auth::user()->email)->where('course_id', $id)->first();
                                @endphp

                                <div class="modal" id="add_review" tabindex="-1" role="dialog" aria-labelledby="add_review" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">

                                            <div class="block block-rounded block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Review</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close" onclick="closeReviewModal()">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="block-content font-13">
                                                    <div class="alert alert-success text-left d-none" id="success_review">
                                                        Your review saved successfully!
                                                    </div>
                                                    <ul class="alert alert-danger text-left d-none" id="error_review"></ul>
                                                </div>
                                                <div class="block-content font-size-sm">
                                                    <div class="row">
                                                        <div class="col mt-2">
                                                            <p>Click on the star to review</p>
                                                        </div>
                                                        <div class="col">
                                                            <span class="mt-1 mb-3 d-flex justify-content-center cursor-pointer">
                                                                <i class="fa fa-star fa-2x {{ $course_review && $course_review->score >= 1 ? 'colored-star' : 'blank-star'}}" onclick="addFirstStar()" id="first_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $course_review && $course_review->score >= 2 ? 'colored-star' : 'blank-star'}}" onclick="addSecondStar()" id="second_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $course_review && $course_review->score >= 3 ? 'colored-star' : 'blank-star'}}" onclick="addThirdStar()" id="third_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $course_review && $course_review->score >= 4 ? 'colored-star' : 'blank-star'}}" onclick="addForthStar()" id="forth_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $course_review && $course_review->score >= 5 ? 'colored-star' : 'blank-star'}}" onclick="addFifthStar()" id="fifth_star"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="block-content block-content-full text-right border-top">
                                                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" onclick="closeReviewModal()">Close</button>
                                                    <button type="button" class="btn btn-primary" id="save_review">Ok</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reviews -->
                                <div class="tab-pane pull-x font-size-sm comment-padding" id="ecom-product-reviews" role="tabpanel">
                                    <!-- Average Rating -->
                                    <div class="block block-rounded bg-body-light">
                                        <div class="block-content text-center">
                                            <p class="mb-2" id="stars">
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                            </p>
                                            <p>
                                                <strong>{{ $average_score }}</strong>/5.0 out of <strong>5</strong> Ratings
                                            </p>
                                            <button type="button" class="btn btn-sm btn-primary mb-2" data-toggle="modal" data-target="#add_review">Add your review</button>
                                        </div>
                                    </div>
                                    <!-- END Average Rating -->

                                    <!-- Ratings -->
                                    @foreach($course_reviews as $review)
                                    @php
                                    $email = $review->email;
                                    $user = App\User::where('email', $email)->first();
                                    $score = $review->score;
                                    @endphp
                                    <div class="media push">
                                        <a class="mr-3" href="javascript:void(0)">
                                            <img class="img-avatar img-avatar32" src="{{ $user->profile_picture ? '/storage/profile/' . $user->profile_picture : asset('media/avatars/avatar1.jpg') }}" alt="">
                                        </a>
                                        <div class="media-body">
                                            <span>
                                                <i class="fa fa-star {{ $score >= 1 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i class="fa fa-star {{ $score >= 2 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i class="fa fa-star {{ $score >= 3 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i class="fa fa-star {{ $score >= 4 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i class="fa fa-star {{$score >= 5 ? 'colored-star' : 'blank-star' }}"></i>
                                            </span>
                                            <a class="font-w600" href="javascript:void(0)">{{ $user->name }}</a>
                                            <span class="text-muted"><em>{{ Carbon\Carbon::createFromDate($review->created_at)->diffForHumans()}}</em></span>
                                        </div>
                                    </div>
                                    @endforeach

                                    <!-- END Ratings -->
                                </div>
                                <!-- END Reviews -->
                            </div>
                        </div>
                        @endif
                        <!-- END Lessons -->
                    </div>
                    <div class="col-xl-4">

                        <!-- Course Info -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default text-center">
                                <h3 class="block-title">About This Course</h3>
                            </div>
                            <div class="block-content">
                                <table class="table table-striped table-borderless font-size-sm">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <i class="fa fa-fw fa-book mr-1"></i> {{ count($course_headings) ?
                                                count($all_course_heading_contents ) : '0'}} Lessons
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa fa-fw fa-clock mr-1"></i> {{ round($course->duration /
                                                3600, 1)}} hours
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa fa-fw fa-heart mr-1"></i> {{ $course->like_count }}
                                                Favorites
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa fa-fw fa-comment mr-1"></i> {{ $course->comments->count() + $course->replies->count() }}
                                                Comments
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa fa-fw fa-calendar mr-1"></i> {{
                                                Carbon\Carbon::parse($course->created_at)->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        @if(count($course_labels))
                                        <tr>
                                            <td>
                                                <i class="fa fa-fw fa-tags mr-1"></i>
                                                @foreach($course_labels as $label)
                                                <a class="badge badge-primary" href="javascript:void(0)">{{
                                                    $label->label }}</a>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END Course Info -->
                        @php
                        $course_participant_emails = App\CourseParticipant::where('course_id', $id)->where('progress_percentage', 100)->pluck('email')->toArray();
                        $course_participants = App\User::whereIn('email', $course_participant_emails)->get();
                        @endphp

                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">
                                    Course Accomplishments
                                </h3>
                            </div>
                            <div class="block-content">
                                <div class="js-slider text-center" data-autoplay="true" data-dots="true" data-arrows="true" data-slides-to-show="3">
                                    @foreach($course_participants as $participant)
                                    <div class="py-3">
                                        <img class="img-avatar" src="{{ $participant->profile_picture ? '/storage/profile/' . $participant->profile_picture : asset('media/avatars/avatar1.jpg') }}" alt="">
                                        <div class="mt-2 fw-semibold">{{ $participant->name }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END Page Content -->

            <!-- Get Started -->
            <div class="content content-boxed">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="block block-rounded">
                            <div class="block-header">
                                <h3 class="block-title">Participants Table</h3>
                            </div>
                            <div class="block-content block-content-full">
                                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                <table class="table table-bordered table-striped table-vcenter /*js-dataTable-full*/" id="example">
                                    <thead>
                                        <tr>
                                            <th class="text-center font-13" style="width: 80px;">SL#</th>
                                            <th class="font-13">Name</th>
                                            <th class="d-none d-sm-table-cell font-13" style="width: 30%;">Employee ID</th>
                                            <th class="d-none d-sm-table-cell font-13" style="width: 30%;">Department</th>
                                            <th class="d-none d-sm-table-cell font-13" style="width: 15%;">Duration</th>
                                            <th class="font-13" style="width: 15%;">Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach($participants as $p)
                                        @php
                                        $user_name = App\User::where('email', $p->email)->value('name');
                                        $user_employee_id = App\User::where('email', $p->email)->value('employeeid');
                                        $user_department = App\User::where('email', $p->email)->value('deptid');
                                        @endphp
                                        <tr>
                                            <td class="text-center font-13">{{ $i }}</td>
                                            <td class="font-w600 font-13">
                                                <p>{{ $user_name }}</p>
                                            </td>
                                            <td class="d-none d-sm-table-cell font-13">
                                                {{ $user_employee_id }}
                                            </td>
                                            <td class="d-none d-sm-table-cell font-13">
                                                {{ $user_department }}
                                            </td>
                                            <td class="d-none d-sm-table-cell font-13">
                                                <span class="badge badge-success">{{ round($p->duration / 60) }} min</span>
                                            </td>
                                            <td>
                                                <em class="text-muted font-13">{{ $p->progress_percentage }}%</em>
                                            </td>
                                        </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- END Get Started -->
        </main>

        <!-- END Main Container -->

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Apps</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="row gutters-tiny">
                                <div class="col-6">
                                    <!-- CRM -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="javascript:void(0)">
                                        <div class="block-content text-center">
                                            <i class="si si-speedometer fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                CRM
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END CRM -->
                                </div>
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="javascript:void(0)">
                                        <div class="block-content text-center">
                                            <i class="si si-rocket fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Products
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="javascript:void(0)">
                                        <div class="block-content text-center">
                                            <i class="si si-plane fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Sales
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Payments -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="javascript:void(0)">
                                        <div class="block-content text-center">
                                            <i class="si si-wallet fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Payments
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

</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });

</script>
<script type="text/javascript">
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let reply_comment_id = null;

    function confirmdelete(id, route, text) {
        route = route.replace(':id', id);

        mess1 = "Are you sure?";
        x = confirm(mess1);
        if (x == true) {
            mess2 = `${text} will be deleted, Press OK to continue.`;
            y = confirm(mess2);
            if (y == true) {
                $.ajax({
                    url: route
                    , type: 'GET'
                    , success: function() {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                    , error: function(err) {
                        console.log(err);
                    }

                });

            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function confirmdeletecontent(id) {
        let delete_route = "{{ route('elearning.delete_course_content', ':id')}}";
        confirmdelete(id, delete_route, 'Content');
    }

    function confirmdeleteheading(id) {
        let delete_route = "{{ route('elearning.delete_course_heading', ':id')}}";
        confirmdelete(id, delete_route, 'The topic title and its content names');
    }

    function showEditHeadingModal(id) {
        $('#edit_heading' + id).modal('show');
    }

    function showEditContentModal(id) {
        $('#edit_content' + id).modal('show');
    }

    $(".save_heading").on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let update_route = "{{ route('elearning.update_course_heading')}}";
        doEdit(id, $('#dangerEditHeadingBtn' + id), $('#successEditHeadingBtn' + id), '#edit_heading_form', update_route, '#heading_id', '#edit_heading_name');
    });

    $(".save_content").on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let update_route = "{{ route('elearning.update_course_content')}}";
        doEdit(id, $('#dangerEditContentBtn' + id), $('#successEditContentBtn' + id), '#edit_content_form', update_route, '#content_id', '#edit_content_name');
    });



    function doEdit(id, error_selector, success_selector, form_selector, update_route, form_id, form_name) {
        form_id = $(form_id + id).val();
        form_name = $(form_name + id).val();

        $.ajax({
            url: update_route
            , headers: {
                'accept': 'application/json'
            }
            , type: 'POST'
            , data: {
                id: form_id
                , name: form_name
                , _token: CSRF_TOKEN
            }
            , success: function() {
                error_selector.addClass('d-none');
                error_selector.removeClass('d-block');

                success_selector.addClass('d-block');
                success_selector.removeClass('d-none');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }
            , error: function(err) {
                success_selector.addClass('d-none');
                success_selector.removeClass('d-block');

                error_selector.addClass('d-block');
                error_selector.removeClass('d-none');

                if (err.status === 500) {
                    error_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }

            }

        });

    }

    function storeComment() {
        let comment = $('#comment').val();
        let store_comment_url = "{{ route('comment.store') }}";
        let course_id = "{{ $id }}";

        $.ajax({
            url: store_comment_url
            , type: 'POST'
            , headers: {
                'accept': 'application/json'
            }
            , data: {
                comment: comment
                , course_id: course_id
                , reply_id: reply_comment_id
                , _token: CSRF_TOKEN
            }
            , success: function() {
                $('#comment').val('');
                window.location.reload();
            }
            , error: function(err) {
                console.log(err);

            }

        });
        reply_comment_id = null;

    }

    function reply(id) {
        reply_comment_id = id;
        $('#comment').focus();
    }

    function deleteComment(id) {
        let delete_comment_url = "{{ route('comment.destroy', ':id') }}";
        delete_comment_url = delete_comment_url.replace(':id', id);
        $.ajax({
            url: delete_comment_url
            , type: 'GET'
            , headers: {
                'accept': 'application/json'
            }
            , success: function() {
                window.location.reload();
            }
            , error: function(err) {
                console.log(err);

            }

        });

    }

    function deleteReply(id) {
        let delete_reply_url = "{{ route('comment.delete_reply', ':id') }}";
        delete_reply_url = delete_reply_url.replace(':id', id);

        $.ajax({
            url: delete_reply_url
            , type: 'GET'
            , headers: {
                'accept': 'application/json'
            }
            , success: function() {
                window.location.reload();
            }
            , error: function(err) {
                console.log(err);

            }

        });

    }

    function addFirstStar() {
        $('#first_star').removeClass('blank-star');
        $('#first_star').addClass('colored-star');

        $('#second_star').removeClass('colored-star');
        $('#second_star').addClass('blank-star');

        $('#third_star').removeClass('colored-star');
        $('#third_star').addClass('blank-star');

        $('#forth_star').removeClass('colored-star');
        $('#forth_star').addClass('blank-star');

        $('#fifth_star').removeClass('colored-star');
        $('#fifth_star').addClass('blank-star');
    }

    function addSecondStar() {
        $('#first_star').removeClass('blank-star');
        $('#first_star').addClass('colored-star');

        $('#second_star').removeClass('blank-star');
        $('#second_star').addClass('colored-star');

        $('#third_star').removeClass('colored-star');
        $('#third_star').addClass('blank-star');

        $('#forth_star').removeClass('colored-star');
        $('#forth_star').addClass('blank-star');

        $('#fifth_star').removeClass('colored-star');
        $('#fifth_star').addClass('blank-star');
    }

    function addThirdStar() {
        $('#first_star').removeClass('blank-star');
        $('#first_star').addClass('colored-star');

        $('#second_star').removeClass('blank-star');
        $('#second_star').addClass('colored-star');

        $('#third_star').removeClass('blank-star');
        $('#third_star').addClass('colored-star');

        $('#forth_star').removeClass('colored-star');
        $('#forth_star').addClass('blank-star');

        $('#fifth_star').removeClass('colored-star');
        $('#fifth_star').addClass('blank-star');
    }

    function addForthStar() {
        $('#first_star').removeClass('blank-star');
        $('#first_star').addClass('colored-star');

        $('#second_star').removeClass('blank-star');
        $('#second_star').addClass('colored-star');

        $('#third_star').removeClass('blank-star');
        $('#third_star').addClass('colored-star');

        $('#forth_star').removeClass('blank-star');
        $('#forth_star').addClass('colored-star');

        $('#fifth_star').removeClass('colored-star');
        $('#fifth_star').addClass('blank-star');
    }

    function addFifthStar() {
        $('#first_star').removeClass('blank-star');
        $('#first_star').addClass('colored-star');

        $('#second_star').removeClass('blank-star');
        $('#second_star').addClass('colored-star');

        $('#third_star').removeClass('blank-star');
        $('#third_star').addClass('colored-star');

        $('#forth_star').removeClass('blank-star');
        $('#forth_star').addClass('colored-star');

        $('#fifth_star').removeClass('blank-star');
        $('#fifth_star').addClass('colored-star');
    }

    let success_review_selector = $('#success_review');
    let error_review_selector = $('#error_review');

    $('#save_review').on('click', function(e) {
        e.preventDefault();

        let review_url = "{{ route('elearning.review') }}";
        let id = "{{ $id }}";
        let score = 0;
        if ($('#first_star').hasClass('colored-star') &&
            $('#second_star').hasClass('blank-star') &&
            $('#third_star').hasClass('blank-star') &&
            $('#forth_star').hasClass('blank-star') &&
            $('#fifth_star').hasClass('blank-star')) {
            score = 1;
        } else if ($('#second_star').hasClass('colored-star') &&
            $('#first_star').hasClass('colored-star') &&
            $('#third_star').hasClass('blank-star') &&
            $('#forth_star').hasClass('blank-star') &&
            $('#fifth_star').hasClass('blank-star')) {
            score = 2;
        } else if ($('#third_star').hasClass('colored-star') &&
            $('#first_star').hasClass('colored-star') &&
            $('#second_star').hasClass('colored-star') &&
            $('#forth_star').hasClass('blank-star') &&
            $('#fifth_star').hasClass('blank-star')) {
            score = 3;
        } else if ($('#forth_star').hasClass('colored-star') &&
            $('#first_star').hasClass('colored-star') &&
            $('#second_star').hasClass('colored-star') &&
            $('#third_star').hasClass('colored-star') &&
            $('#fifth_star').hasClass('blank-star')) {
            score = 4;
        } else if ($('#fifth_star').hasClass('colored-star') &&
            $('#first_star').hasClass('colored-star') &&
            $('#second_star').hasClass('colored-star') &&
            $('#third_star').hasClass('colored-star') &&
            $('#forth_star').hasClass('colored-star')) {
            score = 5;
        }

        $.ajax({
            url: review_url
            , type: 'POST'
            , data: {
                'course_id': id
                , 'score': score
                , '_token': CSRF_TOKEN
            }
            , headers: {
                'accept': 'application/json'
            }
            , success: function() {
                success_review_selector.removeClass('d-none');
                success_review_selector.addClass('d-block');
                error_review_selector.removeClass('d-block');
                error_review_selector.addClass('d-none');

                setTimeout(function() {
                    location.reload(true);
                }, 3000);

            }
            , error: function(err) {
                success_review_selector.addClass('d-none');
                success_review_selector.removeClass('d-block');

                error_review_selector.addClass('d-block');
                error_review_selector.removeClass('d-none');

                if (err.status === 500) {
                    error_review_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_review_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }

        });

    })



    function closeReviewModal() {
        success_review_selector.addClass('d-none');
        success_review_selector.removeClass('d-block');
        error_review_selector.addClass('d-none');
        error_review_selector.removeClass('d-block');

        $('#first_star').removeClass('colored-star');
        $('#second_star').removeClass('colored-star');
        $('#third_star').removeClass('colored-star');
        $('#forth_star').removeClass('colored-star');
        $('#fifth_star').removeClass('colored-star');
    }

    let avarage_score = "{{ $average_score }}";

    document.getElementById("stars").innerHTML = getStars(avarage_score);

    function getStars(rating) {

        // Round to nearest half
        rating = Math.round(rating * 2) / 2;
        let output = [];

        // Append all the filled whole stars
        for (var i = rating; i >= 1; i--)
            output.push('<i class="fa fa-2x fa-star colored-star" aria-hidden="true"></i>&nbsp;');

        // If there is a half a star, append it
        if (i == .5) {
            // output.push('<i class="fa fa-2x fa-star-half" aria-hidden="true"></i>&nbsp;');
            output.push(`<span class="fa-stack mb-12">
                <i class="fa fa-fw fa-2x fa-star-half fa-stack-1x"></i>
                <i class="fa fa-fw fa-2x fa-star fa-stack-1x"></i>
            </span>`);

        }


        // Fill the empty stars
        for (let i = (5 - rating); i >= 1; i--)
            output.push('<i class="fa fa-2x fa-star blank-star" aria-hidden="true"></i>&nbsp;');

        return output.join('');

    }


    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $('[data-toggle="tooltip"]').tooltip();

</script>

</html>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#example').dataTable({
            "aLengthMenu": [
                [5, 10, 15, 20]
                , [5, 10, 15, 20]
            ]
            , "order": [
                [1, "desc"]
            ]
        });

    });

</script>

@endsection
