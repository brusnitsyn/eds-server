<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\CertificateService;
use App\Services\DivisionService;
use App\Services\StaffService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->facades();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->settings();
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }

    private function settings(): void
    {
        JsonResource::withoutWrapping();

        setlocale(LC_ALL, 'ru_RU');
        Carbon::setLocale(config('app.locale'));
    }

    private function facades(): void
    {
        $this->app->bind('auth.facade', AuthService::class);
        $this->app->bind('staff.facade', StaffService::class);
        $this->app->bind('certificate.facade', CertificateService::class);
        $this->app->bind('division.facade', DivisionService::class);
    }
}
