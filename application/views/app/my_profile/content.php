<div class="row row-xs">
    <div class="col-md-6">
        <?= form_open(base_url('app/my_profile/update')); ?>
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Nama</label>
                    <?php $var = 'name'; ?>
                    <input name="<?= $var; ?>" type="text"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : $name; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('app/my_profile/change_password/'); ?>" class="btn btn-sm btn-warning">Ganti Password</a>
                <button class="btn btn-sm btn-primary float-right" type="submit">Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>