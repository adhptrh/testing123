<div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <?=form_open(base_url('data/student/update'));?>
                <div
                    class="align-items-center alert alert-warning <?=$hide = ($this->session->flashdata('update_info_message')) ? '' : 'd-none'?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?=$this->session->flashdata('update_info_message');?>
                </div>

                <div class="form-group d-none">
                    <label>ID</label>
                    <input name="id" type="text" class="form-control"
                        value="<?=$isi = (isset($old['id'])) ? $old['id'] : $data['id'];?>" readonly>
                </div>

                <div class="form-group">
                    <?php $var = 'period'; ?>
                    <label>Periode</label>
                    <select id="bperiod" name="<?= $var; ?>" class="custom-select select2 <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>">
                        <option></option>
                        <?php foreach ($period as $k => $v): ?>
                        <option <?=$v['selected']?> value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>

                <div class="form-group">
                    <?php $var = 'grade'; ?>
                    <label>Kelas</label>
                    <select id="bgrade" name="<?= $var; ?>" class="custom-select select2 <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>">
                        <option></option>
                        <?php foreach ($grade as $k => $v): ?>
                        <option <?=$v['selected']?> value="<?= $v['id'] ?>"><?= $v['kelas'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <?php $var = 'name';?>
                    <input name="<?=$var;?>" type="text"
                        class="form-control <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>"
                        value="<?=$isi = (isset($old[$var])) ? $old[$var] : $data[$var];?>">
                    <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                </div>

                <div class="form-group">
                    <label>NISN</label>
                    <?php $var = 'nisn'; ?>
                    <input name="<?= $var; ?>" type="text"
                        class="form-control <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>"
                        value="<?=$isi = (isset($old[$var])) ? $old[$var] : $data[$var];?>">
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>
            </div>

            <div class="card-footer">
                <a href="<?=base_url('data/student')?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
            <?=form_close();?>
        </div>
    </div>
</div>