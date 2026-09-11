<?php

namespace Tests\App;

use App\Validation\DateRules;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Guards the PHP 8.2 workaround in App\Validation\DateRules.
 *
 * CodeIgniter 4.1.9's own `valid_date[Y-m-d]` rule rejects every date on PHP
 * 8.2, because DateTime::getLastErrors() now returns false instead of an array
 * of zero counts when a parse succeeds. That silently made it impossible to
 * post an assignment with a due date.
 *
 * @internal
 */
final class DateRuleTest extends CIUnitTestCase
{
    private DateRules $rules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rules = new DateRules();
    }

    /**
     * @dataProvider validDates
     */
    public function testAcceptsWellFormedIsoDates(string $date): void
    {
        $this->assertTrue($this->rules->iso_date($date), "{$date} should be accepted");
    }

    public static function validDates(): array
    {
        return [
            ['2024-03-15'],
            ['2024-02-29'], // leap year
            ['1999-12-31'],
            ['2030-01-01'],
        ];
    }

    /**
     * @dataProvider invalidDates
     */
    public function testRejectsMalformedOrImpossibleDates(?string $date): void
    {
        $this->assertFalse($this->rules->iso_date($date), var_export($date, true) . ' should be rejected');
    }

    public static function invalidDates(): array
    {
        return [
            [null],
            [''],
            ['not a date'],
            ['15-03-2024'],   // d-m-Y, the format the old code wrote
            ['2024-13-01'],   // month 13
            ['2023-02-29'],   // not a leap year
            ['2024-3-5'],     // unpadded
            ['2024-03-15 10:00:00'],
        ];
    }

    /**
     * The framework rule this one replaces, demonstrating the breakage. If this
     * ever starts passing, the framework has been upgraded and App\Validation
     * \DateRules can be reconsidered.
     */
    public function testFrameworkValidDateIsBrokenOnThisPhpVersion(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped('valid_date only misbehaves on PHP 8.2 and later.');
        }

        $framework = new \CodeIgniter\Validation\FormatRules();

        $this->assertFalse(
            $framework->valid_date('2024-03-15', 'Y-m-d'),
            'valid_date now works; App\Validation\DateRules may no longer be needed.'
        );
    }
}
