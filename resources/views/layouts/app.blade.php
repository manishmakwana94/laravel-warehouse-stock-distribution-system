<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <div id="app">
        @include('layouts.partials.header')

        <main class="py-4">
            @yield('content')
        </main>
        @include('layouts.partials.footer')

    </div>
</body>
</html>
