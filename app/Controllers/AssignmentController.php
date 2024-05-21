<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\AssignmentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;
use App\Models\AssignmentPostModel;



class AssignmentController extends Controller
{
    public function index()
    {   
        session();
        $AssignmentPostModel = new AssignmentPostModel();
        $data['assignments'] = $AssignmentPostModel->orderBy('assignment_post_id', 'DESC')->findAll();
        return view('assignment/manage_assignment',$data);
    }

    public function view_post_assignment(){
        session();
        $ClassModel = new ClassModel();
        $SubjectModel = new SubjectModel();
        $data['classes'] = $ClassModel->orderBy('class_id', 'DESC')->findAll();
        $data['subjects'] = $SubjectModel->orderBy('subject_id', 'DESC')->findAll();
        return view('assignment/post_assignment',$data);
    }
    public function post_assignment(){
        session();
        $date = date('d-m-y h:i:s');
        $AssignmentPostModel = new AssignmentPostModel();
        $file = $this->request->getFile('file');
       if($file->isValid() && !$file->hasMoved()){
            $post_file= $file->getRandomName();
            $file->move('uploads/assignments/',$post_file);
        }else{
            $post_file = null;
        }
        $data = [
            'assignment_post_title'    => $this->request->getVar('title'),
            'assignment_post_description'  => $this->request->getVar('description'),
            'assignment_post_file'     => $post_file,
            'assignment_post_due_date'  => $this->request->getVar('due_date'),
            'created_at'      => $date,
            'updated_at'      => $date,
            'admin_id'        => $_SESSION['admin']['admin_id'],
            'class_id'        =>$this->request->getVar('class_id'),
            'subject_id'        =>$this->request->getVar('subject_id'),
        ];
        $AssignmentPostModel->insert($data);
        echo "<pre>";
        print_r($data);
        return $this->response->redirect(base_url('/assignments'));
    }

    public function delete_assignment($assignment_id = null)
        {
        $session = session();
        $AssignmentPostModel = new AssignmentPostModel();
        $data['assignment'] = $AssignmentPostModel->where('assignment_post_id', $assignment_id)->delete($assignment_id);
       // echo "<pre>";
        //print_r($data['teacher']);
        $session->setflashdata('errormsg',"Assignment deleted successfully!");
        return $this->response->redirect(base_url('/assignments'));

        }
}