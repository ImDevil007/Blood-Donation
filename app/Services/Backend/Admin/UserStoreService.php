<?php

namespace App\Services\Backend\Admin;

use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserStoreService
{
    public function store(array $requestData): User
    {
        $validated = $this->validate($requestData);

        $user = User::create([
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'blood_group' => $validated['blood_group'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'age' => $validated['age'],
        ]);

        $user->syncRoles($validated['roles']);

        return $user;
    }

    protected function validate(array $data): array
    {
        return validator($data, [
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', new PhoneNumber],
            'password' => 'required|confirmed|min:6',
            'blood_group' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'age' => 'required|integer|min:0',
            'roles' => 'required|array',
        ])->validate();
    }
}
