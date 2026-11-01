<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<?php
    $statusLabels = ['Belum diproses', 'Sedang diproses', 'Selesai'];
    $status = (int) $data['status'];
    $statusClass = ['status-pending', 'status-progress', 'status-done'][$status] ?? 'status-pending';
?>

<div class="detail-back"><a class="text-link" href="<?= base_url('administrator') ?>">← Kembali ke permohonan</a></div>
<section class="admin-page-heading">
    <div>
        <span class="eyebrow">DETAIL PERMOHONAN</span>
        <h1><?= esc($data['kdpermohonan']) ?></h1>
        <p>Diajukan pada <?= esc(date('d M Y, H:i', strtotime($data['tgl_masuk']))) ?></p>
    </div>
    <span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabels[$status] ?? 'Belum diproses') ?></span>
</section>

<div class="detail-grid">
    <section class="admin-card">
        <div class="card-heading">
            <div><span class="eyebrow">INFORMASI PEMOHON</span><h2>Data diri</h2></div>
            <a class="button button-outline" href="<?= base_url('p3/' . $data['p3_id'] . '/edit') ?>">Edit data</a>
        </div>
        <dl class="detail-list">
            <div><dt>Nama lengkap</dt><dd><?= esc($data['nama']) ?></dd></div>
            <div><dt>NIK</dt><dd><?= esc($data['nik']) ?></dd></div>
            <div><dt>Email</dt><dd><a href="mailto:<?= esc($data['email']) ?>"><?= esc($data['email']) ?></a></dd></div>
            <div><dt>Nomor handphone</dt><dd><?= esc($data['no_hp']) ?></dd></div>
            <div><dt>Tempat, tanggal lahir</dt><dd><?= esc($data['tempat_lahir']) ?>, <?= esc(date('d-m-Y', strtotime($data['tanggal_lahir']))) ?></dd></div>
            <div><dt>Pekerjaan</dt><dd><?= esc($data['pekerjaan']) ?></dd></div>
            <div><dt>Alamat lokasi tanah</dt><dd><?= esc($data['alamat']) ?></dd></div>
            <div><dt>Pembaruan terakhir</dt><dd><?= esc(date('d M Y, H:i', strtotime($data['tgl_update']))) ?></dd></div>
        </dl>
        <div class="status-action">
            <?php if ($status < 2) : ?>
                <form action="<?= base_url('update-p3/status') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= esc($data['p3_id']) ?>">
                    <button class="button button-primary" type="submit" name="status" value="<?= $status + 1 ?>">
                        <?= $status === 0 ? 'Tandai sedang diproses' : 'Tandai selesai' ?>
                    </button>
                </form>
            <?php else : ?>
                <p class="completed-note">Permohonan ini telah selesai diproses.</p>
            <?php endif ?>
        </div>
    </section>

    <section class="admin-card document-card">
        <div class="card-heading">
            <div><span class="eyebrow">BERKAS TERLAMPIR</span><h2>Foto KTP</h2></div>
            <a class="text-link" href="<?= base_url('admin/document/' . $data['p3_id'] . '/ktp') ?>" target="_blank" rel="noopener">Buka berkas ↗</a>
        </div>
        <img class="identity-preview" src="<?= base_url('admin/document/' . $data['p3_id'] . '/ktp') ?>" alt="Foto KTP pemohon">
        <div class="card-heading pdf-heading">
            <div><span class="eyebrow">DOKUMEN PERMOHONAN</span><h2>Surat permohonan</h2></div>
            <a class="text-link" href="<?= base_url('admin/document/' . $data['p3_id'] . '/permohonan') ?>" target="_blank" rel="noopener">Buka PDF ↗</a>
        </div>
        <iframe class="pdf-preview" src="<?= base_url('admin/document/' . $data['p3_id'] . '/permohonan') ?>" title="Pratinjau dokumen permohonan"></iframe>
    </section>
</div>

<?= $this->endSection() ?>
