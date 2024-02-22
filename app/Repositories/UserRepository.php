<?php

namespace App\Repositories;

use App\Events\RefreshTokenEvent;
use App\Interfaces\UserInterface;
use App\Models\PassportToken;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\GlobalService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Defuse\Crypto\Crypto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class UserRepository extends GlobalService implements UserInterface {


    public function getUser(array $condition, array $with = [])
    {
        return $this->model(User::class, $condition, $with);
    }

    public function saveUser(array $data) {

        try {
            DB::beginTransaction();

            $exist = $this->checkUserByEmail($data['email']);

            if($exist)
                return false;

            $setting = $data['setting'];
            unset($data['setting']);

            $user = new User();
            $user->fill($data);
            $user->save();
            $user->userSetting()->create($setting);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            $this->logError([
                "function" => 'saveUser',
                "data" => $e
            ]);
            DB::rollBack();
            return false;
        }
    }

    public function updateUser($data) {
        try {
            DB::beginTransaction();

            $setting = $data['setting'];
            unset($data['setting']);
            
            if(empty($data['password'])) 
                unset($data['password']);

            $user = User::find($data['id']);
            $user->fill($data);
            $user->save();
            // $user->userSetting()->update($setting);

            $userSetting = UserSetting::where('user_id', $user->id)->first();
            $userSetting->fill($setting);
            $userSetting->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            $this->logError([
                "function" => 'updateUser',
                "data" => $e
            ]);
            DB::rollBack();
            return false;
        }

    }

    private function checkUserByEmail($email) {
        return User::where('email', $email)->first();
    }

    
    public function oauth($email, $password, $user) {
        try {
            $url = $this->getSetting('api_url')->value;
            $client_id = $this->getClient()->id;
            $client_secret = $this->getClient()->secret;

            $url = "$url/oauth/token";
            $response = Http::withoutVerifying()->asForm()->post($url, [
                'grant_type' => 'password',
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'username' => $email,
                'password' => $password,
                'scope' => ''
            ]);

            
            $exipres = $this->getSetting('token_expiry');
            $refresh = $this->getSetting('token_refresh');

            $client = $response->json();
            $client['active_time'] = $this->systemTimestamp();
            $client['token_expires'] = $this->systemTimestamp() + $exipres->value;
            $client['refresh_expires'] = $this->systemTimestamp() + $refresh->value;
            $client['user_id'] = $user['id'];

            $this->createOrUpdatePassportToken($client);

            unset($client['user_id']);
            $client['user'] = $user;

            return $client;
        } catch (\Exception $e) {
            $this->logError([
                "function" => 'oauth',
                "data" => $e
            ]);

            return false;
        }
    }

    public function refresh($user_id) {
        try {
            $url = $this->getSetting('api_url')->value;
            $client_id = $this->getClient()->id;
            $client_secret = $this->getClient()->secret;

            $token = PassportToken::where('user_id', $user_id)->first();

            $url = "$url/oauth/token";
            $rt = Http::asForm()->post($url, [
                'grant_type' => 'refresh_token',
                'refresh_token' => $token['refresh_token'],
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'scope' => ''
            ]);

            $exipres = $this->getSetting('token_expiry');
            $refresh = $this->getSetting('token_refresh');

            $client = $rt->json();
            $status = $rt->status();

            $client['active_time'] = $this->systemTimestamp();
            $client['token_expires'] = $this->systemTimestamp() + $exipres->value;
            $client['refresh_expires'] = $this->systemTimestamp() + $refresh->value;
            $client['user_id'] = $user_id;

            $this->createOrUpdatePassportToken($client);

            unset($client['user_id']);
            $client['user'] = User::find($user_id);

            return $client;
        } catch (\Exception $e) {
            $this->logError([
                "function" => 'refresh',
                "data" => $e
            ]);

            return false;
        }
    }

    public function logoutUser(int $user_id) {


        try {
            $token = PassportToken::where('is_active', 1)->where('user_id', $user_id)->first();

            if($token) {
                $token->active_time = null;
                $token->is_active = 0;
                $token->save();
            }

            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            $access_token = $tokenRepository->getLatestTokenByUserId($user_id);

            $tokenRepository->revokeAccessToken($access_token->id);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($access_token->id);

            broadcast(new RefreshTokenEvent($user_id, [], false));

            return true;
        } catch (\Exception $e) {

            $this->logError([
                "function" => 'logoutUser',
                "data" => $e
            ]);

            broadcast(new RefreshTokenEvent($user_id, [], false));
            return false;
        }

    }

    
    protected function checkRefreshToken($refresh_token) {
        $app_key = config('app.key');
        $enc_key = base64_decode(substr($app_key, 7));
        try {
            $crypto = Crypto::decryptWithPassword($refresh_token, $enc_key);
        } catch (\Exception $exception){
            return $exception;
        }
        return  json_decode($crypto, true);
    }

    protected function createOrUpdatePassportToken($data) {
        $token = PassportToken::where('user_id', $data['user_id'])->first();

        if(!$token)
            $token = new PassportToken();

        $token->fill($data);
        $token->save();
    }

}