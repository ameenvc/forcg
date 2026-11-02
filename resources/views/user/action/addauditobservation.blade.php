@extends('layouts.multiselect')

@section('content')
    {{-- <main id="main-container"> --}}

    <style>
        .paragraph-style {
            font-weight: bold;
            font-size: 20px;
            color: #191e3b;
            margin-top: 30px;
            margin-left: -1px;
        }

        #res-search {
            top: 100%;
            /*left: 0;*/
            background-color: white !important;
            z-index: 1;
            border: 1px solid grey;
            width: 93%;
            margin: auto;
            border-top: transparent;
            padding-right: 0 !important;
            overflow: auto;
            border-radius: 0 0 2px 2px;
        }

        #res-search li {
            width: 100%;
            list-style-type: none;
            font-size: 13px;
            padding: 2px;
        }

        #res-search li:hover {
            background-color: #0a90eb;
            color: white;
        }
    </style>

    <!-- START Hero -->
    <!-- <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"
                 style="height:10px;">
                <p class="paragraph-style">Internal Audit Observation Form</p>
            </div>
        </div>
    </div> -->

    <div class="bg-body-light">
         <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Audit Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Audit Observations</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('action.viewlist') }}">Back</a>
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
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-10">

                        <!-- Form Horizontal - Default Style -->
                        <?php $f = 0; ?>
                        @foreach($auditors as $auditor)  <?php //dd($auditors); dd(Auth::user()->email); ?>
                        @if(Auth::user()->email == $auditor)
                            <?php $f = 1; ?>
                            @break;
                        @else
                            <?php $f = 0; ?>
                        @endif
                        <?php //break; ?>
                        @endforeach

                        @if($f==1)
                            <form class="mb-5" action="{{url('/user/actionsystem/insertobservations')}}"
                                  enctype="multipart/form-data" method="POST" files="true">
                                {{csrf_field()}}
                                <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                                <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
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
                                @if($dat)

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" for="raised" style="font-size:13px;">Raised
                                                by</label>
                                            <div class="col-sm-12">
                                                <input type="riskname" class="form-control" id="name"
                                                       style="font-size:13px;" name="name"
                                                       value="{{Auth::user()->name}}" readonly="">
                                            </div>
                                        </div>
                                        <?php  $f = 0;
                                        $c = $count;
                                        ?>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" for="category"
                                                   style="font-size:13px;">Category</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="category"
                                                       style="font-size:13px;" name="category" value="Internal Audit"
                                                       readonly>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" for="department"
                                                   style="font-size:13px;">Department</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="department"
                                                       name="department" value="{{$departmentname}}" readonly=""
                                                       style="font-size:13px;">
                                            </div>
                                        </div> -->

                                        <input type="hidden"  name="department" id="department" value="{{$departmentname}}">
                                        <input type="hidden"  name="listing_category" id="listing_category" value="{{$listing_category}}">
                                        <input type="hidden" name="auditid" value="{{$dat->id}}">

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" for="department"
                                                   style="font-size:13px;">{{$listing_category}}</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="listing_name"
                                                       name="listing_name" value="{{$listing_name}}" readonly=""
                                                       style="font-size:13px;">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                                            <div class="col-sm-12">
                                                <select class="form-control" name="type" required="true"
                                                        style="font-size:13px;" placeholder="Choose a Type">
                                                    @if(count($type)>0)
                                                        @foreach($type as $typ)
                                                            <option value="{{$typ->order}}"> {{$typ->name}}</option></br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">
                                                Title <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" style="font-size:13px;"
                                                       id="title" name="title" placeholder="Enter title.."
                                                       value="{{ old('title') }}">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" style="font-size:13px;"
                                                   for="observation">Observation <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <textarea type="text" class="form-control" style="font-size:13px;"
                                                          id="observation" name="observation"
                                                          placeholder="Enter observation"
                                                          >{{ old('observation') }}</textarea>
                                            </div>
                                        </div>
                                        <!-- Potential risks -->
                                        <div class="form-group " style="display:inline-block;width:99%;">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="related_risks">Potential risks</label>
                                        <div class="col-sm-12">
                                            @if(isset($riskList))
                                                <select class="form-control js-select2" id="related_risks" name="related_risks[]"
                                                        style="font-size: 13px;" multiple>
                                                    @foreach($riskList as $riskItem)
                                                        <option value="{{ $riskItem->Rid }}">
                                                            {{ $riskItem->riskname . ' - ' . $riskItem->grp_id }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" style="font-size:13px;"
                                                   for="agreedaction">Recommended action</label>
                                            <div class="col-sm-12">
                                                <textarea type="text" class="form-control" style="font-size:13px;"
                                                          id="agreedaction" name="agreedaction"
                                                          placeholder="Enter recommended action"
                                                          >{{ old('agreedaction') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" style="font-size:13px;"
                                                   for="responsibility">Responsibility <span class="text-danger">*</span></label>
                                            <div class="col-sm-12" style="position: relative;">
                                                {{--                                                <input type="text" class="form-control" style="font-size:13px;"--}}
                                                {{--                                                       id="responsibility" name="responsi">--}}
                                                {{--                                                <ul id="res-search" class="px-1 "--}}
                                                {{--                                                    style="max-height:100px;overflow:hidden;position: absolute;">--}}

                                                {{--                                                </ul>--}}
                                                {{--                                                <input type="hidden"name="responsibility"id="hresponsibility">--}}
                                                {{--                                            --}}
                                                <div class="mb-4">
                                                    <select class="js-select2 form-select" id="responsibility"
                                                            name="responsibility" style="width: 100%;"
                                                            data-placeholder="Choose Responsibility" required>
                                                        <option></option>
                                                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                                        @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                                <option value="{{$use->email}}"> {{ $use->name }}</option>
                                                                <br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            {{--                                        <div class="col-12">--}}
                                            {{--                                            <div class="mb-4">--}}
                                            {{--                                                <select class="js-select2 form-select" id="example-select2" name="example-select2" style="width: 100%;" data-placeholder="Choose one..">--}}
                                            {{--                                                    @if(count($user)> 0 )--}}
                                            {{--                                                        @foreach($user as $use)--}}
                                            {{--                                                            <option  value=" {{$use->email}}"> {{ $use->name }}</option><br>--}}
                                            {{--                                                        @endforeach--}}
                                            {{--                                                    @endif--}}
                                            {{--                                                </select>--}}
                                            {{--                                            </div>--}}
                                            {{--                                        </div>--}}
                                            {{--                                        <div class="col-sm-12">                                                           --}}
                                            {{--                                            <select class="form-control" name = "responsibility" required="true" style="font-size:13px;" placeholder="Choose Responsibility">    --}}
                                            {{--                                                @if(count($user)> 0 )--}}
                                            {{--                                                    @foreach($user as $use)--}}
                                            {{--                                                        <option  value=" {{$use->email}}"> {{ $use->name }}</option><br>--}}
                                            {{--                                                    @endforeach--}}
                                            {{--                                                @endif--}}
                                            {{--                                            </select>--}}
                                            {{--                                        </div>--}}
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" for="date" style="font-size:13px;">
                                                Due date <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-12">
                                                <input type="date" class="js-datepicker form-control"
                                                       style="font-size:13px;" id="date" name="duedate"
                                                       data-week-start="1" data-autoclose="true"
                                                       data-today-highlight="true" data-date-format="dd/mm/yy"
                                                       placeholder="dd/mm/yy">
                                                <input type="hidden" name="issueid" value="{{$dat->aud_id}}">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="notification"
                                                   style="font-size:13px;">Send notifications to:</label>
                                            <div class="col-sm-12">
                                                <select class="js-select2 form-control" id="example-select2-multiple"
                                                        style="font-size:13px;" name="notification[]" multiple>
                                                    <option></option>
                                                    @if(count($user)> 0 )
                                                        @foreach($user as $use)
                                                            <option value=" {{$use->email}}"
                                                                    style="font-size:13px;"> {{ $use->name }}</option>
                                                            <br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="frequency"
                                                   style="font-size:13px;">Email reminder for action:</label>
                                            <div class="col-sm-12">
                                                <select class="js-select2 form-control" id="frequency"
                                                        name="frequency[]" style="font-size:13px;" multiple>
                                                    <option></option>
                                                    <option value=" Monthly"> Monthly</option>
                                                    <br>
                                                    <option value=" Weekly"> Weekly</option>
                                                    <br>
                                                    <option value=" 2 Days Before"> 2 Days Before</option>
                                                    <br>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" style="font-size:13px;width:100%;"
                                                   for="issue_file">Attach issue related files(avoid '#' and '&' in file
                                                names)</label>
                                            <div class="col-sm-12">
                                                <input type="file" class="form-control" style="font-size:13px;"
                                                       id="issue_file" name="issue_file[]" multiple>
                                            </div>
                                        </div>

                                        <br>

                                        <div class="form-group row">
                                            <div class="col-sm-8 ml-auto">
                                                <button type="submit" class="btn btn-primary" onclick="return ddldd()"
                                                        style="font-size:13px; margin-left:84%; ">Add
                                                </button>
                                            </div>
                                        </div>

                                @endif
                            </form>
                        @elseif($f==0)
                            <div class="row justify-content-center">
                                <h2>User Denied </h2>
                            </div>
                    @endif
                    <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->
        <!-- END Page Content -->
        </main>
        <script>
            let data = [];
            let val = [];
            @if(count($user)> 0 )
            @foreach($user as $use)
            data.push('{{$use->name}}');
            val.push('{{$use->email}}');
                @endforeach
                @endif
            const searchInput = document.getElementById("responsibility");
            // const resultList = document.getElementById("res-search");
            // const hrespons = document.getElementById("responsibility");


            function updateResults() {
                const searchText = searchInput.value.toLowerCase();
                const filteredResults = data.filter(item => item.toLowerCase().startsWith(searchText));

                // Clear previous results
                searchInput.innerHTML = "";

                // Display filtered results
                filteredResults.forEach(item => {
                    const listItem = document.createElement("option");

                    listItem.textContent = item;
                    const index = data.indexOf(item);
                    if (index !== -1) {
                        listItem.setAttribute("value", data[index]);
                        // hrespons.setAttribute("value", val[index])

                    }
                    searchInput.appendChild(listItem);

                    // Add a click event listener to each list item
                    listItem.addEventListener("click", () => {
                        // Set the value of the input to the clicked list item's value attribute
                        searchInput.value = listItem.getAttribute("value")

                        // Hide the list after setting the input value
                        // resultList.style.display = "none";
                    });
                });
            }

            // Add a click event listener to the document to hide the list when clicking outside the input or the list
            // document.addEventListener("click", (event) => {
            //     const target = event.target;
            //     if (target !== searchInput && target !== resultList) {
            //         resultList.style.display = "none";
            //     }
            // });

            // Add an input event listener to the search input to update the list
            searchInput.addEventListener("input", () => {
                // resultList.style.display = "block"; // Show the list when the input is updated
                updateResults();
            });
        </script>
        <script>
            function ddldd() {
                if ($("#issue_file").val() == '') {
                    mess2 = 'Are you sure to submit without attachment?';
                    y = confirm(mess2);
                    if (y == true) {
                    } else {
                        return false;
                    }
                } else {
                    mess1 = "Are you sure?";
                    x = confirm(mess1);
                    if (x == true) {
                    } else {
                        return false;
                    }
                }
            }
        </script>
        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
             aria-hidden="true">
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
                                <!--<div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/actionsystem/addissue">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                                        Add Issue
                                                    </p>
                                            </div>
                                        </a>
                                    </div>-->
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0"
                                       href="/user/actionsystem/folders">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0"
                                       href="/user/actionsystem/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open NCRs
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>

                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-2"
                                       href="/user/actionsystem/analysis">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>


                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0"
                                       href='/user/internalaudit/analysis'>
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Audit Schedule Analysis
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
        <!-- END Apps Modal -->
        <script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
        <script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
        <script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
        <script>jQuery(function () {
                One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
            });</script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#sel').select2({});
            });

            $(document).ready(function () {
                $('#sel_freq').select2({});
                {{--$('#responsibility').keyup(function (e) {--}}
                {{--e.preventDefault();--}}
                {{--    let text = $('#responsibility').val();--}}
                {{--$.ajax({--}}
                {{--    type: "get",--}}
                {{--    url: "{{env('API_URL')}}get-responsibilities/"+text,--}}
                {{--    data: "data",--}}
                {{--    dataType: "json",--}}
                {{--    success: function (response) {--}}
                {{--        response.users.forEach(function(users) {--}}
                {{--            $('#res-search').append(`<li>${user}</li>`);--}}
                {{--        });--}}
                {{--    }--}}
                {{--});--}}
                {{--});--}}
            });

        </script>
@endsection
