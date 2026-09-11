<?php

namespace App\Models;

class StudentModel extends BaseModel
{
    protected $table      = 'student';
    protected $primaryKey = 'student_id';

    protected $allowedFields = [
        'student_roll_no',
        'student_name',
        'student_email',
        'student_password',
        'student_mobile',
        'student_image',
        'class_id',
        'admin_id',
    ];

    protected $validationRules = [
        'student_roll_no' => 'required|max_length[50]',
        'student_name'    => 'required|min_length[2]|max_length[100]',
        'student_email'   => 'required|valid_email|max_length[150]|is_unique[student.student_email,student_id,{student_id}]',
        'student_mobile'  => 'permit_empty|max_length[20]',
        'class_id'        => 'permit_empty|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'student_email' => [
            'is_unique' => 'A student with that email address already exists.',
        ],
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('student_email', $email)->first();
    }

    /**
     * Students for one organisation, joined to their class, with optional search.
     */
    public function withClass(int $adminId, ?string $search = null): array
    {
        $builder = $this->select('student.*, class.class_name, class.section_name')
            ->join('class', 'class.class_id = student.class_id', 'left')
            ->where('student.admin_id', $adminId);

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('student.student_name', $search)
                ->orLike('student.student_email', $search)
                ->orLike('student.student_roll_no', $search)
                ->groupEnd();
        }

        return $builder->orderBy('student.student_roll_no', 'ASC')->findAll();
    }
}
