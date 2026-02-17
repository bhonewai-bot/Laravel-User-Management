<?php

namespace App\Support\Permissions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function keysForRole(int $roleId): array
    {
        $cacheKey = "role_permission_keys_v2:{$roleId}";

        return Cache::remember($cacheKey, now()->addHour(), function () use ($roleId) {
            $rows = DB::table('role_permissions as rp')
                ->join('permissions as p', 'p.id', '=', 'rp.permission_id')
                ->join('features as f', 'f.id', '=', 'p.feature_id')
                ->where('rp.role_id', $roleId)
                ->selectRaw("CONCAT(f.name, '.', p.name) as permission_key")
                ->orderBy('permission_key')
                ->pluck('permission_key')
                ->all();

            return array_values(array_unique($rows));
        });
    }

    public function keysForCurrentUser(): array
    {
        $user = auth()->user();
        if (!$user) return [];

        $userId = (int)$user->id;
        $roleId = (int)$user->role_id;

        $sessionKey = "permission_keys_user_v2:{$userId}";

        $payload = session($sessionKey);

        if (is_array($payload) && ($payload['role_id'] ?? null) === $roleId && is_array($payload['keys'] ?? null)) {
            return $payload['keys'];
        }

        $keys = $this->keysForRole($roleId);

        session([
            $sessionKey => [
                'role_id' => $roleId,
                'keys' => $keys
            ]
        ]);

        return $keys;
    }

    public function hasPermission(string $requiredKey): bool
    {
        return in_array($requiredKey, $this->keysForCurrentUser(), true);
    }

    public function clearRoleCache(int $roleId): void
    {
        Cache::forget("role_permission_keys_v2:{$roleId}");
    }

    public function clearCurrentUserSessionCache(): void
    {
        $user = auth()->user();
        if (!$user) return;

        $userId = (int)$user->id;
        session()->forget("permission_keys_user_v2:{$userId}");
    }
}
