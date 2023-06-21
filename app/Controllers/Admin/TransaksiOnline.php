<?php

namespace App\Controllers\Admin;

use \Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use App\Models\DetailTransaksiOnlineModel;
use App\Models\TransaksiOnlineModel;
use DateTime;

class TransaksiOnline extends BaseController
{
    protected $TransaksiOnlineModel;
    protected $DetailTransaksiOnlineModel;

    public function __construct()
    {
        $this->TransaksiOnlineModel = new TransaksiOnlineModel();
        $this->DetailTransaksiOnlineModel = new DetailTransaksiOnlineModel();
    }

    public function index()
    {
        return view('admin/TransaksiOnline/v_index', [
            'title' => 'Transaksi Online',
        ]);
    }

    public function datatransaksi()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->TransaksiOnlineModel
                ->select('transaksi_online.id, tgl_transaksi, tgl_kunjungan, u.nama_user, transaksi_online.status, tgl_bayar')
                ->join('users as u', 'transaksi_online.pengunjungid=u.id')
                ->where('transaksi_online.status !=', 1)
                ->orderBy('transaksi_online.tgl_bayar', 'ASC');

            return DataTable::of($builder)
                ->add('tgl_transaksi', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->tgl_transaksi));
                })
                ->add('tgl_bayar', function ($row) {
                    return date('d M Y H:i:s', strtotime($row->tgl_bayar));
                })
                ->add('tgl_kunjungan', function ($row) {
                    return date('d M Y', strtotime($row->tgl_kunjungan));
                })
                ->add('totalbayar', function ($row) {

                    $totalbayar = $this->DetailTransaksiOnlineModel->total_bayar($row->id);

                    return "<div class='text-right'>" . number_format($totalbayar, 0, ",", '.') . "</div>";
                })
                ->add('status', function ($row) {
                    if ($row->status == 1) {
                        $status = '<span class="badge badge-warning">Belum Bayar</span>';
                    } elseif ($row->status == 2) {
                        $status = '<span class="badge badge-info">Proses</span>';
                    } elseif ($row->status == 3) {
                        $status = '<span class="badge badge-success">Berhasil</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Gagal</span>';
                    }
                    return $status;
                })
                ->add('aksi', function ($row) {
                    return
                        "
                        <div class='text-center'>
                        <div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-success' title='Cetak Transaksi' onclick='cetak(\"$row->id\");'><i class='fas fa-print'></i></button>

                        <button type='button' class='btn btn-sm btn-primary' title='Detail Transaksi' onclick='detail(\"$row->id\");'><i class='fas fa-eye'></i></button>

                        <button type='button' class='btn btn-sm btn-info' title='Pembayaran' onclick='pembayaran(\"$row->id\");'><i class='fab fa-paypal'></i></button>
                        </div>
                        </div>
                    ";
                })
                ->addNumbering('nomor')
                ->filter(function ($builder, $request) {
                    if ($request->tglawal && $request->tglakhir) {
                        $builder
                            ->where("DATE_FORMAT(transaksi_online.tgl_transaksi, '%Y-%m-%d') >=", "$request->tglawal")
                            ->where("DATE_FORMAT(transaksi_online.tgl_transaksi, '%Y-%m-%d') <=", "$request->tglakhir");
                    }
                    if ($request->status) {
                        $builder->where("status", $request->status);
                    }
                    if ($request->tglawal && $request->tglakhir && $request->status) {
                        $builder
                            ->where("DATE_FORMAT(transaksi_online.tgl_transaksi, '%Y-%m-%d') >=", "$request->tglawal")
                            ->where("DATE_FORMAT(transaksi_online.tgl_transaksi, '%Y-%m-%d') <=", "$request->tglakhir")
                            ->where("status", $request->status);
                    }
                })
                ->toJson(true);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function pembayaran()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $transaksi = $this->TransaksiOnlineModel->find($id);
            if ($transaksi) {
                $json = [
                    'data' => view('admin/TransaksiOnline/modalpembayaran', [
                        'transaksi' => $transaksi
                    ])
                ];
            } else {
                $json = [
                    'error' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function simpanTransaksi()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $status = $this->request->getPost('status');
            $cekTransaksi = $this->TransaksiOnlineModel->find($id);
            if ($cekTransaksi) {
                try {
                    $this->TransaksiOnlineModel->update($id, [
                        'status' => $status
                    ]);
                    $json = [
                        'success' => 'Transaksi dengan ID ' . $id . ' berhasil diubah'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Transaksi dengan ID ' . $id . ' tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function detail_transaksi($id)
    {
        $cekTransaksi = $this->TransaksiOnlineModel
            ->select('transaksi_online.*, users.nama_user')
            ->join('users', 'transaksi_online.pengunjungid=users.id')
            ->where('transaksi_online.id', $id)->get()->getRowArray();
        if ($cekTransaksi) {
            $detailTransaksi = $this->DetailTransaksiOnlineModel
                ->select('detail_transaksi_online.*, w.nama_wisata, t.kategori')
                ->join('tiket as t', 'detail_transaksi_online.tiketid=t.id')
                ->join('wisata as w', 't.wisataid=w.id')
                ->where('transonlineid', $id)->get()->getResultArray();
            return view('admin/TransaksiOnline/v_detail', [
                'title' => 'Detail Transaksi',
                'transaksi' => $cekTransaksi,
                'detail' => $detailTransaksi
            ]);
        } else {
            return redirect()->to(site_url('/admin/TransaksiOnline/index'));
        }
    }

    public function getNotif()
    {
        if ($this->request->isAJAX()) {
            if (!empty(session('LoggedUserData'))) {
                $transaksi = $this->TransaksiOnlineModel->where('status', 2);
                $cekTransaksi = $transaksi->get()->getNumRows();
                if ($cekTransaksi) {
                    $transaksiNew = $transaksi->limit(1)->orderBy('tgl_bayar', 'DESC')->get()->getRowArray()['tgl_bayar'];
                    $tgl1 = new DateTime($transaksiNew);
                    $tgl2 = new DateTime(date('Y-m-d H:i:s'));
                    $jarak = $tgl2->diff($tgl1);

                    if ($jarak->d && $jarak->h) {
                        $time = $jarak->d . ' hari ' . $jarak->h . ' jam ' . $jarak->i . ' menit yang lalu';
                    } elseif ($jarak->h) {
                        $time =  $jarak->h . ' jam ' . $jarak->i . ' menit yang lalu';
                    } else {
                        $time = $jarak->i . ' menit yang lalu';
                    }
                    // echo $jarak->d;
                    // echo $jarak->h;
                    // echo $jarak->i;
                    $json = [
                        'data' => view('template/notif', [
                            'jmlNotif' => $cekTransaksi,
                            'time' => $time
                        ])
                    ];
                } else {
                    $json = [
                        'error' => "Gak ada notif"
                    ];
                }
            } else {
                $json = [
                    'error' => "User sudah logout"
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function cetak_transaksi($id)
    {
        $transaksi = $this->TransaksiOnlineModel->select('transaksi_online.*, u.nama_user')
            ->join('users as u', 'transaksi_online.pengunjungid=u.id')
            ->where('transaksi_online.id', $id)
            ->get()->getRowArray();
        $detail = $this->DetailTransaksiOnlineModel
            ->select('detail_transaksi_online.*, t.kategori, w.nama_wisata')
            ->join('tiket as t', 'detail_transaksi_online.tiketid=t.id')
            ->join('wisata as w', 't.wisataid=w.id')
            ->where('transonlineid', $id)
            ->get()->getResultArray();
        // $totalBayar = $this->DetailTransaksiOnlineModel->total_bayar($id);

        return view('admin/TransaksiOnline/cetak_transaksi', [
            'transaksi' => $transaksi,
            'detail' => $detail,
        ]);
    }
}
