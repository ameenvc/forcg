<div id="responsibilityModal" class="modal fadebd-example-modal-sm" aria-labelledby="responsibilityModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header bg-primary-darker">
                    <h3 class="block-title text-white">Change Responsibility</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content fs-sm">
                    <div class="alert alert-success d-none w-100" id="success_resp">
                       Responsibility has been successfully changed.
                    </div>

                    <ul class="alert alert-danger d-none w-100" id="error_resp" style="font-size:12px;">
                        Internal server error, we are trying to fix them soon
                    </ul>

                    <ul class="alert alert-warning d-none w-100" id="validation_error_resp"></ul>
                    <input type="hidden" id="complaint_id" name="complaint_id" value="{{$data->id}}">
                    <div class="row">
                        <div class="form-group col-sm-12" >
                            <div class="table-responsive">
                                <div  style="overflow-x:auto; width:100%;">
                                    <table class="table table-bordered table-striped table-vcenter" >
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="text-center" style="font-size:11px;width:40%;">Correction</th>
                                                <th class="text-center" style="font-size:11px;width:19%;">Department<span class="text-danger">*</span></th>
                                                <th class="text-center" style="font-size:11px;width:19%;" id="division_head">Division</th>                                                
                                                <th class="text-center" style="font-size:11px;width:19%;" id="area_head">Sub Division</th>    
                                                <th class="text-center" style="font-size:11px;width:19%;">Responsibility<span class="text-danger">*</span></th>
                                                <th class="text-center" style="font-size:11px;width:15%;">Target date</th>
                                            </tr>
                                        </thead>      
                                        <tbody>
                                            <tr>
                                                <td class="td-class"><textarea class="form-control" cols="15"  rows="4" style="font-size:13px;" readonly>{{$data->correction}}</textarea></td>
                                                <td class="td-class">
                                                    <div class="col-sm-12">
                                                        <select class="form-control" id="maindept" name="maindept" style="font-size: 13px;width:100%;" required onchange="chooseDivision($(this).val())">
                                                            <option selected disabled>Choose Department</option>
                                                            @if(count($departments)>0)
                                                            @foreach($departments as $dep)
                                                            <option value="{{$dep->id}}" <?= $data->dept == $dep->id ? 'selected' : '' ?>> {{$dep->departmentname}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="td-class" id="division_col">
                                                    <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" onchange="chooseArea($(this).val())">
                                                    </select>
                                                </td>                                                        
                                                   
                                                <td class="td-class" id="area_col">
                                                    <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;">
                                                    </select>
                                                </td>
                                                <td class="td-class">
                                                    <div class="col-sm-12 mt-1">
                                                        <select class="form-select w-100 form-control" id="mainresp" name="mainresp" style="font-size: 13px;width:100%;" required>
                                                            <option selected disabled>Choose Responsibility</option>
                                                            @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                            <option value="{{$use->email}}" <?= $data->responsibility == $use->email ? 'selected' : '' ?>> {{ $use->name }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="td-class">
                                                    <input type="date" class="form-control" id="date" style="font-size: 13px;" name="date" readonly value="{{ $data->tardate}}">
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if(count($rootcausedetails)>0)
                        <div class="form-group col-sm-12" >
                            <div class="table-responsive">
                                <div  style="overflow-x:auto; width:100%;">
                                    <table class="table table-bordered table-striped table-vcenter" >
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="text-center" style="font-size:11px;width:34%;">Root Cause</th>
                                                <th class="text-center" style="font-size:11px;width:34%;">Corrective Action</th>
                                                <th class="text-center" style="font-size:11px;width:22%;">Responsibility<span class="text-danger">*</span></th>
                                                <th class="text-center" style="font-size:11px;width:10%;">Target Date</th>
                                            </tr>
                                        </thead>      
                                        <tbody>
                                            @foreach($rootcausedetails as $root)
                                            <tr>
                                                <input type="hidden" name="rid[]" value="{{$root->id}}">
                                                <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:12px;" readonly>{{$root->root_cause}}</textarea></td>
                                                <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:12px;" readonly>{{$root->corrective_action}}</textarea></td>
                                                <td class="td-class">
                                                    <div class="col-sm-12">
                                                        <select class="form-control js-select2 select-dept" name="department[]" style="font-size: 13px;width:100%;" required>
                                                            <option selected>Choose Department</option>
                                                             <!-- Include active departments -->
                                                                @if(count($departments) > 0)
                                                                    @foreach ($departments as $dep)
                                                                        <option value="{{ $dep->id }}" style="font-size:13px;" 
                                                                            {{ isset($root->department) && intval($root->department) === $dep->id ? 'selected' : '' }}>
                                                                            {{ $dep->departmentname }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            <!-- Include inactive department if already selected -->
                                                                @if (isset($root->department))
                                                                    @php
                                                                        $inactiveDept = App\Departments::where('id', $root->department)
                                                                            ->where('is_active', false)
                                                                            ->first();
                                                                    @endphp
                                                                    @if ($inactiveDept)
                                                                        <option value="{{ $inactiveDept->id }}" style="font-size:13px;" selected>
                                                                            {{ $inactiveDept->departmentname }}
                                                                        </option>
                                                                    @endif
                                                                @endif
                                                        </select>
                                                    </div>
                                                
                                                    <div class="col-sm-12 mt-1">
                                                        <select class="js-select2 form-control w-100 select-resp" name="responsibility[]" style="font-size: 13px;width:100%;" required>
                                                            <option selected disabled>Choose Responsibility</option>
                                                            @if(count($user)> 0)
                                                            @foreach($user as $use)
                                                            <option value="{{$use->id}}" <?= $root->responsibility == $use->id ? 'selected' : '' ?>> {{ $use->name }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                        
                                                </td>
                                                <td class="td-class">
                                                    <input type="date" value="{{$root->target_date}}" class="form-control" name="corr_tardate[]" style="font-size:12px;" readonly>
                                                </td> 
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="block-content block-content-full text-end bg-body text-right">
                  <button type="button" class="btn btn-sm btn-secondary me-1" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-sm btn-primary" onclick="changeResponsibility()">Submit</button>
                </div>
              </div>
            
        </div>
    </div>
</div>
<script>
    $('#maindept').select2();
    $('#mainresp').select2();
    $('.select-dept').select2();
    $('.select-resp').select2();

    $(document).ready(function() { 
        let divisionValue =  '{{ $data->division_id }}';   
        let areaValue = '{{ $data->area_id }}';
        let deptValue = '{{ $data->dept }}'
        $('#division_head, #division_col').addClass('d-none');
        $('#area_head,#area_col').addClass('d-none');
        if(typeof divisionValue !== 'undefined' && divisionValue != 0){
            chooseDivision(deptValue);    
        }       
    });
    
    function chooseDivision(id) { 
        $('#division_select').val("");
        $('#area_select').val("");   
        $('#area_head,#area_col').addClass('d-none');
        let divisionselectElement = $('#division_select');
        divisionselectElement.empty(); 
        divisionselectElement.append(`<option value="">Choose Divisions</option>`);      
        let divisionValue =  '{{ $data->division_id }}';  
        let deptValue = '{{ $data->dept }}' 
        let areaValue = '{{ $data->area_id }}';
        if(id==deptValue){
            if (typeof areaValue !== 'undefined' && areaValue != 0) {                                        
                chooseArea(divisionValue);                                                 
            }
        }           
        $.ajax({
                    data: {
                            "depts_id_value": id
                            , _token: CSRF_TOKEN
                        }          
                    , url: "{{ route('admin.selectdivisiondetails')}}"
                    , type: "POST"
                    , success:function(response) {
                        if(response.status == 200){
                            let divisiondatalist = response.divisions;    
                                if(divisiondatalist.length>0){ 
                                    $('#division_head, #division_col').removeClass('d-none');                         
                                    $.each(divisiondatalist, function (index, option) {
                                        divisionselectElement.append(
                                            $('<option>', { value: option.id, text: option.name })
                                        );
                                    }); 
                                    if(id==deptValue){                                     
                                        if (typeof divisionValue !== 'undefined' && divisionValue != 0) {                                        
                                            divisionselectElement.val(divisionValue);                                                
                                        }
                                    }
                                }else{
                                    $('#division_head, #division_col').addClass('d-none');
                                }                        
                                    
                        }
                    },
                    error: function(xhr, status, error) {
                            console.error('Error fetching divisions:', error);
                            alert('Failed to fetch division details. Please try again.');
                    }
                });
    }

    function chooseArea(divid){
        let subdivisionselectElement = $('#area_select');
        subdivisionselectElement.empty(); 
        subdivisionselectElement.append(`<option value="">Choose Sub Division</option>`);  
        $('#area_select').val("");
        //$('#area_head,#area_col').removeClass('d-none');
        let areaValue =  Number('{{ $data->area_id }}');
        $.ajax({
                    data: {
                            "division_id_value": divid
                            , _token: CSRF_TOKEN
                        }          
                    , url: "{{ route('admin.selectsubdivisiondetails')}}"
                    , type: "POST"
                    , success:function(response) {
                        if(response.status == 200){
                            let subdivisiondatalist = response.subdivisions;    
                                if(subdivisiondatalist.length>0){ 
                                    $('#area_head, #area_col').removeClass('d-none');                          
                                    $.each(subdivisiondatalist, function (index, option) {
                                        subdivisionselectElement.append(
                                            $('<option>', { value: option.id, text: option.name })
                                        );
                                    });                                      
                                    if (typeof areaValue !== 'undefined' && areaValue != 0) {                                        
                                        subdivisionselectElement.val(areaValue);                                               
                                    }
                                }else{
                                    $('#area_head, #area_col').addClass('d-none');
                                }                                                       
                        }
                    },
                    error: function(xhr, status, error) {
                            console.error('Error fetching divisions:', error);
                            alert('Failed to fetch division details. Please try again.');
                    }
                });

    }
</script>