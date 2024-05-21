<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;


class SubjectController extends Controller
{
    public function index(){
        $TeacherModel = new TeacherModel();
        $SubjectModel = new SubjectModel();
        $data['teachers'] = $TeacherModel->orderBy('teacher_id', 'DESC')->findAll();
        $data['subjects'] = $SubjectModel->orderBy('subject_id', 'DESC')->findAll();
        return view('subject/manage_subject',$data);

    }
    public function view_add_subject()
    {
        session();
        $TeacherModel = new TeacherModel();
        //$SubjectModel = new SubjectModel();
        $data['teachers'] = $TeacherModel->orderBy('teacher_id', 'DESC')->findAll();
        //$data['subjects'] = $SubjectModel->orderBy('subject_id', 'DESC')->findAll();
		return view('subject/add_subject',$data);
    }

    public function add_subject() {
        session();
        $date = date('d-m-y h:i:s');
        $SubjectModel = new SubjectModel();
        $data = [
            
            'subject_name'        => $this->request->getVar('subject_name'),
            'teacher_id'        => $this->request->getVar('teacher_id'),
            'created_at'              =>$date,
            'updated_at'              =>$date,
            'admin_id' => $_SESSION['admin']['admin_id']
            //'class_id' => $_SESSION['class_id']
            
        ];
        print_r($data);
        $SubjectModel->insert($data);
       // $session->setflashdata('successmsg',"subject added successfully!!!");
        return $this->response->redirect(base_url('/subjects'));
    }
    //edit subject
    public function edit_subject(){
        session();
        $SubjectModel = new SubjectModel();
        $id = $this->request->getVar('subject_id');
        $data = [
            'subject_name'        => $this->request->getVar('subject_name'),
            'teacher_id'        => $this->request->getVar('teacher_id'),
            'updated_at'              =>$date,
            'admin_id' => $_SESSION['admin_id']
        ];
        print_r($data);
      //  $SubjectModel->update($id, $data);
        //return $this->response->redirect(base_url('/subjects'));
    }  
    public function SingleSubject($id = null){
        session();
        $SubjectModel = new SubjectModel();
        $data['subject'] = $SubjectModel->where('subject_id', $id)->first();
        $TeacherModel = new TeacherModel();
        $data['teachers'] = $TeacherModel->findAll();
        //echo "<pre>";
        //print_r($data);
        return view('subject/edit_subject', $data);
    }
    //delete subject
    public function delete_subject($id = null){
        $session = session();
        $SubjectModel = new SubjectModel();
        $data['data_subject'] = $SubjectModel->where('subject_id', $id)->delete($id);
        $session->setflashdata('errormsg',"subject deleted successfully!!!");
        return $this->response->redirect(base_url('/subjects'));
    }
    
    
}
