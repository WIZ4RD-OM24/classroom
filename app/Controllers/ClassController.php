<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;



class ClassController extends Controller
{
    public function index(){
        session();
        $ClassModel = new ClassModel();
        $data['classes'] = $ClassModel->orderBy('class_id', 'DESC')->findAll();
        return view('class/manage_class',$data);
    }

    //add class
    public function add_class() {
        $session = session(); 
        $date = date('d-m-y h:i:s');
        $ClassModel = new ClassModel();
        $data = [
            'class_name' => $this->request->getVar('class_name'),
            'section_name'  => $this->request->getVar('section_name'),
            'created_at'      => $date,
            'updated_at' =>$date,
            'admin_id' => $_SESSION['admin']['admin_id']
        ]; 
        print_r($data);   
        $ClassModel->insert($data);
        $session->setflashdata('successmsg',"Class added successfully!");
        return $this->response->redirect(base_url('/classes'));
    }    
    
    
    
    // delete class
    public function delete_class($id = null){
        $session = session();
        $ClassModel = new ClassModel();
        $data['class'] = $ClassModel->where('class_id', $id)->delete($id);
        $session->setflashdata('errormsg',"User deleted successfully!");
        return $this->response->redirect(base_url('/classes'));
    }    
    
    
    
    // edit class data
    public function update_class(){
        $ClassModel = new ClassModel();
        $date = date('d-m-y h:i:s');
        $id = $this->request->getVar('class_id');
        $data = [
            'class_name' => $this->request->getVar('class_name'),
            'section_name'  => $this->request->getVar('section_name'),
            'updated_at'   =>$date
        ];
        $ClassModel->update($id, $data);
        return $this->response->redirect(base_url('/classes'));
    }
    
    // show single class
    public function singleClass($id = null){
        $ClassModel = new ClassModel();
        $data['class'] = $ClassModel->where('class_id', $id)->first();
        //return $this->response->redirect(base_url('/edit-class'));
        return view('class/edit_class', $data);
        
    }
    
}