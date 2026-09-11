<?php

use CodeIgniter\I18n\Time;

/**
 * Small view helpers. Loaded for every controller via BaseController::$helpers.
 */

if (! function_exists('field_error')) {
    /**
     * The validation message for one field, if the last request produced one.
     */
    function field_error(string $field): ?string
    {
        $errors = session()->getFlashdata('errors');

        return is_array($errors) && isset($errors[$field]) ? (string) $errors[$field] : null;
    }
}

if (! function_exists('field_value')) {
    /**
     * Repopulate a field: the submitted value if the form is being redisplayed
     * after a validation failure, otherwise the stored value.
     *
     * Previously a failed submission cleared every field on the form.
     *
     * @param mixed $fallback
     */
    function field_value(string $field, $fallback = ''): string
    {
        $old = old($field);

        return (string) ($old ?? $fallback ?? '');
    }
}

if (! function_exists('display_date')) {
    /**
     * Format a stored date for reading, tolerating null and malformed values.
     *
     * Rows written by the old code hold strings like "22-05-14 09:13:07"
     * (`date('d-m-y h:i:s')`), which are not valid MySQL datetimes. Rather than
     * throwing, anything unparseable is shown as a dash.
     */
    function display_date(?string $value, string $format = 'j M Y'): string
    {
        if ($value === null || trim($value) === '' || str_starts_with($value, '0000')) {
            return '—';
        }

        try {
            return Time::parse($value)->format($format) ?: '—';
        } catch (Throwable $e) {
            return '—';
        }
    }
}

if (! function_exists('due_badge')) {
    /**
     * Badge describing how a due date relates to today.
     *
     * @return array{class: string, label: string}
     */
    function due_badge(?string $dueDate): array
    {
        if ($dueDate === null || trim($dueDate) === '') {
            return ['class' => 'badge', 'label' => 'No due date'];
        }

        $today = date('Y-m-d');

        if ($dueDate < $today) {
            return ['class' => 'badge badge--danger', 'label' => 'Overdue'];
        }

        if ($dueDate === $today) {
            return ['class' => 'badge badge--warn', 'label' => 'Due today'];
        }

        $days = (int) ceil((strtotime($dueDate) - strtotime($today)) / 86400);

        if ($days <= 3) {
            return ['class' => 'badge badge--warn', 'label' => "Due in {$days} day" . ($days === 1 ? '' : 's')];
        }

        return ['class' => 'badge badge--success', 'label' => 'Due ' . display_date($dueDate)];
    }
}

if (! function_exists('excerpt_text')) {
    /**
     * A short, plain-text preview of a longer body.
     */
    function excerpt_text(?string $text, int $length = 160): string
    {
        $text = trim(preg_replace('/\s+/', ' ', (string) $text));

        if ($text === '') {
            return '';
        }

        return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '…' : $text;
    }
}
