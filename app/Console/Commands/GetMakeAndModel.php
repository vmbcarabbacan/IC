<?php

namespace App\Console\Commands;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarYear;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetMakeAndModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-make-and-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CarMake::truncate();
        CarModel::truncate();
        CarYear::truncate();
        $this->addYear();

        $makes = Http::withoutVerifying()->asForm()->get('https://www.autoblog.com/api/taxonomy/allmake');

        $makeBar = $this->output->createProgressBar(count($makes->json()));
        $makeBar->start();
        $this->newLine();
        $this->info("Car Make starting");
        foreach($makes->json() as $make) {
            $maker = $this->addMake($make);
            

            $models = Http::withoutVerifying()->asForm()->get('https://www.autoblog.com/api/taxonomy/allmodel?make='.$make);
            $modelBar = $this->output->createProgressBar(count($models->json()));
            $modelBar->start();
            $this->newLine();    
            $this->info("Car Model starting");
            foreach($models->json() as $model) {
                    $data = [
                        'car_make_id' => $maker->id,
                        'name' => $model
                    ];

                    $this->addModel($data);
                    $modelBar->advance();
                }
                $modelBar->finish();
                $this->newLine();
                $makeBar->advance();
        }
        $makeBar->finish();
        $this->newLine();
    }

    private function addMake($name) {
        $make = new CarMake();
        $make->name = $name;
        $make->save();

        return $make;
    }

    private function addModel($data) {
        $model = new CarModel();
        $model->fill($data);
        $model->save();

        return $model;
    }

    private function addYear() {
        $current_year = date('Y');
        $future_year = $current_year + 1;

        $data= array();
        for($x = $future_year; $x > ($future_year - 35); $x--) {
            $data[] = $x;
            $year = new CarYear();
            $year->name = $x;
            $year->save();
        }

    }
}
