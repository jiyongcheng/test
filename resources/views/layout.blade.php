<!DOCTYPE html>
<html lang="en">
<head>
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="/css/app.css">
    <title>Laravel with Vuejs</title>
</head>
<body>
    <div id="app">
        <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @yield('body')
                    </div>
                </div>
        </div><!-- /.container -->
    </div>
    <script src="/js/app.js"></script>
    </body>
</html>