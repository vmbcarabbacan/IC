<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\LoggingTrait;
use App\Traits\ResponseTrait;
use App\Interfaces\UserInterface;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use LoggingTrait, ResponseTrait;


    private $userInterface, $global;

    public function __construct(UserInterface $userInterface, GlobalService $global)
    {
        $this->userInterface = $userInterface;
        $this->global = $global;
    }
    /**
     * @OA\Post(
     *     path="/private/login",
     *     summary="Authenticate user and generate personal access token",
     *      tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                  ),
     *                 example={
     *                      "email":"vincent@test.com",
     *                      "password":"M0a3r1k5",
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
    public function login(Request $request) {
        $rules = [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails())
            return $this->resValidation('Login validation failure', $validator->errors());

        $exist = $this->userInterface->getUser([ 'email' => $data['email'] ]);

        if(!$exist)
            return $this->resUnauthenticated('Email do not exist!');

        if(!password_verify($data['password'], $exist->password))
            return $this->resUnauthenticated('Invalid credentials');

        if($exist->status == 0)
            return $this->resUnauthenticated('User is inactive');

        return $this->resSuccess('Login successfully', $this->userInterface->oauth($data['email'], $data['password'], $exist->toArray()));
    }

    /**
     * @OA\Get(
     *     path="/private/user/logout/{user_id}",
     *     summary="Logout the user",
     *     tags={"Users"},
     *      @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          description="user id",
     *          required=true, 
     *      ),
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Logout successfully"),
     *              @OA\Property(property="data", type="array", maxItems=0,
     *                  @OA\Items()
     *              )
     *          )
     *      ),
     *     @OA\Response(response=403, description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized access"),
     *              @OA\Property(property="data", type="object",
        *              @OA\Property(property="token_checker", type="boolean", example=false)
     *              )
     *          )
     *      ),
     * )
     */
    public function logout($user_id) {
        if($this->userInterface->logoutUser($user_id))
            return $this->resSuccess('Logout successfully');

        return $this->resInvalid('Logout unsuccessfully');
    }


}
