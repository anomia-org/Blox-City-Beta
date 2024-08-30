<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $birthday = '';
    public bool $tos_agree = false;
 
    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'birthday' => ['required', 'date'],
            'tos_agree' => ['required'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div>
    <x-slot name="title">Register</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="auth-page">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Register</h5>
                        <form wire:submit="register">
                            <input wire:model="username" class="form-input" type="text" name="username" placeholder="Username" required>
                            <input wire:model="email" class="form-input" type="email" name="email" placeholder="example@bloxcity.com" required>
                            <input wire:model="password" class="form-input" type="password" name="password" placeholder="Password" required>
                            <input wire:model="password_confirmation" class="form-input" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                            <input wire:model="birthday" class="form-input" type="date" name="birthday" required>
                            <input wire:model="tos_agree" class="form-checkbox" id="tos_agree" type="checkbox" name="tos_agree">
                            <label class="form-label" for="tos_agree">I agree to follow the <a href="#" >terms of service</a></label>
                            <div class="col-1-1" style="margin-top:5px;">
                                <?php//HCaptcha::display(['data-theme' => 'dark']) ?>
                            </div>
                            <div class="align-middle grid-x">
                                <div class="cell auto">
                                    <div class="push-15"></div>
                                    <button class="button button-green" type="submit">Register</button>
                                    @if (Route::has('login'))
                                        <a class="float-right" href="{{ route('login') }}" >
                                                {{ __('Already Registered?') }}
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