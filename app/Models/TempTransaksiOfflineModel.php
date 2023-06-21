<?php

namespace App\Models;

use CodeIgniter\Model;

class TempTransaksiOfflineModel extends Model
{
    protected $table            = 'temp_transaksi_offline';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'transofflineid', 'tiketid', 'hargatiket', 'jml', 'wisataid'
    ];

    public function totalBayar($transofflineid)
    {
        $totalbayar = 0;
        $data = $this->table('temp_transaksi_offline')
            ->where('transofflineid', $transofflineid)
            ->get()->getResultArray();
        foreach ($data as $d) {
            $totalbayar += $d['hargatiket'] * $d['jml'];
        }
        return $totalbayar;
    }
}
