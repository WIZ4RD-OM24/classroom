<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;

 

class TeacherController extends Controller
{
    public function index(){
        session();
        $TeacherModel = new TeacherModel();
        $data['teachers'] = $TeacherModel->orderBy('teacher_id', 'DESC')->findAll();
        return view('teacher/manage_teacher',$data);
    }

    public function view_add_teacher()
        {
            session();
            $ClassModel = new ClassModel();
            $data['classes'] = $ClassModel->orderBy('class_id', 'DESC')->findAll();
    		return view('teacher/add_teacher',$data);
        }

    
     
    public function add_teacher() 
        {
            helper(['form']);
            $rules = [
            'teacher_name'  => 'required|min_length[5]|max_length[50]',
            'teacher_email' => 'required|min_length[4]|max_length[100]|valid_email|is_unique[teacher.teacher_email]',
        ];
        if($this->validate($rules)){   
            session();
            $date = date('d-m-y h:i:s');
            $TeacherModel = new TeacherModel();
            $data = [
                'teacher_name'  => $this->request->getVar('teacher_name'),
                'teacher_email' => $this->request->getVar('teacher_email'),
                'class_id' => $this->request->getVar('class_id'),
                'created_at'    => $date,
                'updated_at'    =>$date,
                'admin_id' => $_SESSION['admin']['admin_id'],
                'teacher_password' => password_hash("teacher@123", PASSWORD_DEFAULT),
                'teacher_organisation'=> $_SESSION['admin']['admin_organisation']             
            ];
            print_r($data);
            $TeacherModel->insert($data);
            session()->setflashdata('successmsg',"User added successfully!");
            //return view('teacher/manage_teacher');
            return $this->response->redirect(base_url('/teachers'));
        }else{
            $data['validation'] = $this->validator;
            return view('teacher/add_teacher',$data);
            //return $this->response->redirect(base_url('/view-add-teacher'));
        }
              
            
        }
        // delete user
        public function delete_teacher($teacher_id = null)
        {
        $session = session();
        $TeacherModel = new TeacherModel();
        $data['teacher'] = $TeacherModel->where('teacher_id', $teacher_id)->delete($teacher_id);
        echo "<pre>";
        print_r($data['teacher']);
        $session->setflashdata('errormsg',"Teacher deleted successfully!");
        //return $this->response->redirect(base_url('/teachers'));

        }
        // update student data
         public function edit_student(){
        //$StudentModel = new StudentModel();
        $student_id = $this->request->getVar('student_id');
        $data = [
            'roll_no' => $this->request->getVar('roll_no'),
            'first_name' => $this->request->getVar('fname'),
            'last_name' => $this->request->getVar('lname'),
            'created_at'      => $date,
            'updated_at' =>$date
            
        ];
        $StudentModel->update($student_id, $data);
        return $this->response->redirect(base_url('/manage_student'));
    }
    // show single user
    public function singleTeacher($teacher_id = null){
        $TeacherModel = new TeacherModel();
        $data['teacher'] = $TeacherModel->where('teacher_id', $teacher_id)->first();
        //return $this->response->redirect(base_url('/view-teacher'),$data);
        return view('teacher/view_teacher_profile_by_admin',$data);
    }

}