<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:staff_roles,name,' . ($this->staff_role->id ?? ''),
            'permissions' => 'required|array',
            'permissions.*' => 'string'
        ];
    }
}
