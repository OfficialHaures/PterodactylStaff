<?php

namespace App\Http\Controllers;

use App\Models\StaffRole;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\StaffPermission;

class StaffRoleController extends Controller
{
    public function index()
    {
        return response()->json(StaffRole::with('users')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:staff_roles,name',
            'permissions' => 'required|array'
        ]);

        $role = new StaffRole();
        if (!$role->validatePermissions($request->permissions)) {
            return response()->json(['error' => 'Invalid permissions'], 422);
        }

        $role = StaffRole::create([
            'name' => $request->name,
            'permissions' => $request->permissions
        ]);

        return response()->json($role, 201);
    }

    public function update(Request $request, StaffRole $role)
    {
        $request->validate([
            'name' => 'required|string|unique:staff_roles,name,' . $role->id,
            'permissions' => 'required|array'
        ]);

        if (!$role->validatePermissions($request->permissions)) {
            return response()->json(['error' => 'Invalid permissions'], 422);
        }

        $role->update([
            'name' => $request->name,
            'permissions' => $request->permissions
        ]);

        return response()->json($role);
    }

    public function destroy(StaffRole $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:staff_roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = StaffRole::findOrFail($request->role_id);

        $user->staffRoles()->sync([$role->id], false);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:staff_roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->staffRoles()->detach($request->role_id);

        return response()->json(['message' => 'Role removed successfully']);
    }

    public function getAvailablePermissions()
    {
        return response()->json(array_map(fn($case) => $case->value, StaffPermission::cases()));
    }
}
