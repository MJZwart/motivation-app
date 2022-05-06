<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{env('APP_NAME')}}</title>
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;600&display=swap" rel="stylesheet">
        <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
        </script>
        @php
        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true);
        @endphp
        @production
            @foreach($manifest['js/app.js']['css'] as $url)
                <link rel="stylesheet" href="/js/{{ $url }}" />
            @endforeach
        @endproduction
    </head>
    <body>
        <div id="app"></div>
        @production
        <script type="module" src="/js/{{ $manifest['js/app.js']['file'] }}"></script>
        @else
        <script type="module" src="http://localhost:3000/@vite/client"></script>
        <script type="module" src="http://localhost:3000/js/app.js"></script>
        @endproduction
    </body>
</html>