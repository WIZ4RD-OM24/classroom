<?php

namespace App\Models;

class TeacherModel extends BaseModel
{
    protected $table      = 'teacher';
    protected $primaryKey = 'teacher_id';

    protected $allowedFields = [
        'teacher_name',
        'teacher_email',
        'teacher_password',
        'teacher_mobile',
        'teacher_image',
        'teacher_organisation',
        'class_id',
        'admin_id',
    ];

    protected $validationRules = [
        'teacher_name'   => 'required|min_length[2]|max_length[100]',
        'teacher_email'  => 'required|valid_email|max_length[150]|is_unique[teacher.teacher_email,teacher_id,{teacher_id}]',
        'teacher_mobile' => 'permit_empty|max_length[20]',
        'class_id'       => 'permit_empty|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'teacher_email' => [
            'is_unique' => 'A teacher with that email address already exists.',
        ],
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('teacher_email', $email)->first();
    }

    /**
     * Teachers for one organisation, joined to their class, with optional search.
     */
    public function withClass(int $adminId, ?string $search = null): array
    {
        $builder = $this->select('teacher.*, class.class_name, class.section_name')
            ->join('class', 'class.class_id = teacher.class_id', 'left')
            ->where('teacher.admin_id', $adminId);

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('teacher.teacher_name', $search)
                ->orLike('teacher.teacher_email', $search)
                ->groupEnd();
        }

        return $builder->orderBy('teacher.teacher_name', 'ASC')->findAll();
    }
}
