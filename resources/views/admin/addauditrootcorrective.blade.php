@extends('admin.datatable')

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
                <h3 class="block-title">Add Root Cause and Corrective Action</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">

                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewCategories">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:90%;margin-left:100px">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{route('auditinsertrootcorrective')}}"  enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                           
                            <div class="col-lg-10">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="title">Title<span style="color:red">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="title" value="{{ old('title') }}" style="font-size: 13px;" required class="form-control">
                                    </div>
                                </div>

                               

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="rootcause" style="font-size: 13px;">Root Cause<span style="color:red">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control"style="font-size:13px;" id="root_cause" name="root_cause" required>{{ old('root_cause') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="correctiveaction" style="font-size: 13px;">Corrective Action<span style="color:red">*</span></label>
                                    <div class="col-sm-8">
                                       <textarea class="form-control"style="font-size:13px;" id="corrective_action" name="corrective_action" required>{{ old('corrective_action') }}</textarea>
                                    </div>
                                </div>

                            </tr>
                                <div>
                                    <div>
                                        <input type="submit" name="save" class="btn btn-primary" style="font-size:10px;margin-left:650px" value="Save">
                                    </div>
                                </div>
                               
                        </form>


                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
</main>
<!-- END Main Container -->

<script>
    function ddlselect() {
        var d = document.getElementById("dept");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementById("dept").value = displaytext;

    }

</script>
<script>
    function validation() {


        if (document.getElementById('dept').value == 0) {
            alert('Please select a department');
            document.getElementById('dept').focus();
            return false;
        }



        
    }

</script>




@endsection
