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
                    <div class="col-md-6 d-none d-md-block">
                        <a href="<?= base_url('app/exam_question/create'); ?>"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>

                <div class="alert alert-info <?= $hide = ($this->session->flashdata('message')) ? '' : 'd-none' ?>">
                    <?= $pesan = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
                </div>


                <div class="table-responsive">
                    <table class="dtable table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width:20%">Aksi</th>
                                <th>Periode</th>
                                <th>Mata Uji</th>
                                <th>Soal Tersedia</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $k => $v): ?>
                            <tr>
                                <td><?= $k++ + 1 ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Pilih
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="<?= base_url('app/exam_question_detail/list/' . $v['id']); ?>"><i
                                                    class="fas fa-list"></i> Kelola Butir Soal</a>
                                            <a class="dropdown-item"
                                                href="<?= base_url('app/exam_question/edit/' . $v['id']); ?>"><i
                                                    class="fas fa-edit"></i> Edit</a>
                                            <a class="dropdown-item hapus" href="#"
                                                data-href="<?= base_url('app/exam_question/delete/' . $v['id']); ?>"><i
                                                    class="fas fa-trash"></i> Hapus</a>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $v['period']?></td>
                                <td><?= $v['studi']?></td>
                                <td>100</td>
                                <td><?= $v['created_by'] . '<br><small>'. $v['created_at'] .'</small>' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>