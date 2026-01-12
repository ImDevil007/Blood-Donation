<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodTest;
use App\Models\BloodUnit;
use Illuminate\Support\Facades\Auth;

class BloodTestStoreService
{
    public function store(array $requestData): BloodTest
    {
        $validated = $this->validate($requestData);

        // Get blood group from blood unit if it exists in DB
        $bloodUnit = BloodUnit::with('donor')->find($validated['blood_unit_id']);
        $bloodGroup = $bloodUnit ? ($bloodUnit->blood_group ?? $bloodUnit->donor->blood_group ?? $validated['blood_group']) : $validated['blood_group'];

        $test = BloodTest::create([
            'test_id' => $this->generateTestId(),
            'blood_unit_id' => $validated['blood_unit_id'],
            'technician_id' => $validated['technician_id'],
            'test_date' => $validated['test_date'],
            'hiv_result' => $validated['hiv_result'],
            'hepatitis_b_result' => $validated['hepatitis_b_result'],
            'hepatitis_c_result' => $validated['hepatitis_c_result'],
            'syphilis_result' => $validated['syphilis_result'],
            'malaria_result' => $validated['malaria_result'],
            'blood_group' => $bloodGroup,
            'overall_status' => $this->determineOverallStatus($validated),
            'test_notes' => $validated['test_notes'],
            'lab_reference' => $validated['lab_reference'],
            'created_by' => Auth::user()->id,
        ]);

        // Update blood unit test results
        $this->updateBloodUnitTestResults($test);

        return $test;
    }

    protected function validate(array $data): array
    {
        return validator($data, [
            'blood_unit_id' => 'required|exists:blood_units,id',
            'technician_id' => 'nullable|exists:users,id',
            'test_date' => 'required|date|before_or_equal:today',
            'hiv_result' => 'required|in:negative,positive,pending',
            'hepatitis_b_result' => 'required|in:negative,positive,pending',
            'hepatitis_c_result' => 'required|in:negative,positive,pending',
            'syphilis_result' => 'required|in:negative,positive,pending',
            'malaria_result' => 'required|in:negative,positive,pending',
            'blood_group' => 'nullable|string|max:10',
            'test_notes' => 'nullable|string|max:1000',
            'lab_reference' => 'nullable|string|max:100',
        ])->validate();
    }

    protected function generateTestId(): string
    {
        $prefix = 'TEST';
        $year = date('Y');
        $month = date('m');

        $lastTest = BloodTest::where('test_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('test_id', 'desc')
            ->first();

        if ($lastTest) {
            $lastNumber = (int) substr($lastTest->test_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function determineOverallStatus(array $data): string
    {
        $results = [
            $data['hiv_result'],
            $data['hepatitis_b_result'],
            $data['hepatitis_c_result'],
            $data['syphilis_result'],
            $data['malaria_result'],
        ];

        // If any result is pending, overall status is pending
        if (in_array('pending', $results)) {
            return 'pending';
        }

        // If any result is positive, blood unit is quarantined
        if (in_array('positive', $results)) {
            return 'quarantined';
        }

        // If all results are negative, blood unit passed
        return 'passed';
    }

    protected function updateBloodUnitTestResults(BloodTest $test): void
    {
        $bloodUnit = BloodUnit::find($test->blood_unit_id);

        if ($bloodUnit) {
            $testResults = [
                'hiv' => $test->hiv_result,
                'hepatitis_b' => $test->hepatitis_b_result,
                'hepatitis_c' => $test->hepatitis_c_result,
                'syphilis' => $test->syphilis_result,
                'malaria' => $test->malaria_result,
            ];

            $bloodUnit->update([
                'test_results' => $testResults,
                'status' => $test->overall_status === 'passed',
                'updated_by' => Auth::user()->id,
            ]);
        }
    }
}
