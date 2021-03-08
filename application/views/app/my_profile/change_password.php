<div class="row row-xs">
    <div class="col-md-6">
        <?= form_open(base_url('app/my_profile/update_password')); ?>
        <div class="card">
            <div class="card-body">
                <div
                    class="align-items-center alert alert-warning <?=$hide = ($this->session->flashdata('message')) ? '' : 'd-none'?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?=$this->session->flashdata('message');?>
                </div>

                <div class="form-group">
                    <label>Password Lama</label>
                    <?php $var = 'password_old'; ?>
                    <input name="<?= $var; ?>" type="password"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : ''; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <?php $var = 'password_new'; ?>
                    <input name="<?= $var; ?>" type="password"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : ''; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
                <div class="form-group">
                    <label>Password Baru Konfirmasi</label>
                    <?php $var = 'password_new_confirmation'; ?>
                    <input name="<?= $var; ?>" type="password"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?= $isi = (isset($old[$var])) ? $old[$var] : ''; ?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>