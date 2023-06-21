<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailTransaksiOnlineModel;
use App\Models\SettingModel;
use App\Models\TempTransaksiOnlineModel;
use App\Models\TransaksiOnlineModel;
use App\Models\UserModel;

class Transaksi extends BaseController
{
    protected $TempTransaksiModel;
    protected $Transaksi;
    protected $DetailTransaksi;
    protected $UserModel;
    protected $SettingModel;

    public function __construct()
    {
        $this->TempTransaksiModel = new TempTransaksiOnlineModel();
        $this->DetailTransaksi = new DetailTransaksiOnlineModel();
        $this->Transaksi = new TransaksiOnlineModel();
        $this->UserModel = new UserModel();
        $this->SettingModel = new SettingModel();
    }

    public function pesan_tiket()
    {
        if ($this->request->isAJAX()) {
            $wisataid = $this->request->getPost('wisataid');
            $tiketid = $this->request->getPost('tiketid');
            $jml = $this->request->getPost('jml');

            try {
                $this->TempTransaksiModel->insert([
                    'wisataid' => $wisataid,
                    'tiketid' => $tiketid,
                    'jml' => $jml,
                    'pengunjungid' => session('LoggedUserData')['id']
                ]);
                $json = [
                    'success' => 'Pesanan sudah dimasukkan kedalam keranjang'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function keranjang()
    {
        $cekData = $this->TempTransaksiModel->cariKeranjang(session('LoggedUserData')['id'])->getResultArray();
        if ($cekData) {
            $data = [
                'keranjang' => $cekData,
                'user' => $this->UserModel->find(session('LoggedUserData')['id']),
                'title' => 'Keranjang'
            ];
            return view('pengunjung/v_keranjang', $data);
        } else {
            session()->setFlashData("msg", 'error#Keranjang Kosong');
            return redirect()->to(site_url('/'));
        }
    }

    public function hapusTemp($id)
    {
        if ($this->request->isAJAX()) {
            try {
                $this->TempTransaksiModel->delete($id);
                $json = [
                    'success' => 'Item berhasil dihapus'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function checkout()
    {
        if ($this->request->isAJAX()) {
            $tgl_kunjungan = $this->request->getPost('tgl_kunjungan');
            $email = $this->request->getPost('email');
            $telp = $this->request->getPost('telp');
            $pengunjungid = session('LoggedUserData')['id'];
            $tgl_transaksi = date('Y-m-d H:i:s');
            $transonlineid = $this->Transaksi->IDTransaksi(date('Y-m-d'));

            $cekKeranjang = $this->TempTransaksiModel->where('pengunjungid', $pengunjungid)->get();
            $cekKeranjangValid = $this->TempTransaksiModel
                ->select('t.harga, temp_transaksi_online.*')
                ->join('tiket as t', 'temp_transaksi_online.tiketid=t.id')
                ->where('t.status_tiket', 1)
                ->where('temp_transaksi_online.pengunjungid', $pengunjungid)->get();

            if ($cekKeranjang->getResultArray()) {
                $jmlKeranjangAwal = $cekKeranjang->getNumRows();
                $jmlkeranjangValid = $cekKeranjangValid->getNumRows();

                if ($jmlKeranjangAwal !== $jmlkeranjangValid) {
                    $json = [
                        'error' => 'Tiket ada yang habis !'
                    ];
                    exit(json_encode($json));
                }

                $arrKeranjang = [];
                foreach ($cekKeranjangValid->getResultArray() as $key => $value) {
                    $arrKeranjang[] = [
                        'transonlineid' => $transonlineid,
                        'pengunjungid' => $pengunjungid,
                        'wisataid' => $value['wisataid'],
                        'tiketid' => $value['tiketid'],
                        'harga_tiket' => $value['harga'],
                        'jml' => $value['jml'],
                    ];
                }

                try {
                    $this->Transaksi->insert([
                        'id' => $transonlineid,
                        'tgl_transaksi' => $tgl_transaksi,
                        'tgl_kunjungan' => $tgl_kunjungan,
                        'email' => $email,
                        'telp' => $telp,
                        'pengunjungid' => $pengunjungid,
                        'status' => 1
                    ]);
                    $this->DetailTransaksi->insertBatch($arrKeranjang);

                    $this->TempTransaksiModel->where('pengunjungid', $pengunjungid)->delete();

                    $json = [
                        'success' => 'Transaksi dengan ID ' . $transonlineid . ' berhasil disimpan'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Keranjang masih kosong !'
                ];
            }

            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function daftar_transaksi()
    {
        $cekData = $this->Transaksi->where('pengunjungid', session('LoggedUserData')['id'])->orderBy('tgl_transaksi', 'DESC')->get()->getResultArray();
        if ($cekData) {
            $data = [
                'transaksi' => $cekData,
                'title' => 'Daftar Transaksi'
            ];
            return view('pengunjung/v_daftartransaksi', $data);
        } else {
            session()->setFlashData("msg", 'error#Transaksi Kosong');
            return redirect()->to(site_url('/'));
        }
    }

    public function modalpembayaran()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $transaksi = $this->Transaksi->find($id);
            if ($transaksi) {
                $norek = $this->SettingModel->select('rekening')->limit(1)->orderBy('id', 'ASC')->get()->getRowArray()['rekening'];
                $totalbayar = $this->DetailTransaksi->total_bayar($id);
                $json = [
                    'data' => view('pengunjung/modalpembayaran', [
                        'transaksi' => $transaksi,
                        'totalbayar' => $totalbayar,
                        'norek' => $norek
                    ])
                ];
            } else {
                $json = [
                    'error' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan !'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function pembayaran()
    {
        if ($this->request->isAJAX()) {
            $transonlineid = $this->request->getPost('transonlineid');
            $buktipembayaran_lama = $this->request->getPost('buktipembayaran_lama');
            $buktipembayaran = $this->request->getFile('buktipembayaran');

            $cekTransaksi = $this->Transaksi->whereIn('status', ['1', '2'])
                ->where('id', $transonlineid)->get()->getRowArray();

            if ($cekTransaksi) {
                $validation = \Config\Services::validation();

                $valid = $this->validate([
                    'buktipembayaran' => [
                        'label' => "Gambar",
                        'rules' => 'uploaded[buktipembayaran]|max_size[buktipembayaran,2048]|is_image[buktipembayaran]|mime_in[buktipembayaran,image/png,image/jpg,image/jpeg]',
                        'errors' => [
                            'uploaded' => '{field} tidak boleh kosong',
                            'max_size' => '{field} tidak boleh lebih dari 2 mb',
                            'is_image' => 'Yg anda input bukan gambar',
                            'mime_in' => '{field} harus jpg/jpeg/png',
                        ]
                    ],
                ]);

                if ($valid) {
                    try {
                        // menghapus gambar lama
                        if ($buktipembayaran_lama !== NULL && $buktipembayaran_lama != "") {
                            unlink($buktipembayaran_lama);
                        }

                        $randomName = $buktipembayaran->getRandomName();
                        $buktipembayaran->move('gambar/bukti/', $randomName);
                        $namaGambar = 'gambar/bukti/' . $randomName;

                        $this->Transaksi->update($transonlineid, [
                            'buktipembayaran' => $namaGambar,
                            'status' => 2,
                            'tgl_bayar' => date('Y-m-d H:i:s')
                        ]);

                        $json = [
                            'success' => 'Bukti pembayaran berhasil diupload !'
                        ];
                    } catch (\Throwable $th) {
                        $json = [
                            'error' => 'Terdapat kesalahan pada sistem'
                        ];
                    }
                } else {
                    $json = [
                        'errors' => [
                            'buktipembayaran' => $validation->getError('buktipembayaran')
                        ]
                    ];
                }
            } else {
                $json = [
                    'error' => 'Transaksi dengan ID ' . $transonlineid . ' tidak ditemukan !'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function hapusTransaksi($id)
    {
        if ($this->request->isAJAX()) {
            $cekTransaksi = $this->Transaksi->whereIn('status', ['1', '2', '4'])
                ->where('id', $id)->get()->getRowArray();
            if ($cekTransaksi) {
                if ($cekTransaksi['buktipembayaran'] !== NULL && $cekTransaksi['buktipembayaran'] != "") {
                    unlink($cekTransaksi['buktipembayaran']);
                }

                $this->DetailTransaksi->where('transonlineid', $id)->delete();
                $this->Transaksi->delete($id);

                $json = [
                    'success' => 'Transaksi dengan ID ' . $id . ' berhasil dihapus'
                ];
            } else {
                $json = [
                    'error' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan !'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function detail_transaksi($id)
    {
        $cekTransaksi = $this->Transaksi
            ->select('transaksi_online.*, users.nama_user')
            ->join('users', 'transaksi_online.pengunjungid=users.id')
            ->where('transaksi_online.pengunjungid', session('LoggedUserData')['id'])
            ->where('transaksi_online.id', $id)->get()->getRowArray();
        if ($cekTransaksi) {
            $detailTransaksi = $this->DetailTransaksi
                ->select('detail_transaksi_online.*, w.nama_wisata, t.kategori')
                ->join('tiket as t', 'detail_transaksi_online.tiketid=t.id')
                ->join('wisata as w', 't.wisataid=w.id')
                ->where('transonlineid', $id)->get()->getResultArray();
            return view('pengunjung/v_detailtransaksi', [
                'title' => 'Detail Transaksi',
                'transaksi' => $cekTransaksi,
                'detail' => $detailTransaksi
            ]);
        } else {
            return redirect()->to(site_url('/transaksi/daftar-transaksi'));
        }
    }

    public function cetak_transaksi($id)
    {
        $cekTransaksi = $this->Transaksi
            ->join('users as u', 'transaksi_online.pengunjungid=u.id')
            ->where('transaksi_online.id', $id)->where('pengunjungid', session('LoggedUserData')['id'])
            ->get()->getRowArray();

        if ($cekTransaksi) {
            $transaksi = $this->Transaksi->select('transaksi_online.*, u.nama_user')
                ->join('users as u', 'transaksi_online.pengunjungid=u.id')
                ->where('transaksi_online.id', $id)
                ->get()->getRowArray();
            $detail = $this->DetailTransaksi
                ->select('detail_transaksi_online.*, t.kategori, w.nama_wisata')
                ->join('tiket as t', 'detail_transaksi_online.tiketid=t.id')
                ->join('wisata as w', 't.wisataid=w.id')
                ->where('transonlineid', $id)
                ->get()->getResultArray();

            return view('pengunjung/cetak_transaksi', [
                'transaksi' => $transaksi,
                'detail' => $detail,
            ]);
        } else {
            return redirect()->to(site_url('/'));
        }
    }
}
