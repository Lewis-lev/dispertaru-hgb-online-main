<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('user/index');
    }

    public function form()
    {
        if (session()->get('userLoggedIn') !== true) {
            session()->setFlashdata('error', 'Masuk atau daftar terlebih dahulu untuk mengajukan permohonan.');

            return redirect()->to('login');
        }

        return view('user/form');
    }

    public function cekStatusDokumen()
    {
        $kode = strtoupper(trim((string) $this->request->getGet('kode')));
        $data = null;

        if ($kode !== '') {
            if (! preg_match('/^P[A-F0-9]{16}$/', $kode)) {
                session()->setFlashdata('error', 'Format kode permohonan tidak valid.');
            } else {
                $data = \Config\Database::connect()
                    ->table('form_p3')
                    ->select('kdpermohonan, tgl_masuk, tgl_update, status')
                    ->where('kdpermohonan', $kode)
                    ->get()
                    ->getRowArray();

                if ($data === null) {
                    session()->setFlashdata('error', 'Permohonan tidak ditemukan. Periksa kembali kode Anda.');
                }
            }
        }

        return view('user/dokumen', ['data' => $data, 'kode' => $kode]);
    }

    public function simpanForm()
    {
        $valid = $this->validate([
            'nama' => 'required|max_length[255]',
            'nik' => 'required|numeric|exact_length[16]',
            'email' => 'required|valid_email|max_length[255]',
            'tempat_lahir' => 'required|max_length[255]',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'no_hp' => 'required|regex_match[/^\+?[0-9]{8,20}$/]',
            'alamat' => 'required|max_length[255]',
            'pekerjaan' => 'required|max_length[255]',
            'ktp' => 'uploaded[ktp]|max_size[ktp,5120]|mime_in[ktp,image/png,image/jpeg]|ext_in[ktp,png,jpg,jpeg]',
            'file_permohonan' => 'uploaded[file_permohonan]|max_size[file_permohonan,10240]|mime_in[file_permohonan,application/pdf]|ext_in[file_permohonan,pdf]',
        ], [
            'nik' => [
                'exact_length' => 'NIK harus terdiri dari 16 angka.',
            ],
            'ktp' => [
                'max_size' => 'Ukuran foto KTP maksimal 5 MB.',
                'mime_in' => 'Foto KTP harus berupa JPG atau PNG.',
            ],
            'file_permohonan' => [
                'max_size' => 'Ukuran dokumen permohonan maksimal 10 MB.',
                'mime_in' => 'Dokumen permohonan harus berupa PDF.',
            ],
        ]);

        if (! $valid) {
            session()->setFlashdata('form_errors', $this->validator->getErrors());

            return redirect()->back()->withInput();
        }

        if (session()->get('userLoggedIn') !== true || ! session()->get('userId')) {
            session()->setFlashdata('error', 'Silakan masuk terlebih dahulu untuk mengirim permohonan.');

            return redirect()->to('login');
        }

        if ($this->request->getPost('tanggal_lahir') > date('Y-m-d')) {
            session()->setFlashdata('form_errors', [
                'tanggal_lahir' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            ]);

            return redirect()->back()->withInput();
        }

        $db = \Config\Database::connect();
        $admin = $db->table('admin')
            ->select('adminid')
            ->where('is_active', 1)
            ->orderBy('adminid', 'ASC')
            ->get()
            ->getRowArray();

        if ($admin === null) {
            log_message('error', 'Unable to save an application because no administrator account exists.');
            session()->setFlashdata('error', 'Permohonan belum dapat disimpan karena akun petugas belum tersedia.');

            return redirect()->back()->withInput();
        }

        $ktp  = $this->request->getFile('ktp');
        $file = $this->request->getFile('file_permohonan');
        $uploadDirectory = WRITEPATH . 'uploads';

        if (! is_dir($uploadDirectory) && ! mkdir($uploadDirectory, 0750, true) && ! is_dir($uploadDirectory)) {
            log_message('error', 'Unable to create the private document upload directory.');
            session()->setFlashdata('error', 'Dokumen belum dapat disimpan. Silakan coba kembali.');

            return redirect()->back()->withInput();
        }

        $ktpName  = $ktp->getRandomName();
        $fileName = $file->getRandomName();
        $ktp->move($uploadDirectory, $ktpName);
        $file->move($uploadDirectory, $fileName);

        $kode = 'P' . strtoupper(bin2hex(random_bytes(8)));
        $db->transBegin();

        $db->table('pemohon')->insert([
            'user_id' => (int) session()->get('userId'),
            'nama' => trim($this->request->getPost('nama')),
            'nik' => trim($this->request->getPost('nik')),
            'email' => trim($this->request->getPost('email')),
            'tempat_lahir' => trim($this->request->getPost('tempat_lahir')),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'no_hp' => trim($this->request->getPost('no_hp')),
            'alamat' => trim($this->request->getPost('alamat')),
            'pekerjaan' => trim($this->request->getPost('pekerjaan')),
        ]);

        $pemohonId = $db->insertID();
        $db->table('form_p3')->insert([
            'pemohon_id' => $pemohonId,
            'kdpermohonan' => $kode,
            'foto_ktp' => $ktpName,
            'file_permohonan' => $fileName,
            'status' => 0,
        ]);
        $applicationId = $db->insertID();
        $db->table('verifikasi')->insert([
            'pemohon_id' => $pemohonId,
            'adminid' => (int) $admin['adminid'],
        ]);
        $db->table('admin_work_log')->insert([
            'application_id' => $applicationId,
            'adminid' => (int) $admin['adminid'],
            'action' => 'application_received',
            'from_status' => null,
            'to_status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            foreach ([$ktpName, $fileName] as $uploadedFile) {
                $uploadedPath = $uploadDirectory . DIRECTORY_SEPARATOR . $uploadedFile;

                if (is_file($uploadedPath) && ! unlink($uploadedPath)) {
                    log_message('error', 'Unable to remove an orphaned application document {filename}.', ['filename' => $uploadedFile]);
                }
            }
            log_message('error', 'Database transaction failed while saving an application.');
            session()->setFlashdata('error', 'Permohonan belum dapat disimpan. Silakan coba kembali.');

            return redirect()->back()->withInput();
        }

        session()->setFlashdata('application_code', $kode);

        return redirect()->to('/');
    }
}
