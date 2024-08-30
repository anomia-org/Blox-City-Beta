<x-app-layout>
    <x-slot name="title">Let Your Creativity Flow</x-slot>
    <x-slot name="navigation"></x-slot>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind:400,500,600,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300">


    </head>
    <body class="landing-page">
        <div class="grid-x">
            <div class="cell medium-4">
                <div class="landing-container" style="top:35%!important;">
                    <div class="landing-header">Let your creativity flow.</div>
                    <div class="landing-text">The fastest growing user-generated sandbox platform designed for all ages.</div>
                    <div class="push-10"></div>
                    <a href="{{ route('register') }}" class="button button-green">Get Started</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ route('login') }}" class="button button-blue">Login</a>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>
