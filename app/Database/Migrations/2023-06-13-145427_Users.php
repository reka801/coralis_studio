<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField('id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        $this->forge->addField('email VARCHAR(255) NOT NULL');
        $this->forge->addField('name VARCHAR(255) NOT NULL');
        $this->forge->addField('password VARCHAR(255) NOT NULL');
        $this->forge->addField('profile_picture VARCHAR(255)');
        $this->forge->addField('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
