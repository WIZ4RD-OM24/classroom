<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;



class AdminController extends Controller
{
    protected $helpers = ['form'];

    public function index(){
        $session = session();
        $AdminModel = new AdminModel();
        $ClassModel = new ClassModel();
        $StudentModel = new StudentModel();
        $SubjectModel = new SubjectModel();
        $TeacherModel = new TeacherModel();
        $data['class_count']= $ClassModel->where('admin_id',$_SESSION['admin']['admin_id'])->countAllResults();
        $data['student_count']= $StudentModel->where('admin_id',$_SESSION['admin']['admin_id'])->countAllResults();
        $data['subject_count']= $SubjectModel->where('admin_id',$_SESSION['admin']['admin_id'])->countAllResults();
        $data['teacher_count']= $TeacherModel->where('admin_id',$_SESSION['admin']['admin_id'])->countAllResults();
        return view('admin_dashboard',$data);

    }


    public function signup()
    {
        helper(['form']);
        $data = [];
        echo view('admin_signup', $data);
    }
    //add detail to register
    public function add_admin()
    {
        helper(['form']);
        $rules = [
            'admin_name'  => 'required|min_length[5]|max_length[50]',
            'admin_email' => 'required|min_length[4]|max_length[100]|valid_email|is_unique[admin.admin_email]',
            'admin_password'  => 'required|min_length[8]|max_length[50]',
            'confirm_password'  => 'matches[admin_password]'
        ];
          
        if($this->validate($rules)){
            $AdminModel = new AdminModel();
            $date = date('d-m-y h:i:s');
            $data = [
                'admin_name'     => $this->request->getVar('admin_name'),
                'admin_mobile'          => $this->request->getVar('admin_mobile'),
                'admin_designation'   => $this->request->getVar('admin_designation'),
                'admin_organisation' =>  $this->request->getVar('admin_organisation'), 
                'admin_email'    => $this->request->getVar('admin_email'),
                'admin_password' => password_hash($this->request->getVar('admin_password'), PASSWORD_DEFAULT),
                'created_at'       => $date,
                'updated_at'       => $date
            ];
            print_r($data);
           $AdminModel->save($data);
           return $this->response->redirect(base_url('/signin'));
        }else{
            $data['validation'] = $this->validator;
            return view('admin_signup',$data);
        }
          
    }

    public function signin()
    {
        helper(['form']);
        echo view('signin');
    } 
  
