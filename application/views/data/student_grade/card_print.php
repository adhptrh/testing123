<style>
@media print {
    body {
        width: 21cm;
        height: 29.7cm;
        /* font-size: x-small; */
        /* margin: 10mm 10mm 10mm 10mm;  */
        /* change the margins as you want them to be. */
    }
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14;
}

tr.header {
    border-bottom: 1pt solid black;
    padding: 10px;
}

td {
    padding: 3px;
}

.cLeft {
    width: 93px;
}

.cRight {
    width: 170px;
}

.cCenter {
    width: 76px;
}

.column {
    float: left;
    border-style: solid;
    border-width: 2px;
    width: 10cm;
    margin-right: 7px;
    margin-bottom: 10px;
    /* padding: 3px; */
}
</style>
<?php
    // DEBUG
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // die();
?>

<body>
    <div class="row">
        <?php foreach ($data as $k => $v):?>
        <div class="column">
            <table>
                <tr class="header">
                    <td colspan="3">
                        <table>
                            <tr>
                                <td><img src="../../../upload/logo2.png" alt="" height="40px"></td>
                                <td align="center"><strong>KARTU PESERTA UJIAN <br>
                                        UJIAN SEKOLAH BERBASIS KOMPUTER <br>
                                        TAHUN PELAJARAN 2021/2022</strong></td>
                                <td><img src="../../../upload/logo.png" alt="" height="40px"></td>
                            </tr>
                        </table>


                    </td>
                </tr>

                <tr>
                    <td rowspan="5" align="center" class='cLeft'><img src="../../../upload/logo3.png" alt="" height="60px"></td>
                    <td>Nama</td>
                    <td><?= $v['name']; ?></td>
                </tr>

                <tr>
                    <td class='cCenter'>NISN</td>
                    <td class='cRight'><?= $v['nisn']; ?></td>
                </tr>

                <tr>
                    <td>Password</td>
                    <td><?= $v['pass_siswa']; ?></td>
                </tr>

                <tr>
                    <td>Kelas</td>
                    <td><?= $v['grade']; ?></td>
                </tr>

                <tr>
                    <td>Sesi</td>
                    <td><?= $v['order']; ?></td>
                </tr>

                <tr>
                    <td coslpan=''></td>
                    <td coslpan=''></td>
                    <td align='center'>
                        Kepala Sekolah<br><br><br>
                        <strong>ANDRI KARMIDI,M.Pd<br>
                            NIP. 197105171995121001</strong </td>
                </tr>
            </table>
        </div>
        <?php if(($k % 8) == 0) : ?>
        <div style="page-break-before:always;"></div>
        <?php endif; ?>
        <?php endforeach;?>
    </div>
</body>