<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: false);
    }
}; ?>

<div>
    <x-slot name="title">Reset Password</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="auth-page">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Reset Password</h5>
                        <form wire:submit="resetPassword">
                            <input wire:model="email" class="form-input" type="email" name="email" placeholder="Email" required>
                            <input wire:model="password" class="form-input" type="password" name="password" placeholder="Password" required>
                            <input wire:model="password_confirmation" class="form-input" id="password_confirmation" type="password" name="password_confirmation" required>
                            <div class="col-1-1" style="margin-top:5px;">
                                <?php//HCaptcha::display(['data-theme' => 'dark']) ?>
                            </div>
                            <div class="align-middle grid-x">
                                <div class="cell auto">
                                    <div class="push-15"></div>
                                    <button class="button button-orange" type="submit">Reset Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </body>
</div>