<x-app-layout>
    <x-slot name="title">Create Item</x-slot>
    <x-slot name="navigation"></x-slot>
	<body class="create-page">
        <div class="container create-container">
            <h3 class="create-title">What do you want to create?</h3>
            <div class="push-25"></div>
            <div class="grid-x grid-margin-x">
                <div class="cell small-6 medium-6 create-cell">
                    <a href="/market/create/shirt">
                        <div class="create-cell-title"><i class="fa-duotone fa-clothes-hanger"></i></div>
                        <div class="create-cell-title">Shirt</div>
                    </a>
                </div>
                <div class="cell small-6 medium-6 create-cell">
                    <a href="/market/create/pants">
                        <div class="create-cell-title font-size-32"><i class="fa-duotone fa-clothes-hanger"></i></div>
                        <div class="create-cell-title">Pants</div>
                    </a>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>