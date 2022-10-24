<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <base href=".">
    </script>
    @php
    $manifest = json_decode(file_get_contents(public_path('js/manifest.json')), true);
    @endphp
    @production
    @foreach($manifest['js/app.js']['css'] as $url)
    <link rel="stylesheet" href="/js/{{ $url }}" type="text/css" />
    @endforeach
    @endproduction
</head>

<body>
    <div id="app"></div>
    @production
    <script type="module" src="/js/{{ $manifest['js/app.js']['file'] }}" type="text/html"></script>
    @else
    <script type="module" src="http://localhost:3000/@@vite/client"></script>
    <script type="module" src="http://localhost:3000/js/app.js"></script>
    @endproduction
</body>

</html>