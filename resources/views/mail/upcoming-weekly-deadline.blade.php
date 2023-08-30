<x-mail::message>
Here is a reminder for the upcoming task deadlines in the current week:

@foreach($tasks as $task)
* <a href="{{ $task->url }}">{{ $task->subject }}</a> <span class="task-number">#{{ $task->number }}</span> <span class="task-deadline">⏰ {{ $task->end_date->format('d-m-Y') }}</span>
@endforeach
</x-mail::message>
