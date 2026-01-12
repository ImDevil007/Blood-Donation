<?php

namespace App\Services\Backend\Admin;

use App\Models\Recipient;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecipientStoreService
{
    public function store(array $requestData): Recipient
    {
        $validated = $this->validate($requestData);

        $recipient = Recipient::create([
            'patient_code' => $this->generatePatientCode(),
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'hospital_name' => $validated['hospital_name'],
            'doctor_name' => $validated['doctor_name'],
            'admission_date' => $validated['admission_date'],
            'blood_required_date' => $validated['blood_required_date'],
            'blood_quantity_required' => $validated['blood_quantity_required'],
            'request_status' => $validated['request_status'],
            'diagnosis' => $validated['diagnosis'],
            'notes' => $validated['notes'],
            'status' => true,
            'created_by' => Auth::user()->id,
        ]);

        return $recipient;
    }

    protected function validate(array $data): array
    {
        return validator($data, [
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'dob' => 'required|date|before:today',
            'gender' => 'required|string',
            'blood_group' => 'required|string',
            'contact_number' => ['required', new PhoneNumber],
            'email' => 'nullable|email|unique:recipients,email',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'hospital_name' => 'nullable|string|max:200',
            'doctor_name' => 'required|string|max:200',
            'admission_date' => 'nullable|date|before_or_equal:today',
            'blood_required_date' => 'nullable|date|after_or_equal:today',
            'blood_quantity_required' => 'nullable|integer|min:1|max:10',
            'request_status' => 'required|in:pending,accepted,fulfilled,rejected',
            'diagnosis' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ])->validate();
    }

    protected function generatePatientCode(): string
    {
        $prefix = 'PAT';
        $year = date('Y');
        $month = date('m');

        $lastRecipient = Recipient::where('patient_code', 'like', $prefix . $year . $month . '%')
            ->orderBy('patient_code', 'desc')
            ->first();

        if ($lastRecipient) {
            $lastNumber = (int) substr($lastRecipient->patient_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
