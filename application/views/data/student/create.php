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

                <div class="form-group d-none">
                    <label>ID</label>
                    <input name="id" type="text" class="form-control" value="0" readonly>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <?php $var = 'name'; ?>
                    <input name="<?= $var; ?>" type="text"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : ''; ?>">
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

            </div>
            <div class="card-footer">
                <a href="<?= base_url('data/student') ?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>