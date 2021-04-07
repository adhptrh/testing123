<div data-value="<?=$exam_schedule_id;?>" id="examSchedule"></div>
<div data-value="<?=$student_grade_exam_id;?>" id="studentGradeExam"></div>
<div data-value="<?=$exam_question_id;?>" id="examQuestion"></div>
<div data-value="<?=$this->config->item('image_url');?>" id="baseURL"></div>
<?=form_open('#') . form_close();?>
<div class="card mg-b-10">
    <div class="card-body">
        <div class="row">
            <div class="col">Mata Uji : <span class="tx-medium" id="tStudy">
                    <?=$study;?>
                </span></div>
            <div class="col text-center">Sesi : <span class="tx-medium" id="tOrder">
                    <?=$order;?>
                </span></div>
            <div class="col text-right">Sisa Waktu : <span class="tx-medium" id="tTimeLeft">
                    <div class="spinner-border spinner-border-sm"></div>
                </span></div>
        </div>
    </div>
</div>

<div id="fTest" class="row">
    <div class="col-md-8">
        <div id="fNotif" class="row d-none">
            <div class="col">
                <div class="align-items-center alert alert-warning">
                    <i data-feather="alert-circle" class="mg-r-10"></i> <span id="tNotif">Ini notifikasi</span>
                </div>
            </div>
        </div>
        <div id="fNotifWarningTimeOut" class="row d-none">
            <div class="col">
                <div class="align-items-center alert alert-danger">
                    <i data-feather="alert-circle" class="mg-r-10"></i> <span id="tNotifWarningTimeOut">Ini
                        notifikasi</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header tx-medium">
                Detail Soal No. <span id="noExam"></span>
            </div>
            <div class="card-body">
                <div class="d-none" id="loadIndicator">
                    <div class="spinner-border spinner-border-sm"></div> Memuat butir soal ...
                </div>
                
                <div class="card d-none">
                    <div id="converter"></div>
                </div>

                <div id="fExamDetail" class="d-none">
                    <p id="tExamDetail"></p>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_a" type="radio" id="ftOpsiA" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiA">A</label>
                        <div id="tOpsiA"></div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_b" type="radio" id="ftOpsiB" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiB">B</label>
                        <div id="tOpsiB"></div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_c" type="radio" id="ftOpsiC" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiC">C</label>
                        <div id="tOpsiC"></div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_d" type="radio" id="ftOpsiD" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiD">D</label>
                        <div id="tOpsiD"></div>
                    </div>

                    <div id="fOpsiE" class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_e" type="radio" id="ftOpsiE" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiE">E</label>
                        <div id="tOpsiE"></div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="dubious" type="radio" id="ftOpsiX" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiX">Ragu-Ragu</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button id="bPrev" class="btn btn-sm btn-primary" type="" name=""><i class="fa fa-backward"></i>
                    Sebelumnya</button>
                <button id="bNext" class="btn btn-sm btn-primary float-right" type="" name="">Selanjutnya <i
                        class="fa fa-forward"></i></button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header tx-medium">
                Daftar Soal
            </div>
            <div id="tListOfNumber" class="card-body text-center">
                <?php
for ($i = 0; $i < $number_of_exam; $i++) {
    // $no = $i + 1;
    if ($i == ($number_of_exam - 1)) {
        echo '<button data-is-last-exam-item="1" data-answer="0" data-exam-item="0" data-exam-question-detail="0" type="button" class="bExamItems btn btn-sm btn-outline-secondary mg-r-5 mg-b-5 w-15">' . $no = ($i < 9) ? '0' . ($i + 1) : ($i + 1) . '</button>';
    } else {
        echo '<button data-is-last-exam-item="0" data-answer="0" data-exam-item="0" data-exam-question-detail="0" type="button" class="bExamItems btn btn-sm btn-outline-secondary mg-r-5 mg-b-5 w-15">' . $no = ($i < 9) ? '0' . ($i + 1) : ($i + 1) . '</button>';
    }
}
?>
            </div>
            <div id="fBFinish" class="card-footer d-none">
                <button id="bFinish" class="btn btn-sm btn-success btn-block" type="" name="">Selesai</button>
            </div>
        </div>
    </div>
</div>