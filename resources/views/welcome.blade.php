<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{env('APP_NAME')}}</title>
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;600&display=swap" rel="stylesheet">
        <!-- <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer> -->
        @php
        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true);
        @endphp
        @production
            @foreach ($manifest['resources/js/app.js']['imports'] as $importName)
                <link rel="modulepreload" href="/js/{{ $manifest[$importName]['file'] }}" as="script">
            @endforeach
            @foreach ($manifest as $export)
                @if (isset($export['css']))
                    @foreach ($export['css'] as $url)
                        <link rel="stylesheet" href="/js/{{ $url }}" />
                    @endforeach
                @endif
            @endforeach
        @endproduction
        <!-- </script> -->
    </head>
    <body>
        <div id="app"></div>
        @production
        <script type="module" src="/js/{{ $manifest['resources/js/app.js']['file'] }}"></script>
        @else
        <script type="module" src="http://localhost:3000/@vite/client"></script>
        <script type="module" src="http://localhost:3000/js/app.js"></script>
        @endproduction
    </body>
</html>