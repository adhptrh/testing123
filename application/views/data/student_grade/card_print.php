<style>
/* @page {
   size: 7in 9.25in;
   margin: 27mm 16mm 27mm 16mm;
} */

table {
    width: 340px;
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
    /* width: 50%; */
    margin-right: 20px;
    margin-bottom: 20px;
}

/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
</style>
<?php
    // DEBUG
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    // die();
?>

<div class="row">
    <?php foreach ($data as $k => $v):?>
    <div class="column">
        <table border="1">
            <tr>
                <td colspan="3">Logo + Kop + Logo</td>
            </tr>

            <tr>
                <td rowspan="5" class='cLeft'>Photo</td>
                <td>Nama</td>
                <td>: <?= $v['name']; ?></td>
            </tr>

            <tr>
                <td class='cCenter'>NISN</td>
                <td class='cRight'>: <?= $v['nisn']; ?></td>
            </tr>

            <tr>
                <td>Password</td>
                <td>: <?= $v['pass_siswa']; ?></td>
            </tr>

            <tr>
                <td>Kelas</td>
                <td>: <?= $v['grade']; ?></td>
            </tr>

            <tr>
                <td>Sesi</td>
                <td>: <?= $v['order']; ?></td>
            </tr>

            <tr>
                <td coslpan='3'>ttd</td>
            </tr>
        </table>
    </div>
    <?php endforeach;?>
</div>