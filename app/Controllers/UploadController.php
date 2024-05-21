<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

class UploadController extends BaseController
{
    protected $helpers = ['form'];

    public function index()
    {
        return view('upload_form', ['errors' => []]);
    }

    public function upload()
    {
        $validationRule = [
            'userfile' => [
                'label' => 'Image File',
                'rules' => 'uploaded[userfile]'
                    . '|mime_in[userfile,application/pdf]',
                    
            ],
        ];
        if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];

            return view('upload_form', $data);
        }

        $img = $this->request->getFile('userfile');

        if (! $img->hasMoved()) {
            $filepath = WRITEPATH . 'uploads/' . $img->store();

            $data = ['uploaded_flleinfo' => new File($filepath)];

            return view('upload_success', $data);
        } else {
            $data = ['errors' => 'The file has already been moved.'];

            return view('upload_form', $data);
        }
    }

    public function download(){
        helper('download');

       // return $response->download('writable/uploads/20220515/1652603221_21dbcbc5f646c7c8f37b.jpeg',NULL);
        return $this->response->download(WRITEPATH . 'uploads/20220515/1652603221_21dbcbc5f646c7c8f37b.jpeg', null)->setFileName('Aadhar.jpeg');
}
}