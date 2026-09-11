<?php

namespace App\Models;

class NoticeModel extends BaseModel
{
    protected $table      = 'notice';
    protected $primaryKey = 'notice_id';

    protected $allowedFields = [
        'notice_title',
        'notice_content',
        'notice_file',
        'class_id',
        'teacher_id',
        'admin_id',
    ];

    protected $validationRules = [
        'notice_title'   => 'required|min_length[3]|max_length[200]',
        'notice_content' => 'permit_empty|max_length[5000]',
        'class_id'       => 'permit_empty|is_natural_no_zero',
    ];

    /**
     * Notices for one organisation, newest first, joined to the target class.
     * A NULL class_id means the notice is addressed to everyone.
     */
    public function withClass(int $adminId, ?int $classId = null, bool $restrictToClass = false): array
    {
        $builder = $this->select('notice.*, class.class_name, class.section_name')
            ->join('class', 'class.class_id = notice.class_id', 'left')
            ->where('notice.admin_id', $adminId);

        if ($restrictToClass) {
            $builder = $this->scopeToClass($builder, $classId);
        }

        return $builder->orderBy('notice.created_at', 'DESC')->findAll();
    }
}
