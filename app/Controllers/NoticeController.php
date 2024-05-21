<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;
use App\Models\NoticeModel;




class NoticeController extends Controller
{  
    public function index(){
        session();
        $NoticeModel = new NoticeModel;
        $data['notices']= $NoticeModel->where('admin_id',$_SESSION['admin']['admin_id'])->findAll() ;
        return view('notice/manage_notice',$data);
    }

    public function view_add_notice()
    {
        session();
        $ClassModel = new ClassModel();
        $NoticeModel = new NoticeModel();
        $data['classes']= $ClassModel->where('admin_id',$_SESSION['admin']['admin_id'])->findAll();
        return view('notice/add_notice',$data);
    }
    public function add_notice(){
    {
        session();
        $date = date('d-m-y h:i:s');
        $ClassModel = new ClassModel();
        $NoticeModel = new NoticeModel();
        //$data['classes']= $NoticeModel->findAll();
        $file = $this->request->getFile('notice_file');
//         $class_id =$this->request->getVar('class_name');
//         $class = $ClassModel->where('class_id',$class_id)->first();
       if($file->isValid() && !$file->hasMoved()){
            $notice_content= $file->getRandomName();
            $file->move('uploads/notices/',$notice_content);
        }else{
            $notice_content = " ";
        }
        $data = [
            'notice_title'    => $this->request->getVar('notice_title'),
            'notice_content'  => $this->request->getVar('notice_content'),
            'notice_file'     => $notice_content,
            'created_at'      => $date,
            'updated_at'      => $date,
            'admin_id'        => $_SESSION['admin']['admin_id'],
            'class_id'        => $this->request->getVar('class_id'),
            
        ];
        $NoticeModel->insert($data);
        //print_r($data);
        return $this->response->redirect(base_url('/notices'));
    }
    }

    public function delete_notice($id = null){
        $session = session();
        $NoticeModel = new NoticeModel();
        $NoticeModel->where('notice_id', $id)->delete($id);
        $session->setflashdata('errormsg',"subject deleted successfully!!!");
        return $this->response->redirect(base_url('/notices'));
    }
}