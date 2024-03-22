<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;
use App\Interfaces\MasterInterface;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarTrim;
use App\Models\CarYear;

class MasterController extends Controller
{
    use ResponseTrait;

    public $masterInterface;

    public function __construct(MasterInterface $masterInterface)
    {
        $this->masterInterface = $masterInterface;
    }

    /**
     * @OA\Post(
     *     path="/configuration/add-make",
     *     summary="Create or update maker",
     *      tags={"Master"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                  ),
     *                 example={
     *                      "name":"Audi",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Login successfully"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="token_type", type="string", example="Bearer"),
     *                  @OA\Property(property="expires_in", type="number", example="900"),
     *                  @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9"),
     *                  @OA\Property(property="refresh_token", type="string", example="def50200af22d480ac24fea3e0bdc8747f4"),
     *                  @OA\Property(property="active_time", type="string", example=1708683263),
     *                  @OA\Property(property="token_expires", type="string", example=1708683278),
     *                  @OA\Property(property="refresh_expires", type="string", example=1708683293),
     *                  @OA\Property(property="user", type="object",
     *                      @OA\Property(property="id", type="number", example=100),
     *                      @OA\Property(property="name", type="string", example="vincent mark"),
     *                      @OA\Property(property="email", type="string", example="vmbcarabbacan@gmail.com"),
     *                      @OA\Property(property="email_verified_at", type="string", example=null),
     *                      @OA\Property(property="status", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-02-22T17:29:46.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-02-22T17:29:46.000000Z"),
     *                      @OA\Property(property="status_text", type="string", example="Active")
     *                  ),
     *              ),
     *          )
     *      ),
     *     @OA\Response(response="403", description="Invalid credentials"),
     * )
     */
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
        if($type == 'model') {
            $rules = [ 'car_make_id' => 'required|exists:car_makes,id' ];
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
                return $this->resValidation('Model validation failure', $validator->errors());

            $data = $this->getValues(CarModel::class, ['car_make_id' => $request->car_make_id]);
        }
            
        if($type == 'make')
            $data = $this->getValues(CarMake::class);

        if($type == 'year')
            $data = $this->getValues(CarYear::class);

        if(isset($data))
            return $this->resSuccess("Get $type", $data);

        return $this->resInvalid('Something went wrong', []);
    }

    public function getCarDetails(Request $request) {
        $data = $request->all();

        $conditions = array();
        $unique = 'label';
        
        if(isset($data['car_make_id'])) $conditions['car_make_id'] = $data['car_make_id'];
        if(isset($data['car_year'])) $conditions['car_year'] = $data['car_year'];
        if(isset($data['car_model_id'])) $conditions['car_model_id'] = $data['car_model_id'];

        if(isset($data['full_details']) && $data['full_details'] == true) 
            $trims = CarTrim::where($conditions)->get();
        else 
            $trims = $this->getValues(CarTrim::class, $conditions);

        $trims = collect($trims);

        if(isset($data['single']) && $data['single'] == true)
            $trims = $trims->unique($unique);

        $trims = $trims->filter(function($q) {
                if($q->label || $q->name)
                    return $q;
            });

        if(isset($data['take']))
            $trims = $trims->take($data['take']);

        $trim = array();
        foreach($trims as $key => $value)
            $trim[] = $value;

        return $trim;
    }

    private function getValues($model, $conditions = null) {
        $value = $model::select('id as value', 'name as label');

        if($conditions)
            $value = $value->where($conditions);

        return $value->get();
    }
}
