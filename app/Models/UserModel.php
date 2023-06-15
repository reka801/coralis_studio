<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
        protected $table = 'users';
        protected $primaryKey = 'id';
        protected $allowedFields = ['email', 'name', 'password', 'profile_picture', 'reset_token', 'reset_token_expires_at', 'created_at'];
}