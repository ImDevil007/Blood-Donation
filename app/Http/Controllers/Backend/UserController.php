<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lov;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Services\Backend\Admin\UserStoreService;
use App\Services\Backend\Admin\UserUpdateService;

class UserController extends Controller
{
    protected $storeService, $updateService;
    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct(UserStoreService $storeService, UserUpdateService $updateService)
    {
        $this->storeService = $storeService;
        $this->updateService = $updateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $blood = $request->input('blood_group');
        $gender = $request->input('gender');

        $users = User::with(['userBloodGroup', 'usergender', 'roles'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($role, fn($q) => $q->whereHas('roles', fn($q2) => $q2->where('id', $role)))
            ->when($blood, fn($q) => $q->where('blood_group', $blood))
            ->when($gender, fn($q) => $q->where('gender', $gender))
            ->paginate(10);

        $roles = Role::all();
        $genders = Lov::where('lov_category_id', 1)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();

        return view('backend.users.index', compact('users', 'roles', 'bloodGroups', 'genders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genders = Lov::where('lov_category_id', 1)->get();
        $titles = Lov::where('lov_category_id', 2)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $roles = Role::all();
        return view('backend.users.create', compact('genders', 'titles', 'bloodGroups', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->storeService->store($request->all());
        return redirect()->route('backend.admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $genders = Lov::where('lov_category_id', 1)->get();
        $titles = Lov::where('lov_category_id', 2)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('backend.users.edit', compact('genders', 'titles', 'bloodGroups', 'user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->updateService->update($user, $request->all());
        return redirect()->route('backend.admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('backend.admin.users.index')->with('success', 'User deleted.');
    }
}
