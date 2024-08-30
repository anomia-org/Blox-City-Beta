<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>


<div>
    <x-slot name="title">Forgot Password</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="auth-page">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-4">
                    <div class="container auth-container">
                        <h5 class="mb-25">Forgot Password</h5>
                        <form wire:submit="sendPasswordResetLink">
                            <input wire:model="email" class="form-input" type="email" name="email" placeholder="Email" required>
                            <div class="col-1-1" style="margin-top:5px;">
                                <?php//HCaptcha::display(['data-theme' => 'dark']) ?>
                            </div>
                            <div class="align-middle grid-x">
                                <div class="cell auto">
                                    <div class="push-15"></div>
                                    <button class="button button-green" type="submit">Email Password Reset Link</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </body>
</div>