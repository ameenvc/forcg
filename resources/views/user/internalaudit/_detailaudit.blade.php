<div id="viewModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            <table class="table table-striped">
                <tr>
                    <th style="width:250px">Auditors id</th>
                    <td>{{ $value->id }}</td>
                </tr>
                <tr>
                    <th>Auditors name</th>
                    <td>{{ $value->auditorsname }}</td>
                </tr>
                <tr>
                    <th>Certifications and Licensing(List all audit Certificates)</th>
                    <td>{{ $value->certifications_licensing }}</td>
                </tr>
                <tr>
                    <th>Total Audit Experience</th>
                    <td>{{ $value->experience }}</td>
                </tr>
                <tr>
                    <th>Overall Experience</th>
                    <td>{{ $value->overall_experience }}</td>
                </tr>
                <tr>
                    <th>Area of Specialization</th>
                    <td>{{ $value->specialization_area }}</td>
                </tr>
                <tr>
                    <th>Certificate</th>
                    <td>
                        <button type="button"
                                class="btn btn-secondary btn-small"
                                style="font-size:13px;width: 150px;"
                                data-toggle="modal"
                                data-target="#viewModal{{ $value->id }}">
                            <strong>View Attachments</strong>
                        </button>
                    </td>
                </tr>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="font-size:11px;" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="viewModal{{ $value->id }}"
     class="modal fade bd-example-modal-lg modal-second" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Attachments</h3>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                    <thead>
                    <tr class="table-secondary">
                        <th>S.No</th>
                        <th>Attachments</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($files))
                        @foreach($files as $index => $file)
                            @php
                                $fId = property_exists($file, 'Sno') ? $file->Sno : $file->sno
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file->certificate }}</td>
                                <td class="text-center">
                                    <a href="{{ asset("storage/images/" . $file->certificate)}}" download="{{ $file->certificate }}"
                                       class="btn btn-sm btn-alt-dark"
                                       data-toggle="tooltip" style="font-size:11px;"
                                       title="Click here to download"> <i class="fa fa-download"></i>
                                    </a>
                                    <a href="{{ url('/user/internalaudit/deletefile?val='. $fId) }}"
                                       class="btn btn-sm btn-alt-dark"
                                       onclick="return confirm('The attachment will be deleted permanently. Press OK to continue')"
                                       style="font-size:11px;"
                                       data-toggle="tooltip" title="Remove"><i class="fa fa-fw fa-times text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">File not found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
