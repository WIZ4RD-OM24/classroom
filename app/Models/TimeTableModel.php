<?php

namespace App\Models;

class TimeTableModel extends BaseModel
{
    protected $table      = 'time_table';
    protected $primaryKey = 'time_table_id';

    protected $allowedFields = [
        'time_table_file',
        'class_id',
        'admin_id',
    ];

    protected $validationRules = [
        'class_id' => 'permit_empty|is_natural_no_zero',
    ];
}
