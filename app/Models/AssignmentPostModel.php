<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class AssignmentPostModel extends Model{
    protected $table = 'assignment_post';
    protected $primaryKey = 'assignment_post_id';
    protected $allowedFields = [
            'assignment_post_title',
            'assignment_post_description',
            'assignment_post_file',
            'assignment_post_due_date',
            'created_at',
            'updated_at',
            'admin_id',
            'teacher_id',
            'class_id',
            'subject_id'
            
    ];
}