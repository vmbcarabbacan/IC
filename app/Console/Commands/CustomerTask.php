<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerDetails;
use App\Services\CarLeadTaskService;

class CustomerTask extends Command
{
    public $carLeadTaskService;

    public function __construct(CarLeadTaskService $carLeadTaskService)
    {
        $this->carLeadTaskService = $carLeadTaskService;
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:customer-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add customer task which value is null';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customer_details = CustomerDetails::query()
        ->whereHas('tasks', function($q) {
            $q->whereNull('closed_at');
        })
        ->whereNull('task_due_date')->get();

        foreach($customer_details as $customer) {
            $customer->task_count = $this->carLeadTaskService->getCount($customer->customer_id);
            $customer->task_due_date = $this->carLeadTaskService->getOldestDueDate($customer->customer_id);

            $customer->save();
        }

        $this->carLeadTaskService->assignUnsignedAgent();
    }
}
