<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TimeTableModel extends Model{
    protected $table = 'time_table';
    protected $primaryKey = 'time_table_id';
    protected $allowedFields = [
            'time_table_file',
            'admin_id',
            'class-id',
            'created_at',
            'updated_at',
    ];
}