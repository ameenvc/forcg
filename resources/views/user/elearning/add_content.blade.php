@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>

<body>
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">E-learning</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Content</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('elearning.course', ['id' => $id]) }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{ route('elearning.insert_content') }}" enctype="multipart/form-data" method="POST" files="true">
                            {{csrf_field()}}
                            <input type="hidden" value="{{ $course->id }}" name="course_id" id="course_id" />
                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="alert alert-warning">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('error')!!}
                            </div>
                            @endif
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Course Title</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;" value="{{ $course->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Do you need to add
                                    this content under existing topic? <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="yes_add_content" name="radio_add_content" onclick="display()"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no_add_content" name="radio_add_content" onclick="display()"><label style="font-size: 13px;">No</label>
                                </div>
                            </div>
                            <div class="form-group d-none" style="width:49%;" id="topic">
                                <label for="topic_title" class="col-sm-12 col-form-label" style="font-size:13px;">New Topic Title</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="topic_title" name="topic_title" style="font-size:13px;">
                                </div>
                            </div>
                            <div class="form-group" style="display:none;width:49%;" id="heading_div">
                                <label for="heading" class="col-sm-12 col-form-label" style="font-size:13px;">Topic
                                    Title</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="heading" id="heading" style="font-size:13px;" placeholder="Choose Heading">
                                        <option value="0" selected="" disabled style="font-size:13px;">Choose one
                                            from the existing topics</option>
                                        @if(count($course_headings))
                                        @foreach($course_headings as $ch)
                                        <option value="{{ $ch->id }}" style="font-size:13px;">{{ $ch->heading }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label for="content_name" class="col-sm-12 col-form-label" style="font-size:13px;">Content name <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="content_name" name="content_name" style="font-size:13px;" required>
                                </div>
                            </div>
                            <div class="form-group" style="width:98.5%;" id="rich_text_div1">
                                <label for="rich_text" class="col-sm-12 col-form-label" style="font-size:13px;">Rich Text field <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea class="js-summernote" name="rich_text" id="rich_text"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px; margin-left:86%;">Add</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->
        </main>
        <!-- END Main Container -->

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
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-square fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Closed Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">

                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' style="margin-top:6px">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let topic_selector = $('#topic');
            let heading_selector = $('#heading_div');

            function display() {
                if (document.getElementById('no_add_content').checked) {
                    topic_selector.addClass('d-inline-block');
                    topic_selector.removeClass('d-none');
                    heading_selector.addClass('d-none');
                    heading_selector.removeClass('d-inline-block');
                } else if (document.getElementById('yes_add_content').checked) {
                    topic_selector.addClass('d-none');
                    topic_selector.removeClass('d-inline-block');
                    heading_selector.addClass('d-inline-block');
                    heading_selector.removeClass('d-none');
                }

            }

        </script>



        @endsection
        @section('js')

        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#rich_text').summernote({
                    height: 200
                , });

            });

        </script>


        @endsection
