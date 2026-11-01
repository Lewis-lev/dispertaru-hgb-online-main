<?php

namespace App\Controllers;

use App\Models\Modeladmin;
use CodeIgniter\Exceptions\PageNotFoundException;

class Admin extends BaseController
{
    public function login()
    {
        return redirect()->to('login');
    }

    public function passwordForm()
    {
        return view('admin/password');
    }

    public function updatePassword()
    {
        if (! $this->validate([
            'password_current' => 'required',
            'password_new' => 'required|max_length[128]',
            'password_confirm' => 'required|matches[password_new]',
        ])) {
            session()->setFlashdata('form_errors', $this->validator->getErrors());

            return redirect()->to('admin/password');
        }

        $adminModel = new Modeladmin();
        $admin = $adminModel->find(session()->get('adminid'));

        if ($admin === null) {
            session()->destroy();

            return redirect()->to('login');
        }

        $currentPassword = (string) $this->request->getPost('password_current');
        $storedPassword = (string) $admin['password'];
        $validPassword = password_verify($currentPassword, $storedPassword)
            || (preg_match('/^[a-f0-9]{32}$/i', $storedPassword) === 1
                && hash_equals(strtolower($storedPassword), md5($currentPassword)));

        if (! $validPassword) {
            session()->setFlashdata('error', 'Kata sandi saat ini tidak sesuai.');

            return redirect()->to('admin/password');
        }

        $updated = $adminModel->update($admin['adminid'], [
            'password' => password_hash((string) $this->request->getPost('password_new'), PASSWORD_DEFAULT),
        ]);

        if (! $updated) {
            log_message('error', 'Unable to update password for administrator {id}.', ['id' => $admin['adminid']]);
            session()->setFlashdata('error', 'Kata sandi belum dapat diperbarui. Silakan coba kembali.');

            return redirect()->to('admin/password');
        }

        session()->regenerate(true);
        session()->setFlashdata('success', 'Kata sandi berhasil diperbarui.');

        return redirect()->to('admin/password');
    }

