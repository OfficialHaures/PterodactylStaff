<?php

namespace App\Traits;

use App\Models\StaffRole;

trait HasStaffRoles
{
    public function staffRoles()
    {
        return $this->belongsToMany(StaffRole::class, 'user_roles', 'user_id', 'role_id');
    }

    public function hasPermission($permission)
    {
        return $this->staffRoles->flatMap->permissions->contains($permission);
    }

    public function hasAnyPermission(array $permissions)
    {
        return $this->staffRoles->flatMap->permissions->intersect($permissions)->isNotEmpty();
    }

    public function hasAllPermissions(array $permissions)
    {
        return $this->staffRoles->flatMap->permissions->intersect($permissions)->count() === count($permissions);
    }
}
