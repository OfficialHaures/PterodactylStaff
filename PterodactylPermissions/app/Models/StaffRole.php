<?php

namespace App\Models;

use App\Enums\StaffPermission;
use Illuminate\Database\Eloquent\Model;

class StaffRole extends Model
{
    protected $fillable = ['name', 'permissions'];
    
    protected $casts = [
        'permissions' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    public function validatePermissions(array $permissions)
    {
        $validPermissions = array_map(fn($case) => $case->value, StaffPermission::cases());
        return empty(array_diff($permissions, $validPermissions));
    }
}
