@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">   
            <div class="block-header">
                <h3 class="block-title">JobCode </h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/home">Back</a>
                        </li>
                    </ol>
                </nav>             
            </div>                     
            <div class="block-content block-content-full"  style="width:90%;">
                <div class="row">                                
                    <div class="col-lg-10">
                        <form class="mb-5" method="POST" action="{{url('/admin/submitjobcode')}}"  enctype="multipart/form-data" >
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif
                            @if(count($jc)>0)
                            @foreach($jc as $c)
                            <div class="form-group row">
                                <input type="hidden"  id="id" name="id" value="{{$c->id}}">
                                <input type="hidden" class="form-control" id="jc" name="jc" value="{{$c->code}}">
                                <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="title" >Set Job Code: <span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <label class="form-check-label" for="vendortype" id="lbltype1" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="type" id="type1" value="AutoGenerate JobCode" required style="font-size:13px;"><span></span>AutoGenerate JobCode <br>
                                        </label>
                                        <br>
                                        <label class="form-check-label" for="vendortype" id="lbltype2" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="type" id="type2" value="Manual JobCode" style="font-size:13px;"><span></span>Manual JobCode
                                        </label>

                                    </div>
                                    <!-- <select class="form-control" style="font-size: 13px;width:200px;"   name="type" id="type">
                                        <option value="{{$c->code}}"> {{ $c->code}}</option><br>
                                        @if($c->code !="AutoGenerate JobCode")
                                            <option value="AutoGenerate JobCode">AutoGenerate JobCode</option>
                                        @endif
                                        @if($c->code !="Manual JobCode")
                                        <option value="Manual JobCode">Manual JobCode</option>
                                        @endif
                            

                                    </select> -->
                                          
                                    <!-- <input type="text" class="form-control" style="font-size: 13px;"  id="score" name="score" required placeholder="Enter score"> -->
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="form-group row">
                                <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="title" >Job Code<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <!-- <select class="form-control" style="font-size: 13px;width:200px;"   name="code" id="code">
                                        <option value="0" selected disabled> Select </option><br>
                                    <option value="AutoGenerate JobCode">AutoGenerate JobCode</option>
                                    <option value="Manual JobCode">Manual JobCode</option>
                                    </select> -->
                                    <div class="form-check">
                                        <label class="form-check-label" for="vendortype" id="lbltype1" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="code" id="code1" value="AutoGenerate JobCode" required style="font-size:13px;"><span></span>AutoGenerate JobCode <br>
                                        </label>
                                        <br> 
                                        <label class="form-check-label" for="vendortype" id="lbltype2" style="font-size:13px;">
                                            <input class="form-check-input" type="radio" name="code" id="code2" value="Manual JobCode" style="font-size:13px;"><span></span>Manual JobCode
                                        </label>

                                    </div>
                                        <!-- <input type="text" class="form-control" style="font-size: 13px;"  id="score" name="score" required placeholder="Enter score"> -->
                                </div>
                            </div>
                            @endif  
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="submit"  name="Submit" value="Submit"  class="btn btn-primary" style="font-size:10px;">
                                </div>
                            </div>      
                        </form> 
                           
                                                
                   </div>
               </div>
           </div>
       </div>
    </div>
    <!-- END Page Content -->     
<script>
    function code()
    {
        var d=document.getElementById("type");
        var displaytext=d.options[d.selectedIndex].text;
        document.getElementById("score").value=displaytext;

    }
    var tt=document.getElementById('jc').value;
    if(tt=="AutoGenerate JobCode")
    {
        document.getElementById("type1").checked=true;
        
    }
    if(tt=="Manual JobCode")
    {
        document.getElementById("type2").checked=true;
        
    }
</script> 

@endsection