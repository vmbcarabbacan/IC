<?php

namespace App\Repositories;

use App\Interfaces\MasterInterface;
use App\Models\CarModel;
use App\Models\CarMake;
use App\Models\CarYear;

class MasterRepository implements MasterInterface {

    public function addOrUpdateMake(array $data)
    {
        $make = '';

        if(isset($data['id']))
            $make = CarMake::find($data['id']);

        if(!$make)
            $make = new CarMake();
        
        $make->fill($data);
        $make->save();
    }

    public function addOrUpdateModel(array $data)
    {
        $model = '';
        
        if(isset($data['id']))
            $model = CarModel::find($data['id']);

        if(!$model)
            $model = new CarModel();
        
        $model->fill($data);
        $model->save();
    }

    public function addOrUpdateYear(array $data)
    {
        $year = '';
        
        if(isset($data['id']))
            $year = CarYear::find($data['id']);

        if(!$year)
            $year = new CarYear();
        
        $year->fill($data);
        $year->save();
    }
}