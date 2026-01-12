<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodTransfer;
use App\Models\BloodInventory;
use App\Models\Lov;
use Illuminate\Http\Request;
use App\Services\Backend\Admin\BloodTransferStoreService;
use App\Services\Backend\Admin\BloodTransferUpdateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BloodTransferController extends Controller
{
    protected $storeService, $updateService;

    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct(BloodTransferStoreService $storeService, BloodTransferUpdateService $updateService)
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
        $status = $request->input('status');
        $bloodGroup = $request->input('blood_group');
        $sourceBank = $request->input('source_bank');
        $destinationBank = $request->input('destination_bank');

        $transfers = BloodTransfer::with(['requestedBy', 'approvedBy', 'bloodGroup'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('transfer_id', 'like', "%{$search}%")
                    ->orWhere('source_bank', 'like', "%{$search}%")
                    ->orWhere('destination_bank', 'like', "%{$search}%");
            }))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($bloodGroup, fn($q) => $q->where('blood_group', $bloodGroup))
            ->when($sourceBank, fn($q) => $q->where('source_bank', $sourceBank))
            ->when($destinationBank, fn($q) => $q->where('destination_bank', $destinationBank))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        
        // Get unique banks from inventory
        $banks = BloodInventory::whereNotNull('storage_location')
            ->distinct()
            ->pluck('storage_location')
            ->sort()
            ->values();

        return view('backend.admin.blood-transfers.index', compact('transfers', 'bloodGroups', 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();
        
        // Get unique banks from inventory
        $banks = BloodInventory::whereNotNull('storage_location')
            ->distinct()
            ->pluck('storage_location')
            ->sort()
            ->values();

        return view('backend.admin.blood-transfers.create', compact('bloodGroups', 'bloodTypes', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->storeService->store($request->all());
            return redirect()->route('backend.admin.blood-transfers.index')->with('success', 'Blood transfer request created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodTransfer $bloodTransfer)
    {
        $bloodTransfer->load(['requestedBy', 'approvedBy', 'bloodGroup', 'bloodType']);
        
        return view('backend.admin.blood-transfers.show', compact('bloodTransfer'));
    }

    /**
     * Approve a transfer request.
     */
    public function approve(BloodTransfer $bloodTransfer)
    {
        try {
            $this->updateService->approve($bloodTransfer);
            return redirect()->back()->with('success', 'Blood transfer approved and inventory updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Reject a transfer request.
     */
    public function reject(Request $request, BloodTransfer $bloodTransfer)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        try {
            $this->updateService->reject($bloodTransfer, $request->rejection_reason);
            return redirect()->back()->with('success', 'Blood transfer rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Cancel a transfer request.
     */
    public function cancel(BloodTransfer $bloodTransfer)
    {
        try {
            $this->updateService->cancel($bloodTransfer);
            return redirect()->back()->with('success', 'Blood transfer cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get available stock for a bank, blood group and blood type (AJAX).
     */
    public function getAvailableStock(Request $request)
    {
        $request->validate([
            'bank' => 'required|string',
            'blood_group' => 'required|string|exists:lovs,id',
            'blood_type' => 'required|string|exists:lovs,id',
        ]);

        $availableStock = BloodInventory::where('storage_location', $request->bank)
            ->where('blood_group', $request->blood_group)
            ->where('blood_type', $request->blood_type)
            ->where('status', true)
            ->sum('quantity');

        return response()->json([
            'available_stock' => (float) $availableStock,
        ]);
    }
}
