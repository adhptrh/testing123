<div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <?= form_open(base_url('app/exam_question/save')); ?>
                <div
                    class="align-items-center alert alert-warning <?= $hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none' ?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?= $this->session->flashdata('create_info_message');  ?>
                </div>
                <div class="form-group">
                    <?php $var = 'period'; ?>
                    <label>Periode</label>
                    <select name="<?= $var; ?>"
                        class="custom-select select2 <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>">
                        <option></option>
                        <?php foreach ($period as $k => $v): ?>
                        
                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>

                        <?php endforeach; ?>
                    </select>
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
                <div class="form-group">
                    <?php $var = 'study'; ?>
                    <label>Mata Uji</label>
                    <select name="<?= $var; ?>"
                        class="custom-select select2 <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>">
                        <option></option>
                        <?php foreach ($study as $k => $v): ?>

                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>

                        <?php endforeach; ?>
                    </select>
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('app/exam_question') ?>" class="btn btn-sm btn-danger" type="button"
                    name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
</div>