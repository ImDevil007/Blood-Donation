<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodTest;
use App\Models\BloodUnit;
use App\Models\Lov;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Backend\Admin\BloodTestStoreService;
use App\Services\Backend\Admin\BloodTestUpdateService;
use Illuminate\Support\Facades\Auth;

class BloodTestController extends Controller
{
    protected $storeService, $updateService;

    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct(BloodTestStoreService $storeService, BloodTestUpdateService $updateService)
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
        $technician = $request->input('technician');

        $tests = BloodTest::with(['bloodUnit.donor', 'technician', 'createBy'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('test_id', 'like', "%{$search}%")
                    ->orWhere('lab_reference', 'like', "%{$search}%")
                    ->orWhereHas('bloodUnit', fn($q2) => $q2->where('unit_id', 'like', "%{$search}%"));
            }))
            ->when($status, fn($q) => $q->where('overall_status', $status))
            ->when($technician, fn($q) => $q->where('technician_id', $technician))
            ->orderBy('test_date', 'desc')
            ->paginate(10);

        $technicians = User::whereHas('roles', function($q) {
            $q->where('name', 'lab_technician');
        })->get();

        return view('backend.admin.blood-tests.index', compact('tests', 'technicians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodUnits = BloodUnit::where('status', true)
            ->where('is_used', false)
            // ->whereDoesntHave('bloodTests')
            ->with('donor')
            ->get();

        $technicians = User::whereHas('roles', function($q) {
            $q->where('name', 'Lab Technician');
        })->get();

        $bloodGroups = Lov::where('lov_category_id', 3)->get();

        return view('backend.admin.blood-tests.create', compact('bloodUnits', 'technicians', 'bloodGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->storeService->store($request->all());
        return redirect()->route('backend.admin.blood-tests.index')->with('success', 'Blood test created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodTest $bloodTest)
    {
        $bloodTest->load(['bloodUnit.donor', 'technician', 'createBy', 'updateBy']);
        return view('backend.admin.blood-tests.show', compact('bloodTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodTest $bloodTest)
    {
        $technicians = User::whereHas('roles', function($q) {
            $q->where('name', 'Lab Technician');
        })->get();

        $bloodGroups = Lov::where('lov_category_id', 3)->get();

        return view('backend.admin.blood-tests.edit', compact('bloodTest', 'technicians', 'bloodGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodTest $bloodTest)
    {
        $this->updateService->update($bloodTest, $request->all());
        return redirect()->route('backend.admin.blood-tests.index')->with('success', 'Blood test updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodTest $bloodTest)
    {
        $bloodTest->delete();
        return redirect()->route('backend.admin.blood-tests.index')->with('success', 'Blood test deleted successfully.');
    }

    /**
     * Quarantine blood unit based on test results.
     */
    public function quarantine(BloodTest $bloodTest)
    {
        $bloodTest->update([
            'overall_status' => 'quarantined',
            'updated_by' => Auth::user()->id,
        ]);

        // Update blood unit status
        $bloodTest->bloodUnit->update([
            'status' => false,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Blood unit quarantined successfully.');
    }

    /**
     * Approve blood unit after successful tests.
     */
    public function approve(BloodTest $bloodTest)
    {
        $bloodTest->update([
            'overall_status' => 'passed',
            'updated_by' => Auth::user()->id,
        ]);

        // Update blood unit status
        $bloodTest->bloodUnit->update([
            'status' => true,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Blood unit approved successfully.');
    }

    /**
     * Get test statistics for dashboard.
     */
    public function getStats()
    {
        $totalTests = BloodTest::count();
        $pendingTests = BloodTest::pending()->count();
        $passedTests = BloodTest::passed()->count();
        $failedTests = BloodTest::failed()->count();
        $quarantinedTests = BloodTest::quarantined()->count();

        return response()->json([
            'total_tests' => $totalTests,
            'pending_tests' => $pendingTests,
            'passed_tests' => $passedTests,
            'failed_tests' => $failedTests,
            'quarantined_tests' => $quarantinedTests,
        ]);
    }

    /**
     * Get blood unit blood group for auto-fill.
     */
    public function getBloodUnitBloodGroup(BloodUnit $bloodUnit)
    {
        $bloodUnit->load('donor');
        return response()->json([
            'blood_group' => $bloodUnit->blood_group ?? $bloodUnit->donor->blood_group ?? null,
        ]);
    }
}
