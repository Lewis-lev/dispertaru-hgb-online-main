<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeladmin extends Model
{
    protected $table            = 'admin';
    protected $primaryKey       = 'adminid';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'password', 'is_active', 'last_seen_at'
    ];
}
