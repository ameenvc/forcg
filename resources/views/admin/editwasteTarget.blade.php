@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-header">
                <h3 class="block-title">Edit target</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/wastetargets">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:75%;">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{route('admin.waste.updatewastetarget')}} " onsubmit="return validation()">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="row mb-4">
                                <div class="col-sm-3 ">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="items">items</label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="items" name="items" value="{{$target->items}}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="qty">qty</label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="qty" name="qty" value="{{$target->qty}}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="approver">approver</label>
                                </div>
                                <div class="col-sm-9">
                                    <select id="approver" name="approver" class="form-control" style="font-size: 13px;" required>
                                        <option value="0" selected="selected"> Select a user...</option>
                                        @foreach($users as $u)
                                        <option value="{{$u->id}}" {{ $target->approvers_id == $u->id ? 'selected' : ''}}>{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" name="id" class="xlarge" value="{{$target->id}}">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
