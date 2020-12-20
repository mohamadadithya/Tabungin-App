<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $useTimestamps = true;
    protected $allowedFields = ['username', 'id'];

    public function update_profile($data)
    {
        $this->save([
            'id' => $data['id'],
            'username' => $data['username']
        ]);
    }
}
