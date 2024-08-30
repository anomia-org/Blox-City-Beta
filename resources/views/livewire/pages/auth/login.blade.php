<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div>
    <x-slot name="title">Log In</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="auth-page">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Log in</h5>
                        <form wire:submit="login">
                            <input wire:model="form.username" class="form-input" type="text" name="username" placeholder="Username" required>
                            <input wire:model="form.password" class="form-input" type="password" name="password" placeholder="Password" required>
                            <input wire:model="form.remember" class="form-checkbox" id="remember" type="checkbox" name="remember">
                            <label class="form-label" for="remember">Remember me</label>
                            <div class="col-1-1" style="margin-top:5px;">
                                <?php//HCaptcha::display(['data-theme' => 'dark']) ?>
                            </div>
                            <div class="align-middle grid-x">
                                <div class="cell auto">
                                    <div class="push-15"></div>
                                    <button class="button button-blue" type="submit">Log in</button>
                                    @if (Route::has('password.request'))
                                        <a class="float-right" href="{{ route('password.request') }}" >
                                                {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </body>
</div>
