<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class SubjectModel extends Model{
    protected $table = 'subject';
    protected $primaryKey = 'subject_id';
    protected $allowedFields = [
            'subject_name',
            'class_id',
            'admin_id',
            'teacher_id',
            'created_at',
            'updated_at',
    ];
}