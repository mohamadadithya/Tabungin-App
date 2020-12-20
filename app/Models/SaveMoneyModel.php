<?php

namespace App\Models;

use CodeIgniter\Model;

class SaveMoneyModel extends Model
{
    protected $table = 'save_money';
    protected $useTimestamps = true;
    protected $allowedFields = ['money_amount', 'user'];

    public function takeMoney($data)
    {
        $this->save([
            'id' => $data['id'],
            'money_amount' => $data['money_amount'],
            'user' => $data['user']
        ]);
    }
}
