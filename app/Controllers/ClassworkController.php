<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;
use App\Models\ClassworkModel;




class ClassworkController extends Controller
{  
    public function index(){
        session();
        $ClassworkModel = new ClassworkModel;
        $data['classworks']= $ClassworkModel->where('admin_id',$_SESSION['admin']['admin_id'])->findAll() ;
        return view('classwork/manage_classwork',$data);
    }

    public function view_add_classwork()
    {
        session();
        $ClassModel = new ClassModel();
        $ClassworkModel = new ClassworkModel();
        $SubjectModel = new SubjectModel();
        $data['subjects']= $SubjectModel->where('admin_id',$_SESSION['admin']['admin_id'])->findAll();
        $data['classes']= $ClassModel->where('admin_id',$_SESSION['admin']['admin_id'])->findAll();
        return view('classwork/add_classwork',$data);
    }


    public function add_classwork(){
    {
        session();
        $date = date('d-m-y h:i:s');
        $ClassModel = new ClassModel();
        $SubjectModel = new SubjectModel();
        $ClassworkModel = new ClassworkModel();
        //$data['classes']= $ClassworkModel->findAll();
        $file = $this->request->getFile('classwork_file');
        $class_id =$this->request->getVar('class_name');
        $class = $ClassModel->where('class_id',$class_id)->first();
        $subject_id =$this->request->getVar('subject_name');
        $subject = $SubjectModel->where('subject_id',$subject_id)->first();
       if($file->isValid() && !$file->hasMoved()){
            $classwork_file= $file->getRandomName();
            $file->move('uploads/classworks/',$classwork_file);
        }else{
            $classwork_file = null;
        }
        $data = [
            'classwork_title'    => $this->request->getVar('classwork_title'),
            'classwork_file'     => $classwork_file,
            'created_at'      => $date,
            'updated_at'      => $date,
            'admin_id'        => $_SESSION['admin']['admin_id'],
            'class_id'        => $class['class_id'],
            'subject_id'        => $subject['subject_id'],
            
        ];
        $ClassworkModel->insert($data);
        print_r($data);
        return $this->response->redirect(base_url('/classworks'));
    }
    }

    public function delete_classwork($id = null){
        $session = session();
        $ClassworkModel = new ClassworkModel();
        $ClassworkModel->where('classwork_id', $id)->delete($id);
        $session->setflashdata('errormsg',"Classwork deleted successfully!!!");
        return $this->response->redirect(base_url('/classworks'));
    }
}