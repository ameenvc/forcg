@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<style>
    table {
        table-layout: fixed !important;
        width: 100% !important;
    }

    th,
    td {
        overflow: hidden;
        word-break: break-word;
        white-space: normal;
    }
    .note-toolbar .note-icon-caret,
    .note-toolbar i {
        font-size: 10px;
    }
</style>
<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>
<body>

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Customer Complaint</li>

                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Edit Complaint</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/complaintmgmt">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:100%">
        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">
                    <form id="editRequestForm" class="mb-5" action="{{ route('editrequestcomplaint') }}" method="POST">
                        {{ csrf_field() }}

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

                        @if(count($data)>0)

                        @foreach($data as $dat)
                        @endforeach
                        @endif


                        @if(count($data)>0)
                        <table class="table table-bordered table-striped table-vcenter" id="dynamicTable" style="width:100%;">

                            <thead style="background-color:#FFFFFF">
                                <tr>
                                    <th class="text-center" style="font-size:13px;text-transform:capitalize; width: 10%">Title</th>
                                    <th class="text-center" style="font-size:13px;text-transform:capitalize; width: 30%">Complaint</th>
                                    <th class="text-center" style="font-size:13px;text-transform:capitalize; width: 10%">Type</th>
                                    <th class="text-center" style="font-size:13px;text-transform:capitalize; width: 30%">Recommonded action</th>
                                    <th class="text-center" style="font-size:13px;text-transform:capitalize; width: 10%">Responsible department</th>
                                    <th class="text-center" style="font-size:13px;text-transform:capitalize;width: 10%">Responsiblility</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($data as $dat1)
                                <tr>
                                    <td class="text-center font-size-sm">
                                        <input type="text" style="font-size:13px;" class="form-control" name="title[]" value="{{$dat1->title}}">
                                    </td>

                                    <td class="font-size-sm" style="vertical-align: top">
                                        <textarea id="complaint{{$dat1->id}}" type="text" style="font-size:13px;" cols="30" rows="5" class="form-control complaint" name="complaint[]"> {{$dat1->complaint}}</textarea>
                                        <div id="complaint{{$dat1->id}}-error" class="text-danger" style="display:none;font-size:12px;">Complaint Description field is required.</div>
                                    </td>

                                    <td class="text-center " style="vertical-align: top">
                                        <select class="form-control" style="font-size: 13px;" name="type[]" placeholder="Choose Type">
                                            <option value="High" {{ $dat1->type === 'High' ? 'selected' : ''}}>High</option><br>
                                            <option value="Moderate" {{ $dat1->type === 'Moderate' ? 'selected' : ''}}>Moderate</option><br>
                                            <option value="Low" {{ $dat1->type === 'Low' ? 'selected' : ''}}>Low</option><br>
                                        </select>
                                    </td>

                                    <td class="font-size-sm" style="vertical-align: top">
                                        <textarea id="action{{$dat1->id}}" type="text" style="font-size:13px;" cols="30" rows="5" class="form-control action" name="action[]"> {{$dat1->action}}</textarea>
                                        <div id="action{{$dat1->id}}-error" class="text-danger" style="display:none;font-size:12px;">Recommended Action field is required.</div>
                                    </td>

                                    <td class="text-center font-size-sm" style="vertical-align: top">
                                        <select class="form-control" name="department[]" style="font-size: 13px;" required="true" placeholder="Choose a Department">
                                            <option value="" selected disabled>Choose Department</option>
                                            @if(!(DB::table('departments')->where('id',$dat1->dept)->first()))
                                            <option value="{{$dat1->dept}}" selected>{{$dat1->dept}}</option>
                                            @endif
                                            @if(count($departments)>0)
                                            @foreach($departments as $dep)
                                            <option value="{{$dep->id}}" {{ $dat1->dept == $dep->id ? 'selected' : '' }}> {{$dep->departmentname}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td class="text-center font-size-sm" style="vertical-align: top">

                                        <select class="js-select2 form-select w-100" name="responsibility[]" style="font-size: 13px;" required="">
                                            {{-- <option value="">Select Responsible</option> --}}
                                            @if(count($users)> 0 )
                                            @foreach($users as $user)
                                            <option value="{{$user->email}}" {{ $dat1->responsibility === $user->email ? 'selected' : '' }}>{{ $user->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>


                                    <div class="form-group">

                                        <input type="hidden" name="sno[]" value="{{ $dat1->id}}">

                                    </div>
                                </tr>

                                @endforeach
                            </tbody>

                        </table>
                        @endif


                        <div>

                            <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:90%;">Submit</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>


</body>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                <div class="block-content text-center">
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
                                <div class="block-content text-center">
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
                                <div class="block-content text-center">
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


</body>
</html>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ versionedCache('js/complaint/summernote_init.js') }}"></script>
    <script>
        function moveToErrorMessage($fieldId) {
            document.querySelector($fieldId)
                .closest('td')
                .scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
        }

        $(document).ready(function() {
            @foreach ($data as $d)
                initTextField('#complaint'+{{$d->id}});
                initTextField('#action'+{{$d->id}});
            @endforeach
        });
        let validateDescription;
        let validateAction;
        $('#editRequestForm').submit(function(e) {
            @foreach ($data as $d)
                validateDescription = validateSummerNoteTextField('#complaint'+{{$d->id}});
                if (!validateDescription) {
                    e.preventDefault();
                    moveToErrorMessage('#complaint'+{{$d->id}})
                    return false;
                }
                validateAction = validateSummerNoteTextField('#action'+{{$d->id}});
                if (!validateAction) {
                    e.preventDefault();
                    moveToErrorMessage('#action'+{{$d->id}})
                    return false;
                }
            @endforeach
        });
    </script>
@endsection
