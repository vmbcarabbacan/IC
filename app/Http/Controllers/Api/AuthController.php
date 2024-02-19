<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\LoggingTrait;
use App\Traits\ResponseTrait;
use App\Interfaces\UserInterface;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Http;

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
            return $this->resUnauthenticated('Email do not exist!', []);

        if(!password_verify($data['password'], $exist->password));
            return $this->resUnauthenticated('Invalid credentials', []);

        if($exist->status == 0)
            return $this->resUnauthenticated('User is inactive', []);

        return $this->resSuccess('Login successful', $this->oauth($data['email'], $data['password']));
    }

    protected function oauth($email, $password) {
        $url = $this->global->getSetting('api_url')->value;
        $client_id = $this->global->getClient()->id;
        $client_secret = $this->global->getClient()->secret;

        $url = "$url/oauth/token";
        $response = Http::withoutVerifying()->asForm()->post($url, [
            'grant_type' => 'password',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'username' => $email,
            'password' => $password,
            'scope' => 'api'
        ]);

        return $response->json();
    }

}
