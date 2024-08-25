<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HELPMOT</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body class="flex-container">
        <div class="content">
            <img class="logo" src="{{asset('images/HelpMot.svg')}}" alt="логотип">
            <h1 class="title">HELPMOT API</h1>
        </div>
    </body>
</html>
