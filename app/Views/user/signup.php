<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<section class="page-intro">
    <div class="site-container">
        <span class="eyebrow">AKUN PEMOHON</span>
        <h1>Buat akun pemohon</h1>
        <p>Daftar satu kali, lalu gunakan akun untuk setiap permohonan yang Anda ajukan.</p>
    </div>
</section>

<section class="site-container auth-section">
    <div class="auth-card">
        <h2>Daftar akun</h2>
        <p>Gunakan email aktif yang dapat Anda akses.</p>
        <?php $errors = session()->getFlashdata('form_errors') ?? []; ?>
        <?php if ($errors !== []) : ?>
            <div class="notice notice-error" role="alert">
                <strong>Periksa data berikut:</strong>
                <ul><?php foreach ($errors as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
            </div>
        <?php endif ?>
        <form action="<?= base_url('daftar') ?>" method="post" class="auth-form">
            <?= csrf_field() ?>
            <div class="field">
                <label for="email">Email aktif</label>
                <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" maxlength="255" autocomplete="email" required>
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input type="password" id="password" name="password" minlength="12" maxlength="128" autocomplete="new-password" aria-describedby="password-help" required>
                <small id="password-help">Gunakan sedikitnya 12 karakter.</small>
            </div>
            <div class="field">
                <label for="password_confirm">Ulangi kata sandi</label>
                <input type="password" id="password_confirm" name="password_confirm" minlength="12" maxlength="128" autocomplete="new-password" required>
            </div>
            <button class="button button-primary auth-submit" type="submit">Buat akun</button>
        </form>
        <p class="auth-switch">Sudah punya akun? <a href="<?= base_url('login') ?>">Masuk di sini</a>.</p>
    </div>
</section>

<?= $this->endSection() ?>
