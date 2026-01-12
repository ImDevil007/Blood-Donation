<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\DonationHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AIEligibilityController extends Controller
{
    public function index()
    {
        return view('backend.admin.ai.eligibility');
    }

    public function predictEligibility(Request $request)
    {
        try {
            $request->validate([
                'donor_id' => 'required|exists:donors,id'
            ]);

            $donor = Donor::with(['donationHistories'])->findOrFail($request->donor_id);

            $prediction = $this->aiEligibilityPrediction($donor);

            return response()->json([
                'eligible' => $prediction['eligible'],
                'confidence' => $prediction['confidence'],
                'reasons' => $prediction['reasons'],
                'recommendations' => $prediction['recommendations'],
                'next_donation_date' => $prediction['next_donation_date']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function aiEligibilityPrediction($donor)
    {
        $reasons = [];
        $recommendations = [];
        $confidence = 100;
        $eligible = true;

        // AI Analysis 1: Age Check
        $age = Carbon::parse($donor->dob)->age;
        if ($age < 18 || $age > 65) {
            $eligible = false;
            $reasons[] = "Age {$age} is outside acceptable range (18-65)";
            $confidence -= 30;
        } else {
            $reasons[] = "✅ Age {$age} is within acceptable range";
        }

        // AI Analysis 2: Weight Check
        if ($donor->weight < 50) {
            $eligible = false;
            $reasons[] = "Weight {$donor->weight}kg is below minimum requirement (50kg)";
            $confidence -= 25;
        } else {
            $reasons[] = "✅ Weight {$donor->weight}kg meets requirements";
        }

        // AI Analysis 3: Medical History
        if ($donor->has_medical_conditions) {
            $eligible = false;
            $reasons[] = "Medical conditions present: {$donor->medical_conditions}";
            $confidence -= 40;
        } else {
            $reasons[] = "✅ No significant medical conditions";
        }

        // AI Analysis 4: Last Donation Interval
        $lastDonation = $donor->donationHistories()
            ->orderBy('donation_date', 'desc')
            ->first();

        if ($lastDonation) {
            $daysSinceLastDonation = Carbon::now()->diffInDays($lastDonation->donation_date);
            if ($daysSinceLastDonation < 56) { // 8 weeks minimum
                $eligible = false;
                $reasons[] = "Last donation was only {$daysSinceLastDonation} days ago (minimum 56 days required)";
                $confidence -= 35;
            } else {
                $reasons[] = "✅ {$daysSinceLastDonation} days since last donation (sufficient interval)";
            }
        } else {
            $reasons[] = "✅ First-time donor - no previous donations";
        }

        // AI Analysis 5: Hemoglobin Level
        if ($donor->hemoglobin_level && $donor->hemoglobin_level < 12.5) {
            $eligible = false;
            $reasons[] = "Hemoglobin level {$donor->hemoglobin_level}g/dL is below minimum (12.5g/dL)";
            $confidence -= 20;
        } elseif ($donor->hemoglobin_level) {
            $reasons[] = "✅ Hemoglobin level {$donor->hemoglobin_level}g/dL is adequate";
        }

        // AI Analysis 6: Blood Pressure
        if ($donor->blood_pressure_systolic && ($donor->blood_pressure_systolic < 90 || $donor->blood_pressure_systolic > 180)) {
            $eligible = false;
            $reasons[] = "Blood pressure {$donor->blood_pressure_systolic}/{$donor->blood_pressure_diastolic} is outside normal range";
            $confidence -= 15;
        } elseif ($donor->blood_pressure_systolic) {
            $reasons[] = "✅ Blood pressure is within acceptable range";
        }

        // AI Analysis 7: Donation Frequency Pattern
        $totalDonations = $donor->donationHistories()->count();
        if ($totalDonations > 0) {
            $firstDonation = $donor->donationHistories()->orderBy('donation_date', 'asc')->first();
            $monthsActive = Carbon::now()->diffInMonths($firstDonation->donation_date);
            $donationRate = $totalDonations / max($monthsActive, 1);

            if ($donationRate > 0.5) { // More than 0.5 donations per month
                $reasons[] = "⚠️ High donation frequency detected ({$donationRate} per month)";
                $confidence -= 10;
            } else {
                $reasons[] = "✅ Healthy donation frequency pattern";
            }
        }

        // AI Recommendations
        if ($eligible) {
            $recommendations[] = "✅ Donor is eligible for blood donation";
            $recommendations[] = "📅 Can donate immediately";
            $recommendations[] = "💧 Ensure donor is well-hydrated";
            $recommendations[] = "🍽️ Light meal recommended before donation";
        } else {
            $recommendations[] = "❌ Donor is not eligible at this time";
            $recommendations[] = "📅 Schedule follow-up assessment";
            $recommendations[] = "🏥 Consider medical consultation if needed";
        }

        // AI Next Donation Date Prediction
        $nextDonationDate = null;
        if ($eligible) {
            $nextDonationDate = Carbon::now()->addDays(56)->format('Y-m-d');
        } elseif ($lastDonation) {
            $nextDonationDate = Carbon::parse($lastDonation->donation_date)->addDays(56)->format('Y-m-d');
        }

        return [
            'eligible' => $eligible,
            'confidence' => max($confidence, 0),
            'reasons' => $reasons,
            'recommendations' => $recommendations,
            'next_donation_date' => $nextDonationDate
        ];
    }
}
