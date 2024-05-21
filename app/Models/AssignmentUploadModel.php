<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class AssignmentUploadModel extends Model{
    protected $table = 'assignment_upload';
    protected $primaryKey = 'assignment_upload_id';
    protected $allowedFields = [
            'assignment_upload_title',
            'assignment_upload_description',
            'assignment_upload_file',
            'assignment_upload_grades',
            'assignment_upload_received_grades',
            'assignment_upload_remarks',
            'admin_id',
            'teacher_id',
            'class-id',
            'subject_id',
            'created_at',
            'updated_at',
    ];
}