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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v6.5.2/css/pro.min.css">
        @livewireStyles
    </head>
    <body {{ $attributes }}>
        <div class="app">
            @if(isset($navigation) && $navigation)
                <livewire:layout.topbar />
                @include('layouts.sidebar')
                @include('layouts.banner')
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
        </div>
        @include('layouts.scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        @livewireScripts
    </body>
</html>
