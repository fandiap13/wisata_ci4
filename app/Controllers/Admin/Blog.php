<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogModel;
use \Hermawan\DataTables\DataTable;

class Blog extends BaseController
{
    protected $BlogModel;
    public function __construct()
    {
        $this->BlogModel = new BlogModel();
    }

    public function index()
    {
        return view('admin/blog/v_blog', [
            'title' => 'Blog'
        ]);
    }

    public function deleted_blog()
    {
        // dd($this->BlogModel->onlyDeleted()->findAll());
        return view('admin/blog/v_deleted_blog', [
            'title' => 'Deleted Blog'
        ]);
    }

    public function datablog()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('blog')->select('id, judul, created_at, updated_at, status')->where('deleted_at IS NULL')->orderBy('created_at', 'DESC');

            return DataTable::of($builder)
                ->add('created_at', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->created_at));
                })
                ->add('updated_at', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->updated_at));
                })
                ->add('status_blog', function ($row) {
                    return $row->status == 2 ? '<span class="badge badge-danger">Draf</span>' : '<span class="badge badge-success">Dipublikasi</span>';
                })
                ->add('aksi', function ($row) {
                    return
                        "
                        <div class='text-center'>
                        <div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-primary' title='Lihat Postingan' onclick='lihat(\"$row->id\");'><i class='fa fa-eye'></i></button>

                        <button type='button' class='btn btn-sm btn-info' title='Edit Data' onclick='edit(\"$row->id\");'><i class='fa fa-edit'></i></button>

                        <button type='button' class='btn btn-sm btn-danger' title='Hapus Data' onclick='hapus(\"$row->id\");'><i class='fa fa-trash-alt'></i></button>
                        </div>
                        </div>
                    ";
                })
                ->addNumbering('nomor')
                ->filter(function ($builder, $request) {
                    if ($request->status)
                        $builder->where('status', $request->status);
                })
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function deleted_datablog()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->BlogModel->select('id, judul, created_at, updated_at, status')->where('deleted_at IS NOT NULL')->orderBy('created_at', 'DESC');

            return DataTable::of($builder)
                ->add('created_at', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->created_at));
                })
                ->add('updated_at', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->updated_at));
                })
                ->add('status', function ($row) {
                    return $row->status == 0 ? '<span class="badge badge-danger">Draf</span>' : '<span class="badge badge-success">Dipublikasi</span>';
                })
                ->add('aksi', function ($row) {
                    return
                        "
                    <div class='text-center'>
                    <button type='button' class='btn btn-sm btn-info' title='Recover data' onclick='recover(\"$row->id\");'><i class='fas fa-redo-alt'></i></button>

                    <button type='button' class='btn btn-sm btn-danger' title='Hapus Data' onclick='hapus(\"$row->id\");'><i class='fa fa-trash-alt'></i></button>
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
            $cek = $this->BlogModel->find($id);
            if ($cek) {
                try {
                    $this->BlogModel->delete($id);
                    $data = [
                        'sukses' => 'Data blog berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $data = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $data = [
                    'error' => 'Data blog tidak ditemukan'
                ];
            }
            echo json_encode($data);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function forceDeleted($id)
    {
        if ($this->request->isAJAX()) {
            $cek = $this->BlogModel->onlyDeleted()->find($id);
            if ($cek) {
                try {
                    $this->BlogModel->delete($id, true);
                    if ($cek['gambar'] != NULL and $cek['gambar'] != "") {
                        if ($cek['gambar'] != "gambar/default.png") {
                            unlink($cek['gambar']);
                        }
                    }
                    $data = [
                        'success' => 'Data blog berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $data = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $data = [
                    'error' => 'Data blog tidak ditemukan'
                ];
            }
            echo json_encode($data);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function tambah_postingan()
    {
        return view('admin/blog/v_tambah_postingan', [
            'title' => 'Tambah Postingan'
        ]);
    }

    public function edit_postingan($id)
    {
        $cekData = $this->BlogModel->find($id);
        if ($cekData) {
            return view('admin/blog/v_edit_postingan', [
                'title' => 'Edit Postingan',
                'data' => $cekData
            ]);
        } else {
            return redirect()->to(site_url('/admin/blog/index'));
        }
    }

    public function simpan()
    {
        if ($this->request->isAJAX()) {
            $judul = $this->request->getPost('judul');
            $deskripsi = $this->request->getPost('deskripsi');
            $status = $this->request->getPost('status');
            $gambar = $this->request->getFile('gambar');

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'gambar' => [
                    'label' => "Gambar",
                    'rules' => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/png,image/jpg,image/jpeg]',
                    'errors' => [
                        'uploaded' => '{field} tidak boleh kosong',
                        'max_size' => '{field} tidak boleh lebih dari 2 mb',
                        'is_image' => 'Yg anda input bukan gambar',
                        'mime_in' => '{field} harus jpg/jpeg/png',
                    ]
                ],
                'judul' => [
                    'label' => "Judul",
                    'rules' => 'is_unique[blog.judul]',
                    'errors' => [
                        'is_unique' => '{field} sudah dipakai',
                    ]
                ]
            ]);

            if ($valid) {
                try {
                    $namaGambar = "gambar/default.png";
                    if ($gambar->getError() != 4) {
                        $namaGb = $gambar->getRandomName();
                        $gambar->move('gambar/blog/', $namaGb);
                        $namaGambar = "gambar/blog/" . $namaGb;
                    }

                    $this->BlogModel->insert([
                        'judul' => $judul,
                        'deskripsi' => $deskripsi,
                        'status' => $status,
                        'gambar' => $namaGambar,
                        'userid' => session('LoggedUserData')['id']
                    ]);
                    $json = [
                        'success' => 'Tambah postingan berhasil'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'gambar' => $validation->getError('gambar'),
                        'judul' => $validation->getError('judul')
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $judul = $this->request->getPost('judul');
            $deskripsi = $this->request->getPost('deskripsi');
            $status = $this->request->getPost('status');
            $gambarLama = $this->request->getPost('gambarLama');
            $gambar = $this->request->getFile('gambar');

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'gambar' => [
                    'label' => "Gambar",
                    'rules' => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/png,image/jpg,image/jpeg]',
                    'errors' => [
                        'max_size' => '{field} tidak boleh lebih dari 2 mb',
                        'is_image' => 'Yg anda input bukan gambar',
                        'mime_in' => '{field} harus jpg/jpeg/png',
                    ]
                ],
                'judul' => [
                    'label' => "Judul",
                    'rules' => 'is_unique[blog.judul, id, ' . $id . ']',
                    'errors' => [
                        'is_unique' => '{field} sudah dipakai',
                    ]
                ]
            ]);

            if ($valid) {
                try {
                    $namaGambar = $gambarLama;
                    if ($gambar->getError() != 4) {
                        if ($gambarLama != 'gambar/default.png') {
                            unlink($gambarLama);
                        }
                        $namaGb = $gambar->getRandomName();
                        $gambar->move('gambar/blog/', $namaGb);
                        $namaGambar = "gambar/blog/" . $namaGb;
                    }

                    $this->BlogModel->update($id, [
                        'judul' => $judul,
                        'deskripsi' => $deskripsi,
                        'status' => $status,
                        'gambar' => $namaGambar,
                    ]);
                    $json = [
                        'success' => 'Edit postingan berhasil'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'gambar' => $validation->getError('gambar'),
                        'judul' => $validation->getError('judul')
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function recover()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->BlogModel->onlyDeleted()->find($id);
            if ($cekData) {
                try {
                    $this->BlogModel->update($id, [
                        'deleted_at' => NULL
                    ]);
                    $json = [
                        'success' => 'Data postingan berhasil dipulihkan'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Data postingan tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }
}
