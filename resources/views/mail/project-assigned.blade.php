<x-mail::message>
You have been assigned to the project &nbsp;<a href="{{ $project->url }}" class="name-with-avatar"><img src="{{ $project->getAvatar() }}" class="avatar" data-auto-embed="base64" /> {{ $project->name }}</a>

<x-mail::button :url="$project->url">
    View Project
</x-mail::button>
</x-mail::message>
