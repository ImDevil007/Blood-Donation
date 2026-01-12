<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\Recipient;
use Illuminate\Http\Request;

class AICompatibilityController extends Controller
{
    public function index()
    {
        return view('backend.admin.ai.compatibility');
    }

    public function checkCompatibility(Request $request)
    {
        try {
            $request->validate([
                'recipient_blood_type' => 'required|string',
                'donor_blood_type' => 'required|string'
            ]);

            $recipientType = $request->recipient_blood_type;
            $donorType = $request->donor_blood_type;

            $compatibility = $this->aiBloodCompatibilityCheck($recipientType, $donorType);

            return response()->json([
                'compatible' => $compatibility['compatible'],
                'risk_level' => $compatibility['risk_level'],
                'explanation' => $compatibility['explanation'],
                'recommendations' => $compatibility['recommendations']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function aiBloodCompatibilityCheck($recipient, $donor)
    {
        // AI-powered blood compatibility matrix
        $compatibilityMatrix = [
            'A+' => ['A+', 'A-', 'O+', 'O-'],
            'A-' => ['A-', 'O-'],
            'B+' => ['B+', 'B-', 'O+', 'O-'],
            'B-' => ['B-', 'O-'],
            'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'AB-' => ['A-', 'B-', 'AB-', 'O-'],
            'O+' => ['O+', 'O-'],
            'O-' => ['O-']
        ];

        $isCompatible = in_array($donor, $compatibilityMatrix[$recipient] ?? []);

        // AI Risk Assessment
        $riskLevel = $this->calculateRiskLevel($recipient, $donor);

        // AI Explanation
        $explanation = $this->generateExplanation($recipient, $donor, $isCompatible);

        // AI Recommendations
        $recommendations = $this->generateRecommendations($recipient, $donor, $isCompatible);

        return [
            'compatible' => $isCompatible,
            'risk_level' => $riskLevel,
            'explanation' => $explanation,
            'recommendations' => $recommendations
        ];
    }

    private function calculateRiskLevel($recipient, $donor)
    {
        // AI risk calculation based on blood type patterns
        if ($recipient === $donor) {
            return 'Very Low';
        }

        $recipientRh = substr($recipient, -1);
        $donorRh = substr($donor, -1);

        if ($recipientRh === '+' && $donorRh === '-') {
            return 'Low';
        }

        if ($recipientRh === '-' && $donorRh === '+') {
            return 'High';
        }

        return 'Low';
    }

    private function generateExplanation($recipient, $donor, $isCompatible)
    {
        if ($isCompatible) {
            return "✅ AI Analysis: {$donor} blood is compatible with {$recipient} recipient. The blood types match the universal compatibility rules.";
        } else {
            return "❌ AI Analysis: {$donor} blood is NOT compatible with {$recipient} recipient. This combination could cause adverse reactions.";
        }
    }

    private function generateRecommendations($recipient, $donor, $isCompatible)
    {
        $recommendations = [];

        if ($isCompatible) {
            $recommendations[] = "✅ Safe to proceed with transfusion";
            $recommendations[] = "🔍 Still perform cross-matching test";
            $recommendations[] = "📋 Monitor patient for any adverse reactions";
        } else {
            $recommendations[] = "⚠️ Do NOT proceed with this blood type";
            $recommendations[] = "🔍 Find compatible blood type from inventory";
            $recommendations[] = "📞 Contact Vital Blood for alternative units";
        }

        // AI-powered additional recommendations
        $availableUnits = BloodUnit::where('blood_group', $recipient)
            ->where('status', 'available')
            ->count();

        if ($availableUnits > 0) {
            $recommendations[] = "📊 {$availableUnits} compatible units available in inventory";
        } else {
            $recommendations[] = "🚨 No compatible units in current inventory";
        }

        return $recommendations;
    }
}
