@component('mail::message')
<style>
    .calibration-table {
        border-collapse: collapse;
        border: 1px solid #dddfe3;
    }
    .calibration-table th {
        font-weight: bold !important;
        background: #f1f2f4 !important;
        color: black !important;
    }
    .calibration-table th, .calibration-table td {
        border: 1px solid #dddfe3 !important;
        padding: 8px !important;
        font-size: 12px !important;
    }
</style>
Module: Calibration Management.

Dear {{ $data['name'] }},<br/>

{!! nl2br($data['email_content']) !!}

@if(isset($data['calibrations']) && count($data['calibrations']))
<table class="table table-bordered table-striped calibration-table">
    <thead>
        <tr>
            <th>Instrument Number</th>
            <th>Department</th>
            <th>New Status</th>
            <th>Calibration Due Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['calibrations'] as $calibration)
            <tr>
                <td>{{ $calibration->Instrument_no }}</td>
                <td>{{ $calibration->departmentdetails ? $calibration->departmentdetails->departmentname : '' }}</td>
                <td>{{ $calibration->Status }}</td>
                <td>{{ date('d-m-Y', strtotime($calibration->Duedate)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

Click on the below link to view more details<br>
<a href="{{ $data['app_url'] }}">Login to System</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
