{{-- @extends('admin.datatable') --}}
@extends('layouts.multiselect')
@section('content')
<main id="main-container">
    <!-- Services -->
    <!-- Hero -->

    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:88%;margin-left:70px;margin-top:-75px">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Transfer Request Details</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">

                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/waste/transferschedule/{{$year}}">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:90%;margin-left:100px">
                <div class="row">
                    <div class="col-md-10">
                        <!-- Form Horizontal - Default Style -->
                        <form method="post" action="{{route('updatetransferrequest')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <?php
                              $id = 0;
                            ?>
                            @foreach($data as $val)

                            <table class="table table-bordered table-striped" style="margin-bottom:70px;">                
                                <tr>
                                    <td class="col-sm-4">
                                        <label class=" col-form-label" style="font-size: 13px;" for="type">Transfer Request ID</label>
                                    </td>
                                    <td class="col-sm-8">
                                       <span style="font-size:13px;margin-left:20px;"><b>{{ $val->request_no }}</b></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">
                                        <label class=" col-form-label" style="font-size: 13px;" for="type">Department</label>
                                    </td>
                                    <td class="col-sm-8">

                                        <select class="form-control" name="dept[]" id="dept" style="font-size:13px;width:350px;" required>
                                            @foreach($departments as $dep)
                                            <option value="{{$dep->id}}" {{ $dep->id == $val->department ? 'selected' : ''}}>{{$dep->departmentname}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">
                                        <label class="col-form-label" style="font-size: 13px;" for="qty">Quantity</label>
                                    </td>
                                    <td class="col-sm-8">
                                        <input class="form-control" name="qty[]" id="qty" style="font-size: 13px;" value="{{ $val->quantity }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">
                                        <label class="col-form-label" style="font-size: 13px;" for="use">Are there Useable parts in the euipment(if yes to be removed and reused)?</label>
                                    </td>
                                    <td class="col-sm-8">
                                    <input class="form-control" name="use[]" id="use" style="font-size: 13px;" value="{{ $val->useableparts}}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">
                                        <label class="col-form-label" style="font-size: 13px;" for="rev">Reviewer</label>
                                    </td>
                                    <td class="col-sm-8">
                                        <select class="js-select2 form-control" name="rev[{{$id}}][]" id="rev[{{$id}}]" style="font-size: 13px;width:350px;" multiple required>
                                            @php
                                            $reviewers = explode(',', $val->reviewer);
                                            @endphp
                                            @if(count($rev)>0)
                                            @foreach($rev as $r)
                                            <option value="{{$r->email}}" {{ (collect($reviewers)->contains($r->email)) ? 'selected':'' }}>{{$r->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="col-sm-4">
                                        <label class="col-form-label" style="font-size: 13px;" for="appr">Approver</label>
                                    </td>
                                    <td class="col-sm-8">
                                        <select class="form-control" name="appr[]" id="appr" style="font-size: 13px; width:350px;" required placeholder="Choose Approver">
                                            <option value="" selected="" disabled>Choose Approver</option>
                                            @foreach($appr as $app)
                                            <option value="{{$app->email}}" {{ $app->email == $val->approver ? 'selected' : ''}}>{{$app->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <input type="hidden" name='id[]' value="<?php echo $val->id; ?>">

                            </table>
                            <?php $id= $id+1; ?>
                            @endforeach
                            <table style="margin-bottom:20px;">
                                <tr>
                                    <td><input type="submit" name="submit" class="btn btn-primary text-center" style="font-size:12px;margin-left:620px;" value="Update"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
</main>
<!-- END Main Container -->

@endsection