    public function loginAuth()
    {
        $session = session();
        $AdminModel = new AdminModel();
        $StudentModel = new StudentModel();
        $TeacherModel = new TeacherModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        if($data= $AdminModel->where('admin_email',$email)->first()){        
            if($data){
                $pass = $data['admin_password'];
                $authenticatePassword = password_verify($password, $pass);
                if($authenticatePassword){
                    $ses_data = [
                        'id' => $data['admin_id'],
                        'name' => $data['admin_name'],
                        'email' => $data['admin_email'],
                        'isLoggedIn' => TRUE
                    ];
                    $_SESSION['admin'] = $data;
                    $session->set($ses_data);
                    //print_r($ses_data);
                    return $this->response->redirect(base_url());
                
                }else{
                    $session->setFlashdata('msg', 'Password is incorrect.');
                    return view('signin');
                    //return $this->response->redirect(base_url('/signin'));
                }
            }else{
                $session->setFlashdata('msg', 'Email does not exist.');
                return $this->response->redirect(base_url('/signin'));
            }
        }else if($data= $TeacherModel->where('teacher_email',$email)->first()){
        
            if($data){
            print_r($data);
                $pass = $data['teacher_password'];
                $authenticatePassword = password_verify($password, $pass);
                if($authenticatePassword){
                    $ses_data = [
                        'id' => $data['teacher_id'],
                        'name' => $data['teacher_name'],
                        'email' => $data['teacher_email'],
                        'isLoggedIn' => TRUE
                    ];
                    $_SESSION['teacher'] = $data;
                    $session->set($ses_data);
                    //print_r($ses_data);
                    echo "teacher logged in";
                    return $this->response->redirect(base_url());
                
                }else{
                    $session->setFlashdata('msg', 'Password is incorrect.');
                    return view('signin');
                    //return $this->response->redirect(base_url('/signin'));
                }
            }else{
                $session->setFlashdata('msg', 'Email does not exist.');
                return $this->response->redirect(base_url('/signin'));
            }
        }elseif ($data = $StudentModel->where('student_email',$email)->first()){

            if($data){
                $pass = $data['student_password'];
               // $authenticatePassword = password_verify($password, $pass);
               // if($authenticatePassword){
                    $ses_data = [
                        'id' => $data['student_id'],
                        'name' => $data['student_name'],
                        'email' => $data['student_email'],
                        'isLoggedIn' => TRUE
                    ];
                    $_SESSION['student'] = $data;
                    $session->set($ses_data);
                    echo "student logged in";
                    //print_r($ses_data);
                    return $this->response->redirect(base_url());
                
//                 }else{
//                     $session->setFlashdata('msg', 'Password is incorrect.');
//                     return view('signin');
//                     //return $this->response->redirect(base_url('/signin'));
//                 }
            }else{
                $session->setFlashdata('msg', 'Email does not exist.');
                return $this->response->redirect(base_url('/signin'));
            }
        }
        /*$data = $AdminModel->where('admin_email', $email)->first();
        
        if($data){
            $pass = $data['admin_password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $ses_data = [
                    'id' => $data['admin_id'],
                    'name' => $data['admin_name'],
                    'email' => $data['admin_email'],
                    'isLoggedIn' => TRUE
                ];
                $_SESSION['admin'] = $data;
                $session->set($ses_data);
                //print_r($ses_data);
                return $this->response->redirect(base_url());
            
            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                return view('signin');
                //return $this->response->redirect(base_url('/signin'));
            }
        }else{
            $session->setFlashdata('msg', 'Email does not exist.');
            return $this->response->redirect(base_url('/signin'));
        }*/
    }
    public function logout()
    {   session();
        session_destroy();
        return $this->response->redirect(base_url('/signin'));
    }
    public function view_admin()
    {
        session();
        $AdminModel = new AdminModel();
        $id = $_SESSION['admin']['admin_id'];
        $data['admin']= $AdminModel->where('admin_id',$id)->first();
        //echo WRITEPATH;
        //print_r($data);
        return view('view_admin_profile',$data);
    }
    public function view_edit_admin_profile(){
        helper(['form']);
        session();
        $AdminModel = new AdminModel();
        $id = $_SESSION['admin']['admin_id'];
        $data['admin']= $AdminModel->where('admin_id',$id)->first();
        //print_r($data['admin']);
        return view('edit_admin_profile',$data);
    }
    
    //edit admin profile
    public function edit_profile(){
     session();
     $AdminModel = new AdminModel();
     $date = date('d-m-y h:i:s');
     $id = $this->request->getVar('admin_id');
     $admin = $AdminModel->find($id);
    
     //print_r($data);
 
        $img = $this->request->getFile('admin_image');
        
       if($img->isValid() && !$img->hasMoved()){
           if(!$admin['admin_image']==" " && file_exists('uploads/'.$admin['admin_image'])){
                unlink('uploads/'.$admin['admin_image']);
           }
            $admin_image= $img->getRandomName();
            $img->move('uploads/',$admin_image);

        }else{
            $admin_image = $admin['admin_image'];
        }
      //  $img->move(WRITEPATH.'public/uploads');
       // $filepath = WRITEPATH . 'public/uploads/' . $img->store();
        //echo $filepath;
       $data = [
        'admin_name'           => $this->request->getVar('admin_name'),
        'admin_mobile'         => $this->request->getVar('admin_mobile'),
        'admin_image'          => $admin_image,
        'admin_designation'    => $this->request->getVar('admin_designation'),
        'admin_organisation'    => $this->request->getVar('admin_organisation'),
        'updated_at'           =>$date
     ];
       // $data = ['uploaded_flleinfo' => new File($filepath)];
        //echo "<pre>";
        //print_r($data);
        $AdminModel->update($id, $data);
        $data['admin']= $AdminModel->where('admin_id',$id)->first();
        return $this->response->redirect(base_url('/admin-profile'));
     
}
    
    public function assignment_index()
    {
        return view('manage_assignment');
    }

    //Delete Notice...........

    public function delete_notice()
    {
        return view('notice_view');
    }
    //Create Notice..........
    public function create_notice()
    {
        $NoticeModel = new $NoticeModel();
        $data = [
            'notice_title' => $this->request->getVar('notice_title'),
            'notice_content'  => $this->request->getVar('notice_content'),
            'notice_file'  => $this->request->getVar(''),
            'created_at'      => $date,
            'updated_at' =>$date,
            'admin_id' => $_SESSION['admin_id'],
            'class_id' => $_SESSION['class_id'],
            
        ];
        return view('notice_view');
    }
    
}