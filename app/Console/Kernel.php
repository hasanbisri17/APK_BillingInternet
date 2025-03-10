<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send scheduled WhatsApp messages every minute
        $schedule->command('whatsapp:send-scheduled')
            ->everyMinute()
            ->withoutOverlapping();

        // Send payment reminders daily at 10 AM
        $schedule->command('whatsapp:payment-reminders')
            ->dailyAt('10:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        Commands\SendScheduledWhatsAppMessages::class,
        Commands\SendPaymentReminders::class,
        Commands\TestWhatsAppCommand::class,
    ];
}
