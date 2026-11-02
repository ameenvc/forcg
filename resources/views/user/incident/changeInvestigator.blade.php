@extends('layouts.multiselect')
@section('content')
<!-- Main Container -->


<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Add</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/incidents/register">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<!-- END Hero -->

<!-- Page Content -->
<div class="content" style="">
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{route('changedInv')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="rid" style="font-size: 13px;">Report ID</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="rid" name="rid" value="{{$rid}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Team Leader</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="tleader" style="font-size: 13px;width:100%;" id="">
                                        @foreach($usrs as $user)
                                        @if($investigators->tleader==$user->email)
                                        <option value="{{$user->email}}" selected>{{$user->name}}</option>
                                        @else
                                        <option value="{{$user->email}}">{{$user->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php
                        $tmembers= explode(',',$investigators->tmembers);
                        $x=0;
                        $y=0;
                        ?>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Team Members</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="tmembers[]" multiple style="font-size: 13px;width:100%;" id="sel_priority">
                                        @while(count($tmembers)>$x)
                                        @foreach($usrs as $user)
                                        @if($user->email==$tmembers[$x] )
                                        <option value="{{$user->email}}" selected>{{$user->name}}</option>
                                        @endif
                                        @endforeach
                                        <?php  $x+=1?>
                                        @endwhile
                                        @while(count($users)>$y)
                                        <option value="{{$users[$y]['email']}}">{{$users[$y]['name']}}</option>
                                        <?php $y+=1; ?>
                                        @endwhile
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Update </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<!-- Opens from the modal toggle button in the header -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">More</h3>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/register">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Incident Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/closed/incidents">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Incident
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/incident/correactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Corrective Actions
                                    </p>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->
<!-- END Page Container -->
<script>
    $(document).ready(function() {
        $('#sel_priority').select2({});
    });

    function display() {
        if (document.getElementById('yes').checked) {
            document.getElementById('tleader').style.display = "";
            document.getElementById('tmember').style.display = "";
            document.getElementById('tdate').style.display = "";
            document.getElementById('just').style.display = "none";
            document.getElementById('sel_priority').required = true;
            document.getElementById('leader').required = true;
            document.getElementById('targetdate').required = true;
            document.getElementById('justification').required = false;
        } else if (document.getElementById('no').checked) {
            document.getElementById('tleader').style.display = "none";
            document.getElementById('tmember').style.display = "none";
            document.getElementById('tdate').style.display = "none";
            document.getElementById('just').style.display = "";
            document.getElementById('sel_priority').required = false;
            document.getElementById('leader').required = false;
            document.getElementById('targetdate').required = false;
            document.getElementById('justification').required = true;
        }
    }

</script>
@endsection
