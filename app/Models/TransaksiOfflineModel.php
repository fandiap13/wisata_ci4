<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiOfflineModel extends Model
{
    protected $table            = 'transaksi_offline';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'kasirid', 'pengunjungid', 'totalbayar', 'dibayar', 'kembalian', 'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function IDTransaksi($tanggalSekarang)
    {
        $cek = $this->table('transaksi_offline')->select('max(id) as id')
            ->where("DATE_FORMAT(created_at, '%Y-%m-%d')", $tanggalSekarang)
            ->get()->getNumRows();
        if ($cek == 0) {
            $ID = date('dmy', strtotime($tanggalSekarang)) . '0001';
        } else {
            $hasil = $this->table('transaksi_offline')->select('max(id) as id')
                ->where("DATE_FORMAT(created_at, '%Y-%m-%d')", $tanggalSekarang)
                ->get()->getRowArray();
            $data = $hasil['id'];
            $lastNoUrut = substr($data, -4);
            $nextNoUrut = intval($lastNoUrut) + 1;
            $ID = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        }
        return $ID;
    }
}
