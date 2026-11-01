<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="detail-back"><a class="text-link" href="<?= base_url('administrator') ?>">← Kembali ke permohonan</a></div>
<section class="admin-page-heading">
    <div>
        <span class="eyebrow">KEAMANAN AKUN</span>
        <h1>Ganti kata sandi</h1>
        <p>Kata sandi baru dapat menggunakan satu karakter atau lebih.</p>
    </div>
</section>

<form class="admin-card password-card" action="<?= base_url('admin/password') ?>" method="post">
    <?= csrf_field() ?>
    <?php $errors = session()->getFlashdata('form_errors') ?? []; ?>
    <?php if ($errors !== []) : ?>
        <div class="notice notice-error" role="alert">
            <strong>Periksa kembali formulir:</strong>
            <ul><?php foreach ($errors as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
        </div>
    <?php endif ?>

    <div class="password-fields">
        <div class="field">
            <label for="password_current">Kata sandi saat ini</label>
            <input id="password_current" type="password" name="password_current" autocomplete="current-password" required>
        </div>
        <div class="field">
            <label for="password_new">Kata sandi baru</label>
            <input id="password_new" type="password" name="password_new" autocomplete="new-password" maxlength="128" required>
            <small>Maksimal 128 karakter.</small>
        </div>
        <div class="field">
            <label for="password_confirm">Konfirmasi kata sandi baru</label>
            <input id="password_confirm" type="password" name="password_confirm" autocomplete="new-password" maxlength="128" required>
        </div>
    </div>
    <div class="form-submit">
        <a class="text-link" href="<?= base_url('administrator') ?>">Batal</a>
        <button class="button button-primary" type="submit">Perbarui kata sandi</button>
    </div>
</form>

<?= $this->endSection() ?>
