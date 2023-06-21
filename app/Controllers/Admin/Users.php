<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{
    protected $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function admin()
    {
        return view('admin/users/v_dataadmin', [
            'title' => 'Data admin',
        ]);
    }

    public function admin_deleted()
    {
        return view('admin/users/v_deleted_dataadmin', [
            'title' => 'Data admin terhapus',
        ]);
    }

    public function pengunjung()
    {
        return view('admin/users/v_datapengunjung', [
            'title' => 'Data pengunjung',
        ]);
    }

    public function pengunjung_deleted()
    {
        return view('admin/users/v_deleted_datapengunjung', [
            'title' => 'Data pengunjung terhapus',
        ]);
    }

    public function dataadmin()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->UserModel->listAdmin();

            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    return
                        "
                    <div class='text-center'>
                    <button type='button' class='btn btn-sm btn-info' title='Edit Data' onclick='edit(\"$row->id\");'><i class='fa fa-edit'></i></button>

                    <button type='button' class='btn btn-sm btn-danger' title='Hapus Data' onclick='hapus(\"$row->id\", \"$row->nama_user\");'><i class='fa fa-trash-alt'></i></button>
                    </div>
                    ";
                })
                ->addNumbering('nomor')
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function dataadmin_deleted()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->UserModel->listAdminDeleted();

            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    return
                        "
                    <div class='text-center'>
                    <button type='button' class='btn btn-sm btn-info' title='Recover data' onclick='recover(\"$row->id\", \"$row->nama_user\");'><i class='fas fa-redo-alt'></i></button>

                    <button type='button' class='btn btn-sm btn-danger' title='Hapus Permanen' onclick='hapusPermanen(\"$row->id\", \"$row->nama_user\");'><i class='fa fa-trash-alt'></i></button>
                    </div>
                    ";
                })
                ->addNumbering('nomor')
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function datapengunjung()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->UserModel->listPengunjung();

            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    return
                        "
                    <div class='text-center'>
                    <button type='button' class='btn btn-sm btn-info' title='Edit Data' onclick='edit(\"$row->id\");'><i class='fa fa-edit'></i></button>

                    <button type='submit' class='btn btn-sm btn-danger' title='Hapus Data' onclick='hapus(\"$row->id\", \"$row->nama_user\");'><i class='fa fa-trash-alt'></i></button>
                    ";
                })
                ->addNumbering('nomor')
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function datapengunjung_deleted()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->UserModel->listPengunjungDeleted();

            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    return
                        "
                    <div class='text-center'>
                    <button type='button' class='btn btn-sm btn-info' title='Recover data' onclick='recover(\"$row->id\", \"$row->nama_user\");'><i class='fas fa-redo-alt'></i></button>

                    <button type='button' class='btn btn-sm btn-danger' title='Hapus Permanen' onclick='hapusPermanen(\"$row->id\", \"$row->nama_user\");'><i class='fa fa-trash-alt'></i></button>
                    </div>
                    ";
                })
                ->addNumbering('nomor')
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            try {
                $cek = $this->UserModel->delete($id);
                if ($cek) {
                    $data = [
                        'sukses' => 'Data admin berhasil dihapus'
                    ];
                }
            } catch (\Throwable $th) {
                $data = [
                    'error' => 'Data admin tidak dapat dihapus'
                ];
            }
            echo json_encode($data);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function forceDelete($id)
    {
        if ($this->request->isAJAX()) {
            try {
                $cek = $this->UserModel->delete($id, true);
                if ($cek) {
                    $data = [
                        'sukses' => 'Data admin berhasil dihapus secara permanen'
                    ];
                }
            } catch (\Throwable $th) {
                $data = [
                    'error' => 'Data admin tidak dapat dihapus'
                ];
            }
            echo json_encode($data);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function recover()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $cekData = $this->UserModel->onlyDeleted()->find($id);
            if ($cekData) {
                try {
                    $this->UserModel->update($id, [
                        'deleted_at' => null
                    ]);
                    $json = [
                        'sukses' => 'Data berhasil dipulihkan'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Data gagal dipulihkan'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Data tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function modaltambahpengunjung()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data' => view('admin/users/modaltambahpengunjung')
            ];
            echo json_encode($data);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function modaleditpengunjung()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->UserModel->find($id);
            if ($cekData) {
                $json = [
                    'data' => view('admin/users/modaleditpengunjung', [
                        'pengunjung' =>  $cekData
                    ])
                ];
            } else {
                $json = [
                    'error' => 'Data dengan id ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function modaltambahadmin()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data' => view('admin/users/modaltambahadmin')
            ];
            echo json_encode($data);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function modaleditadmin()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->UserModel->find($id);
            if ($cekData) {
                $json = [
                    'data' => view('admin/users/modaleditadmin', [
                        'admin' =>  $cekData
                    ])
                ];
            } else {
                $json = [
                    'error' => 'Data dengan id ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function simpanadmin()
    {
        if ($this->request->isAJAX()) {
            $nama_user = $this->request->getPost('nama_user');
            $email_user = $this->request->getPost('email_user');
            $password_user = $this->request->getPost('password_user');
            $telp_user = $this->request->getPost('telp_user');

            $valid = $this->validate([
                'nama_user' => [
                    'label' => 'Nama',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 150 karakter'
                    ]
                ],
                'telp_user' => [
                    'label' => 'Telp',
                    'rules' => 'required|max_length[15]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 15 karakter'
                    ]
                ],
                'password_user' => [
                    'label' => 'Password',
                    'rules' => 'required|max_length[150]|min_length[5]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 150 karakter',
                        'min_length' => '{field} min 5 karakter',
                    ]
                ],
                'retype_password' => [
                    'label' => 'Retype password',
                    'rules' => 'required|matches[password_user]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'matches' => '{field} tidak valid',
                    ]
                ],
                'email_user' => [
                    'label' => 'E-mail',
                    'rules' => 'required|valid_email|max_length[150]|is_unique[users.email_user]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 150 karakter',
                        'is_unique' => '{field} ' . $email_user . ' sudah terdaftar di sistem',
                        'valid_email' => '{field} tidak valid'
                    ]
                ],
            ]);

            $validation = \Config\Services::validation();

            if ($valid) {
                $password = password_hash($password_user, PASSWORD_DEFAULT);
                $this->UserModel->insert([
                    'email_user' => $email_user,
                    'nama_user' => $nama_user,
                    'telp_user' => $telp_user,
                    'password_user' => $password,
                    'role' => 'Admin',
                ]);
                $json = [
                    'success' => "Admin $nama_user berhasil ditambahkan"
                ];
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
            exit('Tidak dapat diprose');
        }
    }

    public function simpanpengunjung()
    {
        if ($this->request->isAJAX()) {
            $nama_user = $this->request->getPost('nama_user');
            $email_user = $this->request->getPost('email_user');
            $password_user = $this->request->getPost('password_user');
            $telp_user = $this->request->getPost('telp_user');

            $valid = $this->validate([
                'nama_user' => [
                    'label' => 'Nama',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 150 karakter'
                    ]
                ],
                'telp_user' => [
                    'label' => 'Telp',
                    'rules' => 'required|max_length[15]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 15 karakter'
                    ]
                ],
                'password_user' => [
                    'label' => 'Password',
                    'rules' => 'required|max_length[150]|min_length[5]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 150 karakter',
                        'min_length' => '{field} min 5 karakter',
                    ]
                ],
                'retype_password' => [
                    'label' => 'Retype password',
                    'rules' => 'required|matches[password_user]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'matches' => '{field} tidak valid',
                    ]
                ],
                'email_user' => [
                    'label' => 'E-mail',
                    'rules' => 'required|valid_email|max_length[150]|is_unique[users.email_user]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'max_length' => '{field} max 150 karakter',
                        'is_unique' => '{field} ' . $email_user . ' sudah terdaftar di sistem',
                        'valid_email' => '{field} tidak valid'
                    ]
                ],
            ]);

            $validation = \Config\Services::validation();

            if ($valid) {
                $password = password_hash($password_user, PASSWORD_DEFAULT);
                $this->UserModel->insert([
                    'email_user' => $email_user,
                    'nama_user' => $nama_user,
                    'telp_user' => $telp_user,
                    'password_user' => $password,
                    'role' => 'Pengunjung',
                ]);
                $json = [
                    'success' => "Pengunjung $nama_user berhasil ditambahkan"
                ];
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
            exit('Tidak dapat diprose');
        }
    }

    public function update_pengunjung()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nama_user = $this->request->getPost('nama_user');
            $email_user = $this->request->getPost('email_user');
            $telp_user = $this->request->getPost('telp_user');

            $cekData = $this->UserModel->find($id);
            if ($cekData) {
                $valid = $this->validate([
                    'nama_user' => [
                        'label' => 'Nama',
                        'rules' => 'required|max_length[150]',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 150 karakter'
                        ]
                    ],
                    'telp_user' => [
                        'label' => 'Telp',
                        'rules' => 'required|max_length[15]',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 15 karakter'
                        ]
                    ],
                    'email_user' => [
                        'label' => 'E-mail',
                        'rules' => 'required|valid_email|max_length[150]|is_unique[users.email_user, id, ' . $id . ']',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 150 karakter',
                            'is_unique' => '{field} <b>' . $email_user . '</b> sudah terdaftar di sistem',
                            'valid_email' => '{field} tidak valid'
                        ]
                    ],
                ]);

                $validation = \Config\Services::validation();

                if ($valid) {
                    $this->UserModel->update($id, [
                        'email_user' => $email_user,
                        'nama_user' => $nama_user,
                        'telp_user' => $telp_user,
                        'role' => 'Pengunjung',
                    ]);
                    $json = [
                        'success' => "Pengunjung $nama_user berhasil diubah"
                    ];
                } else {
                    $json = [
                        'errors' => [
                            'email_user' => $validation->getError('email_user'),
                            'nama_user' => $validation->getError('nama_user'),
                        ]
                    ];
                }
            } else {
                $json = [
                    'error' => 'Pengunjung dengan ID ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diprose');
        }
    }

    public function update_admin()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nama_user = $this->request->getPost('nama_user');
            $email_user = $this->request->getPost('email_user');
            $password_user = $this->request->getPost('password_user');
            $telp_user = $this->request->getPost('telp_user');

            $cekData = $this->UserModel->find($id);
            if ($cekData) {
                if (!empty($password_user)) {
                    $password = password_hash($password_user, PASSWORD_DEFAULT);
                    $rules_password = 'max_length[150]|min_length[5]';
                } else {
                    $rules_password = "permit_empty";
                    $password = $cekData['password_user'];
                }

                $valid = $this->validate([
                    'nama_user' => [
                        'label' => 'Nama',
                        'rules' => 'required|max_length[150]',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 150 karakter'
                        ]
                    ],
                    'telp_user' => [
                        'label' => 'Telp',
                        'rules' => 'required|max_length[15]',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 15 karakter'
                        ]
                    ],
                    'password_user' => [
                        'label' => 'Password',
                        'rules' => $rules_password,
                        'errors' => [
                            'max_length' => '{field} max 150 karakter',
                            'min_length' => '{field} min 5 karakter',
                        ]
                    ],
                    'retype_password' => [
                        'label' => 'Retype password',
                        'rules' => 'matches[password_user]',
                        'errors' => [
                            'matches' => '{field} tidak valid',
                        ]
                    ],
                    'email_user' => [
                        'label' => 'E-mail',
                        'rules' => 'required|valid_email|max_length[150]|is_unique[users.email_user, id, ' . $id . ']',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 150 karakter',
                            'is_unique' => '{field} <b>' . $email_user . '</b> sudah terdaftar di sistem',
                            'valid_email' => '{field} tidak valid'
                        ]
                    ],
                ]);

                $validation = \Config\Services::validation();

                if ($valid) {
                    $this->UserModel->update($id, [
                        'email_user' => $email_user,
                        'nama_user' => $nama_user,
                        'password_user' => $password,
                        'telp_user' => $telp_user,
                        'role' => 'Admin',
                    ]);
                    $json = [
                        'success' => "Admin $nama_user berhasil diubah"
                    ];
                } else {
                    $json = [
                        'errors' => [
                            'email_user' => $validation->getError('email_user'),
                            'nama_user' => $validation->getError('nama_user'),
                            'password_user' => $validation->getError('password_user'),
                            'telp_user' => $validation->getError('telp_user'),
                            'retype_password' => $validation->getError('retype_password'),
                        ]
                    ];
                }
            } else {
                $json = [
                    'error' => 'Admin dengan ID ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diprose');
        }
    }

    public function modalpelanggan()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('admin/transaksioffline/modalpelanggan')
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diprose');
        }
    }

    public function datapelanggan()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->UserModel->listPengunjung();

            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    return
                        "
                    <div class='text-center'>
                    <button type='button' class='btn btn-sm btn-info' title='Pilih Pelanggan' onclick='pilih(\"$row->id\",\"$row->nama_user\");'>Pilih</button>
                    </div>
                    ";
                })
                ->addNumbering('nomor')
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }
}
