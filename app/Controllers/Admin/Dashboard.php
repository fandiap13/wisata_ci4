<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DetailTransaksiOnlineModel;
use App\Models\TransaksiOfflineModel;
use App\Models\TransaksiOnlineModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $TransaksiOnline;
    protected $TransaksiOffline;
    protected $DetailTransaksi;
    protected $UserModel;

    public function __construct()
    {
        $this->TransaksiOnline = new TransaksiOnlineModel();
        $this->TransaksiOffline = new TransaksiOfflineModel();
        $this->DetailTransaksi = new DetailTransaksiOnlineModel();
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        $dataUser = $this->UserModel->find(session('LoggedUserData')['id']);

        $jmlOrder = $this->TransaksiOnline->where('status', 2)->get()->getNumRows();

        // total transaksi online
        $totalOnline = 0;
        $dataTransonline = $this->TransaksiOnline->where('status', 3)->get()->getResultArray();
        foreach ($dataTransonline as $t) {
            $totalOnline += $this->DetailTransaksi->total_bayar($t['id']);
        }

        // total transaksi offline
        $totalOffline = 0;
        $dataTransOffline = $this->TransaksiOffline->findAll();
        foreach ($dataTransOffline as $d) {
            $totalOffline += $d['totalbayar'];
        }

        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'jmlOrder' => $jmlOrder,
            'totalOnline' => $totalOnline,
            'totalOffline' => $totalOffline,
            'user' => $dataUser,
        ]);
    }
}
