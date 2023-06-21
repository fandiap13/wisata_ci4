<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GaleriModel;
use App\Models\TiketModel;
use App\Models\WisataModel;

class Wisata extends BaseController
{
    protected $WisataModel;
    protected $GaleriModel;
    protected $TiketModel;

    public function __construct()
    {
        $this->WisataModel = new WisataModel();
        $this->GaleriModel = new GaleriModel();
        $this->TiketModel = new TiketModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Wisata',
            'data' => $this->WisataModel->findAll()
        ];
        return view('admin/wisata/v_wisata', $data);
    }

    public function deleted_wisata()
    {
        $data = [
            'title' => 'Data Wisata Terhapus',
            'data' => $this->WisataModel->onlyDeleted()->findAll()
        ];
        return view('admin/wisata/v_deleted_wisata', $data);
    }

    public function tambah()
    {
        return view('/admin/wisata/v_tambahwisata', [
            'title' => 'Tambah Wisata',
        ]);
    }

    public function lengkapi_data($id)
    {
        $cekData = $this->WisataModel->find($id);
        if ($cekData) {
            return view('/admin/wisata/v_editwisata', [
                'title' => 'Lengkapi Data Wisata',
                'data' => $cekData
            ]);
        } else {
            return redirect()->to(site_url('/admin/wisata/index'));
        }
    }

    public function edit($id)
    {
        $cekData = $this->WisataModel->find($id);
        if ($cekData) {
            return view('/admin/wisata/v_editwisata', [
                'title' => 'Edit Wisata',
                'data' => $cekData
            ]);
        } else {
            return redirect()->to(site_url('/admin/wisata/index'));
        }
    }

    public function dataGaleri()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $dataGambar = $this->GaleriModel
                ->where('wisataid', $id)->where('status', 1)
                ->get()->getRowArray();
            if ($dataGambar) {
                $gambar_utama = $dataGambar['gambar'];
            } else {
                $gambar_utama = "";
            }

            $json = [
                'data' => view('admin/wisata/data_galeri', [
                    'galeri' => $this->GaleriModel->cariGaleri($id)->getResultArray(),
                    'gambar_utama' => $gambar_utama
                ])
            ];
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function tambah_wisata()
    {
        if ($this->request->isAJAX()) {
            $nama_wisata = $this->request->getPost('nama_wisata');
            $deskripsi = $this->request->getPost('deskripsi');
            $alamat = $this->request->getPost('alamat');
            $lokasi_gmap = $this->request->getPost('lokasi_gmap');
            $jam_buka = $this->request->getPost('jam_buka');
            $jam_tutup = $this->request->getPost('jam_tutup');

            try {
                $this->WisataModel->insert([
                    'nama_wisata' => $nama_wisata,
                    'deskripsi' => $deskripsi,
                    'alamat' => $alamat,
                    'lokasi_gmap' => $lokasi_gmap,
                    'jam_buka' => $jam_buka,
                    'jam_tutup' => $jam_tutup,
                    'status' => 0,
                ]);
                $json = [
                    'success' => 'Data wisata berhasil disimpan',
                    'id' => $this->WisataModel->getInsertID(),
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function simpan()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nama_wisata = $this->request->getPost('nama_wisata');
            $deskripsi = $this->request->getPost('deskripsi');
            $alamat = $this->request->getPost('alamat');
            $lokasi_gmap = $this->request->getPost('lokasi_gmap');
            $jam_buka = $this->request->getPost('jam_buka');
            $jam_tutup = $this->request->getPost('jam_tutup');
            $status = $this->request->getPost('status');

            try {
                $this->WisataModel->update($id, [
                    'nama_wisata' => $nama_wisata,
                    'deskripsi' => $deskripsi,
                    'alamat' => $alamat,
                    'lokasi_gmap' => $lokasi_gmap,
                    'jam_buka' => $jam_buka,
                    'jam_tutup' => $jam_tutup,
                    'status' => $status,
                ]);
                $json = [
                    'success' => 'Data wisata berhasil diubah',
                    'map' => $lokasi_gmap,
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function simpan_galeri()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $gambar = $this->request->getFileMultiple('gambar');
            $keterangan = $this->request->getPost('keterangan');

            $cekData = $this->WisataModel->find($id);
            if ($cekData) {
                $validation = \Config\Services::validation();

                $valid = $this->validate([
                    'gambar' => [
                        'label' => 'Gambar',
                        'rules' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/png,image/jpg,image/jpeg]',
                        'errors' => [
                            'uploaded' => '{field} tidak boleh kosong',
                            'max_size' => '{field} tidak boleh lebih dari 2 mb',
                            'is_image' => 'Yg anda input bukan gambar',
                            'mime_in' => '{field} harus jpg/jpeg/png',
                        ]
                    ]
                ]);

                if ($valid) {
                    try {
                        $daftarGambar = [];
                        $i = 1;
                        foreach ($gambar as $key => $file) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $namaGambar = time() . '_' . $i . '.' . $file->getExtension();
                                $file->move('gambar/galeri/', $namaGambar);

                                $daftarGambar[] = [
                                    'wisataid' => $id,
                                    'gambar' => 'gambar/galeri/' . $namaGambar
                                ];
                            }
                            $i++;
                        }
                        $this->GaleriModel->insertBatch($daftarGambar);
                        $json = [
                            'success' => "Gambar berhasil ditambahkan ke galeri"
                        ];
                    } catch (\Throwable $th) {
                        $json = [
                            'error' => 'Terdapat kesalahan pada sistem'
                        ];
                    }
                } else {
                    $json = [
                        'errors' => [
                            'gambar' => $validation->getError('gambar')
                        ]
                    ];
                }
            } else {
                $json = [
                    'error' => 'Wisata dengan ID ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function hapusGaleri()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->GaleriModel->find($id);
            if ($cekData) {
                try {
                    unlink($cekData['gambar']);
                    $this->GaleriModel->delete($id);
                    $json = [
                        'success' => 'Gambar berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Gambar tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function gambarUtama()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $wisataid = $this->request->getPost('wisataid');

            $cekData = $this->GaleriModel->find($id);
            if ($cekData) {
                try {
                    // $db = \Config\Database::connect();
                    $this->GaleriModel->set('status', 0)
                        ->where('wisataid', $wisataid)->update();

                    $this->GaleriModel->update($id, [
                        'status' => 1
                    ]);

                    $json = [
                        'success' => 'Gambar berhasil dipilih'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Gambar tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function dataTiket()
    {
        if ($this->request->isAJAX()) {
            $wisataid = $this->request->getPost('id');
            $json = [
                'data' => view('admin/wisata/data_tiket', [
                    'tiket' => $this->TiketModel->where('wisataid', $wisataid)->get()->getResultArray()
                ])
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function tambahTiket()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $kategori = $this->request->getPost('kategori');
            $harga = $this->request->getPost('harga');
            $status_tiket = $this->request->getPost('status_tiket');

            try {
                $this->TiketModel->insert([
                    'kategori' => $kategori,
                    'harga' => $harga,
                    'status_tiket' => $status_tiket,
                    'wisataid' => $id,
                ]);
                $json = [
                    'success' => 'Data tiket berhasil ditambahkan'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terjadi kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function editTiket()
    {
        if ($this->request->isAJAX()) {
            $tiketid = $this->request->getPost('tiketid');
            $kategori = $this->request->getPost('kategori');
            $harga = $this->request->getPost('harga');
            $status_tiket = $this->request->getPost('status_tiket');

            try {
                $this->TiketModel->update($tiketid, [
                    'kategori' => $kategori,
                    'harga' => $harga,
                    'status_tiket' => $status_tiket,
                ]);
                $json = [
                    'success' => 'Data tiket berhasil diubah'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terjadi kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function hapusTiket()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $cekData = $this->TiketModel->find($id);
            if ($cekData) {
                try {
                    $this->TiketModel->delete($id);
                    $json = [
                        'success' => 'Data tiket berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Data tiket tidak dapat dihapus'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Data tiket tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            $cekData = $this->WisataModel->find($id);
            if ($cekData) {
                try {
                    $this->WisataModel->delete($id);
                    $json = [
                        'success' => 'Wisata ' . $cekData['nama_wisata'] . ' berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Wisata ' . $cekData['nama_wisata'] . ' tidak dapat dihapus'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Wisata tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function recover($id)
    {
        if ($this->request->isAJAX()) {
            $cekData = $this->WisataModel->onlyDeleted()->find($id);
            if ($cekData) {
                try {
                    $this->WisataModel->update($id, [
                        'deleted_at' => NULL
                    ]);
                    $json = [
                        'success' => 'Data wisata berhasil direcover'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Data wisata tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }

    public function forceDelete($id)
    {
        if ($this->request->isAJAX()) {
            $cekData = $this->WisataModel->onlyDeleted()->find($id);
            if ($cekData) {
                try {
                    // hapus tiket
                    $this->TiketModel->where('wisataid', $id)->delete();

                    // hapus galeri
                    $dataGaleri = $this->GaleriModel->where('wisataid', $id)->get()->getResultArray();
                    foreach ($dataGaleri as $g) {
                        unlink($g['gambar']);
                        $this->GaleriModel->delete($g['id']);
                    }

                    // hapus wisata
                    $this->WisataModel->delete($id, true);

                    $json = [
                        'success' => 'Wisata berhasil terhapus secara permanent'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Data wisata tidak dapat dihapus'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Wisata dengan ID ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diperoses');
        }
    }
}
