<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodCollectionCamp;
use Illuminate\Http\Request;
use App\Services\Backend\Admin\BloodCollectionCampStoreService;
use App\Services\Backend\Admin\BloodCollectionCampUpdateService;
use Illuminate\Support\Facades\Auth;

class BloodCollectionCampController extends Controller
{
    protected $storeService, $updateService;

    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct(BloodCollectionCampStoreService $storeService, BloodCollectionCampUpdateService $updateService)
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
        $dateFilter = $request->input('date_filter');

        $camps = BloodCollectionCamp::with(['createBy'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('organizer_name', 'like', "%{$search}%")
                    ->orWhere('camp_id', 'like', "%{$search}%");
            }))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFilter === 'upcoming', fn($q) => $q->upcoming())
            ->when($dateFilter === 'ongoing', fn($q) => $q->ongoing())
            ->when($dateFilter === 'completed', fn($q) => $q->completed())
            ->when($dateFilter === 'past', fn($q) => $q->past())
            ->latest()
            ->paginate(10);

        return view('backend.admin.blood-collection-camps.index', compact('camps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.blood-collection-camps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->storeService->store($request->all());
        return redirect()->route('backend.admin.blood-collection-camps.index')->with('success', 'Blood collection camp created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodCollectionCamp $bloodCollectionCamp)
    {
        $bloodCollectionCamp->load(['createBy', 'updateBy']);
        return view('backend.admin.blood-collection-camps.show', compact('bloodCollectionCamp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodCollectionCamp $bloodCollectionCamp)
    {
        return view('backend.admin.blood-collection-camps.edit', compact('bloodCollectionCamp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodCollectionCamp $bloodCollectionCamp)
    {
        $this->updateService->update($bloodCollectionCamp, $request->all());
        return redirect()->route('backend.admin.blood-collection-camps.index')->with('success', 'Blood collection camp updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodCollectionCamp $bloodCollectionCamp)
    {
        $bloodCollectionCamp->delete();
        return redirect()->route('backend.admin.blood-collection-camps.index')->with('success', 'Blood collection camp deleted successfully.');
    }

    /**
     * Update camp status.
     */
    public function updateStatus(Request $request, BloodCollectionCamp $bloodCollectionCamp)
    {
        $request->validate([
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $bloodCollectionCamp->update([
            'status' => $request->status,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Camp status updated successfully.');
    }

    /**
     * Get camp statistics for dashboard.
     */
    public function getStats()
    {
        $totalCamps = BloodCollectionCamp::count();
        $scheduledCamps = BloodCollectionCamp::scheduled()->count();
        $ongoingCamps = BloodCollectionCamp::ongoing()->count();
        $completedCamps = BloodCollectionCamp::completed()->count();
        $totalDonors = BloodCollectionCamp::sum('actual_donors');
        $totalUnits = BloodCollectionCamp::sum('collected_units');

        return response()->json([
            'total_camps' => $totalCamps,
            'scheduled_camps' => $scheduledCamps,
            'ongoing_camps' => $ongoingCamps,
            'completed_camps' => $completedCamps,
            'total_donors' => $totalDonors,
            'total_units' => $totalUnits,
        ]);
    }
}
