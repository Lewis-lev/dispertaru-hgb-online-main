<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelverifikasi extends Model
{
    protected $table            = 'verifikasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pemohon_id', 'adminid', 'tanggal', 'status'
    ];
}
