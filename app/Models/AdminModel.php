<?php

namespace App\Models;

class AdminModel extends BaseModel
{
    protected $table      = 'admin';
    protected $primaryKey = 'admin_id';

    protected $allowedFields = [
        'admin_name',
        'admin_email',
        'admin_password',
        'admin_mobile',
        'admin_image',
        'admin_designation',
        'admin_organisation',
    ];

    protected $validationRules = [
        'admin_name'  => 'required|min_length[3]|max_length[100]',
        'admin_email' => 'required|valid_email|max_length[150]|is_unique[admin.admin_email,admin_id,{admin_id}]',
    ];

    protected $validationMessages = [
        'admin_email' => [
            'is_unique' => 'An account with that email address already exists.',
        ],
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('admin_email', $email)->first();
    }
}
