<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\Lov;
use Illuminate\Http\Request;
use App\Services\Backend\Admin\BloodInventoryStoreService;
use App\Services\Backend\Admin\BloodInventoryUpdateService;
use Illuminate\Support\Facades\Auth;

class BloodInventoryController extends Controller
{
    protected $storeService, $updateService;

    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct(BloodInventoryStoreService $storeService, BloodInventoryUpdateService $updateService)
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

        $inventories = BloodInventory::with(['bloodGroup', 'bloodType', 'createBy'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('inventory_id', 'like', "%{$search}%")
                    ->orWhere('storage_location', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            }))
            ->when($bloodGroup, fn($q) => $q->where('blood_group', $bloodGroup))
            ->when($bloodType, fn($q) => $q->where('blood_type', $bloodType))
            ->when($status !== null, fn($q) => $q->where('status', $status))
            ->orderBy('inventory_id', 'desc')
            ->paginate(10);

        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();

        return view('backend.admin.blood-inventory.index', compact('inventories', 'bloodGroups', 'bloodTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();

        return view('backend.admin.blood-inventory.create', compact('bloodGroups', 'bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->storeService->store($request->all());
        return redirect()->route('backend.admin.blood-inventory.index')->with('success', 'Blood inventory added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodInventory $bloodInventory)
    {
        $bloodInventory->load(['bloodGroup', 'bloodType', 'createBy', 'updateBy']);

        return view('backend.admin.blood-inventory.show', compact('bloodInventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodInventory $bloodInventory)
    {
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();

        return view('backend.admin.blood-inventory.edit', compact('bloodGroups', 'bloodTypes', 'bloodInventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodInventory $bloodInventory)
    {
        $this->updateService->update($bloodInventory, $request->all());
        return redirect()->route('backend.admin.blood-inventory.index')->with('success', 'Blood inventory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodInventory $bloodInventory)
    {
        $bloodInventory->delete();
        return redirect()->route('backend.admin.blood-inventory.index')->with('success', 'Blood inventory deleted successfully.');
    }

    /**
     * Toggle inventory status.
     */
    public function toggleStatus(BloodInventory $bloodInventory)
    {
        $bloodInventory->update([
            'status' => !$bloodInventory->status,
            'updated_by' => Auth::user()->id,
        ]);

        $status = $bloodInventory->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Blood inventory {$status} successfully.");
    }

}



