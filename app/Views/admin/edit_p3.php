<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="detail-back"><a class="text-link" href="<?= base_url('p3/' . $data['p3_id']) ?>">← Kembali ke detail permohonan</a></div>
<section class="admin-page-heading">
    <div>
        <span class="eyebrow">PEMBARUAN DATA</span>
        <h1>Edit data pemohon</h1>
        <p>Kode permohonan: <?= esc($data['kdpermohonan']) ?></p>
    </div>
</section>

<form class="admin-card edit-form" action="<?= base_url('p3-update') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="pemohon_id" value="<?= esc($data['pemohon_id']) ?>">
    <input type="hidden" name="form_p3_id" value="<?= esc($data['p3_id']) ?>">
    <?php $errors = session()->getFlashdata('form_errors') ?? []; ?>
    <?php if ($errors !== []) : ?>
        <div class="notice notice-error" role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul><?php foreach ($errors as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
        </div>
    <?php endif ?>

    <div class="form-grid">
        <div class="field"><label for="nama">Nama lengkap</label><input id="nama" name="nama" value="<?= esc(old('nama', $data['nama'])) ?>" maxlength="255" required></div>
        <div class="field"><label for="nik">NIK</label><input id="nik" name="nik" value="<?= esc(old('nik', $data['nik'])) ?>" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" minlength="16" required></div>
        <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="<?= esc(old('email', $data['email'])) ?>" maxlength="255" required></div>
        <div class="field"><label for="no_hp">Nomor handphone</label><input id="no_hp" name="no_hp" value="<?= esc(old('no_hp', $data['no_hp'])) ?>" type="tel" inputmode="tel" maxlength="20" required></div>
        <div class="field"><label for="tempat_lahir">Tempat lahir</label><input id="tempat_lahir" name="tempat_lahir" value="<?= esc(old('tempat_lahir', $data['tempat_lahir'])) ?>" maxlength="255" required></div>
        <div class="field"><label for="tanggal_lahir">Tanggal lahir</label><input id="tanggal_lahir" type="date" name="tanggal_lahir" value="<?= esc(old('tanggal_lahir', $data['tanggal_lahir'])) ?>" required></div>
        <div class="field"><label for="pekerjaan">Pekerjaan</label><input id="pekerjaan" name="pekerjaan" value="<?= esc(old('pekerjaan', $data['pekerjaan'])) ?>" maxlength="255" required></div>
        <div class="field field-wide"><label for="alamat">Alamat lokasi tanah</label><textarea id="alamat" name="alamat" rows="3" maxlength="255" required><?= esc(old('alamat', $data['alamat'])) ?></textarea></div>
    </div>
    <div class="form-submit">
        <a class="text-link" href="<?= base_url('p3/' . $data['p3_id']) ?>">Batal</a>
        <button class="button button-primary" type="submit">Simpan perubahan</button>
    </div>
</form>

<?= $this->endSection() ?>
