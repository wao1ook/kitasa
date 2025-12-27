<?php

namespace Emanate\Kitasa\Http\Livewire\Auth;

use Emanate\Kitasa\Contracts\OtpSender;
use Emanate\Kitasa\Services\OtpService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getPhoneNumberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getPhoneNumberFormComponent(): TextInput
    {
        return TextInput::make('phone_number')
            ->label(__('kitasa::auth.phone_number'))
            ->required()
            ->tel()
            ->autofocus();
    }

    public function request(): void
    {
        $data = $this->form->getState();

        $phoneColumn = config('kitasa.phone_column', 'phone_number');
        $user = app(config('auth.providers.users.model'))->where($phoneColumn, $data['phone_number'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'data.phone_number' => __('kitasa::auth.phone_not_found'),
            ]);
        }

        $otp = app(OtpService::class)->generate($data['phone_number']);

        app(OtpSender::class)->send($data['phone_number'], $otp);

        Notification::make()
            ->title(__('kitasa::auth.otp_sent'))
            ->success()
            ->send();

        $this->redirect(URL::temporarySignedRoute(
            'kitasa.password-reset.reset',
            now()->addMinutes(config('kitasa.otp.expiry', 10)),
            [
                'phone' => $data['phone_number'],
            ]
        ));

    }

    protected function getFormActions(): array
    {
        return [
            $this->getRequestFormAction(),
        ];
    }

    protected function getRequestFormAction(): Action
    {
        return Action::make('request')
            ->label(__('kitasa::auth.request_otp'))
            ->submit('request');
    }
}
