<div class="row row-xs">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mg-b-10">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Cari di sini" aria-label="Username"
                                aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <?php if (!$data['student']): ?>
                    <div class="col-md-6 d-none d-md-block">
                        <a href="<?=base_url('app/exam_schedule/create');?>"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-plus"></i> Tambah</a>
                    </div>
                    <?php endif;?>
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
                                <th>Soal</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Sesi</th>
                                <th>Waktu</th>
                                <?=$token = (!$data['student']) ? '<th>Token</th>' : '';?>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['exam_schedule'] as $k => $v): ?>
                            <?php if (
                                date("d-m-Y", $v['time_server_now']) == $v['date']
                                &&
                                date("H:i:s", ($v['time_server_now'] + 900 )) >= $v['start']
                                &&
                                date("H:i:s", $v['time_server_now']) <= $v['finish']
                            ): ?>

                            <tr>
                                <td><?=$k++ + 1?></td>
                                <td>
                                    <?php if (!$data['student']): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Pilih
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="<?=base_url('app/exam_schedule/edit/' . $v['id']);?>"><i
                                                    class="fas fa-edit"></i> Edit</a>
                                            <a class="dropdown-item hapus" href="#"
                                                data-href="<?=base_url('app/exam_schedule/delete/' . $v['id']);?>"><i
                                                    class="fas fa-trash"></i> Hapus</a>
                                            <a class="dropdown-item token" href="#" data-href=""><i
                                                    class="fas fa-key"></i> Token</a>
                                            <a class="dropdown-item token"
                                                href="<?=base_url('app/test/main/' . $v['id']) . '/123';?>"
                                                data-href=""><i class="fas fa-check-square"></i> Ikuti Ujian</a>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <a class="btn btn-sm btn-success" href="<?php echo base_url('app/test/confirm/' . $v['id']); ?>"><i class="fas fa-edit"></i> Ikuti
                                        Ujian</a>
                                    <?php endif;?>

                                </td>
                                <td><?=$v['study']?></td>
                                <td><?=$v['grade']?></td>
                                <td><?=$v['date']?></td>
                                <td><?=$v['order']?></td>
                                <td><?=$v['start'] . ' - ' . $v['finish']?></td>
                                <?=$token = (!$data['student']) ? '<td>-</td>' : '';?>
                                <td><?=$v['created_by'] . '<br><small>' . $v['created_at'] . '</small>'?></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>