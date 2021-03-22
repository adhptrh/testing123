<div id="top_content" data-number-of-options="<?= $number_of_options; ?>"></div>
<div class="row row-xs">
    <div class="col-md">
        <div data-base-url="<?=base_url();?>" class="card create d-none">
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
                <div class="card-header d-flex align-items-center justify-content-between">
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
                </div>
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