<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;
use App\Traits\LoggingTrait;
use App\Interfaces\UserInterface;

class UserController extends Controller
{
    use ResponseTrait, LoggingTrait;

    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }
    
    /**
     * @OA\Post (
     *     path="/private/user/save",
     *     summary="Add new user",
     *     security={{"bearerAuth":{}}},
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(ref="#/components/schemas/UserSchema"),
     *                 example={
     *                      "name":"Vincent",
     *                      "email":"vincent@test.com",
     *                      "password":"Password@123",
     *                      "setting": {
     *                          "role_id": 1,
     *                          "team_leader_id": 0,
     *                          "underwriter_id": 0,
     *                          "is_underwriter": 0,
     *                          "is_round_robin": 0,
     *                          "agent_type": 0,
     *                          "renewal_deals": 0,
     *                          "insurance_type": 1,
     *                          "status": 1
     *                      }
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User save successfully"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="name", type="string", example="Vincent"),
     *                  @OA\Property(property="email", type="string", example="vincent@test.com"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-02-23T16:39:54.000000Z"),
     *                  @OA\Property(property="created_at", type="string", example="2024-02-23T16:39:54.000000Z"),
     *                  @OA\Property(property="id", type="number", example=101),
     *                  @OA\Property(property="status_text", type="string", example="Active")
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      )
     * ),
     */
    public function saveUser(Request $request) {
        $data = $request->all();

        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required'
            ];
    
            $validator = Validator::make($data, $rules);
    
            if($validator->fails())
                $this->resValidation('Validation Error', $validator->errors());
    
            $user = $this->userInterface->saveUser($data);
    
            if($user)
                return $this->resSuccess('User save successfully', $user);
    
            return $this->resInvalid('Something went wrong', []);
        } catch (\Exception $e) {
            $this->logError([
                'function' => 'saveUser',
                'data' => $e
            ]);
        }
    }
    
    
    /**
    * @OA\Post (
    *     path="/private/user/update",
    *     summary="Update user by id",
    *     security={{"bearerAuth":{}}},
    *     tags={"Users"},
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                  @OA\Property(
    *                     property="id",
    *                     type="number"
    *                 ),
    *                 @OA\Property(ref="#/components/schemas/UserSchema"),
    *                 example={
    *                      "id": 100,
    *                      "name":"Vincent",
    *                      "email":"vincent@test.com",
    *                      "password":"Password@123",
    *                      "setting": {
    *                          "role_id": 1,
    *                          "team_leader_id": 0,
    *                          "underwriter_id": 0,
    *                          "is_underwriter": 0,
    *                          "is_round_robin": 0,
    *                          "agent_type": 0,
    *                          "renewal_deals": 0,
    *                          "insurance_type": 1,
    *                          "status": 1
    *                      }
    *                }
    *             )
    *         )
    *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User save successfully"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="name", type="string", example="Vincent"),
     *                  @OA\Property(property="email", type="string", example="vincent@test.com"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-02-23T16:39:54.000000Z"),
     *                  @OA\Property(property="created_at", type="string", example="2024-02-23T16:39:54.000000Z"),
     *                  @OA\Property(property="id", type="number", example=101),
     *                  @OA\Property(property="status_text", type="string", example="Active")
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
    *      @OA\Response(
    *          response=401,
    *          description="Validation error",
    *          @OA\JsonContent(
    *              @OA\Property(property="meta", type="string", example="Unauthenticated")
    *          )
    *      ),
    * )
    *
    */
    public function updateUser(Request $request) {
        $data = $request->all();

        try {
            $rules = [
                'id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required'
            ];
    
            $validator = Validator::make($data, $rules);
    
            if($validator->fails())
                $this->resValidation('Validation Error', $validator->errors());
    
            $user = $this->userInterface->updateUser($data);
    
            if($user)
                return $this->resSuccess('User updated successfully', $user);
    
            return $this->resInvalid('Something went wrong', []);
        } catch (\Exception $e) {
            $this->logError([
                'function' => 'updateUser',
                'data' => $e
            ]);
        }
    }
}
