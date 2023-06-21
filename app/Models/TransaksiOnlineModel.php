<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiOnlineModel extends Model
{
    protected $table            = 'transaksi_online';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'tgl_transaksi', 'tgl_kunjungan', 'email', 'telp', 'pengunjungid', 'status', 'buktipembayaran', 'status', 'tgl_bayar'
    ];

    public function IDTransaksi($tanggalSekarang)
    {
        $cek = $this->table('transaksi_online')->select('max(id) as id')
            ->where("DATE_FORMAT(tgl_transaksi, '%Y-%m-%d')", $tanggalSekarang)
            ->get()->getNumRows();
        if ($cek == 0) {
            $ID = date('dmy', strtotime($tanggalSekarang)) . '0001';
        } else {
            $hasil = $this->table('transaksi_online')->select('max(id) as id')
                ->where("DATE_FORMAT(tgl_transaksi, '%Y-%m-%d')", $tanggalSekarang)
                ->get()->getRowArray();
            $data = $hasil['id'];
            $lastNoUrut = substr($data, -4);
            $nextNoUrut = intval($lastNoUrut) + 1;
            $ID = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        }
        return $ID;
    }
}
