<div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <?= form_open(base_url('data/student/save')); ?>
                <div
                    class="alert alert-warning align-items-center <?= $hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none' ?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?= $this->session->flashdata('create_info_message');  ?>
                </div>

                <div class="divider-text">Biodata</div>

                <div class="form-group">
                    <label>Nama</label>
                    <?php $var = 'name'; ?>
                    <input name="<?= $var; ?>" type="text"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : ''; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label><br/>
                    <button data-value="1" type="button" class="btn btn-outline-success btn-sm bSelect bGender">Laki-laki</button>
                    <button data-value="2" type="button" class="btn btn-outline-success btn-sm bSelect bGender">Perempuan</button>
                    <?php $var = 'gender'; ?>
                    <input id="iGender" name="<?= $var; ?>" type="text"
                        class="d-none form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : '0'; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>

                <div class="form-group">
                    <label>NISN</label>
                    <?php $var = 'nisn'; ?>
                    <input name="<?= $var; ?>" type="text"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : ''; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>

                <div class="divider-text">Sekolah Asal</div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input name="not_riau_province" type="checkbox" class="custom-control-input"
                            id="notRiauProvince">
                        <label class="custom-control-label" for="notRiauProvince">Luar Provinsi Riau</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Kab/Kota Asal (Provinsi Riau)</label>
                    <?php $var = 'regency'; ?>
                    <select class="custom-select select2" name="regency" id="regency">
                        <option></option>
                        <?php foreach ($regency as $k => $v): ?>
                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                        <?php endforeach;?>
                    </select>
                </div>

            </div>
            <div class="card-footer">
                <a href="<?= base_url('data/student') ?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>