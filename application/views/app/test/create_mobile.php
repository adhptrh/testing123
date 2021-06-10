<div data-value="<?=$exam_schedule_id;?>" id="examSchedule"></div>
<div data-value="<?=$exam_question_id;?>" id="examQuestion"></div>
<div data-value="<?=$student_grade_exam_id;?>" id="studentGradeExam"></div>
<div data-value="<?=$this->config->item('image_url');?>" id="baseURL"></div>
<?=form_open('#') . form_close();?>
<div class="row">
    <div class="col-md-12 mg-sm-t-60">
        <button id="bIzin" class="btn btn-sm btn-warning btn-block d-none"> Ajukan Izin</button>
        <div id="fNotifCountDownExamShow" class="align-items-center alert alert-warning d-none">
            <i data-feather="alert-circle" class="mg-r-10"></i> <span id="tNotifCountDownExamShow">sedang
                memuat...</span>
        </div>

        <div id="fNotifNext" class="align-items-center text-center alert alert-warning d-none">
            <i data-feather="alert-circle" class="mg-r-10 mg-b-3"></i>
            <br>
            Waktu mengerjakan soal nomor <span id="tNo">0</span> telah berakhir
        </div>
        <button style="margin-top:-15px" id="bNext" class="btn btn-sm btn-success btn-block d-none"> Soal
            Berikutnya</button>

        <div id="fNotifCountDownExamThinking" class="align-items-center alert alert-warning d-none">
            <i data-feather="alert-circle" class="mg-r-10"></i> <span>Soal No. <span class="tx-bold"
                    id="tNoExamHeader">0</span>, Waktu Anda : <strong id="tTimeleft">sedang memuat
                    ...</strong></span>
        </div>
            <button style="margin-top:-15px" id="bNextForce" class="btn btn-sm btn-danger float-right btn-block"><i class="fas fa-hourglass-end"></i>
                Abaikan waktu tunggu</button>

    </div>
    <div id="fExamDetail" class="col-md-12 d-none">
        <div class="card">
            <div class="card-body">
                <div class="d-none" id="loadIndicator">
                    <div class="spinner-border spinner-border-sm"></div> <span id="tLoadIndicator"></span>
                </div>

                <div class="card d-none">
                    <div id="converter"></div>
                </div>

                <div class="">
                    <p id="tExamDetail">Exam Text</p>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_a" type="radio" id="ftOpsiA" name="bOpsi"
                            class="custom-control-input bOpsi">
                        <label class="custom-control-label tx-medium" for="ftOpsiA">A</label>
                        <div id="tOpsiA">Exam Option A</div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_b" type="radio" id="ftOpsiB" name="bOpsi"
                            class="custom-control-input bOpsi">
                        <label class="custom-control-label tx-medium" for="ftOpsiB">B</label>
                        <div id="tOpsiB">Exam Option B</div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_c" type="radio" id="ftOpsiC" name="bOpsi"
                            class="custom-control-input bOpsi">
                        <label class="custom-control-label tx-medium" for="ftOpsiC">C</label>
                        <div id="tOpsiC">Exam Option C</div>
                    </div>

                    <div class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_d" type="radio" id="ftOpsiD" name="bOpsi"
                            class="custom-control-input bOpsi">
                        <label class="custom-control-label tx-medium" for="ftOpsiD">D</label>
                        <div id="tOpsiD">Exam Option D</div>
                    </div>

                    <div id="fOpsiE" class="custom-control custom-radio mg-b-20">
                        <input data-value="opsi_e" type="radio" id="ftOpsiE" name="bOpsi"
                            class="custom-control-input bOpsi">
                        <label class="custom-control-label tx-medium" for="ftOpsiE">E</label>
                        <div id="tOpsiE">Exam Option E</div>
                    </div>

                    <!-- <div class="custom-control custom-radio mg-b-20">
                        <input data-value="dubious" type="radio" id="ftOpsiX" name="bOpsi" class="custom-control-input">
                        <label class="custom-control-label tx-medium" for="ftOpsiX">Ragu-Ragu</label>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div id="notifFinishTime" class="col-md-12 d-none">
        <div class="card">
            <div class="card-body">
                <p id="tFinishTime"></p>
            </div>
        </div>
    </div>
</div>