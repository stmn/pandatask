<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!--
           APPLICATION LICENSE
           This application is licensed under the XYZ License.
           All rights reserved. Unauthorized use or distribution prohibited.
           For licensing inquiries, please contact [Your Company Name].
        -->
        <meta charset="utf-8">
{{--        <meta name="viewport" content="width=device-width, initial-scale=1">--}}

        <title inertia>{{ config('app.name', 'Pandatask') }}</title>
        <link rel="icon" type="image/png" href="/logo.png">

        <!-- Fonts -->
{{--        <link rel="preconnect" href="https://fonts.bunny.net">--}}
{{--        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />--}}

        <!-- Scripts -->
        <style>
            body {
                --el-color-primary: {{ \App\Models\Setting::whereName('theme.primary_color')->value('value') }};
                /*--text-color: var(--el-color-white);*/
            }
            /** {*/
            /*    --el-color-primary: #0000cc;*/
            /*}*/
        </style>
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <script src="https://kit.fontawesome.com/9d759f8fd4.js" crossorigin="anonymous"></script>
    </head>
    <body style="--text-color: var(--el-color-white); color: var(--text-color);">
        @inertia
    </body>
</html>
