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
                            <a class="link-fx" href="/admin/vendorEvaluationDetails">Back</a>
                        </li>
                    </ol>
                </nav>
                    
            
            </div>                     
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">                                
                    <div class="col-lg-10">
                        @foreach($eval as $c)
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/updateqstns')}}" onsubmit="return validation()" enctype="multipart/form-data" >
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Question <span style="color:red">*</span></label>
                                    
                                </div>
                                <div class="col-sm-8">
                                    <textarea type="text" class="form-control"  style="font-size: 13px;"    id="qstn" name="qstn" required  >{{$c->question}}</textarea>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <label style="font-size: 13px;" class="col-form-label" for="title">Score Out Of</label>
                                    
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  style="font-size: 13px;"    id="score"  name="score" required value="{{ $c->score}}">

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">Selected Type</label>

                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  style="font-size: 13px;"   id="input01" name="type1" required value="{{ $c->type }}" readonly>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">Type</label>

                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control" name="type" id="type"  style="font-size: 13px;" onchange="ddltype()">
                                        <option value="0" selected="" disabled >Choose</option>
                                        <option value="Product">Product</option>
                                        <option value="Service Provider">Service Provider</option>
                                    </select>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">Selected To Be Filled By</label>

                                </div>
                                <div class="col-sm-8">
                                <input type="text" class="form-control"   style="font-size: 13px;" id="fill1" name="fill1" required value="{{ $c->filled_by }}" readonly>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">To Be Filled By</label>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control" name="fill" id="fill"  style="font-size: 13px;" onchange="ddlfill()">
                                        <option value="0" selected="" disabled>Choose</option>
                                        <option value="End User">End User</option>
                                        <option value="HSE">HSE</option>
                                        <option value="Procurement">Procurement</option>
                                    </select>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden"  name="id" id="id"  value= "{{ $c->id}}">
                                    <input type="hidden"   name="id" class = "xlarge" value= "{{$c->id}}">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                        </form> 
                        @endforeach
                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->  
<script>
function ddltype()
{
    var d=document.getElementById("type");
    var displaytext=d.options[d.selectedIndex].text;
    document.getElementById("input01").value=displaytext;

}
function ddlfill()
{
    var k=document.getElementById("fill");
    var displaytext=k.options[k.selectedIndex].text;
    document.getElementById("fill1").value=displaytext;

}

</script> 

    
   

@endsection