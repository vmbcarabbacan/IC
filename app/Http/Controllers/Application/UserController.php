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
