<?php

namespace App\Providers;

use App\Facades\AuthFacade;
use App\Services\AuthService;
use App\Services\CertificateService;
use App\Services\StaffService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->facades();
    }

    public function boot(): void
    {
        $this->settings();
        $this->eventListener();
    }

    private function facades(): void
    {
        $this->app->bind('auth.facade', AuthService::class);
        $this->app->bind('staff.facade', StaffService::class);
        $this->app->bind('certificate.facade', CertificateService::class);
    }

    private function settings()
    {
        JsonResource::withoutWrapping();

        setlocale(LC_ALL, 'ru_RU');
        Carbon::setLocale(config('app.locale'));
    }

    private function eventListener(): void
    {

    }
}
