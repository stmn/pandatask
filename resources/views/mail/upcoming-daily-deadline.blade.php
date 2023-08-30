<x-mail::message>
Here is a reminder for the upcoming task deadlines today:

@foreach($tasks as $task)
* <a href="{{ $task->url }}">{{ $task->subject }}</a> <span class="task-number">#{{ $task->number }}</span>
@endforeach
</x-mail::message>
