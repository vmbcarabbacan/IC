<?php

namespace App\Console\Commands;

use App\Events\RefreshTokenEvent;
use App\Models\PassportToken;
use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class TokenChecker extends Command
{
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:token-checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the token and refresh if about to expire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tokens = PassportToken::where('is_active', 1)->get();
        $token_check = $this->userRepository->getSetting('token_check');

        foreach($tokens as $token) {
            $token_expires = ($token->token_expires - $this->userRepository->systemTimestamp()) / 60;
            $refresh_expires = ($token->refresh_expires - $this->userRepository->systemTimestamp()) / 60;

            if($token_expires <= (int) $token_check->value || $refresh_expires <= (int) $token_check->value) {
                $refresh = $this->userRepository->refresh($token->user_id);

                broadcast(new RefreshTokenEvent($token->user_id, $refresh));
            } else {
                $token->is_active = false;
                $token->active_time = null;
                $token->save();

                $tokenRepository = app(TokenRepository::class);
                $refreshTokenRepository = app(RefreshTokenRepository::class);

                $access_token = $tokenRepository->getLatestTokenByUserId($token->user_id);

                $tokenRepository->revokeAccessToken($access_token->id);
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($access_token->id);

                broadcast(new RefreshTokenEvent($token->user_id, [], false));
            }

            sleep(2);
        }
    }
}
