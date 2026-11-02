@php
    $thInlineStyles = 'font-size:12px;';
    $thSpanInlineStyles = 'font-size:12px;';
    $tdInlineStyles = 'font-size:11px;';
    $tdSpanInlineStyles = 'font-size:10px;';
@endphp
<style>
    .text-center{ text-align: center }
    .custom-badge{
        display:inline-block; font-size:9px !important;
        color:#555 !important;
        background:#f2f2f2 !important;
        padding:2px 6px !important;
        border-radius:4px !important;
        margin-top:2px !important;
    }
    .badge-class {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .badge {
        display: inline-block;
        padding: 4px 8px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        color: #fff;
    }

    .badge-success {
        background: #198754;
    }

    .badge-warning {
        background: #ffc107;
        color: #000;
    }

    .badge-danger {
        background: #dc3545;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    .bg-flat {
        background-color: #f1f3f5;
        color: #495057;
    }

    .badge-info {
        background-color: #0dcaf0;

    }

    .bg-amethyst-light {
        background-color: #f3e8ff;
        color: #6f42c1;
    }

    .bg-city-light {
        background-color: #e9edf2;
        color: #4a5568;
    }

    .status {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-size: 75%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .status-warning {
        color: #af6f1e;
        background-color: #fbdece;
        border-color: #fbf4ea;
    }

    .status-gray {
        color: #737373;
        background-color: #e6e6e6;
        border-color: #fbf4ea;
    }

    .status-blue {
        color: #25499c;
        background-color: #d9e2f6;
        border-color: #d9e2f6;
    }

    .status-success {
        color: #176044;
        background-color: #e0edcf;
        border-color: #bff0dd;
    }

    .bold-text {
        font-weight: bold;
    }
</style>

<div>
    <p>Department:&nbsp;<span class="bold-text">{{ $contextData['department'] }}</span>
    @if ($contextData['division'])
        <br>Division:&nbsp;<span class="bold-text">{{$contextData['division']}}</span>
    @endif
    @if ($contextData['subDivision'])
        <br>Sub Division:&nbsp;<span class="bold-text">{{$contextData['subDivision']}}</span>
    @endif
    </p>
    @if (isset($data) && count($data) > 0)
        <table class="table2" border='1' style='width: 100%; border-collapse:collapse;' bordercolor='black'  align='center' id="headerTableOpen">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th class="text-center" style="{{$thInlineStyles}}">
                            <span style="{{ $thSpanInlineStyles }}">
                                {{$header}}
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $d)
                    <tr>
                        <td class="text-center" style="{{ $tdInlineStyles }}">{{ $index+1 }}</td>
                        <td class="text-center" style="{{ $tdInlineStyles }}"><span class="{{getProcessStageStyle($d->status, 'pdf')}}" style="{{ $tdSpanInlineStyles }}">{{ $d->objective_id }}</span></td>
                        <td class="text-center" style="{{ $tdInlineStyles }}">
                            @if ($d->objectiveTypes)
                                {!! $d->objectiveTypes->pluck('name')->implode(',<br>') !!}
                            @else
                                {{'-'}}
                            @endif
                        </td>
                        <td class="text-center" style="{{ $tdInlineStyles }}">{{ $d->objectives ?? '-' }}</td>
                        <td class="text-center" style="{{ $tdInlineStyles }}">{{ $d->otarget ?? '-' }}</td>
                        <td class="text-center" style="{{ $tdInlineStyles }}">{{ $d->oachieved ?? '-'}}</td>
                        <td class="text-center" style="{{ $tdInlineStyles }}">{{ $d->tardate ?? '-'}}</td>
                        @php
                            $responsibleUsers = getUsersFromEmailsOrNames($d->responsibility);
                            $responsibleUsersName = $responsibleUsers->pluck('name')->implode(', ');
                        @endphp
                        <td class="text-center" style="{{ $tdInlineStyles }}">{{ $responsibleUsersName ?? '-' }}</td>
                        <td class="text-center" style="{{ $tdInlineStyles }}"><span class="{{getProcessStageStyle($d->status, 'pdf')}}" style="{{ $tdSpanInlineStyles }}">{{ $d->status ?? '-' }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
