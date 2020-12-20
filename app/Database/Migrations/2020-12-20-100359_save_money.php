<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SaveMoney extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          	   => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'money_amount'     => [
				'type'           => 'INT',
				'constraint'     => 11,
			],
			'user'     		   => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'created_at'       => [
				'type'           => 'DATETIME'
			],
			'updated_at'       => [
				'type'           => 'DATETIME'
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('save_money');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
	}
}
