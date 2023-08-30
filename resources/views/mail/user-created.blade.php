<x-mail::message>
Your account on <a href="{{ url('/') }}">{{ str_replace(['https://','http://'], '', url('/')) }}</a> has been created.

Your login details are as follows:

<x-mail::table>
| Login              | Password        |
|:------------------:|:---------------:|
| {{ $user->email }} | {{ $password }} |
</x-mail::table>

<x-mail::button :url="route('login')">
    Login
</x-mail::button>
</x-mail::message>
<style>
    .table th {
        font-size: 13px;
        color: #333;
        background: #f9f9f9;
        padding: 5px 0;
    }
    .table td, .table th {
        width: 50%;
    }
</style>
