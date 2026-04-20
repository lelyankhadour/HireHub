<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CleanContent implements ValidationRule
{
    
    protected array $badWords = [
        'badword1', 
        'badword2',
        'badword3',
        'badword4',
        'badword5',
      
    ];

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      
        if (! $value) {
            return;
        }


        foreach ($this->badWords as $word) {
            if (stripos($value, $word) !== false) {
                $fail("The $attribute contains inappropriate content.");
                return;
            }
        }
    }
}
