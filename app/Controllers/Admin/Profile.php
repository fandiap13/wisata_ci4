<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function ubah_profile()
    {
        if ($this->request->isAJAX()) {
            $id = session('LoggedUserData')['id'];
            $email_user = $this->request->getPost('email_user');
            $nama_user = $this->request->getPost('nama_user');
            $telp_user = $this->request->getPost('telp_user');
            $password_user = $this->request->getPost('password_user');

            $validation = \Config\Services::validation();

            if ($password_user == "" || $password_user === NULL) {
                $rulesPassword = 'permit_empty';
                $rulesRetype = "permit_empty";
            } else {
                $rulesPassword = 'required|max_length[100]|min_length[5]';
                $rulesRetype = "required|matches[password_user]";
            }

            $valid = $this->validate([
                'email_user' => [
                    'label' => 'E-mail',
                    'rules' => 'required|valid_email|is_unique[users.email_user, id, ' . $id . ']',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !',
                        'valid_email' => '{field} tidak valid !',
                        'is_unique' => '{field} sudah digunakan'
                    ]
                ],
                'nama_user' => [
                    'label' => 'Nama',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !',
                    ]
                ],
                'telp_user' => [
                    'label' => 'Telp',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !',
                    ]
                ],
                'password_user' => [
                    'label' => 'Password',
                    'rules' => $rulesPassword,
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !',
                        'max_length' => '{field} maksimal 100 karakter',
                        'min_length' => '{field} minimal 5 karakter'
                    ]
                ],
                'retype_password' => [
                    'label' => 'Retype Password',
                    'rules' => $rulesRetype,
                    'errors' => [
                        'matches' => '{field} tidak sesuai !',
                        'required' => '{field} tidak boleh kosong !',
                    ]
                ],
            ]);

            if ($valid) {
                try {
                    if ($password_user == "" || $password_user === NULL) {
                        $this->UserModel->update($id, [
                            'email_user' => $email_user,
                            'nama_user' => $nama_user,
                            'telp_user' => $telp_user,
                        ]);
                    } else {
                        $password = password_hash($password_user, PASSWORD_DEFAULT);
                        $this->UserModel->update($id, [
                            'email_user' => $email_user,
                            'nama_user' => $nama_user,
                            'telp_user' => $telp_user,
                            'password_user' => $password,
                        ]);
                    }
                    $json = [
                        'success' => 'Profile berhasil diubah'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Profile gagal diubah'
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'email_user' => $validation->getError('email_user'),
                        'nama_user' => $validation->getError('nama_user'),
                        'telp_user' => $validation->getError('telp_user'),
                        'password_user' => $validation->getError('password_user'),
                        'retype_password' => $validation->getError('retype_password'),
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses !');
        }
    }
}
