<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\RoundrobinSalesAgentEvent;

class Roundrobin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:roundrobin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set customer details agent id if value is 0';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        event(new RoundrobinSalesAgentEvent());
    }
}
