<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ClassModel extends Model{
    protected $table = 'class';
    protected $primaryKey = 'class_id';
    protected $allowedFields = [
            'class_name',
            'section_name',
            'created_at',
            'updated_at',
            'admin_id'
    ];
}