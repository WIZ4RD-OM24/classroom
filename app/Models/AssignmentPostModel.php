<?php

namespace App\Models;

class AssignmentPostModel extends BaseModel
{
    protected $table      = 'assignment_post';
    protected $primaryKey = 'assignment_post_id';

    protected $allowedFields = [
        'assignment_post_title',
        'assignment_post_description',
        'assignment_post_file',
        'assignment_post_due_date',
        'class_id',
        'subject_id',
        'teacher_id',
        'admin_id',
    ];

    protected $validationRules = [
        'assignment_post_title'       => 'required|min_length[3]|max_length[200]',
        'assignment_post_description' => 'permit_empty|max_length[5000]',
        'assignment_post_due_date'    => 'permit_empty|iso_date',
        'class_id'                    => 'permit_empty|is_natural_no_zero',
        'subject_id'                  => 'permit_empty|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'assignment_post_due_date' => [
            'iso_date' => 'Enter the due date as a calendar date.',
        ],
    ];

    /**
     * Assignments for one organisation, joined to class and subject names.
     *
     * Pass $restrictToClass to narrow the list to what one class may see (the
     * student view); $openOnly drops assignments whose due date has passed.
     */
    public function withRelations(
        int $adminId,
        ?int $classId = null,
        bool $openOnly = false,
        bool $restrictToClass = false
    ): array {
        $builder = $this->select('assignment_post.*, class.class_name, class.section_name, subject.subject_name')
            ->join('class', 'class.class_id = assignment_post.class_id', 'left')
            ->join('subject', 'subject.subject_id = assignment_post.subject_id', 'left')
            ->where('assignment_post.admin_id', $adminId);

        if ($restrictToClass) {
            $builder = $this->scopeToClass($builder, $classId);
        }

        if ($openOnly) {
            // Compared as a DATE column, not as a formatted string. The old
            // code compared 'd-m-y' strings, which ordered dates incorrectly.
            $builder->groupStart()
                ->where('assignment_post.assignment_post_due_date >=', date('Y-m-d'))
                ->orWhere('assignment_post.assignment_post_due_date', null)
                ->groupEnd();
        }

        return $builder->orderBy('assignment_post.assignment_post_due_date', 'ASC')->findAll();
    }
}
