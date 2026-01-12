<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Recipient;
use App\Models\BloodInventory;
use App\Models\BloodUnit;
use App\Models\BloodCollectionCamp;
use App\Models\BloodTest;
use App\Models\DonationHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
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

        // Blood Group Distribution
        $bloodGroupStats = $this->getBloodGroupDistribution();

        // Monthly Donation Trends
        $monthlyDonations = $this->getMonthlyDonationTrends();

        // Camp Performance
        $campPerformance = $this->getCampPerformance();

        // Test Results Summary
        $testResults = $this->getTestResultsSummary();

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        // Low Stock Alerts
        $lowStockAlerts = $this->getLowStockAlerts();

        // Expiry Alerts
        $expiryAlerts = $this->getExpiryAlerts();

        return view('backend.admin.dashboard.index', compact(
            'stats',
            'bloodGroupStats',
            'monthlyDonations',
            'campPerformance',
            'testResults',
            'recentActivities',
            'lowStockAlerts',
            'expiryAlerts'
        ));
    }

    private function getBloodGroupDistribution()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $distribution = [];

        foreach ($bloodGroups as $group) {
            $distribution[$group] = [
                'available' => BloodUnit::where('blood_group', $group)
                    ->where('status', 'available')
                    ->count(),
                'total' => BloodUnit::where('blood_group', $group)->count(),
                'donors' => Donor::whereHas('userBloodGroup', function($query) use ($group) {
                    $query->where('blood_group', $group);
                })->count(),
            ];
        }

        return $distribution;
    }

    private function getMonthlyDonationTrends()
    {
        $months = [];
        $donations = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');

            $donations[] = DonationHistory::whereYear('donation_date', $month->year)
                ->whereMonth('donation_date', $month->month)
                ->count();
        }

        return [
            'months' => $months,
            'donations' => $donations
        ];
    }

    private function getCampPerformance()
    {
        return BloodCollectionCamp::with(['createBy'])
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($camp) {
                return [
                    'name' => $camp->name,
                    'location' => $camp->location,
                    'start_date' => $camp->start_date->format('M d, Y'),
                    'target_donors' => $camp->target_donors,
                    'actual_donors' => $camp->actual_donors,
                    'success_rate' => $camp->getSuccessRate(),
                    'status' => $camp->status,
                ];
            });
    }

    private function getTestResultsSummary()
    {
        return [
            'total_tests' => BloodTest::count(),
            'pending' => BloodTest::pending()->count(),
            'approved' => BloodTest::approved()->count(),
            'quarantined' => BloodTest::quarantined()->count(),
            'rejected' => BloodTest::rejected()->count(),
            'recent_tests' => BloodTest::with(['bloodUnit', 'technician'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent donations
        $recentDonations = DonationHistory::with(['donor'])
            ->orderBy('donation_date', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($donation) {
                return [
                    'type' => 'donation',
                    'message' => "New donation from {$donation->donor->first_name} {$donation->donor->last_name}",
                    'date' => $donation->donation_date->format('M d, Y H:i'),
                    'icon' => 'fas fa-heart text-danger'
                ];
            });

        // Recent blood units
        $recentUnits = BloodUnit::with(['donor'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($unit) {
                return [
                    'type' => 'blood_unit',
                    'message' => "New blood unit {$unit->unit_id} added",
                    'date' => $unit->created_at->format('M d, Y H:i'),
                    'icon' => 'fas fa-tint text-primary'
                ];
            });

        // Recent tests
        $recentTests = BloodTest::with(['bloodUnit'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($test) {
                return [
                    'type' => 'test',
                    'message' => "Test completed for unit {$test->bloodUnit->unit_id}",
                    'date' => $test->created_at->format('M d, Y H:i'),
                    'icon' => 'fas fa-flask text-info'
                ];
            });

        return $activities->merge($recentDonations)
            ->merge($recentUnits)
            ->merge($recentTests)
            ->sortByDesc('date')
            ->take(10);
    }

    private function getLowStockAlerts()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $alerts = [];

        foreach ($bloodGroups as $group) {
            $available = BloodUnit::where('blood_group', $group)
                ->where('status', 'available')
                ->count();

            if ($available < 5) { // Threshold for low stock
                $alerts[] = [
                    'blood_group' => $group,
                    'available_units' => $available,
                    'status' => $available == 0 ? 'critical' : 'low'
                ];
            }
        }

        return $alerts;
    }

    private function getExpiryAlerts()
    {
        $today = Carbon::today();
        $sevenDaysFromNow = $today->copy()->addDays(7);

        return BloodUnit::where('status', 'available')
            ->whereBetween('expiry_date', [$today, $sevenDaysFromNow])
            ->with(['donor'])
            ->orderBy('expiry_date', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($unit) {
                $daysUntilExpiry = $unit->expiry_date->diffInDays(Carbon::today());
                return [
                    'unit_id' => $unit->unit_id,
                    'blood_group' => $unit->blood_group,
                    'expiry_date' => $unit->expiry_date->format('M d, Y'),
                    'days_until_expiry' => $daysUntilExpiry,
                    'status' => $daysUntilExpiry <= 3 ? 'critical' : 'warning'
                ];
            });
    }

    public function reports()
    {
        return view('backend.admin.dashboard.reports');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:donors,blood_units,donations,tests,camps',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'format' => 'required|in:pdf,excel'
        ]);

        $reportType = $request->report_type;
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);

        switch ($reportType) {
            case 'donors':
                return $this->generateDonorReport($dateFrom, $dateTo, $request->format);
            case 'blood_units':
                return $this->generateBloodUnitReport($dateFrom, $dateTo, $request->format);
            case 'donations':
                return $this->generateDonationReport($dateFrom, $dateTo, $request->format);
            case 'tests':
                return $this->generateTestReport($dateFrom, $dateTo, $request->format);
            case 'camps':
                return $this->generateCampReport($dateFrom, $dateTo, $request->format);
        }
    }

    private function generateDonorReport($dateFrom, $dateTo, $format)
    {
        $donors = Donor::with(['userBloodGroup', 'userGender', 'userTitle'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $reportTitle = 'Donor Report';
        $period = $dateFrom->format('M d, Y') . ' - ' . $dateTo->format('M d, Y');
        $filename = 'donor_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d');

        if ($format === 'excel') {
            return $this->generateCsv($donors, $filename, [
                'Donor ID', 'First Name', 'Last Name', 'Email', 'Phone',
                'Blood Group', 'Gender', 'Date of Birth', 'Age', 'Total Donations', 'Status'
            ], function($donor) {
                return [
                    $donor->donor_id,
                    $donor->first_name,
                    $donor->last_name,
                    $donor->email,
                    $donor->phone,
                    $donor->userBloodGroup->name ?? 'N/A',
                    $donor->userGender->name ?? 'N/A',
                    $donor->dob ? $donor->dob->format('Y-m-d') : 'N/A',
                    $donor->age,
                    $donor->total_donations ?? 0,
                    $donor->is_eligible ? 'Eligible' : 'Not Eligible'
                ];
            });
        } else {
            return $this->generatePdf($donors, $reportTitle, $period, $filename, [
                'Donor ID', 'Name', 'Email', 'Blood Group', 'Total Donations', 'Status'
            ], function($donor) {
                return [
                    $donor->donor_id,
                    $donor->first_name . ' ' . $donor->last_name,
                    $donor->email,
                    $donor->userBloodGroup->name ?? 'N/A',
                    $donor->total_donations ?? 0,
                    $donor->is_eligible ? 'Eligible' : 'Not Eligible'
                ];
            });
        }
    }

    private function generateBloodUnitReport($dateFrom, $dateTo, $format)
    {
        $units = BloodUnit::with(['donor'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $reportTitle = 'Blood Unit Report';
        $period = $dateFrom->format('M d, Y') . ' - ' . $dateTo->format('M d, Y');
        $filename = 'blood_unit_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d');

        if ($format === 'excel') {
            return $this->generateCsv($units, $filename, [
                'Unit ID', 'Blood Group', 'Donor', 'Collection Date', 'Expiry Date', 'Status', 'Storage Location'
            ], function($unit) {
                return [
                    $unit->unit_id,
                    $unit->blood_group,
                    $unit->donor ? $unit->donor->first_name . ' ' . $unit->donor->last_name : 'N/A',
                    $unit->collection_date ? $unit->collection_date->format('Y-m-d') : 'N/A',
                    $unit->expiry_date ? $unit->expiry_date->format('Y-m-d') : 'N/A',
                    ucfirst($unit->status),
                    $unit->storage_location ?? 'N/A'
                ];
            });
        } else {
            return $this->generatePdf($units, $reportTitle, $period, $filename, [
                'Unit ID', 'Blood Group', 'Donor', 'Collection Date', 'Expiry Date', 'Status'
            ], function($unit) {
                return [
                    $unit->unit_id,
                    $unit->blood_group,
                    $unit->donor ? $unit->donor->first_name . ' ' . $unit->donor->last_name : 'N/A',
                    $unit->collection_date ? $unit->collection_date->format('Y-m-d') : 'N/A',
                    $unit->expiry_date ? $unit->expiry_date->format('Y-m-d') : 'N/A',
                    ucfirst($unit->status)
                ];
            });
        }
    }

    private function generateDonationReport($dateFrom, $dateTo, $format)
    {
        $donations = DonationHistory::with(['donor'])
            ->whereBetween('donation_date', [$dateFrom, $dateTo])
            ->get();

        $reportTitle = 'Donation Report';
        $period = $dateFrom->format('M d, Y') . ' - ' . $dateTo->format('M d, Y');
        $filename = 'donation_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d');

        if ($format === 'excel') {
            return $this->generateCsv($donations, $filename, [
                'Donation ID', 'Donor', 'Blood Group', 'Donation Date', 'Volume (ml)', 'Status'
            ], function($donation) {
                return [
                    $donation->id,
                    $donation->donor ? $donation->donor->first_name . ' ' . $donation->donor->last_name : 'N/A',
                    $donation->blood_group ?? 'N/A',
                    $donation->donation_date ? $donation->donation_date->format('Y-m-d') : 'N/A',
                    $donation->volume ?? 'N/A',
                    ucfirst($donation->status ?? 'Completed')
                ];
            });
        } else {
            return $this->generatePdf($donations, $reportTitle, $period, $filename, [
                'Donation ID', 'Donor', 'Blood Group', 'Donation Date', 'Volume (ml)', 'Status'
            ], function($donation) {
                return [
                    $donation->id,
                    $donation->donor ? $donation->donor->first_name . ' ' . $donation->donor->last_name : 'N/A',
                    $donation->blood_group ?? 'N/A',
                    $donation->donation_date ? $donation->donation_date->format('Y-m-d') : 'N/A',
                    $donation->volume ?? 'N/A',
                    ucfirst($donation->status ?? 'Completed')
                ];
            });
        }
    }

    private function generateTestReport($dateFrom, $dateTo, $format)
    {
        $tests = BloodTest::with(['bloodUnit', 'technician'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $reportTitle = 'Test Report';
        $period = $dateFrom->format('M d, Y') . ' - ' . $dateTo->format('M d, Y');
        $filename = 'test_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d');

        if ($format === 'excel') {
            return $this->generateCsv($tests, $filename, [
                'Test ID', 'Blood Unit', 'Test Date', 'Result', 'Status', 'Technician'
            ], function($test) {
                return [
                    $test->id,
                    $test->bloodUnit ? $test->bloodUnit->unit_id : 'N/A',
                    $test->test_date ? $test->test_date->format('Y-m-d') : 'N/A',
                    ucfirst($test->result ?? 'N/A'),
                    ucfirst($test->status ?? 'Pending'),
                    $test->technician ? $test->technician->first_name . ' ' . $test->technician->last_name : 'N/A'
                ];
            });
        } else {
            return $this->generatePdf($tests, $reportTitle, $period, $filename, [
                'Test ID', 'Blood Unit', 'Test Date', 'Result', 'Status'
            ], function($test) {
                return [
                    $test->id,
                    $test->bloodUnit ? $test->bloodUnit->unit_id : 'N/A',
                    $test->test_date ? $test->test_date->format('Y-m-d') : 'N/A',
                    ucfirst($test->result ?? 'N/A'),
                    ucfirst($test->status ?? 'Pending')
                ];
            });
        }
    }

    private function generateCampReport($dateFrom, $dateTo, $format)
    {
        $camps = BloodCollectionCamp::with(['createdBy'])
            ->whereBetween('start_date', [$dateFrom, $dateTo])
            ->get();

        $reportTitle = 'Camp Report';
        $period = $dateFrom->format('M d, Y') . ' - ' . $dateTo->format('M d, Y');
        $filename = 'camp_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d');

        if ($format === 'excel') {
            return $this->generateCsv($camps, $filename, [
                'Camp Name', 'Location', 'Start Date', 'End Date', 'Target Donors', 'Actual Donors', 'Status'
            ], function($camp) {
                return [
                    $camp->name,
                    $camp->location,
                    $camp->start_date ? $camp->start_date->format('Y-m-d') : 'N/A',
                    $camp->end_date ? $camp->end_date->format('Y-m-d') : 'N/A',
                    $camp->target_donors ?? 0,
                    $camp->actual_donors ?? 0,
                    ucfirst($camp->status ?? 'Pending')
                ];
            });
        } else {
            return $this->generatePdf($camps, $reportTitle, $period, $filename, [
                'Camp Name', 'Location', 'Start Date', 'Target Donors', 'Actual Donors', 'Status'
            ], function($camp) {
                return [
                    $camp->name,
                    $camp->location,
                    $camp->start_date ? $camp->start_date->format('Y-m-d') : 'N/A',
                    $camp->target_donors ?? 0,
                    $camp->actual_donors ?? 0,
                    ucfirst($camp->status ?? 'Pending')
                ];
            });
        }
    }

    private function generateCsv($data, $filename, $headers, $rowCallback)
    {
        $output = fopen('php://temp', 'r+');

        // Add BOM for UTF-8
        fwrite($output, "\xEF\xBB\xBF");

        // Write headers
        fputcsv($output, $headers);

        // Write data rows
        foreach ($data as $item) {
            fputcsv($output, $rowCallback($item));
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
    }

    private function generatePdf($data, $title, $period, $filename, $headers, $rowCallback)
    {
        $html = view('backend.admin.dashboard.report-pdf', [
            'title' => $title,
            'period' => $period,
            'headers' => $headers,
            'data' => $data,
            'rowCallback' => $rowCallback
        ])->render();

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '.html"');
    }
}
