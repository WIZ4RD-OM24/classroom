<?php

namespace Tests\App;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ViewHelperTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        helper('view');
    }

    public function testDisplayDateFormatsAnIsoDate(): void
    {
        $this->assertSame('15 Mar 2024', display_date('2024-03-15'));
    }

    public function testDisplayDateHandlesMissingValues(): void
    {
        $this->assertSame('—', display_date(null));
        $this->assertSame('—', display_date(''));
        $this->assertSame('—', display_date('0000-00-00 00:00:00'));
    }

    /**
     * Rows written by the old code hold `date('d-m-y h:i:s')` strings, which
     * are not valid datetimes. They must not blow up a listing page.
     */
    public function testDisplayDateSurvivesLegacyGarbage(): void
    {
        $this->assertIsString(display_date('not a date at all'));
    }

    public function testDueBadgeMarksOverdue(): void
    {
        $badge = due_badge(date('Y-m-d', strtotime('-1 day')));
        $this->assertSame('Overdue', $badge['label']);
        $this->assertStringContainsString('danger', $badge['class']);
    }

    public function testDueBadgeMarksToday(): void
    {
        $this->assertSame('Due today', due_badge(date('Y-m-d'))['label']);
    }

    public function testDueBadgeCountsDownWithinThreeDays(): void
    {
        $this->assertSame('Due in 2 days', due_badge(date('Y-m-d', strtotime('+2 days')))['label']);
        $this->assertSame('Due in 1 day', due_badge(date('Y-m-d', strtotime('+1 day')))['label']);
    }

    public function testDueBadgeHandlesNoDueDate(): void
    {
        $this->assertSame('No due date', due_badge(null)['label']);
    }

    /**
     * The old listing compared 'd-m-y' strings, so 01-01-25 sorted before
     * 31-12-24. Ordering by ISO date is what the model now relies on.
     */
    public function testIsoDatesCompareChronologically(): void
    {
        $this->assertTrue('2024-12-31' < '2025-01-01');
    }

    public function testExcerptTruncatesAndCollapsesWhitespace(): void
    {
        $this->assertSame('a b c', excerpt_text("a\n\n  b \t c"));
        $this->assertSame('aaaaa…', excerpt_text(str_repeat('a', 20), 5));
        $this->assertSame('', excerpt_text(null));
    }
}
