<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <select id="bPeriod" name="periods" class="custom-select select2">
                                <option></option>
                                <?php foreach ($data['periods'] as $k => $v): ?>
                                <option <?=$v['selected'];?>
                                    value="<?=base_url('data/exam_results/result/') . $v['id'];?>">
                                    <?=$v['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Studi</label>
                            <select id="bStudy" name="studies" class="custom-select select2">
                                <option></option>
                                <?php foreach ($data['studies'] as $k => $v): ?>
                                <option <?=$v['selected'];?>
                                    value="<?=base_url('data/exam_results/result/') . $v['period_id'] . '/' . $v['id'];?>">
                                    <?=$v['exam'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select id="bGrade" name="grades" class="custom-select select2">
                                <option></option>
                                <?php foreach ($data['grades'] as $k => $v): ?>
                                <option <?=$v['selected'];?>
                                    value="<?=base_url('data/exam_results/result/') . $v['period_id'] . '/' . $v['exam_question_id'] . '/' . $v['id'];?>">
                                    <?=$v['grade'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mb-10">
                    <div class="col-md-6">
                        <div class="input-group mg-b-10">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control dtp_cari" placeholder="Cari di sini"
                                aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <a href="<?= $data['button']['href']; ?>"
                            class="btn btn-sm pd-x-15 btn-success btn-uppercase mg-l-5 float-right <?= $data['button']['disabled']; ?>"><i
                                class="fa fa-download"></i> Download</a>
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
                                <th style="width:20%">Aksi</th>
                                <th>Nama</th>
                                <th>Waktu Ujian</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['student_with_score'] as $k => $v): ?>
                            <tr>
                                <td><?=$k++ + 1?></td>
                                <td> <a href="<?= base_url('data/exam_results/detail/') . $v['student_grade_exam_id'] ?>" class="btn btn-sm btn-primary">Detail</a> </td>
                                <td><?=$v['name']?></td>
                                <td><?=$v['date']?></td>
                                <td><?=$v['score']?></td>
                                <td><?= "Benar (" . $v['correct'] . ") - Salah (" . $v['incorrect'] .")";?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>