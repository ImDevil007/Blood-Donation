<?php

namespace App\Http\Controllers\Backend\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Show donation history for Donor.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Donor profile not found. Please contact administrator.');
        }

        $donationHistories = $donor->donationHistories()
            ->with(['donationType', 'collectionLocation', 'technician'])
            ->orderBy('donation_date', 'desc')
            ->paginate(15);

        return view('backend.donor.history', compact('donationHistories', 'donor'));
    }
}
