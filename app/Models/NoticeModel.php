<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class NoticeModel extends Model{
    protected $table = 'notice';
    protected $primaryKey = 'notice_id';
    protected $allowedFields = [
            'notice_title',
            'notice_content',
            'notice_file',
            'admin_id',
            'teacher_id',
            'class_id',
            'created_at',
            'updated_at',
    ];
}