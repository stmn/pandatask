<x-mail::message>
Task status has been changed by <a class="name-with-avatar"><img src="{{ $activity->user->getAvatar() }}" class="avatar" data-auto-embed="base64" /> {{ $activity->user->full_name }}</a>:<br>
on the task: <a href="{{ $task->url }}">{{ $task->subject }}</a> <span class="task-number">#{{ $task->number }}</span>

New status: <span class="task-status" style="border-color: {{ $task->status->color }};">{{ $task->status->name }}</span><br>

<x-mail::button :url="$task->url">
    View Task
</x-mail::button>
</x-mail::message>
