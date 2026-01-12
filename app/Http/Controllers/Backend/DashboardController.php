<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BloodCollectionCamp;
use App\Models\BloodTest;
use App\Models\BloodUnit;
use App\Models\Donor;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Constructor method for the the class.
     * Create a new instance.
     * Initializes necessary dependencies.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // xdebug_break();
        return view('backend.index');
    }

    /**
     * Show the application dashboard for Admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        // Get current date and date ranges
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Basic Statistics
        $stats = [
            'total_donors' => Donor::count(),
            'active_donors' => Donor::active()->count(),
            'eligible_donors' => Donor::eligible()->count(),
            'total_recipients' => Recipient::count(),
            'total_blood_units' => BloodUnit::count(),
            'available_units' => BloodUnit::where('status', 'available')->count(),
            'expired_units' => BloodUnit::where('expiry_date', '<', $today)->count(),
            'total_camps' => BloodCollectionCamp::count(),
            'completed_camps' => BloodCollectionCamp::completed()->count(),
            'pending_tests' => BloodTest::pending()->count(),
            'approved_tests' => BloodTest::approved()->count(),
            'quarantined_tests' => BloodTest::quarantined()->count(),
        ];

        return view('backend.dashboard.admin', compact('stats'));
    }

    /**
     * Show the application dashboard for Donor.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function donorDashboard()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Donor profile not found. Please contact administrator.');
        }

        $today = Carbon::today();
        $thisYear = Carbon::now()->startOfYear();

        // Get donation statistics for this donor
        $totalDonations = $donor->donationHistories()->count();
        $successfulDonations = $donor->donationHistories()->successful()->count();
        $thisYearDonations = $donor->donationHistories()
            ->whereYear('donation_date', $today->year)
            ->count();

        $lastDonation = $donor->latestDonation;
        $nextEligibleDate = null;
        if ($lastDonation && $lastDonation->donation_date) {
            $nextEligibleDate = $lastDonation->donation_date->copy()->addDays(56); // 8 weeks minimum gap
        }

        $stats = [
            'total_donations' => $totalDonations,
            'successful_donations' => $successfulDonations,
            'this_year_donations' => $thisYearDonations,
            'is_eligible' => $donor->is_eligible,
            'eligibility_reason' => $donor->eligibility_reason,
            'last_donation_date' => $lastDonation ? $lastDonation->donation_date : null,
            'next_eligible_date' => $nextEligibleDate,
            'blood_group' => $donor->userBloodGroup?->value ?? 'N/A',
        ];

        // Recent donations (last 5)
        $recentDonations = $donor->donationHistories()
            ->with(['donationType', 'collectionLocation'])
            ->orderBy('donation_date', 'desc')
            ->limit(5)
            ->get();

        return view('backend.dashboard.donor', compact('stats', 'donor', 'recentDonations'));
    }
}
