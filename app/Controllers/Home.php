<?php

namespace App\Controllers;

use App\Models\BlogModel;
use App\Models\GaleriModel;
use App\Models\SettingModel;
use App\Models\TiketModel;
use App\Models\UserModel;
use App\Models\WisataModel;

class Home extends BaseController
{
    protected $BlogModel;
    protected $WisataModel;
    protected $TiketModel;
    protected $SettingModel;
    protected $GaleriModel;
    protected $UserModel;
    public function __construct()
    {
        $this->BlogModel = new BlogModel();
        $this->WisataModel = new WisataModel();
        $this->TiketModel = new TiketModel();
        $this->SettingModel = new SettingModel();
        $this->GaleriModel = new GaleriModel();
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        return view('index', [
            'title' => 'Home',
            'blog' => $this->BlogModel->where('deleted_at', NULL)->where('status', 1)->limit(3)->get()->getResultArray(),
            'wisata' => $this->WisataModel->where('status', 1)->where('deleted_at', NULL)->get()->getResultArray(),
            'setting' => $this->SettingModel->limit(1)->orderBy('id', 'ASC')->get()->getRowArray()
        ]);
    }

    public function about_us()
    {
        return view('v_about_us', [
            'title' => 'About Us',
            'setting' => $this->SettingModel->limit(1)->orderBy('id', 'ASC')->get()->getRowArray()
        ]);
    }

    public function blog($id = null)
    {
        if ($id === null) {
            $cari = $this->request->getGet('cari');
            if ($cari) {
                $blog = $this->BlogModel->where('deleted_at', NULL)->where('status', 1)
                    ->like('judul', $cari)->orderBy('created_at', 'DESC')->paginate(9, 'blog');
            } else {
                $blog = $this->BlogModel->where('deleted_at', NULL)->where('status', 1)
                    ->orderBy('created_at', 'DESC')->paginate(9, 'blog');
            }
            $nohalaman = $this->request->getVar('page_blog') ? $this->request->getVar('page_blog') : 1;
            return view('v_blog', [
                'title' => 'Blog',
                'blog' => $blog,
                'nohalaman' => $nohalaman,
                'pager' => $this->BlogModel->pager
            ]);
        } else {
            $cekData = $this->BlogModel
                ->select('blog.*, users.nama_user')
                ->join('users', 'blog.userid=users.id')
                ->where('blog.deleted_at', NULL)
                ->where('blog.status', 1)
                ->where('blog.id', $id)->get()->getRowArray();
            $terkait = $this->BlogModel->where('deleted_at', NULL)->where('status', 1)
                ->limit(3)->orderBy('created_at', 'DESC')->get()->getResultArray();
            if ($cekData) {
                return view('v_detail_blog', [
                    'title' => 'Detail Blog',
                    'blog' => $cekData,
                    'terkait' => $terkait,
                ]);
            } else {
                return redirect()->to(site_url('/blog'));
            }
        }
    }

    public function contact()
    {
        return view('v_contact', [
            'title' => 'Contact',
            'setting' => $this->SettingModel->limit(1)->orderBy('id', 'ASC')->get()->getRowArray()
        ]);
    }

    public function destination($id = null)
    {
        if ($id === null) {
            return view('v_destination', [
                'title' => 'Destinasi',
                'destinasi' => $this->WisataModel->where('status', 1)->where('deleted_at', NULL)->get()->getResultArray()
            ]);
        } else {
            $destinasi = $this->WisataModel->where('status', 1)->where('id', $id)->where('deleted_at', NULL)->get()->getRowArray();
            $tiket = $this->TiketModel->where('wisataid', $id)->where('status_tiket', 1)->get()->getResultArray();
            $galeri = $this->GaleriModel->where('wisataid', $id)->get()->getResultArray();
            if ($destinasi) {
                return view('v_detail_destination', [
                    'title' => 'Detail Destinasi',
                    'destinasi' => $destinasi,
                    'tiket' => $tiket,
                    'galeri' => $galeri,
                ]);
            } else {
                return redirect()->to(site_url('/destination'));
            }
        }
    }

    public function tampil_tiket()
    {
        if ($this->request->isAJAX()) {
            $wisataid = $this->request->getPost('wisataid');

            $json = [
                'data' => view('data_tiket', [
                    'tiket' => $this->TiketModel->where('wisataid', $wisataid)->where('status_tiket', 1)
                        ->get()->getResultArray()
                ])
            ];

            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function profile()
    {
        $cekUser = $this->UserModel->find(session('LoggedUserData')['id']);
        if ($cekUser) {
            return view('pengunjung/v_profile', [
                'title' => "Profile",
                'user' => $cekUser
            ]);
        } else {
            return redirect()->to(site_url('/'));
        }
    }

    public function ubah_profile()
    {
        if ($this->request->isAJAX()) {
            $id = session('LoggedUserData')['id'];
            $cekUser = $this->UserModel->find($id);
            if ($cekUser) {
                $nama_user = $this->request->getPost('nama_user');
                $email = $this->request->getPost('email');
                $telp = $this->request->getPost('telp');

                $validation = \Config\Services::validation();

                $valid = $this->validate([
                    'nama_user' => [
                        'label' => 'Nama',
                        'rules' => 'required|max_length[150]',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 150 karakter'
                        ]
                    ],
                    'email' => [
                        'label' => 'E-mail',
                        'rules' => 'required|valid_email|max_length[150]|is_unique[users.email_user, id, ' . $id . ']',
                        'errors' => [
                            'required' => '{field} tidak boleh kosong',
                            'max_length' => '{field} max 150 karakter',
                            'is_unique' => '{field} <b>' . $email . '</b> sudah terdaftar di sistem',
                            'valid_email' => '{field} tidak valid'
                        ]
                    ],
                ]);

                if ($valid) {
                    try {
                        $this->UserModel->update($id, [
                            'nama_user' => $nama_user,
                            'email_user' => $email,
                            'telp_user' => $telp
                        ]);
                        $json = [
                            'success' => 'Update profile berhasil'
                        ];
                    } catch (\Throwable $th) {
                        $json = [
                            'error' => 'Terdapat kesalahan pada sistem'
                        ];
                    }
                } else {
                    $json = [
                        'errors' => [
                            'nama_user' => $validation->getError('nama_user'),
                            'email' => $validation->getError('email'),
                        ]
                    ];
                }
            } else {
                $json = [
                    'error' => 'Data user tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function ubah_password()
    {
        if ($this->request->isAJAX()) {
            $id = session('LoggedUserData')['id'];
            $cekUser = $this->UserModel->find($id);
            if ($cekUser) {
                $password = $this->request->getPost('password');

                $validation = \Config\Services::validation();

                $valid = $this->validate([
                    'password' => [
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
                        'rules' => 'matches[password]',
                        'errors' => [
                            'matches' => '{field} tidak valid',
                        ]
                    ],
                ]);

                if ($valid) {
                    try {
                        $passwordBaru = password_hash($password, PASSWORD_DEFAULT);
                        $this->UserModel->update($id, [
                            'password_user' => $passwordBaru,
                        ]);
                        $json = [
                            'success' => 'Update password berhasil'
                        ];
                    } catch (\Throwable $th) {
                        $json = [
                            'error' => 'Terdapat kesalahan pada sistem'
                        ];
                    }
                } else {
                    $json = [
                        'errors' => [
                            'password' => $validation->getError('password'),
                            'retype_password' => $validation->getError('retype_password'),
                        ]
                    ];
                }
            } else {
                $json = [
                    'error' => 'Data user tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }
}
