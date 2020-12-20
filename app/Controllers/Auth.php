<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Validation\Rules;
use Config\Services;

class Auth extends BaseController
{
    protected $validation;
    protected $usersModel;
    protected $session;
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->usersModel = new UsersModel();
        $this->session = \Config\Services::session();
        helper(['form']);
    }

    public function index()
    {
        session();
        $data = [
            'title' => 'Login',
            'validation' => $this->validation
        ];
        return view('auth/login', $data);
    }

    public function login()
    {
        if (!$this->validate([
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} must be fill!'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} must be fill!'
                ]
            ]
        ])) {
            return redirect()->to('/')->withInput();
        } else {
            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');

            $user = $this->usersModel->where('username', $username)->first();

            if (is_null($user)) {
                session()->setFlashdata('password', 'User not found!');
                return redirect()->to('/');
            } else {
                if (!password_verify($password, $user['password'])) {
                    session()->setFlashdata('password', 'Password is wrong!');
                    return redirect()->to('/');
                } else {
                    $this->session->set('isLoggedIn');
                    $this->session->set('userData', [
                        'username' => $user['username'],
                        'user_id' => $user['user_id']
                    ]);
                    return redirect()->to('/dashboard');
                }
            }
        }
    }

    public function logout()
    {
        $this->session->remove(['isLoggedIn', 'userData']);
        return redirect()->to('/login');
    }
}
