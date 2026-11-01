<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class FilterLogin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('isLoggedIn') !== true || ! session()->get('adminid')) {
            session()->setFlashdata('error', 'Silakan masuk untuk melanjutkan.');

            return redirect()->to('login');
        }

        $db = \Config\Database::connect();
        $adminId = (int) session()->get('adminid');
        $admin = $db->table('admin')
            ->select('is_active')
            ->where('adminid', $adminId)
            ->get()
            ->getRowArray();

        if ($admin === null || (int) $admin['is_active'] !== 1) {
            session()->destroy();
            session()->setFlashdata('error', 'Akun petugas tidak aktif atau tidak tersedia.');

            return redirect()->to('login');
        }

        $lastSeenCutoff = date('Y-m-d H:i:s', time() - 60);
        $db->table('admin')
            ->set('last_seen_at', date('Y-m-d H:i:s'))
            ->where('adminid', $adminId)
            ->groupStart()
                ->where('last_seen_at', null)
                ->orWhere('last_seen_at <', $lastSeenCutoff)
            ->groupEnd()
            ->update();
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}