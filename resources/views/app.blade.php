@php use App\Models\Setting; @endphp
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
    <!--suppress HtmlUnknownTarget -->
    <link rel="icon" type="image/png" href="/logo.png">

    <!-- Scripts -->
    <style>
        body {
            --brand-color: {{ Setting::query()->where('name', 'theme.brand_color')->value('value') ?? '#347CE4' }};
            --brand-text-color: {{ '#cc0000' }};
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
<body style=" --el-bg-color-page: var(--brand-color);">
@inertia
</body>
</html>
