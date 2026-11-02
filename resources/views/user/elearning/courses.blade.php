@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">

<body>
    <style>
        .select2-container {
            width: 100% !important;
            padding: 0;
        }
    </style>
    <!-- Horizontal -->
    <div id="page-container"
        class="/*sidebar-o*/ sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!-- Main Container -->
        <main id="main-container" style="padding-top: 0 !important">

            <ul id="error_selector" class="alert alert-danger mt-4" style="display:none;"></ul>

            <!-- Hero Content -->
            <div class="bg-image" style="background-image: url('/images/blue_banner.jpeg');">
                <div style="/*background: #3B4350 !important;*/">
                    <div class="content content-full overflow-hidden">
                        <div class="mt-3 mb-1 text-center">
                            <h1 class="h2 text-white mb-2 invisible" data-toggle="appear"
                                data-class="animated fadeInDown">Learn new creative skills today.</h1>
                            <h2 class="h4 font-w400 text-white-75 invisible" data-toggle="appear"
                                data-class="animated fadeInDown">Acquire New Skills and Shape Customized Courses on Our
                                E-Learning Platform"</h2>
                            <a href="#" class="btn mr-1 mb-3 text-white" style="background-color: #118987"
                                data-toggle="modal" data-target="#new_course">
                                Add New Course <i class="fa fa-fw fa-plus mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Hero Content -->


            <div class="modal fade" id="new_course" tabindex="-1" role="dialog" aria-labelledby="new_courseLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="new_courseLabel">Add New Course
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="data" method="post" action="{{ route('elearning.add_course') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">
                                    Course added successfully!
                                </div>
                                <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                                <div class="form-group">
                                    <label for="course_name" class="col-form-label" style="font-size:12px;">Course Name
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="course_name" id="course_name"
                                        style="font-size:12px;" required />
                                </div>
                                <div class="form-group">
                                    <label for="labels" class="col-form-label" style="font-size:12px;">Add
                                        Labels</label>
                                    <input type="text" class="form-control" name="labels" id="labels"
                                        style="font-size:12px;" />
                                </div>

                                <div class="form-group">
                                    <label for="department" class="col-form-label"
                                        style="font-size:12px;">Department</label>
                                    <select class="js-select2 form-control" style="font-size:13px;" id="departments"
                                        name="departments" style="width: 100%;" data-placeholder="Choose Department"
                                        multiple>
                                        @foreach($departments as $dep)
                                        <option style="font-size:13px;" value="{{ $dep->id }}">{{ $dep->departmentname
                                            }}</option><br>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="cover" class="col-form-label" style="font-size:12px;">Cover
                                        image</label>
                                    <input type="file" class="form-control" name="cover" id="cover"
                                        style="font-size:12px;" />
                                </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"
                                style="font-size:12px">Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" style="font-size:12px">Save</button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>


            <!-- Page Content -->
            <div class="content content-boxed">
                <div class="row row-deck py-4">
                    @if(count($courses))
                    @foreach($courses as $course)
                    @php
                    $dep_arr = [];
                    foreach($course->departments as $dep) {
                    $dep_arr[] = $dep->id;
                    }

                    $labels = App\CourseLabel::where('courses_id', $course->id)->pluck('label')->toArray();
                    $labels = implode(',', $labels);
                    @endphp


                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="block block-rounded block-link-pop">
                            @if($course->cover_image)
                            <div class="text-center"
                                onclick="window.location='{{ route('elearning.course', ['id' => $course->id ]) }}'"
                                style="cursor:pointer">
                                <img src="{{ '/storage/elearning/' . $course->cover_image }}" alt="image" width="100%"
                                    height="185px" />
                            </div>
                            @else
                            <div class="block-content block-content-full text-center {{ $colors_array[rand(0,10)] }}"
                                onclick="window.location='{{ route('elearning.course', ['id' => $course->id ]) }}'"
                                style="cursor:pointer">
                                <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto invisible"
                                    data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                                    <i class="{{ $icons_array[rand(0,10)] }} fa-2x text-white-75"></i>
                                </div>
                                <div class="font-size-sm text-white-75">
                                    <em>sample text</em>
                                </div>
                            </div>
                            @endif
                            <div class="block-content block-content-full">
                                <h4 class="h5 mb-1">{{ $course->name }}</h4>
                                <div class="font-size-sm text-muted">{{
                                    Carbon\Carbon::parse($course->created_at)->format('M d, Y') }} </div>

                                @if(Gate::allows('elearning-add-content'))
                                <div class="row">
                                    <div class="col">
                                        @if($course->published)
                                        <button class="btn btn-danger mt-2"
                                            onclick="unpublishCourse('{{ $course->id }}')"
                                            style="font-size:12px;">Unpublish</button>
                                        @else
                                        <button class="btn btn-primary mt-2"
                                            onclick="publishCourse('{{ $course->id }}')"
                                            style="font-size:12px;">Publish</button>
                                        @endif
                                    </div>
                                    <div class="col text-right">
                                        <div class="btn-group mt-2">
                                            <button type="button" class="btn btn-sm btn-alt-primary"
                                                data-toggle="tooltip" title="Like" id="like{{ $course->id }}"
                                                onclick="addLike('{{ $course->id }}')">
                                                <i class="far fa-fw fa-heart"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-alt-primary"
                                                data-toggle="tooltip" title="Edit"
                                                onclick="showEditModal('{{ $course->id }}')">
                                                <i class="fa fa-fw fa-pencil-alt"></i>
                                            </button>
                                            <div class="modal edit-modal fade" id="edit{{ $course->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="edit{{ $course->id }}Label"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="new_courseLabel">Edit Course
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="edit{{ $course->id }}" method="post"
                                                                action="{{ route('elearning.update_course', ['id' => $course->id ])}}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="alert alert-success text-left"
                                                                    id="successEditBtn{{ $course->id }}"
                                                                    style="display:none;font-size:12px;">
                                                                    Course edited successfully!
                                                                </div>
                                                                <ul class="alert alert-danger text-left"
                                                                    id="dangerEditBtn{{ $course->id }}"
                                                                    style="display:none;font-size:12px;"></ul>
                                                                <input type="hidden" id="id" name="id"
                                                                    value="{{ $course->id }}" />
                                                                <div class="form-group text-left">
                                                                    <label for="edit_name" class="col-form-label"
                                                                        style="font-size:12px;">Course Name <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        name="edit_name" id="edit_name{{ $course->id }}"
                                                                        style="font-size:12px;"
                                                                        value="{{ $course->name }}" required />
                                                                </div>
                                                                <div class="form-group text-left">
                                                                    <label for="edit_labels" class="col-form-label"
                                                                        style="font-size:12px;">Labels</label>
                                                                    <input type="text" class="form-control"
                                                                        name="edit_labels"
                                                                        id="edit_labels{{ $course->id }}"
                                                                        style="font-size:12px;" value="{{ $labels }}" />
                                                                </div>
                                                                <div class="form-group text-left">
                                                                    <label for="edit_departments" class="col-form-label"
                                                                        style="font-size:12px;">Department</label>
                                                                    <select class="js-select2 form-control edit-select"
                                                                        style="font-size:13px;"
                                                                        id="edit_departments{{ $course->id }}"
                                                                        name="edit_departments" style="width: 100%;"
                                                                        data-placeholder="Choose Department" multiple>
                                                                        @foreach($departments as $dep)
                                                                        <option style="font-size:13px;"
                                                                            value="{{ $dep->id }}" {{
                                                                            collect($dep_arr)->contains($dep->id) ?
                                                                            'selected':'' }}>{{ $dep->departmentname }}
                                                                        </option><br>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="form-group text-left">
                                                                    <label for="edit_cover" class="col-form-label"
                                                                        style="font-size:12px;">Cover image</label>
                                                                    <input type="file" class="form-control"
                                                                        name="edit_cover"
                                                                        id="edit_cover{{ $course->id }}"
                                                                        style="font-size:12px;" />
                                                                </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal" style="font-size:12px">Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-primary"
                                                                style="font-size:12px">Save</button>
                                                        </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-sm btn-alt-primary"
                                                data-toggle="tooltip" title="Delete"
                                                onclick="return confirmdelete('{{ $course->id }}')">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="m-auto">
                        No Course Exists yet
                    </div>
                    @endif
                </div>
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
            aria-hidden="true">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0"
                                        href="javascript:void(0)">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0"
                                        href="javascript:void(0)">
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

