<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DetailTransaksiOfflineModel;
use App\Models\TempTransaksiOfflineModel;
use App\Models\TiketModel;
use App\Models\TransaksiOfflineModel;
use App\Models\WisataModel;
use \Hermawan\DataTables\DataTable;

class TransaksiOffline extends BaseController
{
    protected $TransaksiOfflineModel;
    protected $DetailTransaksiOfflineModel;
    protected $TempTransaksiOfflineModel;
    protected $WisataModel;
    protected $TiketModel;

    public function __construct()
    {
        $this->TransaksiOfflineModel = new TransaksiOfflineModel();
        $this->DetailTransaksiOfflineModel = new DetailTransaksiOfflineModel();
        $this->TempTransaksiOfflineModel = new TempTransaksiOfflineModel();
        $this->TiketModel = new TiketModel();
        $this->WisataModel = new WisataModel();
    }

    public function index()
    {
        // ->where("DATE_FORMAT(created_at, '%Y-%m-%d') >=", "2022-11-27")
        //     ->where("DATE_FORMAT(created_at, '%Y-%m-%d') <=", "2022-11-27")->get()->getResultArray();
        return view('admin/transaksioffline/v_index', [
            'title' => 'Kasir',
        ]);
    }

    public function datatransaksi()
    {
        if ($this->request->isAJAX()) {
            // $builder = $this->TransaksiOfflineModel->query("SELECT u.id, u.created_at, u.totalbayar, kasir.nama_user nama_kasir, pembeli.nama_user nama_pembeli FROM transaksi_offline u LEFT JOIN users kasir on u.kasirid=kasir.id LEFT JOIN users pembeli on u.pengunjungid=pembeli.id");
            $builder = $this->TransaksiOfflineModel
                ->select("transaksi_offline.id, transaksi_offline.created_at, transaksi_offline.totalbayar, kasir.nama_user nama_kasir, pembeli.nama_user as nama_pembeli")
                ->join("users as kasir", "transaksi_offline.kasirid=kasir.id", "left")
                ->join("users as pembeli", "transaksi_offline.pengunjungid=pembeli.id", "left");

            return DataTable::of($builder)
                ->add('created_at', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->created_at));
                })
                ->add('totalbayar', function ($row) {
                    return "<div class='text-right'>" . number_format($row->totalbayar, 0, ",", '.') . "</div>";
                })
                ->add('aksi', function ($row) {
                    return
                        "
                        <div class='text-center'>
                        <div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-primary' title='Cetak Transaksi' onclick='cetak(\"$row->id\");'><i class='fas fa-print'></i></button>

                        <button type='button' class='btn btn-sm btn-danger' title='Hapus Data' onclick='hapus(\"$row->id\");'><i class='fa fa-trash-alt'></i></button>
                        </div>
                        </div>
                    ";
                })
                ->addNumbering('nomor')
                ->filter(function ($builder, $request) {
                    if ($request->tglawal && $request->tglakhir) {
                        $builder
                            ->where("DATE_FORMAT(transaksi_offline.created_at, '%Y-%m-%d') >=", "$request->tglawal")
                            ->where("DATE_FORMAT(transaksi_offline.created_at, '%Y-%m-%d') <=", "$request->tglakhir");
                    }
                })
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            $cekData = $this->TransaksiOfflineModel->find($id);
            if ($cekData) {
                try {
                    $this->DetailTransaksiOfflineModel->where('transofflineid', $id)->delete();
                    $this->TransaksiOfflineModel->delete($id);
                    $json = [
                        'success' => 'Transaksi dengan ID ' . $id . ' berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Transaksi dengan ID ' . $id . ' tidak terdapat di sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function tambah_transaksi()
    {
        $wisata = $this->WisataModel->findAll();
        return view('admin/transaksioffline/v_tambah_transaksi', [
            'title' => 'Kasir Transaksi',
            'wisata' => $wisata,
            'transofflineid' => $this->TransaksiOfflineModel->IDTransaksi(date('Y-m-d'))
        ]);
    }

    public function cekID($tanggal)
    {
        if ($this->request->isAJAX()) {
            $json = [
                'transofflineid' => $this->TransaksiOfflineModel->IDTransaksi($tanggal)
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function tampilDataTemp()
    {
        if ($this->request->isAJAX()) {
            $transofflineid = $this->request->getPost('transofflineid');
            $json = [
                'data' => view('admin/transaksioffline/dataTemp', [
                    'detail' => $this->TempTransaksiOfflineModel
                        ->select('temp_transaksi_offline.*, kategori, nama_wisata')
                        ->where('transofflineid', $transofflineid)
                        ->join('tiket', 'temp_transaksi_offline.tiketid=tiket.id')
                        ->join('wisata', 'temp_transaksi_offline.wisataid=wisata.id')
                        ->get()->getResultArray()
                ]),
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function tempatWisata()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->TiketModel->where('wisataid', $id)->where('status_tiket', 1)->get()->getResultArray();
            $json = [
                'kategori_tiket' => $cekData ? view('admin/transaksioffline/kategori_wisata', [
                    'kategori' => $cekData
                ]) : ""
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function kategori_tiket()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->TiketModel->find($id);
            $json = [
                'hargatiket' => isset($cekData['harga']) ? $cekData['harga'] : ""
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function simpanItem()
    {
        if ($this->request->isAJAX()) {
            $transofflineid = $this->request->getPost('transofflineid');
            $wisataid = $this->request->getPost('wisataid');
            $kategoriid = $this->request->getPost('kategoriid');
            $hargatiket = $this->request->getPost('hargatiket');
            $jml = $this->request->getPost('jml');

            try {
                $this->TempTransaksiOfflineModel->insert([
                    'transofflineid' => $transofflineid,
                    'wisataid' => $wisataid,
                    'tiketid' => $kategoriid,
                    'hargatiket' => $hargatiket,
                    'jml' => $jml,
                ]);
                $json = [
                    'success' => 'Item berhasil disimpan'
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terdapat kesalahan pada sistem'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function hapusItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekData = $this->TempTransaksiOfflineModel->find($id);
            if ($cekData) {
                try {
                    $this->TempTransaksiOfflineModel->delete($id);
                    $json = [
                        'success' => 'Item berhasil dihapus'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Item tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function modalpembayaran()
    {
        if ($this->request->isAJAX()) {
            $transofflineid = $this->request->getPost('transofflineid');
            $created_at = $this->request->getPost('created_at');
            $pengunjungid = $this->request->getPost('pengunjungid');
            $cekData = $this->TempTransaksiOfflineModel->where('transofflineid', $transofflineid)->get()->getNumRows();
            if ($cekData > 0) {
                $totalbayar = $this->TempTransaksiOfflineModel->totalBayar($transofflineid);
                $json = [
                    'data' => view('admin/transaksioffline/modalpembayaran', [
                        'transofflineid' => $transofflineid,
                        'created_at' => $created_at,
                        'pengunjungid' => $pengunjungid,
                        'totalbayar' => $totalbayar
                    ])
                ];
            } else {
                $json = [
                    'error' => 'Anda belum menambahkan item ke keranjang'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function simpanTransaksi()
    {
        if ($this->request->isAJAX()) {
            $transofflineid = $this->request->getPost('idtransaksi');
            $dibayar = $this->request->getPost('dibayar');
            $kembalian = $this->request->getPost('kembalian');
            $totalbayar = $this->request->getPost('totalbayar');
            $pengunjungid = $this->request->getPost('pengunjungid');
            $tgltransaksi = $this->request->getPost('tgltransaksi');

            try {
                $arrDetail = [];
                $detailTransaksi = $this->TempTransaksiOfflineModel->where('transofflineid', $transofflineid)->get();
                foreach ($detailTransaksi->getResultArray() as $key => $value) {
                    $arrDetail[] = [
                        'transofflineid' => $value['transofflineid'],
                        'wisataid' => $value['wisataid'],
                        'tiketid' => $value['tiketid'],
                        'hargatiket' => $value['hargatiket'],
                        'jml' => $value['jml'],
                    ];
                }

                // simpan transaksi
                $this->TransaksiOfflineModel->insert([
                    'id' => $transofflineid,
                    'pengunjungid' => $pengunjungid,
                    'kasirid' => session('LoggedUserData')['id'],
                    'totalbayar' => $totalbayar,
                    'dibayar' => $dibayar,
                    'kembalian' => $kembalian,
                    'created_at' => $tgltransaksi . " " . date("H:i:s"),
                    'updated_at' => $tgltransaksi,
                ]);
                $this->DetailTransaksiOfflineModel->insertBatch($arrDetail);

                // kosongkan transaksi
                $this->TempTransaksiOfflineModel->where('transofflineid', $transofflineid)->delete();

                $json = [
                    'success' => 'Transaksi dengan ID ' . $transofflineid . ' berhasil tersimpan',
                    'cetaktransaksi' => site_url('/admin/TransaksiOffline/cetak-transaksi/' . $transofflineid)
                ];
            } catch (\Throwable $th) {
                $json = [
                    'error' => 'Terjadi kesalahan pada sistem'
                ];
            }

            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function cetak_transaksi($id)
    {
        $transaksi = $this->TransaksiOfflineModel
            ->select("transaksi_offline.*, kasir.nama_user nama_kasir, pembeli.nama_user as nama_pembeli, pembeli.telp_user, pembeli.email_user")
            ->join("users as kasir", "transaksi_offline.kasirid=kasir.id", "left")
            ->join("users as pembeli", "transaksi_offline.pengunjungid=pembeli.id", "left")
            ->where('transaksi_offline.id', $id)->get()->getRowArray();

        $detail = $this->DetailTransaksiOfflineModel
            ->select('detail_transaksi_offline.*, t.kategori, w.nama_wisata')
            ->join('tiket as t', 'detail_transaksi_offline.tiketid=t.id')
            ->join('wisata as w', 't.wisataid=w.id')
            ->where('transofflineid', $id)->get()->getResultArray();

        return view('admin/transaksioffline/cetak_transaksi', [
            'transaksi' => $transaksi,
            'detail' => $detail,
        ]);
    }
}
