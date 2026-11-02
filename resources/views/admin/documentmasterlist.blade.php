@extends('admin.nav')
@section('content')
<!-- Page Content -->
<div class="content">
    @if(Session::has('message'))
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
    @endif
    <!-- Dynamic Table Full -->
    <div class="block block-rounded" >
        <div class="block-header">
            <h3 class="block-title">Documents Master Table</h3>
        </div>

        <div class="block-content block-content-full">
                
            <div class="row">
                <div class="col-md-6 col-lg-12">
                    <form method="post" action="{{url('/admin/multipledocmasteraction')}}" id="deleteForm">
                        {{ csrf_field() }}
                        <table width="100%" style="margin-bottom:20px;">
                            <tr>
                                <td align="right" colspan="2">
                                    <input class="btn btn-danger btn-sm"  type="submit" onclick="return delins()" name="submit" value="Delete"/>
                                </td>
                            </tr>
                        </table>
                    </form>     
                </div>

                    
                <div class="col-md-6 col-lg-12">
                    <table width="100%" style="margin-bottom:20px;" border="0">
                        <tr>
                            <td width="50%">
                                <form>
                                    <select id="pagination" style="border-color:#cccccc;font-size:14px;width:60px;height:30px;">
                                        <option value="10" @if($items == 10) selected @endif >10</option>
                                        <option value="25" @if($items == 25) selected @endif >25</option>
                                        <option value="50" @if($items == 50) selected @endif >50</option>
                                        <option value="75" @if($items == 75) selected @endif >75</option>
                                        <option value="100" @if($items == 100) selected @endif >100</option>
                                        <option value="1000" @if($items == 1000) selected @endif >1000</option>
                                    </select>
                                </form>
                            </td>
                            <form method="get" action="{{url('/admin/documentmasterlist')}}"> 
                                <td align="right" width="49%">
                                    <input type="text" class="form-control" name="search" placeholder="Search.." value="{{$serchval}}" style="font-size:14px;width:210px;height:30px;">
                                </td>
                                <td align="left" width="1%">
                                <span class="input-group-btn">
                                        <button type="submit"class="btn btn-default-sm">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </td>  
                            </form>
                        </tr>
                    </table>
                </div>
                

                <div class="col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table width="100%" class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-left" style="width:1%;"><input type="checkbox" id="checkAll"></th>
                                    <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize">Sl No</th>
                                    <th class="text-left" style="width: 10%;font-size:10px;text-transform:capitalize">Document Number</th>
                                    <th class="text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Document</th>
                                    <th class="text-left"  style="width: 5%;font-size:10px;text-transform:capitalize">Revision Number</th>
                                    <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize" >Revision Date</th>
                                    <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize" >Type</th>
                                    <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize" >Level</th>
                                    <th class="text-left" style="width: 3%;font-size:10px;text-transform:capitalize" >Department</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($docdetails)>0)
                                    @foreach($docdetails as $key => $val)
                                        @php
                                        $erevdate = $val->erevdate;
                                        $date = date('Y-m-d',strtotime($erevdate));
                                        $dt = explode('-',$date);
                                        $dateval = $erevdate == null ? null :  $dt[2]."-".$dt[1]."-".$dt[0];
                                        @endphp

                                        <?php $id=$val->id;?>
                                        <tr>
                                        <td><input name='id[]' type="checkbox" class="rowCheckbox" id="checkItem{{$val->id}}" value="{{$val->id}}"></td>
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{ $docdetails->firstItem() + $key }}</span>
                                            </td>                    
                                                            
                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$val->docnumber}}</span>
                                            </td>
                                            <td style="font-size:10px" class="text-left font-w600 ">
                                                <span><a href="#">{{$val->document}}</a></span>
                                            </td>

                                            <td  style="font-size:10px" class="text-left">
                                                    <span>{{$val->revnumber}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                            @if($val->erevdate=="1970-01-01")
                                            <span>&nbsp;&nbsp;</span>
                                            @else
                                                <span>{{$dateval}}</span>
                                            @endif
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$val->type}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$val->level}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                    <span>{{$val->department}}</span>
                                            </td>     
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                    <td colspan="13" align="center" style="font-size:16px">No data available in table</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <table width="100%">
                            <tr>
                                <td width="70%" align="left" style="margin-left:5px;">Page <b>{{$docdetails->currentPage()}}</b> of <b>{{$docdetails->lastPage()}}</b></td>
                                <td width="20%" align="right">
                                    {{ $docdetails->appends(compact('items'))->links() }}
                                </td> 
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--</form>-->
    </div>
    <!-- END Dynamic Table Full -->

</div>
<!-- END Page Content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>      
    document.getElementById('pagination').onchange = function() { 
        window.location = "{!! $docdetails->url(1) !!}&items=" + this.value; 
    }; 
</script>
<script language="javascript">
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
<script>
   function delins() {
        // REMOVE OLD HIDDEN INPUTS FIRST (CRITICAL)
        $('#deleteForm').find('input[name="id[]"]').remove();

        let selected = [];

        $('.rowCheckbox:checked').each(function () {
            selected.push($(this).val());
        });

        //  remove duplicates
        selected = [...new Set(selected)];

        if (selected.length === 0) {
            alert("Please select at least one record");
            return false;
        }

        if (!confirm("Are you sure?")) return false;
        if (!confirm("Document Details will be deleted permanently, Press OK to continue.")) return false;

        //  Append fresh inputs
        selected.forEach(function (id) {
            $('#deleteForm').append(
                '<input type="hidden" name="id[]" value="' + id + '">'
            );
        });

        return true;
    }
</script>
@endsection
