<?php

namespace Emanate\Kitasa\Http\Livewire\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public bool $isStageTwo = false;

    public string $phone_number = '';

    public string $password = '';

    public function mount(): void
    {
        parent::mount();

        if (Auth::check()) {
            redirect()->intended(filament()->getUrl());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getPhoneNumberFormComponent(),
                $this->getPasswordFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getPhoneNumberFormComponent(): TextInput
    {
        return TextInput::make('phone_number')
            ->label(__('kitasa::auth.phone_number'))
            ->required()
            ->tel()
            ->autocomplete()
            ->autofocus()
            ->disabled(fn () => $this->isStageTwo)
            ->dehydrated()
            ->hintAction(
                fn () => FormAction::make('requestPasswordReset')
                    ->label(__('filament-panels::pages/auth/login.actions.request_password_reset.label'))
                    ->url(filament()->getRequestPasswordResetUrl())
            );
    }

    protected function getPasswordFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->password()
            ->required()
            ->autocomplete('current-password')
            ->visible(fn () => $this->isStageTwo);
    }

    public function next(): void
    {
        $data = $this->form->getState();

        $phoneColumn = config('kitasa.phone_column', 'phone_number');

        $user = app(config('auth.providers.users.model'))->where($phoneColumn, $data['phone_number'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'data.phone_number' => __('kitasa::auth.phone_not_found'),
            ]);
        }

        $this->phone_number = $data['phone_number'];
        $this->isStageTwo = true;
    }

    public function authenticate(): ?LoginResponse
    {
        if (! $this->isStageTwo) {
            $this->next();

            return null;
        }

        return parent::authenticate();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            config('kitasa.phone_column', 'phone_number') => $this->phone_number,
            'password' => $data['password'],
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
        ];
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(fn () => $this->isStageTwo ? __('filament-panels::pages/auth/login.form.actions.authenticate.label') : __('kitasa::auth.next'))
            ->submit('authenticate');
    }
}
