<?php

namespace App\Validation;

use DateTimeImmutable;

/**
 * Date validation that works on PHP 8.2.
 *
 * CodeIgniter 4.1.9's built-in `valid_date[format]` rule reads:
 *
 *     $errors = DateTime::getLastErrors();
 *     return $date !== false && $errors !== false && $errors['warning_count'] === 0 ...
 *
 * As of PHP 8.2, `DateTime::getLastErrors()` returns `false` when the last
 * parse produced no warnings or errors, rather than an array of zero counts.
 * The `$errors !== false` guard therefore fails for *every* well-formed date,
 * so `valid_date` rejects all input on this PHP version — which silently made
 * every assignment due date invalid.
 */
class DateRules
{
    /**
     * A date in strict `Y-m-d` form, e.g. 2024-03-15.
     */
    public function iso_date(?string $str = null): bool
    {
        if ($str === null || $str === '') {
            return false;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $str);

        // createFromFormat is lenient — it accepts "2024-02-31" and rolls it
        // over to 2 March. Re-formatting and comparing rejects those.
        return $date !== false && $date->format('Y-m-d') === $str;
    }
}
