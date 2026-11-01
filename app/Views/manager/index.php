<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0c3158">
    <title>Reviu pekerjaan | Dispertaru DIY</title>
    <link rel="icon" href="<?= base_url('user/image/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('user/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/css/admin.css') ?>">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="site-container admin-nav">
            <a class="brand" href="<?= base_url('manager') ?>">
                <img src="<?= base_url('user/image/logo.png') ?>" alt="" width="42" height="48">
                <span><strong>DISPERTARU DIY</strong><small>Panel reviu manajer</small></span>
            </a>
            <?= $this->include('manager/_navigation', ['activePage' => 'activity']) ?>
        </div>
    </header>
    <main class="admin-main">
        <div class="site-container admin-container">
            <section class="admin-page-heading">
                <div>
                    <span class="eyebrow">PEMANTAUAN KINERJA</span>
                    <h1>Review pekerjaan petugas</h1>
                    <p>Audit setiap permohonan, petugas yang menangani, dan riwayat perubahan status.</p>
                </div>
                <span class="record-count"><?= count($applications) ?> aktivitas</span>
            </section>
            <section class="admin-card">
                <form action="<?= base_url('manager') ?>" method="get" class="filter-form">
                    <div>
                        <label for="adminid">Petugas</label>
                        <select name="adminid" id="adminid">
                            <option value="all" <?= $adminId === 'all' ? 'selected' : '' ?>>Semua petugas</option>
                            <?php foreach ($admins as $admin) : ?>
                                <option value="<?= esc($admin['adminid']) ?>" <?= $adminId === (string) $admin['adminid'] ? 'selected' : '' ?>><?= esc($admin['username']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div>
                        <label for="status">Status permohonan</label>
                        <select name="status" id="status">
                            <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Semua status</option>
                            <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Belum diproses</option>
                            <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Sedang diproses</option>
                            <option value="2" <?= $status === '2' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                    <button class="button button-primary" type="submit">Terapkan filter</button>
                    <?php if ($adminId !== 'all' || $status !== 'all') : ?>
                        <a class="filter-reset" href="<?= base_url('manager') ?>">Reset</a>
                    <?php endif ?>
                </form>
                <div class="table-wrap">
                    <table class="admin-table manager-table">
                        <thead>
                            <tr>
                                <th scope="col">Kode / tanggal</th>
                                <th scope="col">Pemohon</th>
                                <th scope="col">Aktivitas</th>
                                <th scope="col">Status saat ini</th>
                                <th scope="col">Waktu aktivitas</th>
                                <th scope="col">Rincian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $application) : ?>
                                <?php
                                    $statusLabels = ['Belum diproses', 'Sedang diproses', 'Selesai'];
                                    $applicationStatus = (int) $application['application_status'];
                                    $statusClass = ['status-pending', 'status-progress', 'status-done'][$applicationStatus] ?? 'status-pending';
                                    $actionLabel = match ($application['action']) {
                                        'application_received' => 'Permohonan diterima',
                                        'baseline_imported' => 'Status awal (data lama)',
                                        default => 'Status diperbarui',
                                    };
                                ?>
                                <tr>
                                    <td data-label="Kode / tanggal">
                                        <strong class="application-code"><?= esc($application['kdpermohonan']) ?></strong>
                                        <small><?= esc(date('d M Y, H:i', strtotime($application['tgl_masuk']))) ?></small>
                                    </td>
                                    <td data-label="Pemohon"><strong><?= esc($application['nama']) ?></strong></td>
                                    <td data-label="Aktivitas">
                                        <strong><?= esc($actionLabel) ?></strong>
                                        <small>Petugas: <?= esc($application['admin_username'] ?? 'Tidak diketahui') ?></small>
                                    </td>
                                    <td data-label="Status saat ini"><span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabels[$applicationStatus] ?? 'Belum diproses') ?></span></td>
                                    <td data-label="Waktu aktivitas"><strong><?= esc(date('d M Y, H:i', strtotime($application['activity_at']))) ?></strong></td>
                                    <td data-label="Rincian"><a class="action-link" href="<?= base_url('manager/application/' . $application['p3_id']) ?>">Riwayat kerja</a></td>
                                </tr>
                            <?php endforeach ?>
                            <?php if ($applications === []) : ?>
                                <tr><td class="empty-state" colspan="6">Belum ada pekerjaan yang cocok dengan filter ini.</td></tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
    <footer class="admin-footer"><div class="site-container">© <?= date('Y') ?> Dinas Pertanahan dan Tata Ruang DIY</div></footer>
</body>
</html>
