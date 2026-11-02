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

Dear reviewer,<br/>

{!! nl2br(htmlspecialchars($data['email_content'])) !!}

@if(isset($data['calibrations']) && count($data['calibrations']))
<table class="table table-bordered table-striped calibration-table">
    <thead>
    <tr>
        <th style="width: 20%">Instrument Number</th>
        <th style="width: 20%">Department</th>
        <th style="width: 15%">Status</th>
        <th style="width: 20%">Due Date</th>
        <th style="width: 15%">Link</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['calibrations'] as $item)
        <tr>
            <td>{{ $item['instrument_no'] }}</td>
            <td>{{ $item['department'] }}</td>
            <td>{{ $item['previous_status'] }}</td>
            <td>{{ $item['due_date'] }}</td>
            <td><a href="{{ $item['url'] }}">Login to System</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif

<br><br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
