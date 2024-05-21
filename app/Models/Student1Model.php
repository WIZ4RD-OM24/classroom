<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class Student1Model extends Model
{
    protected $table = 'student1';
    protected $allowedFields = [
        'student_roll_no',
        'student_contact',
        'student_name', 
        'student_email'
    ];
}  