<div data-id="<?= $exam_schedule_id; ?>" id="examScheduleID"></div>
<?= form_open('#') . form_close() ?>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 mg-b-20">
                <div class="card">
                    <div class="card-header tx-medium">
                        Biodata Peserta
                    </div>
                    <div class="card-body list-group list-group-flush">
                        <div class="row list-group-item d-flex">
                            <div class="col">Nama</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">NISN</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Tempat, Tanggal Lahir</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Terdaftar Pada Sesi Ujian</div>
                            <div class="col">: </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header tx-medium">
                        Data Ujian yang Akan Diikuti
                    </div>
                    <div class="card-body list-group list-group-flush">
                        <div class="row list-group-item d-flex">
                            <div class="col">Mata Pelajaran</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Jumlah Soal</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Tanggal Ujian</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Waktu Ujian</div>
                            <div class="col">: </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Sesi</div>
                            <div class="col">: </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header tx-medium">
                Konfirmasi
            </div>
            <div class="card-body">
                <div class="mg-b-20" id="confirmData">
                    <p>Apakah data disamping sudah sesuai?</p>
                    <button id="bConfirmDataYes" class="btn btn-sm btn-success wd-80">Ya</button>
                    <button id="bConfirmDataNo" class="btn btn-sm btn-danger wd-80">Tidak</button>
                </div>
                <div class="input-group d-none" id="confirmToken">
                    <hr>
                    <input type="text" class="form-control" id="iConfirmToken"
                        placeholder="Masukkan token dan klik konfirmasi">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" id="bConfirmToken">Konfirmasi</button>
                    </div>
                </div>

                <div class="d-none" id="confirmCountdown">
                    <div class="alert alert-primary d-flex align-items-center mg-t-20" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> Ujian akan dimulai dalam &nbsp;
                        <div id="tTimeLeft">00:00:00</div>
                    </div>
                </div>

                <div class="d-none" id="confirmInfo">
                    <div class="alert alert-primary d-flex align-items-center mg-t-20 d-none" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> Ujian sudah tersedia, silahkan klik "Mulai
                        Ujian".
                    </div>
                </div>

                <button class="btn btn-primary btn-block d-none" id="bStartTest">Mulai Ujian</button>
            </div>
        </div>
    </div>
</div>