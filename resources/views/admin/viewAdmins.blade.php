@extends('admin.datatable')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
    @endif
    @if(count($errors)>0)
    @foreach($errors->all() as $error)
    <div class="alert alert-warning">
        {{$error}}
    </div>
    @endforeach
    @endif
    <div class="block block-rounded" >
        <div class="block-header">
            <h3 class="block-title">Admin Table</h3>
        </div>
        <div class="block-content block-content-full" style="width:90%;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Email</th>
                            
                            <th class="text-left"style="width: 5%;font-size:10px;text-transform:capitalize" >Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($admins as $c)
                            {{csrf_field()}}  
                            <?php $id = $c->id ; $email=$c->email; ?>
                            <tr>
                                <td style="font-size:10px" class="text-left">
                                        <span>{{ $number }}</span>
                                </td>
                                <?php $number++; ?>
                                <td style="font-size:10px" class="text-left">
                                        <span>{{$c->name}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                        <span>{{$c->email}}</span>
                                </td>
                                <td class="text-left">
                                    <div class="btn-group">
                                        <a href="{{url('/admin/removeAdmin?val='.$id.'&email='.$email)}}" style="font-size:13px"  class="btn btn-sm btn-alt-primary" onclick="return ddldd()" data-toggle="tooltip" title="Remove"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-6">
                    <!-- <div class="form-group">
                        <label for="example-text-input">Enter Name</label>
                        
                        <input type="text" class="form-control" id="example-text-input"  required  name="department" >
                    </div> -->
                </div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addAdmin')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <select class="form-control" style="font-size:13px;" id="admin"   name="admin" required  onchange="ddlselect()"   onchange="ddlselect()">
                                    <option value="">Please select Name</option>
                                    @if(count($user)>0)
                                    @foreach($user as $use)
                                    <option value="{{$use->email}}" id="adminemail" name="adminemail" >{{$use->name}}</option>  
                                    @endforeach
                                    @endif   
                                </select>
                                <input type="hidden"  id="try" name="try">
                                <div class="input-group-append">
                                    <button type="submit" id="add" name="add" class="btn btn-primary" style="font-size:12px">Add</button>
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
    function ddlselect(){
        var d=document.getElementById("admin");
        var displaytext=d.options[d.selectedIndex].text;
        document.getElementById("try").value=displaytext;
    }
    function ddldd(){
    
        mess1= "Are you sure?";
        x=confirm(mess1);
        if(x==true){
            mess2='The admin will be deleted permanently,Press OK to continue.';
            y=confirm(mess2);
            if(y==true){}
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
</script>  
@endsection