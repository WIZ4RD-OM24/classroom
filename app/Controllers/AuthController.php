<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Enter your email address and password.');
        }

        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        if (! $this->auth->attempt($email, $password)) {
            // One message for both an unknown email and a wrong password: the
            // old code said "Email does not exist", which let anyone enumerate
            // the registered accounts.
            log_message('notice', 'Failed sign-in attempt for {email}', ['email' => $email]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Those credentials do not match our records.');
        }

        $intended = session()->getFlashdata('redirect_url');

        return redirect()->to($intended ?: base_url('/'))
            ->with('success', 'Welcome back, ' . $this->auth->name() . '.');
    }

    public function showRegister()
    {
        return view('auth/register');
    }

    /**
     * Registers a new organisation together with its first administrator.
     */
    public function register()
    {
        $model = new AdminModel();

        $rules = [
            'admin_name'         => 'required|min_length[3]|max_length[100]',
            'admin_email'        => 'required|valid_email|max_length[150]|is_unique[admin.admin_email]',
            'admin_organisation' => 'required|min_length[2]|max_length[150]',
            'admin_designation'  => 'permit_empty|max_length[100]',
            'admin_mobile'       => 'permit_empty|max_length[20]',
            'admin_password'     => 'required|min_length[8]|max_length[72]',
            'confirm_password'   => 'required|matches[admin_password]',
        ];

        $messages = [
            'admin_email'      => ['is_unique' => 'An account with that email address already exists.'],
            'confirm_password' => ['matches' => 'The two passwords do not match.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please correct the highlighted fields.');
        }

        $model->skipValidation(true)->insert([
            'admin_name'         => $this->request->getPost('admin_name'),
            'admin_email'        => $this->request->getPost('admin_email'),
            'admin_mobile'       => $this->request->getPost('admin_mobile'),
            'admin_designation'  => $this->request->getPost('admin_designation'),
            'admin_organisation' => $this->request->getPost('admin_organisation'),
            // The plain-text password was previously echoed back to the browser
            // with print_r() before the row was saved.
            'admin_password' => password_hash((string) $this->request->getPost('admin_password'), PASSWORD_DEFAULT),
        ]);

        $row = $model->find($model->getInsertID());
        $this->auth->login(Auth::ROLE_ADMIN, $row);

        return redirect()->to(base_url('/'))
            ->with('success', 'Your workspace is ready.');
    }

    public function logout()
    {
        $this->auth->logout();

        return redirect()->to(route_to('login'))
            ->with('success', 'You have been signed out.');
    }
}
