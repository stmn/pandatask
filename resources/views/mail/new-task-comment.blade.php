<x-mail::message>
A comment has been made by <a class="name-with-avatar"><img src="{{ $activity->user->getAvatar() }}" class="avatar" data-auto-embed="base64" /> {{ $activity->user->full_name }}</a>:<br>
on the task: <a href="{{ $task->url }}" style="text-decoration: none;">{{ $task->subject }}</a> <span class="task-number">#{{ $task->number }}</span>

<x-mail::panel>
    <div class="task-comment ProseMirror">
        {!! $activity->comment->content !!}
    </div>
</x-mail::panel>

<x-mail::button :url="$task->url">
    View Task
</x-mail::button>
</x-mail::message>
