<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class PhoneNumber implements ValidationRule
{
    use ValidatesAttributes;

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
        $regex = '%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i';

        if (!preg_match($regex, $value) || strlen($value) < 9 || strlen($value) > 12) {
            $fail($this->message($attribute));
        }
    }

    /**
     * Get the validation error message.
     *
     * @param string $attribute
     * @return string
     */
    public function message(string $attribute): string
    {
        return 'Please enter a valid phone number.';
    }
}