</html>
@endsection
@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let route = "{{ route('elearning.add_course') }}";

    let error_selector = $('#dangerBtn');
    let success_selector = $('#successBtn');

    let whole_error_selector = $('#error_selector');

    $("form#data").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('name', $('#course_name').val());
        formData.append('labels', $('#labels').val());
        formData.append('cover', $('#cover').val() ? $('#cover') : '');

        var departments = $('#departments').val();
        $.each(departments, function(i, x) {
            formData.append('departments[]', x);
        });


        $.ajax({
            url: route
            , headers: {
               'accept': 'application/json'
            }
            , type: 'POST'
            , data: formData
            , cache: false
            , contentType: false
            , processData: false
            , success: function(response) {
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
    });

</script>
<script>
    function addLike(id) {
        let addlike_route = "{{ route('elearning.add_like', ':id')}}";
        addlike_route = addlike_route.replace(':id', id);

        let like_selector = $('#like' + id);
        let auth_user_email = '{{ Auth::user()->email }}';
        like_selector.find($(".fa-heart")).removeClass('far')
        like_selector.find($(".fa-heart")).addClass('fas')

        if (localStorage.getItem('liked' + auth_user_email + '_' + id)) {
           like_selector.attr('disabled', 'disabled');
        } else {
            localStorage.setItem('liked' + auth_user_email + '_' + id , true);
            like_selector.attr('disabled', 'false');
            $.ajax({
               url: addlike_route
              , type: 'GET'
              , success: function() {
                console.log('success liked');
               }
               , error: function() {
               }
            });
        }
    }
    function publishCourse(id) {
        let publish_route = "{{ route('elearning.publish', ':id')}}";
        publish_route = publish_route.replace(':id', id);

        $.ajax({
            url: publish_route
            , type: 'GET'
            , success: function(response) {
                whole_error_selector.addClass('d-none');
                whole_error_selector.removeClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 1000);

            }
            , error: function(err) {
                whole_error_selector.addClass('d-block');
                whole_error_selector.removeClass('d-none');

                if (err.status === 500) {
                    whole_error_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                }

            }

        });

    }

    function unpublishCourse(id) {
        let unpublish_route = "{{ route('elearning.unpublish', ':id')}}";
        unpublish_route = unpublish_route.replace(':id', id);

        $.ajax({
            url: unpublish_route
            , type: 'GET'
            , success: function(response) {
                console.log('un done');
                whole_error_selector.addClass('d-none');
                whole_error_selector.removeClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 1000);

            }
            , error: function(err) {
                console.log(err);
                whole_error_selector.addClass('d-block');
                whole_error_selector.removeClass('d-none');

                if (err.status === 500) {
                    whole_error_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                }

            }

        });

    }

    /**------confirm delete course--------- */
    function confirmdelete(id) {
        let delete_route = "{{ route('elearning.delete_course', ':id')}}";
        delete_route = delete_route.replace(':id', id);

        mess1 = "Are you sure?";
        x = confirm(mess1);
        if (x == true) {
            mess2 = 'Course will be deleted, Press OK to continue.';
            y = confirm(mess2);
            if (y == true) {
                $.ajax({
                    url: delete_route
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

    function showEditModal(id) {
        $('#edit' + id).modal('show');

        let edit_error_selector = $('#dangerEditBtn' + id);
        let edit_success_selector = $('#successEditBtn' + id);

        $("form#edit" + id).submit(function(e) {
            e.preventDefault();
            let update_route = "{{ route('elearning.update_course')}}";
            var formData = new FormData(this);
            formData.append('id', id);
            formData.append('edit_name', $('#edit_name' + id).val());
            formData.append('edit_labels', $('#edit_labels' + id).val());
            formData.append('edit_cover', $('#edit_cover' + id).val() ? $('#edit_cover') : '');

            var departments = $('#edit_departments' + id).val();
            $.each(departments, function(i, x) {
                formData.append('edit_departments[]', x);
            });

            $.ajax({
                url: update_route
                , headers: {
                   'accept': 'application/json'
                }
                , type: 'POST'
                , data: formData
                , cache: false
                , contentType: false
                , processData: false
                , success: function(response) {
                    console.log('success');

                    edit_error_selector.addClass('d-none');
                    edit_error_selector.removeClass('d-block');

                    edit_success_selector.addClass('d-block');
                    edit_success_selector.removeClass('d-none');


                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);

                }
                , error: function(err) {
                    edit_success_selector.addClass('d-none');
                    edit_success_selector.removeClass('d-block');

                    edit_error_selector.addClass('d-block');
                    edit_error_selector.removeClass('d-none');

                    if (err.status === 500) {
                        edit_error_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                    } else if (err.status === 422) {
                        for (let i in err.responseJSON.errors) {
                            edit_error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                        }
                    }

                }

            });
        });
    }

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $('[data-toggle="tooltip"]').tooltip()

</script>
<script>
    let course_ids = "{{ $course_ids }}";
    course_ids = course_ids.split(',');
    for (let i = 0; i < course_ids.length; i++) {
        $(`#edit_departments${i}`).select2({
            dropdownParent: $(`#edit${i}`)
        });
    }

</script>

@endsection
