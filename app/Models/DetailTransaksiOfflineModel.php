<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiOfflineModel extends Model
{
    protected $table            = 'detail_transaksi_offline';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'transofflineid', 'tiketid', 'hargatiket', 'jml', 'wisataid'
    ];
}
