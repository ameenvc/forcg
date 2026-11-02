@extends('layouts.backend')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Context Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Need More Action</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ route('closreviewcontext', ['id' => $id]) }}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">

        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-10">
                    <form class="mb-5" action="{{ route('closreviewcontext_post')}}" method="post">
                        {{ csrf_field() }}
                        @if(count($data)>0)
                        @foreach($data as $dat)

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="action_status">Action Status</label>
                            <div class="col-sm-12">
                                <select class="form-control" style="font-size: 13px;" id="action_status" name="action_status">
                                    <option value="Partially Completed-50%" {{ $dat->action_status == "Partially Completed-50%" ? 'selected' : ''}}>Partially Completed-50%</option><br>
                                    <option value="Progress-25%" {{ $dat->action_status == "Progress-25%" ? 'selected' : ''}}>Progress-25%</option><br>
                                    <option value="Not Started-0%" {{ $dat->action_status == "Not Started-0%" ? 'selected' : ''}}>Not Started-0%</option><br>
                                </select>
                                <input type="hidden" value="{{auth::user()->email}}" name="post">
                                <input type="hidden" value="{{auth::user()->email}}" name="review">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;position:relative;top:28px">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="description">Description<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                            <textarea type="text" class="form-control"  style="font-size: 13px;" id="description" name="description" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id" value="{{ $dat->id}}">
                        </div>
                        <div style="text-align:center;">
                            <button type="submit" class="btn btn-primary" style="font-size: 13px;margin-left:69.5%;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
