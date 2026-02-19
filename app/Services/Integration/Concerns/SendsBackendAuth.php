<?php

namespace App\Services\Integration\Concerns;

trait SendsBackendAuth
{
    protected function backendHeaders(): array
    {
        $auth = config('services.backend.authorization');
        return $auth ? ['Authorization' => $auth] : [];
    }
}
