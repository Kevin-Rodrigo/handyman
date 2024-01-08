<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModuleDisable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modules = ['Auth', 'UserManagement', 'ZoneManagement', 'CategoryManagement', 'PromotionManagement', 'ServiceManagement', 'ProviderManagement',
            'PaymentModule', 'BusinessSettingsModule', 'BookingModule', 'SMSModule', 'TransactionModule', 'ReviewModule', 'CartModule', 'AdminModule', 'CustomerModule',
            'ServicemanModule', 'ChattingModule'];
        foreach ($modules as $module) {
            Artisan::call('module:disable ' . $module);
        }
        return 0;
    }
}
