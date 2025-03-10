<?php

namespace App\Providers;

use App\Services\WhatsAppService;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WhatsAppService::class, function ($app) {
            return new WhatsAppService();
        });
    }

    public function boot(): void
    {
        // Register scheduled tasks
        if ($this->app->runningInConsole()) {
            $schedule = $this->app->make(\Illuminate\Console\Scheduling\Schedule::class);
            
            // Send scheduled messages every minute
            $schedule->command('whatsapp:send-scheduled')->everyMinute();
            
            // Send payment reminders at 10 AM daily
            $schedule->command('whatsapp:payment-reminders')->dailyAt('10:00');
        }
    }
}
