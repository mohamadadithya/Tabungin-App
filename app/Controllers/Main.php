<?php

namespace App\Controllers;

use App\Models\SaveMoneyModel;
use App\Models\SaveMonLogModel;
use App\Models\UsersModel;

class Main extends BaseController
{
    protected $usersModel;
    protected $session;
    protected $userData;
    protected $validation;
    protected $savemoneyModel;
    protected $savemoneylogModel;
    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->savemoneyModel = new SaveMoneyModel();
        $this->savemoneylogModel = new SaveMonLogModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->userData = $this->usersModel->where('user_id', $this->session->userData['user_id'])->first();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'userData' => $this->userData,
            'my_money' => $this->savemoneyModel->where('user', $this->userData['username'])->first(),
            'my_savedLog' => $this->savemoneylogModel->orderBy('id', 'DESC')->where('user_id', $this->userData['id'])->first()
        ];
        return view('pages/dashboard', $data);
    }

    public function save_money()
    {
        $data = [
            'title' => 'Save Money',
            'userData' => $this->userData
        ];
        return view('pages/save-money', $data);
    }

    public function takeSaveMoneyData()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data_save' => $this->savemoneyModel->where('user', $this->session->userData['username'])->first()
            ];
            if ($data['data_save']) {
                $msg = [
                    'data' => $data['data_save']['money_amount']
                ];
            } else {
                $msg = [
                    'data' => 0
                ];
            }
            echo json_encode($msg);
        }
    }

    public function takeSaveMoneyLog()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data_log' => $this->savemoneylogModel->orderBy('id', 'DESC')->where('user_id', $this->userData['id'])->findAll()
            ];
            $msg = [
                'data' => view('data/data_log', $data)
            ];
            echo json_encode($msg);
        } else {
            return redirect()->to('/save-money');
        }
    }

    public function save_money_processing()
    {
        if ($this->request->isAJAX()) {
            $valid = $this->validate([
                'money' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} amount must be fill!'
                    ]
                ]
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'money' => $this->validation->getError('money')
                    ]
                ];
                echo json_encode($msg);
            } else {
                $money_amount = $this->request->getVar('money');
                $save_db = $this->savemoneyModel->where('user', $this->session->userData['username'])->first();
                // Condition
                if ($save_db) {
                    $this->savemoneyModel->save([
                        'id' => $save_db['id'],
                        'money_amount' => $save_db['money_amount'] + $money_amount,
                        'user' => $this->session->userData['username']
                    ]);
                } else {
                    $this->savemoneyModel->save([
                        'money_amount' => $money_amount,
                        'user' => $this->session->userData['username']
                    ]);
                }
                // Save Money Log
                $data = [
                    'money_amount' => $money_amount,
                    'user_id' => $this->userData['id'],
                    'log' => 'Last saved on ' . date('F d Y m:h') . ' with an amount Rp. ' . $money_amount
                ];
                $this->savemoneylogModel->saveLog($data);
                $msg = [
                    'success' => 'you has been saved Rp. ' . $money_amount
                ];
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('/save-money');
        }
    }

    public function take_money()
    {
        $data = [
            'title' => 'Take Money',
            'userData' => $this->userData
        ];
        return view('pages/take-money', $data);
    }

    public function take_money_processing()
    {
        if ($this->request->isAJAX()) {
            $valid = $this->validate([
                'money' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} amount must be fill!'
                    ]
                ]
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'money' => $this->validation->getError('money')
                    ]
                ];
                echo json_encode($msg);
            } else {
                $money_amount = $this->request->getVar('money');
                $savedData = $this->savemoneyModel->where('user', $this->userData['username'])->first();
                if ($savedData && $savedData['money_amount'] >= $money_amount) {
                    $data = [
                        'id' => $savedData['id'],
                        'money_amount' => $savedData['money_amount'] - $money_amount,
                        'user' => $this->userData['username']
                    ];
                    $this->savemoneyModel->takeMoney($data);
                    // Take money log
                    $data_log = [
                        'money_amount' => $money_amount,
                        'user_id' => $this->userData['id'],
                        'log' => 'Last taked on ' . date('F d Y m:h') . ' with an amount Rp. ' . $money_amount
                    ];
                    $this->savemoneylogModel->takeLog($data_log);
                    $msg = [
                        'success' => 'You has been taked Rp. ' . $money_amount
                    ];
                    echo json_encode($msg);
                } else {
                    $msg = [
                        'message' => [
                            'null' => 'You don\'t have much money, can\'t take!.'
                        ]
                    ];
                    echo json_encode($msg);
                }
            }
        } else {
            return redirect()->to('/take-money');
        }
    }

    public function profile()
    {
        session();
        $data = [
            'title' => 'Profile | ' . $this->userData['username'],
            'userData' => $this->userData,
            'validation' => \Config\Services::validation()
        ];
        return view('pages/profile', $data);
    }

    public function update_profile()
    {
        if (!$this->validate([
            'username' => [
                'rules' => 'required|is_unique[users.username]',
                'errors' => [
                    'required' => '{field} must be fill!'
                ]
            ]
        ])) {
            return redirect()->to('/profile')->withInput();
        } else {
            $data = [
                'id' => $this->userData['id'],
                'username' => $this->request->getVar('username')
            ];
            $this->usersModel->update_profile($data);
            $this->session->setFlashdata('message', 'Your profile has been updated!');
            return redirect()->to('/profile');
        }
    }
}
