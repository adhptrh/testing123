<div class="row mg-b-20">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class='table table-striped'>
                            <tr>
                                <td class="wd-150">Nama Peserta</td>
                                <td>: <strong><?=$data['summary']['name']?></strong></td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: <?=$data['summary']['nisn']?></td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: <?=$data['summary']['grade']?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col">
                        <table class='table table-striped'>
                            <tr>
                                <td class="wd-150">Mata Uji</td>
                                <td>: <?=$data['summary']['study']?></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>: <?=$data['summary']['date']?></td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>: <?=$data['summary']['time']?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="fDetail" class="row d-none">
    <div class="col">
        <div class="card">
            <div class="card-header tx-medium d-flex align-items-center justify-content-between">
                <span id="hContent">Detail Soal dan Jawaban Siswa</span>
                <div class="d-flex align-items-center tx-18">
                    <a href="#" id="bCloseDetail" class="link-03 lh-0"><i class="icon ion-md-close"></i></a>
                </div>
            </div>
            <div class="card-body">
                <p id="iDetail">Detail soal</p>

                <p> <strong> Opsi A </strong></p>
                <p id="iOpsi_A">Opsi B</p>

                <p> <strong> Opsi B </strong></p>
                <p id="iOpsi_B">Opsi B</p>

                <p> <strong> Opsi C </strong></p>
                <p id="iOpsi_C">Opsi C`</p>

                <p> <strong> Opsi D </strong></p>
                <p id="iOpsi_D">Opsi D</p>

                <p> <strong> Opsi E </strong></p>
                <p id="iOpsi_E">Opsi E</p>

                <div>
                    <div class="alert alert-primary" role="alert">Kunci Jawaban : <span id="iKeyword">Opsi D</span>
                    </div>
                    <div class="alert alert-success" role="alert">Jawaban Siswa : <span id="iAnswer">Opsi D</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="fDetails" class="row">
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
                                <th style="width:10%">Aksi</th>
                                <th>No. Soal</th>
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
                                <!-- <td><a href="#modal1" data-toggle="modal" href="#" class="btn btn-sm btn-primary">Lihat Soal</a> -->
                                <td>
                                    <button data-href="<?=base_url('data/exam_results/detail_question/' . $v['id']);?>"
                                        class="btn btn-sm btn-primary bShowQuestion">
                                        Lihat Soal
                                    </button>
                                </td>
                                <td><?=$v['exam_question_detail_id'];?></td>
                                <td><?=$v['keyword'];?></td>
                                <td><?=$benar = ($v['answer'] == 'dubious') ? 'Ragu-ragu' : $v['answer'];?></td>
                                <td><?=$benar = ($v['is_correct'] == 1) ? '<strong>Benar</strong>' : 'Salah';?></td>
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

<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2">Detail Soal dan Jawaban Siswa</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary tx-13" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>