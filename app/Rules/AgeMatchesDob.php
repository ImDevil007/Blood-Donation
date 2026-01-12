<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class AgeMatchesDob implements ValidationRule
{
    protected $dob;

    public function __construct($dob)
    {
        $this->dob = $dob;
    }

    /**
     * Define the validation logic and error message.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->dob) {
            return;
        }

        try {
            $dob = Carbon::parse($this->dob);
            $today = Carbon::today();
            $calculatedAge = (int) $dob->diffInYears($today);
            $providedAge = (int) $value;

            // Check if age matches calculated age from DOB
            if ($providedAge != $calculatedAge) {
                $fail('Age does not match the date of birth. Based on the date of birth, the age should be ' . $calculatedAge . ' years.');
            }

            // Check age range based on DOB
            if ($calculatedAge < 18) {
                $fail('Donor must be at least 18 years old. Current age from date of birth: ' . $calculatedAge . ' years.');
            } elseif ($calculatedAge > 65) {
                $fail('Donor must be 65 years or younger. Current age from date of birth: ' . $calculatedAge . ' years.');
            }
        } catch (\Exception $e) {
            $fail('Invalid date of birth format.');
        }
    }
}
