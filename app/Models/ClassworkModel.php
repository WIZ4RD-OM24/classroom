<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ClassworkModel extends Model{
    protected $table = 'classwork';
    protected $primaryKey = 'classwork_id';
    protected $allowedFields = [
            'classwork_title',
            'classwork_file',
            'admin_id',
            'teacher_id',
            'class_id',
            'subject_id',
            'created_at',
            'updated_at',
    ];
}