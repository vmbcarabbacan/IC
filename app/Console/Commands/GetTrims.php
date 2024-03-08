<?php

namespace App\Console\Commands;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarTrim;
use App\Models\CarYear;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Traits\LoggingTrait;
use Illuminate\Support\Facades\Log;

class GetTrims extends Command
{
    use LoggingTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-trims';

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
        try {
            // DB::beginTransaction();

            $years = CarYear::whereBetween('id', [4, 16])->get();
            $makes = CarMake::where('id', '>', 6)->get();
            
            $data = array();
            foreach($makes as $make) {
                $make_name = $make->name;
                $models = CarModel::where('car_make_id', $make->id)
                    ->where('id', '>', 16)->get();
                foreach($years as $year) {
                    foreach($models as $model) {
                    $model_name = $model->name;
                        $this->info('started');
                        $exist = CarTrim::where([
                            'car_year' => $year->name,
                            'car_make_id' => $make->id,
                            'car_model_id' => $model->id
                        ])->exists();

                        if(!$exist) {
                            $year_name = $year->name;
                            Log::info("https://www.carqueryapi.com/api/0.3/?cmd=getTrims&make=$make_name&year=$year_name&model=$model_name&full_results=1");
                            $result = Http::withoutVerifying()->asForm()->get("https://www.carqueryapi.com/api/0.3/?cmd=getTrims&make=$make_name&year=$year_name&model=$model_name&full_results=1");
                            Log::info($result->json());
                            if($result->json()) {
                                if($result->json()['Trims']) {
                                    $bar = $this->output->createProgressBar(count($result->json()['Trims']));
                                    $bar->start();
                                    $this->newLine();
                                    $this->info("$make_name | $model_name | $year_name");
                                    foreach($result->json()['Trims'] as $trim) {
                                        $trim['name'] = $trim['model_trim'];
                                        $trim['car_year'] = $year_name;
                                        $trim['car_make_id'] = $make->id;
                                        $trim['car_model_id'] = $model->id;

                                        $car_trim = new CarTrim();
                                        $car_trim->fill($trim);
                                        $car_trim->save();

                                        $bar->advance();
                                    }
                                    $bar->finish();
                                    $this->newLine();
                                    $this->info("Completed");
                                }
                            }
                        }

                        sleep(1);
                    }
                }
            }

            
            // DB::commit();
        } catch(\Exception $e) {
            // DB::rollBack();
            $this->logError($e);
        }
    }
}
