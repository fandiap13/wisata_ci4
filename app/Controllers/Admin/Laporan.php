<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiOfflineModel;
use App\Models\TransaksiOnlineModel;

class Laporan extends BaseController
{
    protected $TransaksiOnlineModel;
    protected $TransaksiOfflineModel;
    public function __construct()
    {
        $this->TransaksiOnlineModel = new TransaksiOnlineModel();
        $this->TransaksiOfflineModel = new TransaksiOfflineModel();
    }

    public function index()
    {
        return view('admin/laporan/v_index', [
            'title' => 'Laporan'
        ]);
    }

    public function transaksi_online()
    {
        return view('admin/laporan/v_transonline', [
            'title' => 'Laporan Transaksi Online'
        ]);
    }

    public function transaksi_offline()
    {
        return view('admin/laporan/v_transoffline', [
            'title' => 'Laporan Transaksi Offline'
        ]);
    }

    public function grafikTransaksiOnline()
    {
        $bulan = $this->request->getPost('bulan');
        $db = \Config\Database::connect();
        $query = $db->query("SELECT tgl_transaksi AS tgl, id FROM transaksi_online WHERE `status`='3' AND DATE_FORMAT(tgl_transaksi, '%Y-%m') = '$bulan' ORDER BY tgl_transaksi ASC")->getResultArray();

        $data = [
            'grafik' => $query
        ];

        $json = [
            'data' => view('admin/laporan/grafik_transonline', $data)
        ];

        echo json_encode($json);
    }

    public function grafikTransaksiOffline()
    {
        $bulan = $this->request->getPost('bulan');
        $db = \Config\Database::connect();
        $query = $db->query("SELECT created_at AS tgl, id, totalbayar FROM transaksi_offline WHERE DATE_FORMAT(created_at, '%Y-%m') = '$bulan' ORDER BY created_at ASC")->getResultArray();

        $data = [
            'grafik' => $query
        ];

        $json = [
            'data' => view('admin/laporan/grafik_transoffline', $data)
        ];

        echo json_encode($json);
    }

    public function laporan_transaksi_online()
    {
        $tglawal = $this->request->getGet('tglawal');
        $tglakhir = $this->request->getGet('tglakhir');

        $transaksi = $this->TransaksiOnlineModel
            ->select('transaksi_online.*, users.nama_user')
            ->join('users', 'transaksi_online.pengunjungid=users.id')
            ->where('DATE_FORMAT(tgl_transaksi, "%Y-%m-%d") >=', $tglawal)
            ->where('DATE_FORMAT(tgl_transaksi, "%Y-%m-%d") <=', $tglakhir)
            ->where('status', 3)
            ->get()->getResultArray();

        return view('admin/laporan/v_lap_transonline', [
            'transaksi' => $transaksi
        ]);
    }

    public function laporan_transaksi_offline()
    {
        $tglawal = $this->request->getGet('tglawal');
        $tglakhir = $this->request->getGet('tglakhir');

        $transaksi = $this->TransaksiOfflineModel
            ->select("transaksi_offline.*, kasir.nama_user as nama_kasir, pembeli.nama_user as nama_pembeli")
            ->join("users as kasir", "transaksi_offline.kasirid=kasir.id", "left")
            ->join("users as pembeli", "transaksi_offline.pengunjungid=pembeli.id", "left")
            ->where('DATE_FORMAT(transaksi_offline.created_at, "%Y-%m-%d") >=', $tglawal)
            ->where('DATE_FORMAT(transaksi_offline.created_at, "%Y-%m-%d") <=', $tglakhir)
            ->get()->getResultArray();

        // dd($transaksi);

        return view('admin/laporan/v_lap_transoffline', [
            'transaksi' => $transaksi
        ]);
    }
}
