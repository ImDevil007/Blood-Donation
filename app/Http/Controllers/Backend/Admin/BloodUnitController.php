<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\Donor;
use App\Models\Lov;
use Illuminate\Http\Request;
use App\Services\Backend\Admin\BloodUnitStoreService;
use App\Services\Backend\Admin\BloodUnitUpdateService;
use Illuminate\Support\Facades\Auth;

class BloodUnitController extends Controller
{
    protected $storeService, $updateService;

    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct(BloodUnitStoreService $storeService, BloodUnitUpdateService $updateService)
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
        $bloodGroup = $request->input('blood_group');
        $bloodType = $request->input('blood_type');
        $status = $request->input('status');
        $expiryFilter = $request->input('expiry_filter');

        $bloodUnits = BloodUnit::with(['donor', 'bloodGroup', 'bloodType', 'createBy'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('unit_id', 'like', "%{$search}%")
                    ->orWhere('storage_location', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('donor', fn($q2) => $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            }))
            ->when($bloodGroup, fn($q) => $q->where('blood_group', $bloodGroup))
            ->when($bloodType, fn($q) => $q->where('blood_type', $bloodType))
            ->when($status !== null, fn($q) => $q->where('is_used', $status))
            ->when($expiryFilter === 'expired', fn($q) => $q->where('expiry_date', '<=', now()))
            ->when($expiryFilter === 'expiring_soon', fn($q) => $q->where('expiry_date', '<=', now()->addDays(7))
                ->where('expiry_date', '>', now()))
            ->orderBy('expiry_date', 'desc')
            ->paginate(10);

        $bloodGroups = Lov::where('lov_category_id', 1)->get();
        $bloodTypes = Lov::where('lov_category_id', 2)->get();

        return view('backend.admin.blood-units.index', compact('bloodUnits', 'bloodGroups', 'bloodTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();
        $donors = Donor::where('is_eligible', true)->where('status', true)->get();

        return view('backend.admin.blood-units.create', compact('bloodGroups', 'bloodTypes', 'donors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->storeService->store($request->all());
        return redirect()->route('backend.admin.blood-units.index')->with('success', 'Blood unit added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodUnit $bloodUnit)
    {
        $bloodUnit->load(['donor', 'bloodGroup', 'bloodType', 'createBy', 'updateBy']);

        return view('backend.admin.blood-units.show', compact('bloodUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodUnit $bloodUnit)
    {
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();
        $donors = Donor::where('is_eligible', true)->where('status', true)->get();

        return view('backend.admin.blood-units.edit', compact('bloodGroups', 'bloodTypes', 'donors', 'bloodUnit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodUnit $bloodUnit)
    {
        $this->updateService->update($bloodUnit, $request->all());
        return redirect()->route('backend.admin.blood-units.index')->with('success', 'Blood unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodUnit $bloodUnit)
    {
        $bloodUnit->delete();
        return redirect()->route('backend.admin.blood-units.index')->with('success', 'Blood unit deleted successfully.');
    }

    /**
     * Mark blood unit as used.
     */
    public function markAsUsed(Request $request, BloodUnit $bloodUnit)
    {
        $request->validate([
            'used_for' => 'required|string|max:255',
        ]);

        $bloodUnit->update([
            'is_used' => true,
            'used_date' => now(),
            'used_for' => $request->used_for,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Blood unit marked as used successfully.');
    }

    /**
     * Get blood unit statistics for dashboard.
     */
    public function getStats()
    {
        $totalUnits = BloodUnit::count();
        $availableUnits = BloodUnit::available()->count();
        $usedUnits = BloodUnit::where('is_used', true)->count();
        $expiredUnits = BloodUnit::expired()->count();
        $expiringSoon = BloodUnit::expiringSoon()->count();

        return response()->json([
            'total_units' => $totalUnits,
            'available_units' => $availableUnits,
            'used_units' => $usedUnits,
            'expired_units' => $expiredUnits,
            'expiring_soon' => $expiringSoon,
        ]);
    }

    /**
     * Get donor blood group for auto-fill.
     */
    public function getDonorBloodGroup(Donor $donor)
    {
        return response()->json([
            'blood_group' => $donor->blood_group,
        ]);
    }
}
