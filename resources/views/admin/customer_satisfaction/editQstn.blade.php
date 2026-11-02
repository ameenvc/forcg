@extends('admin.datatable')

@section('content')
        <!-- Page Content -->
        <div class="content">
            <!-- Horizontal -->
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">Edit questions</h3>
                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/admin/customer-satisfaction/evaluationDetails">Back</a>
                            </li>
                        </ol>
                    </nav>


                </div>
                <div class="block-content block-content-full" style="width:90%;">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Form Horizontal - Default Style -->
                            <form class="mb-5" method="POST" action="{{ route('admin.customer.updateQstn') }}"
                                  onsubmit="return validation()" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @if(Session::has('msg'))
                                    <div class="alert alert-primary">
                                        <a class="close" data-dismiss="alert">×</a>
                                        {!!Session::get('msg')!!}
                                    </div>
                                @endif
                                <div class="row mb-4">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" style="font-size: 13px;" for="qstn">Question</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" style="font-size: 13px;" id="qstn"
                                                  name="qstn" required>{{$customer_detail->question}}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-3">
                                        <label style="font-size: 13px;" class="col-form-label" for="score">Weightage</label>
                                        
                                    </div>
                                    <div class="col-sm-9">
                                        <select class="form-control" style="font-size: 13px;" name="score" id="score">
                                            <option value="0" {{ $customer_detail->score == null ? 'selected' : '' }}>Choose</option>
                                            <option value="5" {{ $customer_detail->score == 5 ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ $customer_detail->score == 10 ? 'selected' : '' }}>10</option>
                                            <option value="15" {{ $customer_detail->score == 15 ? 'selected' : '' }}>15</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <input type="hidden" name="id" id="id" value="{{ $customer_detail->id }}">
                                        
                                        <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </div>
    <script>
        function ddltype() {
            let d = document.getElementById("type");
            let displaytext = d.options[d.selectedIndex].text;
            document.getElementById("input01").value = displaytext;
        }

        function ddlfill() {
            let k = document.getElementById("fill");
            let displaytext = k.options[k.selectedIndex].text;
            document.getElementById("fill1").value = displaytext;
        }

    </script>

@endsection
