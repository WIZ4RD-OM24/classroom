<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\StudentModel;

class CsvController extends Controller
{
    public function index()
    {
        return view('stud_view');
    }
    public function importCsvToDb()
    {
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
                    echo "<pre>";
                    print_r($filedata);
                    $num = count($filedata);
                    echo $num;
                    if($i > 0 && $num == $numberOfFields){ 
                        $csvArr[$i]['student_roll_no'] = $filedata[0];
                        $csvArr[$i]['student_name'] = $filedata[1];
                        $csvArr[$i]['student_email'] = $filedata[2];
                        //echo "<pre>";
                       // print_r($csvArr[$i]);
                    }
                    $i++;
                }
                fclose($file);
                $count = 0;
                foreach($csvArr as $userdata){
                    echo "<pre>";
                    print_r($userdata);
                    $students = new StudentModel();
                    $findRecord = $students->where('student_roll_no', $userdata['student_roll_no'])->countAllResults();
                    echo $findRecord;
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
        //return redirect()->route('/');         
    }
}