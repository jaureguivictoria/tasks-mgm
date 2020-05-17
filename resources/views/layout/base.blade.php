<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layout.head')
</head>
<body>
    @include('layout.navbar')

    <main id="content" class="p-5" role="main">
        <div class="container mt-5">
            @yield('content')
        </div>
    </main>
</body>
</html>
