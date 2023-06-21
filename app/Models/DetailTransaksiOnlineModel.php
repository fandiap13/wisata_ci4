<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiOnlineModel extends Model
{
    protected $table            = 'detail_transaksi_online';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'transonlineid', 'wisataid', 'tiketid', 'harga_tiket', 'jml', 'pengunjungid'
    ];

    public function total_bayar($ID)
    {
        $total = 0;
        $data = $this->table('detail_transaksi_online')
            ->where('transonlineid', $ID)->get()->getResultArray();
        foreach ($data as $key => $value) {
            $total += $value['jml'] * $value['harga_tiket'];
        }
        return $total;
    }
}
