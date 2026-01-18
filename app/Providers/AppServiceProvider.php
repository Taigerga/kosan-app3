<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WhatsAppService;
//use App\Services\NotificationService;
use App\Services\ALLNotificationService;
use App\Console\Commands\SendContractReminders;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(WhatsAppService::class, function ($app) {
            return new WhatsAppService();
        });

        // Register ALLNotificationService dengan dependency WhatsAppService
        $this->app->singleton(ALLNotificationService::class, function ($app) {
            return new ALLNotificationService(
                $app->make(WhatsAppService::class)
            );
        });

        $this->app->singleton(ALLNotificationService::class, function ($app) {
            return new ALLNotificationService($app->make(WhatsAppService::class));
        });

        //$this->app->singleton(NotificationService::class, function ($app) {
            //return new NotificationService($app->make(WhatsAppService::class));
        //});

        // Register the contract reminder command
        //$this->app->singleton(SendContractReminders::class, function ($app) {
            //return new SendContractReminders($app->make(NotificationService::class));
        //});
        $this->app->singleton(SendContractReminders::class, function ($app) {
            return new SendContractReminders(
                $app->make(ALLNotificationService::class)
            );
        });        

    }

    public function boot()
    {
        // Optional: Register command if not auto-discovered
        if ($this->app->runningInConsole()) {
            $this->commands([
                SendContractReminders::class,
            ]);
        }
    }
}