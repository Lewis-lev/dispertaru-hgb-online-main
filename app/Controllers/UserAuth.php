<?php

namespace App\Controllers;

class UserAuth extends BaseController
{
    public function login()
    {
        $redirect = $this->authenticatedRedirect();

        if ($redirect !== null) {
            return redirect()->to($redirect);
        }

        return view('user/login');
    }

    public function signup()
    {
        $redirect = $this->authenticatedRedirect();

        if ($redirect !== null) {
            return redirect()->to($redirect);
        }

        return view('user/signup');
    }

    public function register()
    {
        $redirect = $this->authenticatedRedirect();

        if ($redirect !== null) {
            return redirect()->to($redirect);
        }

        if (! $this->validate([
            'email' => 'required|valid_email|max_length[255]',
            'password' => 'required|min_length[12]|max_length[128]',
            'password_confirm' => 'required|matches[password]',
        ])) {
            session()->setFlashdata('form_errors', $this->validator->getErrors());

            return redirect()->to('daftar')->withInput();
        }

        $email = mb_strtolower(trim((string) $this->request->getPost('email')));
        $db = \Config\Database::connect();

        if ($db->table('pemohon_accounts')->where('email', $email)->countAllResults() > 0) {
            session()->setFlashdata('error', 'Email sudah terdaftar. Silakan masuk dengan akun Anda.');

            return redirect()->to('daftar')->withInput();
        }

        $created = $db->table('pemohon_accounts')->insert([
            'email' => $email,
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (! $created) {
            log_message('error', 'Unable to create applicant account.');
            session()->setFlashdata('error', 'Akun belum dapat dibuat. Silakan coba kembali.');

            return redirect()->to('daftar')->withInput();
        }

        session()->regenerate(true);
        session()->remove(['isLoggedIn', 'adminid', 'username', 'managerLoggedIn', 'managerId', 'managerUsername', 'userLoggedIn', 'userId', 'userEmail']);
        session()->set([
            'userLoggedIn' => true,
            'userId' => (int) $db->insertID(),
            'userEmail' => $email,
        ]);

        return redirect()->to('account');
    }

    public function authenticate()
    {
        $identity = trim((string) $this->request->getPost('identity'));
        $password = (string) $this->request->getPost('password');
        $db = \Config\Database::connect();
        $role = null;
        $account = null;

        if ($identity !== '') {
            $account = $db->table('manager_accounts')->where('username', $identity)->get()->getRowArray();
            if ($account !== null && password_verify($password, (string) $account['password_hash'])) {
                $role = 'manager';
            }

            if ($role === null) {
                $account = (new \App\Models\Modeladmin())
                    ->where('username', $identity)
                    ->where('is_active', 1)
                    ->first();
                if ($account !== null) {
                    $storedPassword = (string) $account['password'];
                    $validPassword = password_verify($password, $storedPassword);
                    $legacyPassword = preg_match('/^[a-f0-9]{32}$/i', $storedPassword) === 1
                        && hash_equals(strtolower($storedPassword), md5($password));

                    if ($validPassword || $legacyPassword) {
                        if ($legacyPassword && ! (new \App\Models\Modeladmin())->update($account['adminid'], [
                            'password' => password_hash($password, PASSWORD_DEFAULT),
                        ])) {
                            log_message('error', 'Unable to upgrade a legacy administrator password hash.');
                            session()->setFlashdata('error', 'Login belum dapat diproses. Silakan coba kembali.');

                            return redirect()->to('login');
                        }

                        $role = 'admin';
                    }
                }
            }

            if ($role === null && filter_var($identity, FILTER_VALIDATE_EMAIL)) {
                $account = $db->table('pemohon_accounts')
                    ->where('email', mb_strtolower($identity))
                    ->get()
                    ->getRowArray();

                if ($account !== null && password_verify($password, (string) $account['password_hash'])) {
                    $role = 'user';
                }
            }
        }

        if ($role === null) {
            session()->setFlashdata('error', 'Email/nama pengguna atau kata sandi salah.');

            return redirect()->to('login')->withInput();
        }

        session()->regenerate(true);
        session()->remove(['isLoggedIn', 'adminid', 'username', 'managerLoggedIn', 'managerId', 'managerUsername', 'userLoggedIn', 'userId', 'userEmail']);

        if ($role === 'manager') {
            session()->set([
                'managerLoggedIn' => true,
                'managerId' => (int) $account['id'],
                'managerUsername' => $account['username'],
            ]);

            return redirect()->to('manager');
        }

        if ($role === 'admin') {
            session()->set([
                'isLoggedIn' => true,
                'adminid' => (int) $account['adminid'],
                'username' => $account['username'],
            ]);

            return redirect()->to('administrator');
        }

        session()->set([
            'userLoggedIn' => true,
            'userId' => (int) $account['id'],
            'userEmail' => $account['email'],
        ]);

        return redirect()->to('account');
    }

    public function account()
    {
        $applications = \Config\Database::connect()->table('pemohon')
            ->select('pemohon.nama, form_p3.kdpermohonan, form_p3.tgl_masuk, form_p3.tgl_update, form_p3.status')
            ->join('form_p3', 'form_p3.pemohon_id = pemohon.id')
            ->where('pemohon.user_id', (int) session()->get('userId'))
            ->orderBy('form_p3.tgl_masuk', 'DESC')
            ->get()
            ->getResultArray();

        return view('user/account', ['applications' => $applications]);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }

    private function authenticatedRedirect(): ?string
    {
        if (session()->get('managerLoggedIn') === true) {
            return 'manager';
        }

        if (session()->get('isLoggedIn') === true) {
            return 'administrator';
        }

        if (session()->get('userLoggedIn') === true) {
            return 'account';
        }

        return null;
    }
}
