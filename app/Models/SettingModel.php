<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'setting';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'nama_web', 'deskripsi_web', 'motto', 'gambar_carousel', 'cinematic_link', 'caption_carousel_1', 'caption_carousel_2', 'gambar', 'about_us', 'email', 'no_wa', 'rekening', 'favicon', 'instagram', 'pembelian_tiket'
    ];
}
