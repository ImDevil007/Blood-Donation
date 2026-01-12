<?php

namespace App\Services\Backend\Admin;

use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Hash;

class UserUpdateService
{
    public function update(User $user, array $requestData): User
    {
        $validated = $this->validate($user, $requestData);

        $updateData = [
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'blood_group' => $validated['blood_group'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'age' => $validated['age'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->syncRoles($validated['roles']);

        return $user;
    }

    protected function validate(User $user, array $data): array
    {
        return validator($data, [
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'phone' => ['required', new PhoneNumber],
            'blood_group' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'age' => 'required|integer|min:0',
            'roles' => 'required|array',
        ])->validate();
    }
}
