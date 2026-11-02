<div id="viewFieldModal" class="modal fadebd-example-modal-lg" aria-labelledby="viewFieldModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">{{ $category->categoryname }} Field Table</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body m-body p-4">
                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:10%;font-size:10px;text-transform:capitalize">SL No.</th>
                                <th class="text-center" style="width:30%;font-size:10px;text-transform:capitalize">Field Name</th>
                                <th class="text-center" style="width:15%;font-size:10px;text-transform:capitalize">Field Type</th>
                                <th class="text-center" style="width:35%;font-size:10px;text-transform:capitalize">Display Label</th>
                                <th class="text-center" style="width:10%;font-size:10px;text-transform:capitalize">Mandatory</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$addedFields->isEmpty())
                                @foreach($addedFields as $field)
                                <tr>
                                    <td style="font-size:10px;" class="text-center">
                                        <span>{{ $loop->index + 1 }}</span>
                                    </td>
                                    <td style="font-size:10px;" class="text-center">
                                        @if($field->field_name=='machine_equipment_id')
                                            <span>Machine / Equipment ID</span>
                                        @elseif($field->field_name=='operator_shift')
                                            <span>Operator / Shift</span>
                                        @else 
                                            <span>{{ ucwords(str_replace('_', ' ', $field->field_name)) }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size:10px;" class="text-center p-0">
                                        <span>{{ ucfirst($field->field_type) }}</span>
                                    </td>
                                    <td style="font-size:10px;" class="text-center p-0">
                                        <span>{{ ucfirst($field->display_label) }}</span>
                                    </td>
                                    <td style="font-size:10px;" class="text-center p-0">
                                        <span>{{ $field->is_mandatory ? 'Yes' : 'No'}}</span>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-danger text-center fw-100" style="font-size:10px;">
                                        <strong>No fields have been added to the {{ $category->categoryname }} configuration.</strong>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal" style="font-size:12px">Close</button>
            </div>
        </div>
    </div>
</div>