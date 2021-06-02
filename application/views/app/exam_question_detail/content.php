<div id="top_content" data-number-of-options="<?= $number_of_options; ?>"></div>
<div class="row row-xs">
    <div class="col-md">
        <div id="baseURL" data-value="<?=base_url();?>" class="card create d-none">
            form create here
        </div>

        <div class="list">
            <div class="row">
                <div class="col-md">
                    <div class="alert alert-info d-none" id="falert">
                        <i class="fa fa-info-circle"></i>
                        <span id="malert"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card">
                <table id="tSummary" data-id="<?=$exam_question['id']?>" class="table">
                    <thead class="thead-primary">
                        <tr>
                            <th colspan="2">Ringkasan Informasi Soal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="width:20%">
                            <td>Mata Ujian</td>
                            <td><?=$exam_question['exam']?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Soal</td>
                            <td><?=$exam_question['jsoal']?></td>
                        </tr>
                        <tr>
                            <td>Total Durasi Ujian</td>
                            <td>
                                <span id="tDuration">menghitung ...</span>
                                <br>
                                <small>
                                    <i class="fa fa-info-circle"></i> Jika ujian dilaksanakan dengan motode distrubusi waktu per butir soal
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td>Aksi</td>
                            <td>
                                <a href="<?=base_url('app/exam_question');?>"
                                    class="btn btn-xs pd-x-15 btn-secondary btn-uppercase mg-l-5"><i
                                        class="fa fa-arrow-left"></i>
                                    Daftar Mata Uji</a>
                                <a href="#" data-id="<?=$id?>"
                                    data-href="<?=base_url('app/exam_question_detail/create/' . $id);?>"
                                    class="add btn btn-xs pd-x-15 btn-secondary btn-uppercase mg-l-5"><i
                                        class="fa fa-plus"></i>
                                    Tambah</a>
                                <a href="<?=base_url('app/exam_question_detail/create_from_another_period/' . $id);?>"
                                    class="btn btn-xs pd-x-15 btn-secondary btn-uppercase mg-l-5"><i
                                        class="fa fa-clock"></i> Import Dari Periode Lain</a>
                                <a href="<?=base_url('app/exam_question_detail/create_excel/' . $id);?>"
                                    class="btn btn-xs pd-x-15 btn-secondary btn-uppercase mg-l-5"><i
                                        class="fa fa-download"></i> Import Excel
                                    Xls</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mg-t-10">Mata Uji : <?=$exam_question['exam']?> (<span
                            id="jsoal"><?=$exam_question['jsoal']?></span> butir
                        soal)</h6>
                    <div class=d-flex align-items-center tx-18">
                        <a href="<?=base_url('app/exam_question');?>"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-arrow-left"></i>
                            Daftar Mata Uji</a>
                        <a href="#" data-id="<?=$id?>"
                            data-href="<?=base_url('app/exam_question_detail/create/' . $id);?>"
                            class="add btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-plus"></i>
                            Tambah</a>
                        <a href="<?=base_url('app/exam_question_detail/create_from_another_period/' . $id);?>"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-clock"></i> Import Dari Periode Lain</a>
                        <a href="<?=base_url('app/exam_question_detail/create_excel/' . $id);?>"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-download"></i> Import Excel
                            Xls</a>
                    </div>
                </div> -->
            </div>

            <div class="card d-none">
                <div id="dConverter"></div>
            </div>

            <div class="" id="eq_list" data-token="<?=$token;?>">
                <br>
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <div class="spinner-grow spinner-grow-sm" role="status"></div>
                <span>memuat soal ...</span>
            </div>
        </div>
    </div>
</div>