<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lov;
use App\Models\User;
use App\Rules\PhoneNumber;
use App\Services\DonorService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $genders = Lov::where('lov_category_id', 1)->get();
        $titles = Lov::where('lov_category_id', 2)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $roles = Role::all();
        return view('auth.register', compact('genders', 'titles', 'bloodGroups', 'roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', new PhoneNumber],
            'blood_group' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'age' => 'required|integer|min:0',
        ]);

        $user = User::create([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'blood_group' => $request->blood_group,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'age' => $request->age,
        ]);
        $user->syncRoles([5]);

        // Create Donor record linked to User
        $donorService = new DonorService();
        $donorService->createDonorFromUser([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'blood_group' => $request->blood_group,
            'gender' => $request->gender,
            'dob' => $request->dob,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('backend.donor.dashboard', absolute: false))
            ->with('success', 'Registration successful! Welcome to Vital Blood.');
    }
}
