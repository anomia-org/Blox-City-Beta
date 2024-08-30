<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Marketplace</x-slot>
    <body class="market-page">
        <div class="grid-x grid-margin-x mb-25">
            <div class="auto cell">
                <div class="market-header">Marketplace</div>
            </div>
            <div class="shrink cell">
                <a href="{{ route('market.create') }}" class="button button-green">Create</a>
                <a href="{{ route('market.index') }}" class="button button-blue">Home</a>
            </div>
        </div>
        <livewire:market.search-box />
        <div class="container">
            <livewire:market.index />
        </div>
    </body>
</x-app-layout>