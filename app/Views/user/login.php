<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<section class="site-container auth-section">
    <div class="auth-card">
        <h2>Selamat datang</h2>
        <p>Masuk menggunakan email atau nama pengguna dan kata sandi Anda.</p>
        <form action="<?= base_url('login') ?>" method="post" class="auth-form">
            <?= csrf_field() ?>
            <div class="field">
                <label for="identity">Email atau nama pengguna</label>
                <input type="text" id="identity" name="identity" value="<?= esc(old('identity')) ?>" maxlength="255" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button class="button button-primary auth-submit" type="submit">Masuk</button>
        </form>
        <p class="auth-switch">Pemohon belum punya akun? <a href="<?= base_url('daftar') ?>">Daftar di sini</a>.</p>
    </div>
</section>

<?= $this->endSection() ?>
