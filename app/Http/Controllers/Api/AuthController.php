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
     * @SWG\Get(
     *     path="/users",
     *     summary="Get a list of users",
     *     tags={"Users"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
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

    public function logout($user_id) {
        if($this->userInterface->logoutUser($user_id))
            return $this->resSuccess('Logout successfully');

        return $this->resInvalid('Logout unsuccessfully');
    }


}
