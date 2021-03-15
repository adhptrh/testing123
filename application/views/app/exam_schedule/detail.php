<div class="row row-xs">
    <div class="col-md-12">
        <div class="card mg-b-20">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class='table table-striped'>
                            <tr>
                                <td class="wd-150">Nama Ujian</td>
                                <td><strong><?=$summary['study']?></strong></td>
                            </tr>
                            <tr>
                                <td class="wd-150">Jumlah Butir Soal</td>
                                <td><strong><?=$summary['number_of_exam']?></strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal Ujian</td>
                                <td><strong><?=$summary['date']?></strong></td>
                            </tr>
                            <tr>
                                <td>Waktu Ujian</td>
                                <td><strong><?=$summary['start'] . ' - ' . $summary['finish'] . ' (' . $summary['durasi'] . ')'?></strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col">
                        <table class='table table-striped'>
                            <tr>
                                <td class="wd-200">Kelas</td>
                                <td><strong><?=$summary['grade']?></strong></td>
                            </tr>
                            <tr>
                                <td>Jumlah Siswa Login</td>
                                <td><strong><?= $start_test_info['is_login_count']; ?>
                                        <small>[<?= $start_test_info['is_login_count'] - $start_test_info['is_finish']; ?>
                                            Belum Selesai, <?= $start_test_info['is_finish']; ?> Selesai]</small>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td>Jumlah Siswa Belum Login</td>
                                <td><strong><?= $start_test_info['isnot_login_count']; ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mg-b-10">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control dtp_cari" placeholder="Cari di sini" aria-label="Username"
                                aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="col-md-6 d-none d-md-block">
                        <a href="<?= base_url('app/exam_schedule/attendees/') . $summary['id'] ?>" target="_blank"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-print"></i> Cetak Daftar Hadir</a>
                    </div>
                </div>

                <div class="alert alert-info <?=$hide = ($this->session->flashdata('message')) ? '' : 'd-none'?>">
                    <?=$pesan = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''?>
                </div>

                <div class="table-responsive">
                    <table class="dtable table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aksi</th>
                                <th>Nama Siswa</th>
                                <th>Soal Terjawab</th>
                                <th>Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $k => $v): ?>
                            <tr>
                                <td><?=$k++ + 1?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Pilih
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item bReset" href="#"
                                                data-href="<?=base_url('app/test/reset_by_operator/' . $v['exam_schedule_id'] . '/' . $v['student_grade_exam_id']);?>"><i
                                                    class="fas fa-trash"></i> Hapus Ujian</a>
                                            <?php if ($v['finish_time'] == null): ?>
                                            <a class="dropdown-item bSetFinish" href="#"
                                                data-href="<?=base_url('app/test/closing_by_operator/' . $v['exam_schedule_id'] . '/' . $v['student_grade_exam_id']);?>"><i
                                                    class="fas fa-clock"></i> Set Selesai</a>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                if($v['exam_schedule_id'] == null){
                                    echo $v['name'] . '<br><span class="badge badge-danger">Belum Mulai Ujian</span>';
                                }else{
                                    echo $v['name'] . $status = ($v['finish_time'] != null) ? '<br><span class="badge badge-success">Selesai</span>' : '<br><span class="badge badge-warning">Belum Selesai</span>';
                                }
                                ?>
                                </td>
                                <td>
                                    <?php
                                        if ($v['exam_schedule_id'] == null) {
                                            echo '-';
                                        } else {
                                            $total_soal = $v['correct'] + $v['incorrect'] + $v['numbers_before_answer'];
                                            $terjawab = $v['correct'] + $v['incorrect'];
                                            echo (($terjawab / $total_soal) * 100) . '%';
                                        }
                                    ?>
                                </td>
                                <td><?=$v['updated_at'];?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>