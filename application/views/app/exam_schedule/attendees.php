<style>
body {
    font-size: 14px;
}

.center {
    text-align: center;
}

.pengawas {
    width: 30%;
}

.username {
    width: 15%;
}

.note {
    width: 15%;
}

.ttd {
    width: 10%;
}

.ttd-number {
    font-size: 10px;
}

.no {
    width: 5%;
}

.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
}

table.header {
    width: 100%;
    margin-bottom: 20px;
}

table.header td.col-1 {
    width: 15%;
}

table.header td.col-2 {
    width: 35%;
}

table.header td.col-3 {
    width: 15%;
}

table.header td.col-4 {
    width: 35%;
}

table.content {
    width: 100%;
    border: 1px solid black;
    border-collapse: collapse;
}

table.content th {
    border: 1px solid black;
    border-collapse: collapse;
}

table.content td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
    font-size: 12px;
}

table.footer {
    width: 100%;
    margin-top: 20px;
}
</style>
<?php foreach ($rooms as $k => $v): ?>

<div class='title'>
    DAFTAR HADIR PESERTA UJIAN <br />
    PERIODE <?= strtoupper($summary['period_name']); ?>
</div>

<table class='header'>
    <tr>
        <td class='col-1'>Mata Pelajaran</td>
        <td class='col-2'>: <?=$summary['study']?></td>
        <td class='col-3'>Tanggal</td>
        <td class='col-5'>: <?=$summary['date']?></td>
    </tr>
    <tr>
        <td>Ruang</td>
        <td>: <?= $v ?></td>
        <td>Sesi</td>
        <td>: <?= $summary['order']; ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Waktu</td>
        <td>: <?=$summary['start'] . ' - ' . $summary['finish']; ?></td>
    </tr>
</table>

<table class='content'>
    <tr>
        <th class="no">No</th>
        <th class="username">Username</th>
        <th>Nama Peserta</th>
        <th colspan="2">Tanda Tangan</th>
        <th class="note">Keterangan</th>
    </tr>
    <?php foreach ($students as $k1 => $v1): ?>
    <?php if ($v1['room'] == $v): ?>
    <tr>
        <td class="center"><?= $k1+1 ?></td>
        <td class="center"><?= $v1['nisn'] ?></td>
        <td><?= $v1['name'] ?></td>
        <td class="ttd"> <span class="ttd-number"><?= $no = ($k1 % 2 == 0) ? $k1+1 : '' ?></span> </td>
        <td class="ttd"> <span class="ttd-number"><?= $no = ($k1 % 2 == 0) ? '' : $k1+1 ?></span> </td>
        <td></td>
    </tr>
    <?php endif;?>
    <?php endforeach; ?>
</table>

<table class="footer">
    <tr>
        <td>Jumlah Peserta yang Seharusnya Hadir</td>
        <td>: _____ orang</td>
        <td class="pengawas" rowspan="5">
            Pengawas,
            <br />
            <br />
            <br />
            <br />
            (__________________________)
            <br />
            NIP. -
        </td>
    </tr>
    <tr>
        <td>Jumlah Peserta yang Tidak Hadir</td>
        <td>: _____ orang</td>
    </tr>
    <tr>
        <td>Jumlah Peserta Hadir</td>
        <td>: _____ orang</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

<?php if ($k < (count($rooms) - 1)): ?>
<div style="page-break-before:always;"></div>
<?php endif;?>
<?php endforeach; ?>