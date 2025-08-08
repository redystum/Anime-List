<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name') }}@yield('title')</title>

    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body class="bg-white dark:bg-neutral-900 text-gray-900 dark:text-neutral-100">

{{--<x-toast/>--}}

<x-navbar/>

<main>
    @yield('content')
</main>


@livewireScripts
</body>

</html>
