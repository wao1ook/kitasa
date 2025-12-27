<?php

namespace Emanate\Kitasa\Http\Livewire\Auth;

use Emanate\Kitasa\Services\OtpService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPassword extends BaseResetPassword
{
    public ?string $phone = null;

    public function mount(): void
    {
        parent::mount();

        $this->phone = request()->query('phone');

        $this->form->fill([
            'phone_number' => $this->phone,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('phone_number')
                    ->label(__('kitasa::auth.phone_number'))
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('otp')
                    ->label(__('kitasa::auth.otp'))
                    ->required(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    public function resetPassword(): void
    {
        $data = $this->form->getState();

        if (! app(OtpService::class)->verify($this->phone, $data['otp'])) {
            throw ValidationException::withMessages([
                'data.otp' => __('kitasa::auth.otp_invalid'),
            ]);
        }

        $phoneColumn = config('kitasa.phone_column', 'phone_number');
        $user = app(config('auth.providers.users.model'))->where($phoneColumn, $this->phone)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'data.phone_number' => __('kitasa::auth.phone_not_found'),
            ]);
        }

        $this->resetPasswordStep($user, $data['password']);

        $this->redirect(filament()->getLoginUrl());
    }

    protected function resetPasswordStep(Authenticatable $user, string $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();

        filament()->auth()->login($user);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction(),
        ];
    }

    protected function getResetPasswordFormAction(): Action
    {
        return Action::make('resetPassword')
            ->label(__('kitasa::auth.reset_password'))
            ->submit('resetPassword');
    }
}
