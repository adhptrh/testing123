<div id="exam_schedule_id"></div>
<?=form_open('#') . form_close();?>
<div class="card mg-b-10">
    <div class="card-body">
        <div class="row">
            <div class="col">Mata Uji : <span class="tx-medium" id="tStudy">
                    <?= $study; ?>
                </span></div>
            <div class="col text-center">Sesi : <span class="tx-medium" id="tOrder">
                    <?= $order; ?>
                </span></div>
            <div class="col text-right">Sisa Waktu : <span class="tx-medium" id="tTimeLeft">
                    <div class="spinner-border spinner-border-sm"></div>
                </span></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col">
                <div class="align-items-center alert alert-warning">
                    <i data-feather="alert-circle" class="mg-r-10"></i> <span id="tNotif">Ini notifikasi</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header tx-medium">
                Detail Soal
            </div>
            <div id="tExamDetail" class="card-body">
                Butir Soal
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
                        echo '<button type="button" class="btn btn-sm btn-outline-primary mg-r-5 mg-b-5 w-15">' . $no = ($i < 9) ? '0' . ($i + 1) : ($i + 1) . '</button>';
                    }
                ?>
            </div>
            <div class="card-footer">
                <button class="btn btn-sm btn-success btn-block" type="" name="">Selesai</button>
            </div>
        </div>
    </div>
</div>