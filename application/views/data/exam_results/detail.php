<div class="row mg-b-20">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class='table table-striped'>
                            <tr>
                                <td class="wd-150">Nama Peserta</td>
                                <td>: <strong><?= $data['summary']['name'] ?></strong></td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: <?= $data['summary']['nisn'] ?></td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: <?= $data['summary']['grade'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col">
                        <table class='table table-striped'>
                            <tr>
                                <td class="wd-150">Mata Uji</td>
                                <td>: <?= $data['summary']['study'] ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>: <?= $data['summary']['date'] ?></td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>: <?= $data['summary']['time'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
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
                        <a href="#" class="d-none btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-download"></i> Download</a>
                        <button id="letMeBack" href="#"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-arrow-left"></i> Kembali</button>
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
                                <th>ID Soal</th>
                                <th>Kunci Jawaban</th>
                                <th>Jawaban Siswa</th>
                                <th>Benar/Salah</th>
                                <th>Waktu Jawab</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $k => $v): ?>
                            <tr>
                                <td><?=$k++ + 1?></td>
                                <td><a href="#" class="btn btn-sm btn-primary">Lihat Soal</a></td>
                                <td><?= $v['exam_question_detail_id']; ?></td>
                                <td><?= $v['keyword']; ?></td>
                                <td><?= $benar = ($v['answer'] == 'dubious') ? 'Ragu-ragu' : $v['answer']; ?></td>
                                <td><?= $benar = ($v['is_correct'] == 1) ? '<strong>Benar</strong>' : 'Salah'; ?></td>
                                <td><?= $v['updated_at']; ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>