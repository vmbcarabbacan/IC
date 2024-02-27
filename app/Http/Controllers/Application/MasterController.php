<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;
use App\Interfaces\MasterInterface;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarYear;

class MasterController extends Controller
{
    use ResponseTrait;

    public $masterInterface;

    public function __construct(MasterInterface $masterInterface)
    {
        $this->masterInterface = $masterInterface;
    }

    public function addMake(Request $request) {
        $data = $request->all();

        $rules = [
            'name' => 'required'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
            return $this->resValidation('Make validation failure', $validator->errors());

       return $this->resSuccess('Add/Update Make', $this->masterInterface->addOrUpdateMake($data)); 

    }

    public function addModel(Request $request) {
        $data = $request->all();

        $rules = [
            'car_make_id' => 'required|exists:car_makes,id',
            'name' => 'required'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
            return $this->resValidation('Model validation failure', $validator->errors());

       return $this->resSuccess('Add/Update Model', $this->masterInterface->addOrUpdateModel($data)); 

    }

    public function addYear(Request $request) {
        $data = $request->all();

        $rules = [
            'name' => 'required'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails())
            return $this->resValidation('Year validation failure', $validator->errors());

       return $this->resSuccess('Add/Update Year', $this->masterInterface->addOrUpdateYear($data)); 
    }

    public function getMaster($type, Request $request) {
        if($type == 'model')
            $data = $this->getValues(CarModel::class, ['car_make_id' => $request->car_make_id]);

        if($type == 'make')
            $data = $this->getValues(CarMake::class);

        if($type == 'year')
            $data = $this->getValues(CarYear::class);

        if(isset($data))
            return $this->resSuccess("Get $type", $data);

        return $this->resInvalid('Something went wrong', []);
    }

    private function getValues($model, $conditions = null) {
        $value = $model::select('id as value', 'name as label');

        if($conditions)
            $value = $value->where($conditions);

        return $value->get();
    }
}
