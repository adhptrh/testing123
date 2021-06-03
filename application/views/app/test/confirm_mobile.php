<div data-id="<?= $exam_schedule_id; ?>" id="examScheduleID"></div>
<?= form_open('#') . form_close() ?>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 mg-b-20 mg-sm-t-70 mg-sm-l-20">
                <div class="card">
                    <div class="card-header tx-medium">
                        Biodata Peserta
                    </div>
                    <div class="card-body list-group list-group-flush">
                        <div class="row list-group-item d-flex">
                            <div class="col">Nama : <strong><?= $student['name'] ?></strong> </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">NISN : <?= $student['nisn'] ?></div>
                            <div class="col tx-medium"></div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">TTL : -</div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Terdaftar Pada Sesi Ujian : <?= $student['order'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mg-b-20 mg-sm-l-20">
                <div class="card">
                    <div class="card-header tx-medium">
                        Data Mata Uji
                    </div>
                    <div class="card-body list-group list-group-flush">
                        <div class="row list-group-item d-flex">
                            <div class="col">Nama : <strong><?= $data['study'] ?></strong> </div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Jumlah Soal : <?= $data['number_of_exam'] ?></div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Tanggal Ujian : <?= $data['date'] ?></div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Waktu Ujian : <?= $data['start'] . ' s.d ' . $data['finish'] ?></div>
                        </div>
                        <div class="row list-group-item d-flex">
                            <div class="col">Sesi : <?= $data['order'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mg-sm-t-70">
        <div class="card">
            <div class="card-header tx-medium">
                Konfirmasi
            </div>
            <div class="card-body">
                <div class="mg-b-20" id="confirmData">
                    <p>Apakah informasi yang ditampilkan sudah sesuai?</p>
                    <button id="bStartTest" class="btn btn-sm btn-success wd-80">Ya</button>
                    <button id="bConfirmDataNo" class="btn btn-sm btn-danger wd-80">Tidak</button>
                </div>
                <!-- <div class="input-group d-none" id="confirmToken">
                    <hr>
                    <input autocomplete="off" type="text" class="form-control" id="iConfirmToken"
                        placeholder="Masukkan token dan klik konfirmasi">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" id="bConfirmToken">Konfirmasi</button>
                    </div>
                </div> -->

                <!-- <div class="d-none" id="confirmCountdown">
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

                <button class="btn btn-primary btn-block d-none" id="bStartTest">Mulai Ujian</button> -->
            </div>
        </div>
    </div>
</div>