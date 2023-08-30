<x-mail::message>
You have been assigned to a task in the project &nbsp;<a href="{{ $task->project->url }}" class="name-with-avatar"><img src="{{ $task->project->getAvatar() }}" class="avatar" data-auto-embed="base64" /> {{ $task->project->name }}</a>

Subject: <a href="{{ $task->url }}">{{ $task->subject }}</a> <span class="task-number">#{{ $task->number }}</span>

Priority: <span class="task-priority" style="border-color: {{ $task->priority->color }}">{{ $task->priority->name }}</span>

@if($task->end_date)
Deadline: <span class="task-deadline">⏰ {{ $task->end_date->format('d-m-Y') }}</span>
@endif

<x-mail::button :url="$task->url">
    View Task
</x-mail::button>
</x-mail::message>
