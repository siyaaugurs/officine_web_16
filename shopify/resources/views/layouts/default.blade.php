<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('shopify-app.app_name') }}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            .container-fluid {
                padding: 10px 15px;
            }
        </style>
        @yield('styles')
    </head>

    <body>
        <div class="app-wrapper container-fluid">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #e3f2fd; margin-bottom: 20px;">
                <a class="navbar-brand" href="{{ route('home') }}">Home</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link active float-right" href="{{ route('settings') }}">Settings</a>
                    </div>
                </div>
            </nav>
            
            <div class="app-content">                
                <main role="main">
                    @yield('content')
                </main>
            </div>
        </div>

        @if(config('shopify-app.appbridge_enabled'))
            <script src="https://unpkg.com/@shopify/app-bridge{{ config('shopify-app.appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
            <script>
                var AppBridge = window['app-bridge'];
                var createApp = AppBridge.default;
                var app = createApp({
                    apiKey: '{{ config('shopify-app.api_key') }}',
                    shopOrigin: '{{ ShopifyApp::shop()->shopify_domain }}',
                    forceRedirect: true
                });
            </script>

            @include('partials.flash_messages')
        @endif
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        @yield('scripts')
    </body>
</html>