@php $answer = json_decode($item->result, true) ?: []; @endphp
@if(isset($answer['draft']))
<strong>{{ $answer['draft']['risk_name'] }}</strong><p>{{ $answer['draft']['risk_description'] }}</p>
@elseif(isset($answer['review']))
<p>{{ $answer['review']['summary'] }}</p>
@endif
@if(isset($answer['suggestions']))<ul>@foreach($answer['suggestions'] as $suggestion)<li><strong>{{ $suggestion['risk_name'] }}</strong> — {{ $suggestion['reason'] }}</li>@endforeach</ul>@endif
@foreach(['gaps' => 'Information gaps', 'proposed_controls' => 'Proposed controls / follow-up', 'questions' => 'Reviewer questions', 'organization_comments' => 'Organization perspective'] as $field => $label)
@php $values = $answer['review'][$field] ?? $answer['draft'][$field] ?? []; @endphp
@if(count($values))<strong>{{ $label }}</strong><ul>@foreach($values as $value)<li>{{ $value }}</li>@endforeach</ul>@endif
@endforeach
