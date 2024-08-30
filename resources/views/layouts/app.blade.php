<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Page Title' }} / BLOX City</title>

        <!-- Meta Data -->
        <!-- reinserted later -->

        <!-- Stylesheets -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind:400,500,600,700">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v6.5.2/css/pro.min.css">
        @livewireStyles
    </head>
    <body {{ $attributes }}>
            @if(isset($navigation) && $navigation)
                <livewire:layout.topbar />
                @include('layouts.sidebar')
                @include('layouts.banner')
                @auth
                    @include('layouts.ads')
                @endauth
            @endif
        
            <!-- BEGIN CONTENT -->
            <div class="page-wrapper">
                <div class="grid-container {{ isset($gridFluid) ? 'fluid' : '' }} {{ $gridClass ?? '' }}">
                    <div class="grid-x">
                        <div class="cell medium-10 medium-offset-1">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END CONTENT -->
            @if (!isset($blank))
                @include('layouts.footer')
            @endif
        
        @livewireScripts
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script>
            toastr.options =
                {
                    "closeButton": true,
                    "progressBar": true,
                    "newestOnTop": true,
                }
            
            Livewire.on('toast:success', message => {
                toastr.success(message, "Success");
            });
            Livewire.on('toast:error', message => {
                toastr.error(message, "Error");
            });
            Livewire.on('toast:info', message => {
                toastr.info(message, "Information");
            });
            Livewire.on('toast:warning', message => {
                toastr.warning(message, "Warning");
            });
            
            // success message popup notification
            @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Success");
            @endif

            // info message popup notification
            @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}", "Information");
            @endif

            // warning message popup notification
            @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}", "Warning");
            @endif

            // error message popup notification
            @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}", "Error");
            @endif

            @if($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", "Error");
                @endforeach
            @endif
        </script>

        @if(isset($script) && $script)
            {{ $script }}
        @endif
        
    </body>
</html>
