<?php

namespace App\Models;

use CodeIgniter\Model;

class SaveMonLogModel extends Model
{
    protected $table = 'save_money_log';
    protected $useTimestamps = true;
    protected $allowedFields = ['money_amount', 'user_id', 'log'];

    public function saveLog($data)
    {
        $this->save([
            'money_amount' => $data['money_amount'],
            'user_id' => $data['user_id'],
            'log' => $data['log']
        ]);
    }

    public function takeLog($data_log)
    {
        $this->save([
            'money_amount' => $data_log['money_amount'],
            'user_id' => $data_log['user_id'],
            'log' => $data_log['log']
        ]);
    }
}
