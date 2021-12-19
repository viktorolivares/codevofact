<?php

namespace App\Traits;
use Modules\Offline\Models\OfflineConfiguration;

trait CommandTrait
{
    /**
     * Is offline
     * @return boolean
     */
    private function isOffline() {
        return (bool) OfflineConfiguration::firstOrFail()->is_client;
    }
}
