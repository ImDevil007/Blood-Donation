<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lov;
use App\Models\Recipient;
use App\Services\Backend\Admin\RecipientStoreService;
use App\Services\Backend\Admin\RecipientUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    protected $storeService;
    protected $updateService;

    public function __construct(RecipientStoreService $storeService, RecipientUpdateService $updateService)
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
        $blood_group = $request->input('blood_group');
        $gender = $request->input('gender');
        $status = $request->input('status');

        $recipients = Recipient::with(['userBloodGroup', 'userGender', 'userTitle'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('patient_code', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->when($blood_group, function ($q) use ($blood_group) {
                $q->where('blood_group', $blood_group);
            })
            ->when($gender, function ($q) use ($gender) {
                $q->where('gender', $gender);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('request_status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $genders = Lov::where('lov_category_id', 1)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $statuses = ['pending', 'accepted', 'fulfilled', 'rejected'];

        return view('backend.admin.recipients.index', compact('recipients', 'bloodGroups', 'genders', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genders = Lov::where('lov_category_id', 1)->get();
        $titles = Lov::where('lov_category_id', 2)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        return view('backend.admin.recipients.create', compact('genders', 'titles', 'bloodGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $recipient = $this->storeService->store($request->all());
            return redirect()->route('backend.admin.recipients.index')->with('success', 'Recipient registered successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to register recipient: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipient $recipient)
    {
        $recipient->load(['userBloodGroup', 'userGender', 'userTitle', 'createBy', 'updateBy']);
        return view('backend.admin.recipients.show', compact('recipient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipient $recipient)
    {
        $genders = Lov::where('lov_category_id', 1)->get();
        $titles = Lov::where('lov_category_id', 2)->get();
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        return view('backend.admin.recipients.edit', compact('recipient', 'genders', 'titles', 'bloodGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipient $recipient)
    {
        try {
            $this->updateService->update($recipient, $request->all());
            return redirect()->route('backend.admin.recipients.index')->with('success', 'Recipient updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update recipient: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipient $recipient)
    {
        try {
            $recipient->delete();
            return redirect()->route('backend.admin.recipients.index')->with('success', 'Recipient deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete recipient: ' . $e->getMessage());
        }
    }

    /**
     * Toggle recipient status
     */
    public function toggleStatus(Recipient $recipient)
    {
        try {
            $recipient->update([
                'status' => !$recipient->status,
                'updated_by' => Auth::user()->id,
            ]);

            $status = $recipient->status ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Recipient {$status} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update recipient status: ' . $e->getMessage());
        }
    }
}
