<?php

namespace App\Observers;

use App\Models\UserSetting;
use App\Services\GlobalService;

class UserSettingObserver extends GlobalService
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
