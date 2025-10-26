<?php

declare(strict_types=1);

namespace App\Actions\User;

use Illuminate\Support\Facades\Config;

final class ActiveOauthProviderAction
{
    /**
     * Get all active OAuth providers.
     *
     * @return array<int, array{slug: string, active: bool, icon: string}>
     */
    public function handle(): array
    {
        /** @var array<int, array{slug: string, active: bool, icon: string}> $providers */
        $providers = Config::array('oauth.providers');

        return array_filter($providers, fn (array $provider): bool => $provider['active']);
    }
}
