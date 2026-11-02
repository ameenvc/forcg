@extends('admin.nav')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">Edit department</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/admin/viewDepartments">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:80%;">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" method="POST" action="{{url('/admin/updateDepartment')}} " onsubmit="return validation()" enctype="multipart/form-data">
                        {{csrf_field()}}
                        @if(count($datas)>0)
                        @foreach($datas as $dat)
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        @if(!empty($errors->all()))
                        <ul class="alert alert-warning">
                            @foreach ($errors->all() as $error)
                            <li class="">{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif


                        <div class="col-lg-12">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Edit Department name</label>
                                <div class="col-sm-8">
                                    <input type="text" id="dept1" name="dept1" value="{{ request()->get('dept') }}" style="font-size: 13px;" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Edit Department code</label>
                                <div class="col-sm-8">
                                    <input type="text" id="code" name="code" value="{{ request()->get('code') }}" style="font-size: 13px;" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Edit iso clauses</label>
                                    <div class="col-sm-8">
                                        <textarea id="isoclauses"  name="isoclauses"   style="font-size: 13px;" row=5 class="form-control" >{{ $isoclauses }}</textarea>
                                    </div>
                            </div> --}}

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Current HOD set to</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="inputhod" name="changehod" value="{{ request()->get('hod') }}" readonly>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Change HOD Name</label>
                                <div class="col-sm-8">
                                    <select id="hodselect" name="dept" class="form-control" style="font-size: 13px;" required="Please" onchange="ddlhodselect();">
                                        <option value="0" selected="selected" disabled> Select a HOD..</option>
                                        @foreach($users as $u)
                                        <option value="{{$u->id}}" {{ $u->name == request()->get('hod') ? 'selected' : '' }}>{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{-- <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">HOD email</label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="input04" name="hodemail" value="{{ request()->get('hodemail') }}">
                                    <input type="hidden" style="font-size: 13px;" class="form-control" id="input04" name="oldemail" value="{{ request()->get('hodemail') }}">
                                </div>
                            </div> --}}


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">HOD Position</label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="input03" name="hodposition" value="{{ request()->get('hodposition') }}">
                                </div>
                            </div>

                            @if($hodasst!=NULL)
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Current HOD Assistants set to</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="inputasst" name="inputasst[]" value="{{ $hodasst }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Change HOD Assistants</label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name="asst[]" id="asst" style="font-size: 13px;width:100%;" onchange="hodasstselect(this);" multiple required>
                                        @foreach($users as $u)
                                        <option value="{{$u->name}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Scope</label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name="scope[]" style="font-size: 12px;width:100%;" required multiple
                                        data-placeholder="Select Scope" onchange="displayscopeoth(this.value)">
                                        <?php
                                        $scopedatas = explode(',',$depscopes);
                                        ?>
                                        @foreach($scopes as $scope)
                                        <option value="{{$scope->name}}" {{ (collect($scopedatas)->contains($scope->name)) ? 'selected':'' }}>{{$scope->name}}</option>
                                        @endforeach
                                        <option value="other"{{ (collect($scopedatas)->contains('other')) ? 'selected':'' }}>Others</option>
                                    </select>
                                </div>
                            </div>

                            @if($depscopes=='other')
                            <div class="form-group row" id="scope_oth">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size:12px;" name="scope_other[]" id="scope_other" value="{{ $scope_other }}" placeholder="Please Enter Scope">
                                    <span class="text-danger" style="font-size:10px;">Please Enter Scope separated by
                                        commas. For example: Workforce management, employee support,Technology support
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" id="scope_oth" style="display:none;">
                                <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size:12px;" name="scope_other[]"
                                    id="scope_other" value="" placeholder="Please Enter Scope">
                                    <span class="text-danger" style="font-size:10px;">Please Enter Scope separated by
                                        commas. For example: Workforce management, employee support,Technology support
                                    </span>
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Current Department Icon</label>
                                <div class="col-sm-8">
                                    <i class="{{$curicon}} fa-2x"> <span style="font-size:16px">{{$curicon}}</span></i>
                                    <input type="hidden" id="cicon" name="cicon" value="{{$dat->icon}}">
                                </div>
                            </div>


                            <a class="btn btn-sm btn-alt-success" id="icon" data-id="icon" data-target="#icons" data-toggle="modal" title="View">
                                <span data-toggle="tooltip" title="Click here to change department icon">Change Department Icon</span>
                            </a>
                            <input type="hidden" id="ficon" name="ficon">
                            <br><br>
                            <div class="form-group row">
                                <div class="col-sm-8 text-right">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{ request()->get('val') }}">
                        </div>
                        <div class="modal fadebd-example-modal-lg" id="icons" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="block block-rounded block-themed block-transparent mb-0">

                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">Choose Icons</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                    <i class="fa fa-fw fa-times"></i>
                                                </button>
                                            </div>

                                        </div>
                                        <div class="block-content">
                                            <div class="js-icon-list row items-push-2x text-center">
                                                @if(count($icons)>0)
                                                    @for($i=0;$i<count($icons);$i++) 
                                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                                            <a href="#" class="" onclick="return selectedicon('{{$icons[$i]}}')">
                                                                <p><i class="{{$icons[$i]}} fa-2x" style="color:grey"></i></p>
                                                                <code style="color:grey">{{$icons[$i]}}</code>
                                                            </a>
                                                        </div>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="block-content block-content-full text-right border-top">
                                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;" id="close">Close</button>
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Fade In Block Modal -->
                        <script>
                            function selectedicon(icon) {
                                document.getElementById('icon').innerText = icon;
                                document.getElementById('ficon').value = icon;
                                document.getElementById('close').click();
                                return false;
                            }

                        </script>
                        @endforeach
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
<script>
    function displayscopeoth(value)
    {
        if(value=='other')
        {
            document.getElementById('scope_oth').style.display='';
            document.getElementById('scope_other').required= true;
        }
        else
        {
            document.getElementById('scope_oth').style.display='none';
            document.getElementById('scope_other').value= null;
            document.getElementById('scope_other').required= false;
        }
     }
</script>

<script>
    function ddlhodselect() {
        var d = document.getElementById("hodselect");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementById("inputhod").value = displaytext;

    }

</script>

<script>
    function hodasstselect(sel) {
        var opts = []
            , opt;
        var len = sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = sel.options[i];

            if (opt.selected) {
                opts.push(opt.value);
                //alert(opt.value);
            }

        }
        document.getElementById("inputasst")['value'] = opts.join(',');
        //return opts;
    }
    //display hod assistants value on windows load
    window.onload = displayHodAsst();

    function displayHodAsst() {
        var inputasstval = document.getElementById("inputasst").value;
        var inputasstvals = inputasstval.split(',');
        var asstdocs = document.getElementById("asst").options;
        for (var i = 0; i < asstdocs.length; i++) {
            if (inputasstvals.includes(asstdocs[i].value)) {
                asstdocs[i].selected = true;
            }

        }

    }
</script>

<script>
    function ddlselect() {
        var d = document.getElementById("dept");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementById("input01").value = displaytext;
    }
</script>
@endsection
