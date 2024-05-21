<?php 
namespace App\Models;  
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'student';
    protected $primaryKey = 'student_id';
    protected $allowedFields = [
        'student_roll_no',
        'student_name',
        'student_email',
        'student_mobile',
        'student_password',
        'student_image',
        'created_at',
        'updated_at',
        'admin_id',
        'class_id',
        'user_type'
    ];
} 