<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'email_user', 'password_user', 'nama_user', 'telp_user', 'created_at', 'updated_at', 'deleted_at', 'token_ganti_pass', 'token_register', 'role'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function cekEmail($email)
    {
        return $this->where('email_user', $email)->where('token_register IS NULL', NULL)->where('deleted_at IS NULL')->first();
    }

    public function listAdmin()
    {
        return $this->select('id, email_user, nama_user, telp_user, role')->where('role', 'Admin')->where('deleted_at IS NULL');
    }

    public function listAdminDeleted()
    {
        return $this->select('id, email_user, nama_user, telp_user, role')->where('role', 'Admin')->where('deleted_at IS NOT NULL');
    }

    public function listPengunjung()
    {
        return $this->select('id, email_user, nama_user, telp_user, role')->where('role', 'Pengunjung')->where('deleted_at IS NULL');
    }

    public function listPengunjungDeleted()
    {
        return $this->select('id, email_user, nama_user, telp_user, role')->where('role', 'Pengunjung')->where('deleted_at IS NOT NULL');
    }
}
