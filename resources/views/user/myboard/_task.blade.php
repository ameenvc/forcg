<li class="mb-task" data-task-key="{{ $task['key'] }}">
    <span class="mb-task-icon" aria-hidden="true"><i class="fa {{ $task['icon'] }}"></i></span>
    <div class="mb-task-copy"><span class="mb-task-meta">{{ $task['module_name'] }} <span aria-hidden="true">·</span> {{ $task['reference'] }}</span><h3>{{ $task['title'] }}</h3><p>{{ $task['instruction'] }}@if($task['status']) · {{ $task['status'] }}@endif</p></div>
    <div class="mb-task-due"><span class="mb-kind mb-kind-{{ $task['kind'] }}">{{ ['action'=>'Action','review'=>'Review','approval'=>'Approval'][$task['kind']] }}</span><span class="{{ $task['overdue'] ? 'mb-overdue' : '' }}">{{ $task['overdue'] ? 'Overdue · ' : '' }}{{ $task['due_label'] }}</span>@if($task['priority'])<small class="mb-priority-{{ strtolower($task['priority']) }}">{{ $task['priority'] }} priority</small>@endif</div>
    <div class="mb-task-actions">
        @if($task['action_url'])<a class="mb-task-action" href="{{ $task['action_url'] }}" aria-label="{{ $task['action_label'] }} · {{ $task['reference'] }}">{{ $task['action_label'] }}</a>@endif
        @if($task['url'])<a class="mb-open" href="{{ $task['url'] }}" aria-label="View {{ $task['reference'] }}" title="{{ $task['direct'] ? 'View details' : 'View module' }}"><i class="fa fa-eye" aria-hidden="true"></i></a>@endif
    </div>
</li>
