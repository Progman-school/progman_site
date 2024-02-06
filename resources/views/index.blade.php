<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>ProgMan school</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="images/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#000000">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Locate meta tags -->
    <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta name="description" content="{{ $locateMetaTags["language_locate_meta_tag_description"] ?? "" }}" />
    <meta itemprop="name" content="{{ $locateMetaTags["language_locate_meta_tag_itemprop_name"] ?? "" }}" />
    <meta name="keywords" content="{{ $locateMetaTags["language_locate_meta_tag_keywords"] ?? "" }}" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BXB21SWDYD"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-BXB21SWDYD');
    </script>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,500,0,0" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('/css/main.css')}}" />
    <link rel="stylesheet" href="{{asset('/css/noscript.css')}}" />
    @vite('resources/css/app.css')
</head>
<body class="is-preload">
<div id="wrapper"></div>
<div id="bg"></div>
@vite('resources/js/app.js')
</body>
</html>
