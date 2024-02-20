<?php

namespace App\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Models\UserSetting;
use App\Models\User;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Log;

class UserSettingObserver extends GlobalService implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the UserSetting "created" event.
     */
    public function created(UserSetting $userSetting): void
    {
        //
    }

    /**
     * Handle the UserSetting "updated" event.
     */
    public function updated(UserSetting $userSetting): void
    {
        $attemps = $this->getSetting('login_attempts')->value;
        $user = User::find($userSetting->user_id);


        if($userSetting->wasChanged('status')) {
            $user->status = $userSetting->status;
            $user->saveQuietly();
        }

        if($userSetting->failed_attempt == $attemps)
            $userSetting->status = 0;

        $userSetting->saveQuietly();
    }

    /**
     * Handle the UserSetting "deleted" event.
     */
    public function deleted(UserSetting $userSetting): void
    {
        //
    }

    /**
     * Handle the UserSetting "restored" event.
     */
    public function restored(UserSetting $userSetting): void
    {
        //
    }

    /**
     * Handle the UserSetting "force deleted" event.
     */
    public function forceDeleted(UserSetting $userSetting): void
    {
        //
    }
}