    public function index()
    {
        $status = (string) ($this->request->getGet('status') ?? 'all');
        $search = trim((string) ($this->request->getGet('q') ?? ''));
        $search = mb_substr($search, 0, 100);

        if (! in_array($status, ['all', '0', '1', '2'], true)) {
            $status = 'all';
        }

        $builder = \Config\Database::connect()->table('form_p3')
            ->select('form_p3.id AS p3_id, form_p3.kdpermohonan, form_p3.foto_ktp, form_p3.file_permohonan, form_p3.tgl_masuk, form_p3.status, pemohon.nama, pemohon.nik')
            ->join('pemohon', 'pemohon.id = form_p3.pemohon_id');

        if ($status !== 'all') {
            $builder->where('form_p3.status', (int) $status);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('form_p3.kdpermohonan', $search)
                ->orLike('pemohon.nama', $search)
                ->orLike('pemohon.nik', $search)
                ->groupEnd();
        }

        return view('admin/index', [
            'data' => $builder->orderBy('form_p3.tgl_masuk', 'DESC')->get()->getResultArray(),
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function p3Detail(int $id)
    {
        return view('admin/p3Detail', ['data' => $this->getApplication($id)]);
    }

    public function p3Edit(int $id)
    {
        return view('admin/edit_p3', ['data' => $this->getApplication($id)]);
    }

    public function p3Delete(int $id)
    {
        $db = \Config\Database::connect();
        $application = $db->table('form_p3')->where('id', $id)->get()->getRowArray();

        if ($application === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $db->transBegin();
        $db->table('verifikasi')->where('pemohon_id', $application['pemohon_id'])->delete();
        $db->table('admin_work_log')->where('application_id', $id)->delete();
        $db->table('form_p3')->where('id', $id)->delete();
        $db->table('pemohon')->where('id', $application['pemohon_id'])->delete();
        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', 'Database transaction failed while deleting application {id}.', ['id' => $id]);
            session()->setFlashdata('error', 'Permohonan belum dapat dihapus. Silakan coba kembali.');

            return redirect()->to('administrator');
        }

        $this->removeStoredDocument('ktp', $application['foto_ktp']);
        $this->removeStoredDocument('permohonan', $application['file_permohonan']);
        session()->setFlashdata('success', 'Permohonan berhasil dihapus.');

        return redirect()->to('administrator');
    }

    public function p3Update()
    {
        $id = filter_var($this->request->getPost('form_p3_id'), FILTER_VALIDATE_INT);
        $pemohonId = filter_var($this->request->getPost('pemohon_id'), FILTER_VALIDATE_INT);

        if ($id === false || $pemohonId === false) {
            throw PageNotFoundException::forPageNotFound();
        }

        $application = $this->getApplication($id);

        if ((int) $application['pemohon_id'] !== $pemohonId) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate([
            'nama' => 'required|max_length[255]',
            'nik' => 'required|numeric|exact_length[16]',
            'email' => 'required|valid_email|max_length[255]',
            'tempat_lahir' => 'required|max_length[255]',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'no_hp' => 'required|regex_match[/^\+?[0-9]{8,20}$/]',
            'alamat' => 'required|max_length[255]',
            'pekerjaan' => 'required|max_length[255]',
        ])) {
            session()->setFlashdata('form_errors', $this->validator->getErrors());

            return redirect()->back()->withInput();
        }

        $updated = \Config\Database::connect()->table('pemohon')
            ->where('id', $pemohonId)
            ->update([
                'nama' => trim($this->request->getPost('nama')),
                'nik' => trim($this->request->getPost('nik')),
                'email' => trim($this->request->getPost('email')),
                'tempat_lahir' => trim($this->request->getPost('tempat_lahir')),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'no_hp' => trim($this->request->getPost('no_hp')),
                'alamat' => trim($this->request->getPost('alamat')),
                'pekerjaan' => trim($this->request->getPost('pekerjaan')),
            ]);

        if (! $updated) {
            log_message('error', 'Unable to update applicant record {id}.', ['id' => $pemohonId]);
            session()->setFlashdata('error', 'Data pemohon belum dapat diperbarui. Silakan coba kembali.');

            return redirect()->to('p3/' . $id . '/edit');
        }

        session()->setFlashdata('success', 'Data pemohon berhasil diperbarui.');

        return redirect()->to('p3/' . $id . '/edit');
    }

    public function updateStatusP3()
    {
        $id = filter_var($this->request->getPost('id'), FILTER_VALIDATE_INT);
        $nextStatus = filter_var($this->request->getPost('status'), FILTER_VALIDATE_INT);

        if ($id === false || $nextStatus === false) {
            throw PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $application = $db->table('form_p3')->select('pemohon_id, status')->where('id', $id)->get()->getRowArray();

        if ($application === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $currentStatus = (int) $application['status'];

        if ($currentStatus >= 2 || $nextStatus !== $currentStatus + 1) {
            session()->setFlashdata('error', 'Perubahan status tidak valid.');

            return redirect()->to('p3/' . $id);
        }

        $db->transBegin();
        $updated = $db->table('form_p3')
            ->where('id', $id)
            ->where('status', $currentStatus)
            ->update([
                'status' => $nextStatus,
                'tgl_update' => date('Y-m-d H:i:s'),
            ]);

        if (! $updated || $db->affectedRows() !== 1) {
            $db->transRollback();
            log_message('error', 'Unable to update status for application {id}.', ['id' => $id]);
            session()->setFlashdata('error', 'Status belum dapat diperbarui. Muat ulang halaman dan coba kembali.');

            return redirect()->to('p3/' . $id);
        }

        $db->table('verifikasi')
            ->where('pemohon_id', $application['pemohon_id'])
            ->update([
                'adminid' => (int) session()->get('adminid'),
                'status' => $nextStatus,
                'tanggal' => date('Y-m-d H:i:s'),
            ]);
        $db->table('admin_work_log')->insert([
            'application_id' => $id,
            'adminid' => (int) session()->get('adminid'),
            'action' => 'status_updated',
            'from_status' => $currentStatus,
            'to_status' => $nextStatus,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            log_message('error', 'Unable to update verification record for application {id}.', ['id' => $id]);
            session()->setFlashdata('error', 'Status belum dapat diperbarui. Silakan coba kembali.');

            return redirect()->to('p3/' . $id);
        }

        $db->transComplete();
        session()->setFlashdata('success', 'Status permohonan berhasil diperbarui.');

        return redirect()->to('p3/' . $id);
    }

    public function document(int $id, string $type)
    {
        $application = \Config\Database::connect()->table('form_p3')
            ->select('foto_ktp, file_permohonan')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if ($application === null || ! in_array($type, ['ktp', 'permohonan'], true)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $filename = $type === 'ktp' ? $application['foto_ktp'] : $application['file_permohonan'];

        if (basename($filename) !== $filename) {
            throw PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $filename;

        if (! is_file($path)) {
            $legacyDirectory = $type === 'ktp' ? 'ktp' : 'permohonan';
            $path = FCPATH . 'berkas' . DIRECTORY_SEPARATOR . $legacyDirectory . DIRECTORY_SEPARATOR . $filename;
        }

        if (! is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        $allowedMimes = $type === 'ktp' ? ['image/jpeg', 'image/png'] : ['application/pdf'];

        if ($mime === false || ! in_array($mime, $allowedMimes, true)) {
            log_message('error', 'Unexpected MIME type for stored application document {id}.', ['id' => $id]);
            throw PageNotFoundException::forPageNotFound();
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            log_message('error', 'Unable to read stored application document {id}.', ['id' => $id]);

            return $this->response->setStatusCode(500)->setBody('Dokumen tidak dapat dibuka.');
        }

        $extension = $type === 'permohonan' ? 'pdf' : ($mime === 'image/png' ? 'png' : 'jpg');

        return $this->response
            ->setContentType($mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . ($type === 'ktp' ? 'identitas' : 'permohonan') . '.' . $extension . '"')
            ->setHeader('Cache-Control', 'private, no-store, max-age=0')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody($contents);
    }

    public function logout()
    {
        \Config\Database::connect()->table('admin')
            ->where('adminid', (int) session()->get('adminid'))
            ->update(['last_seen_at' => null]);

        session()->destroy();

        return redirect()->to('/');
    }

    private function getApplication(int $id): array
    {
        $application = \Config\Database::connect()->table('form_p3')
            ->select('form_p3.id AS p3_id, form_p3.pemohon_id, form_p3.kdpermohonan, form_p3.foto_ktp, form_p3.file_permohonan, form_p3.tgl_masuk, form_p3.tgl_update, form_p3.status, pemohon.nama, pemohon.nik, pemohon.email, pemohon.tempat_lahir, pemohon.tanggal_lahir, pemohon.no_hp, pemohon.alamat, pemohon.pekerjaan')
            ->join('pemohon', 'pemohon.id = form_p3.pemohon_id')
            ->where('form_p3.id', $id)
            ->get()
            ->getRowArray();

        if ($application === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $application;
    }

    private function removeStoredDocument(string $type, string $filename): void
    {
        if (basename($filename) !== $filename) {
            log_message('error', 'Refusing to delete an application document with an invalid filename.');

            return;
        }

        $directory = WRITEPATH . 'uploads';
        $privatePath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (is_file($privatePath) && ! unlink($privatePath)) {
            log_message('error', 'Unable to delete private application document {filename}.', ['filename' => $filename]);
        }

        $legacyDirectory = $type === 'ktp' ? 'ktp' : 'permohonan';
        $legacyPath = FCPATH . 'berkas' . DIRECTORY_SEPARATOR . $legacyDirectory . DIRECTORY_SEPARATOR . $filename;

        if (is_file($legacyPath) && ! unlink($legacyPath)) {
            log_message('error', 'Unable to delete legacy application document {filename}.', ['filename' => $filename]);
        }
    }
}
