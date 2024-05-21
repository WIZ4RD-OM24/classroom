<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AdminModel;
use App\Models\AssignmentPostModel;



class StudentController extends Controller
{  
    
    public function index()
    {
        session();
        $StudentModel = new StudentModel();
        $data['students'] = $StudentModel->orderBy('student_id', 'DESC')->findAll();
        return view('student/manage_student',$data);
    }

      public function view_add_student()
            {
                session();
                $ClassModel = new ClassModel();
                $data['classes'] = $ClassModel->orderBy('class_id', 'DESC')->findAll();
        		return view('student/add_student',$data);
            }

    public function add_student() 
        {
            $session = session();
            $date = date('d-m-y h:i:s');
            $StudentModel = new StudentModel();
            $data = [
                'student_roll_no' => $this->request->getVar('student_roll_no'),
                'student_name' => $this->request->getVar('student_name'),
                'student_email'  => $this->request->getVar('student_email'),
                'class_id' => $this->request->getVar('class_id'),
                'created_at'      => $date,
                'updated_at' =>$date,
                'admin_id' => $_SESSION['admin']['admin_id']
                
            ];
           print_r($data);
            $StudentModel->insert($data);
            $session->setflashdata('successmsg',"Student added successfully!");
            return $this->response->redirect(base_url('/students'));
            
        }
        public function add_bulk_student()
        {
            session();
            $input = $this->validate([
                'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
            ]);
            if (!$input) {
                $data['validation'] = $this->validator;
                return view('index', $data); 
            }else{
                if($file = $this->request->getFile('file')) {
                if ($file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('../public/csv', $newName);
                    $file = fopen('../public/csv/'.$newName,'r');
                    $i = 0;
                    $numberOfFields = 3;
                    $csvArr = array();
                    
                    while (($filedata = fgetcsv($file,1000, ',')) !== FALSE) {
                       // echo "<pre>";
                       // print_r($filedata);
                       $date = date('d-m-y h:i:s');
                        $num = count($filedata);
                        //echo $num;
                        if($i > 0 && $num == $numberOfFields){ 
                            $csvArr[$i]['student_roll_no'] = $filedata[0];
                            $csvArr[$i]['student_name'] = $filedata[1];
                            $csvArr[$i]['student_email'] = $filedata[2];
                            $csvArr[$i]['admin_id'] = $_SESSION['admin']['admin_id'];
                            $csvArr[$i]['created_at'] = $date;
                            $csvArr[$i]['updated_at'] =$date;
                            echo "<pre>";
                            print_r($csvArr[$i]);
                        }
                        $i++;
                    }
                    fclose($file);
                    $count = 0;
                    foreach($csvArr as $userdata){
                       // echo "<pre>";
                        //print_r($userdata);
                        $students = new StudentModel();
                        $findRecord = $students->where('student_roll_no', $userdata['student_roll_no'])->countAllResults();
                        //echo $findRecord;
                        if($findRecord == 0){
                            if($students->insert($userdata)){
                            $count++;
                            }
                        }
                        echo $count;
                    }
                    session()->setFlashdata('message', $count.' rows successfully added.');
                    session()->setFlashdata('alert-class', 'alert-success');
                }
                else{
                    session()->setFlashdata('message', 'CSV file coud not be imported.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
                }else{
                session()->setFlashdata('message', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return $this->response->redirect(base_url('/students'));
            //return redirect()->route('/students');         
        } 
        // delete user
        public function delete_student($student_id = null)
        {
        $session = session();
        $StudentModel = new StudentModel();
        $data['student'] = $StudentModel->where('student_id', $student_id)->delete($student_id);
        $session->setflashdata('errormsg',"Student deleted successfully!");
        echo "<pre>";
        print_r($data['student_obj']);
        return $this->response->redirect(base_url('/students'));

        }
        // update student data
        public function edit_student(){
        $StudentModel = new StudentModel();
        $student_id = $this->request->getVar('student_id');
        $data = [
            'roll_no' => $this->request->getVar('roll_no'),
            'first_name' => $this->request->getVar('fname'),
            'last_name' => $this->request->getVar('lname'),
            'created_at'      => $date,
            'updated_at' =>$date
            
        ];
        $StudentModel->update($student_id, $data);
        return $this->response->redirect(base_url('/students'));
    }
    // show single user
    public function singleStudent($student_id = null){
        $StudentModel = new StudentModel();
        $data['student'] = $StudentModel->where('student_id', $student_id)->first();
        return view('student/view_student_profile_by_admin', $data);
    }

    public function view_assignments(){
        $AssignmentPostModel = new AssignmentPostModel();
        $assignments=$AssignmentPostModel->findAll();
        $data['my_assignments']= $AssignmentPostModel->findAll();
        echo "<pre>";

        $currentdate=date('Y-m-d');

        $i=0;        foreach($data['my_assignments'] as $val)
        {
            $val['assignment_post_due_date'];
            
         //   echo "<br>";

            
  
   if($val['assignment_post_due_date'] > $currentdate ||  $val['assignment_post_due_date'] == $currentdate)
   {
    

      $data1['my_assignments'][$i] = $val;
   }
            $i++;

        }
        

        print_r($data1);

    }
}