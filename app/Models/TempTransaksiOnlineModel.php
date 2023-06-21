<?php

namespace App\Models;

use CodeIgniter\Model;

class TempTransaksiOnlineModel extends Model
{
    protected $table            = 'temp_transaksi_online';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'pengunjungid', 'wisataid', 'tiketid', 'tgl_kunjungan', 'jml'
    ];

    public function cariKeranjang($id)
    {
        return $this->table('temp_transaksi_online')
            ->select('temp_transaksi_online.*, t.harga, t.kategori, w.nama_wisata, t.status_tiket')
            ->join('tiket as t', 'temp_transaksi_online.tiketid=t.id')
            ->join('wisata as w', 't.wisataid=w.id')
            ->where('temp_transaksi_online.pengunjungid', $id)->get();
    }
}
