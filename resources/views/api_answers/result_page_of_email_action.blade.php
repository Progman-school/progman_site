<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Result Status | ProgMan.site</title>
</head>
<body>
    <div style="text-align: center">
        <h3>Status:</h3>
        <p><b>{!! $text !!}</b></p>
        <a href="/" title="progman.site">Go back to the site >></a>
    </div>
</body>
</html>
