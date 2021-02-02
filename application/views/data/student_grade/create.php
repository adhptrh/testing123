<?=form_open(base_url('data/student_grade/save'));?>

<div class="card">
    <div class="card-header tx-medium">
        Kelas : <?= $grade_period['kelas'] ?> <small>[Periode : <?= $grade_period['periode'] ?>]</small>
    </div>
    <div class="card-body">
        <div class="form-group d-none">
            <label>Grade Periode ID</label>
            <input name="id" type="text" class="form-control"
                value="<?=$isi = (isset($old['grade_period_id'])) ? $old['grade_period_id'] : $grade_period_id;?>"
                readonly>
        </div>
        <div class="card-group">
            <div class="card">
                <!-- <div class="card-header">
                    <h6>Daftar Siswa pada kelas Ini</h6>
                    <small>Hilangkan centang siswa jika ingin mengeluarkan dari kelas ini</small>
                </div> -->
                <div class="card-body">
                    <div class="marker marker-ribbon marker-info pos-absolute t-10 l-0">Daftar Siswa pada kelas Ini
                    </div>
                    <div class="row mg-t-25">
                        <?php foreach ($student_current as $k => $v):?>
                        <div class="col-md-6 mg-b-10">
                            <div class="custom-control custom-checkbox">
                                <input name="studentCurrent[]" value="<?= $v['id'] ?>" type="checkbox" class="custom-control-input"
                                    id="<?= $v['id'] ?>">
                                <label class="custom-control-label" for="<?= $v['id'] ?>">
                                    <?= $v['name'] . " <br><small>NISN : " . $v['nisn'] . "</small>" ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="divider-text divider-vertical">and</div>
            <div class="card">
                <div class="card-body">
                    <div class="marker marker-ribbon marker-info pos-absolute t-10 l-0">Daftar Siswa yang belum
                        memiliki kelas</div>
                    <div class="row mg-t-25">
                        <?php foreach ($student_ready as $k => $v):?>
                        <div class="col-md-6 mg-b-10">
                            <div class="custom-control custom-checkbox">
                                <input name="studentready[]" value="<?= $v['id'] ?>" type="checkbox" class="custom-control-input"
                                    id="<?= $v['id'] ?>">
                                <label class="custom-control-label" for="<?= $v['id'] ?>">
                                    <?= $v['name'] . " <br><small>NISN : " . $v['nisn'] . "</small>" ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card-footer">
        <a href="<?= base_url('data/grade_period') ?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
        <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
    </div>
</div>
<?=form_close();?>