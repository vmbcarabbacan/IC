<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CustomerInterface;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;
use App\Traits\LoggingTrait;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use ResponseTrait, LoggingTrait;

    protected $customerInterface;

    public function __construct(CustomerInterface $customerInterface)
    {
        $this->customerInterface = $customerInterface;
    }

    /**
    * @OA\Post (
    *     path="/public/api/add-customer",
    *     summary="Add new Customer with a Quick Lead",
    *     tags={"Public Customers"},
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                  @OA\Property(
    *                     property="id",
    *                     type="number"
    *                 ),
    *                 @OA\Property(ref="#/components/schemas/CustomerQLSchema"),
    *                 example={
    *                      "name":"Vincent Mark",
    *                      "email":"vmbcarabbacan@gmail.com",
    *                      "country_code":"+971",
    *                      "phone_number":"566368779",
    *                      "other_contact_info": null,
    *                      "insurance_type":1,
    *                }
    *             )
    *         )
    *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Customer added/updated successfully"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="name", type="string", example="Vincent Mark"),
     *                  @OA\Property(property="email", type="string", example="vmbcarabbacan@gmail.com"),
     *                  @OA\Property(property="country_code", type="string", example="+971"),
     *                  @OA\Property(property="phone_number", type="string", example="566368779"),
     *                  @OA\Property(property="other_contact_info", type="string", example=null),
     *                  @OA\Property(property="updated_at", type="string", example="2024-02-23T16:39:54.000000Z"),
     *                  @OA\Property(property="created_at", type="string", example="2024-02-23T16:39:54.000000Z"),
     *                  @OA\Property(property="id", type="number", example=18),
     *                  @OA\Property(property="created_by", type="number", example=1),
     *                  @OA\Property(property="complete_phone_number", type="string", example="971566368779")
     *              ),
     *          )
     *      ),
     *     @OA\Response(response=400, description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Something went wrong"),
     *              @OA\Property(property="data", type="array", maxItems=0,
     *                  @OA\Items()
     *              )
     *          )
     *      ),
    * )
    *
    */
    public function addCustomer(Request $request) {
        $rules = [
            'country_code' => 'required',
            'phone_number' => 'required',
            'name' => 'required',
            'insurance_type' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) return $this->resValidation('Add customer validation failure', $validator->errors());

        try {
            DB::beginTransaction();
            $customer = $this->customerInterface->createOrUpdate($data);

            DB::commit();
            return $this->resSuccess('Customer added/updated successfully', $customer);
        } catch (\Exception $e) {
            $this->logError([
                "function" => "addCustomer",
                "data" => $e
            ]);
            DB::rollBack();
        }
        
    }
}
