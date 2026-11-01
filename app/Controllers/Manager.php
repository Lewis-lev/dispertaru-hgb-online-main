<?php

namespace App\Controllers;

class Manager extends BaseController
{
    public function login()
    {
        return redirect()->to('login');
    }

    public function createAdmin()
    {
        if (! $this->validate([
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[100]',
            'password' => 'required|max_length[128]',
            'password_confirm' => 'required|matches[password]',
        ])) {
            session()->setFlashdata('form_errors', $this->validator->getErrors());

            return redirect()->to('manager/admins/new')->withInput();
        }

        $username = trim((string) $this->request->getPost('username'));
        $db = \Config\Database::connect();
        $adminModel = new \App\Models\Modeladmin();
        $existingAdmin = $adminModel->where('username', $username)->first();
        $existingManager = $db->table('manager_accounts')->where('username', $username)->countAllResults();

        if ($existingAdmin !== null || $existingManager > 0) {
            session()->setFlashdata('error', 'Nama pengguna sudah digunakan.');

            return redirect()->to('manager/admins/new')->withInput();
        }

        if (! $adminModel->insert([
            'username' => $username,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
        ])) {
            log_message('error', 'Unable to create staff account for manager {id}.', [
                'id' => session()->get('managerId'),
            ]);
            session()->setFlashdata('error', 'Akun petugas belum dapat dibuat. Silakan coba kembali.');

            return redirect()->to('manager/admins/new')->withInput();
        }

        session()->setFlashdata('success', 'Akun petugas berhasil dibuat.');

        return redirect()->to('manager/admins');
    }

    public function updateAdmin(int $adminId)
    {
        $adminModel = new \App\Models\Modeladmin();
        $admin = $adminModel->find($adminId);

        if ($admin === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate([
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[100]',
            'password' => 'permit_empty|max_length[128]',
        ])) {
            session()->setFlashdata('form_errors', $this->validator->getErrors());

            return redirect()->to('manager/admins')->withInput();
        }

        $username = trim((string) $this->request->getPost('username'));
        $db = \Config\Database::connect();
        $duplicateAdmin = $adminModel->where('username', $username)
            ->where('adminid !=', $adminId)
            ->first();
        $duplicateManager = $db->table('manager_accounts')
            ->where('username', $username)
            ->countAllResults();

        if ($duplicateAdmin !== null || $duplicateManager > 0) {
            session()->setFlashdata('error', 'Nama pengguna sudah digunakan.');

            return redirect()->to('manager/admins')->withInput();
        }

        $changes = ['username' => $username];
        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $changes['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (! $adminModel->update($adminId, $changes)) {
            log_message('error', 'Unable to update staff account {id} by manager {managerId}.', [
                'id' => $adminId,
                'managerId' => session()->get('managerId'),
            ]);
            session()->setFlashdata('error', 'Perubahan akun petugas belum dapat disimpan.');

            return redirect()->to('manager/admins')->withInput();
        }

        session()->setFlashdata('success', 'Akun petugas berhasil diperbarui.');

        return redirect()->to('manager/admins');
    }

    public function updateAdminStatus(int $adminId)
    {
        $adminModel = new \App\Models\Modeladmin();
        $admin = $adminModel->find($adminId);

        if ($admin === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $isActive = filter_var($this->request->getPost('is_active'), FILTER_VALIDATE_INT);
        if (! in_array($isActive, [0, 1], true)) {
            session()->setFlashdata('error', 'Status akun petugas tidak valid.');

            return redirect()->to('manager/admins');
        }

        $updated = $adminModel->update($adminId, [
            'is_active' => $isActive,
            'last_seen_at' => $isActive === 1 ? $admin['last_seen_at'] : null,
        ]);

        if (! $updated) {
            log_message('error', 'Unable to change status for staff account {id}.', [
                'id' => $adminId,
            ]);
            session()->setFlashdata('error', 'Status akun petugas belum dapat diperbarui.');

            return redirect()->to('manager/admins');
        }

        session()->setFlashdata('success', $isActive === 1
            ? 'Akun petugas berhasil diaktifkan.'
            : 'Akun petugas dinonaktifkan. Riwayat audit tetap tersimpan.');

        return redirect()->to('manager/admins');
    }

    public function admins()
    {
        $admins = \Config\Database::connect()->table('admin')
            ->select('adminid, username, is_active, last_seen_at')
            ->orderBy('username', 'ASC')
            ->get()
            ->getResultArray();

        return view('manager/admins', ['admins' => $admins]);
    }

    public function newAdmin()
    {
        return view('manager/create_admin');
    }

    public function index()
    {
        $status = (string) ($this->request->getGet('status') ?? 'all');
        $adminId = (string) ($this->request->getGet('adminid') ?? 'all');

        if (! in_array($status, ['all', '0', '1', '2'], true)) {
            $status = 'all';
        }

        $db = \Config\Database::connect();
        $builder = $db->table('form_p3')
            ->select('admin_work_log.id AS log_id, admin_work_log.action, admin_work_log.from_status, admin_work_log.to_status, admin_work_log.created_at AS activity_at, form_p3.id AS p3_id, form_p3.kdpermohonan, form_p3.tgl_masuk, form_p3.tgl_update, form_p3.status AS application_status, pemohon.nama, admin.username AS admin_username')
            ->join('admin_work_log', 'form_p3.id = admin_work_log.application_id', 'left')
            ->join('pemohon', 'pemohon.id = form_p3.pemohon_id')
            ->join('admin', 'admin.adminid = admin_work_log.adminid', 'left');

        if ($status !== 'all') {
            $builder->where('form_p3.status', (int) $status);
        }

        if ($adminId !== 'all' && ctype_digit($adminId)) {
            $builder->where('admin_work_log.adminid', (int) $adminId);
        } else {
            $adminId = 'all';
        }

        $admins = $db->table('admin')
            ->select('adminid, username')
            ->orderBy('username', 'ASC')
            ->get()
            ->getResultArray();

        return view('manager/index', [
            'applications' => $builder->orderBy('COALESCE(admin_work_log.created_at, form_p3.tgl_masuk)', 'DESC', false)->get()->getResultArray(),
            'admins' => $admins,
            'status' => $status,
            'adminId' => $adminId,
        ]);
    }

    public function detail(int $id)
    {
        $db = \Config\Database::connect();
        $application = $db->table('form_p3')
            ->select('form_p3.id AS p3_id, form_p3.kdpermohonan, form_p3.tgl_masuk, form_p3.tgl_update, form_p3.status, pemohon.nama, pemohon.nik, pemohon.email')
            ->join('pemohon', 'pemohon.id = form_p3.pemohon_id')
            ->where('form_p3.id', $id)
            ->get()
            ->getRowArray();

        if ($application === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $history = $db->table('admin_work_log')
            ->select('admin_work_log.action, admin_work_log.from_status, admin_work_log.to_status, admin_work_log.created_at, admin.username')
            ->join('admin', 'admin.adminid = admin_work_log.adminid', 'left')
            ->where('admin_work_log.application_id', $id)
            ->orderBy('admin_work_log.created_at', 'ASC')
            ->get()
            ->getResultArray();

        return view('manager/detail', ['application' => $application, 'history' => $history]);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
