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

    <style>
        body {
            --brand-color: {{ Setting::query()->where('name', 'theme.brand_color')->value('value') ?? '#347CE4' }};
            --brand-text-color: {{ '#cc0000' }};
        }

        /** {*/
        /*    --el-color-primary: #0000cc;*/
        /*}*/

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            /*background: var(--el-bg-color);*/
        }

        #loader .icon {
            font-size: 30vh;
            transform: translate(-50%, -50%);
            transform: -webkit-translate(-50%, -50%);
            transform: -moz-translate(-50%, -50%);
            transform: -ms-translate(-50%, -50%);
            color: #fff;
            opacity: 0.3;
        }
    </style>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    <script src="https://kit.fontawesome.com/9d759f8fd4.js" crossorigin="anonymous"></script>
</head>
<body style="--el-bg-color-page: var(--brand-color);">
<div id="loader">
{{--    <img src="/logo.png" alt="Pandatask" width="200" class="fa-pulse icon">--}}
</div>
@inertia
</body>
</html>
