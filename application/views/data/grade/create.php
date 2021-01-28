<div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
            <?=form_open(base_url('data/grade/save'));?>
                <div
                    class="align-items-center alert alert-warning <?=$hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none'?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?=$this->session->flashdata('create_info_message');?>
                </div>

                <div class="form-group d-none">
                    <label>ID</label>
                    <input name="id" type="text" class="form-control"
                        value="0" readonly>
                </div>

                <div class="form-group">
                    <?php $var = 'period';?>
                    <label>Periode</label>
                    <select name="<?=$var;?>" class="custom-select select2">
                        <option></option>
                        <?php foreach ($period as $k => $v): ?>
                        <option value="<?=$v['id']?>"><?=$v['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                </div>

                <div class="form-group">
                    <?php $var = 'major';?>
                    <label>Jurusan</label>
                    <select id="bmajor" name="<?=$var;?>" class="custom-select select2">
                        <option></option>
                        <?php foreach ($major as $k => $v): ?>
                        <option value="<?=$v['id']?>"><?=$v['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                </div>

                <div class="form-group">
                    <?php $var = 'grade';?>
                    <label>Kelas</label>
                    <select name="<?=$var;?>" id="bgrade" class="custom-select select2">
                        <option></option>
                    </select>
                    <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?=base_url('data/grade')?>" class="btn btn-sm btn-danger" type="button"
                    name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
            <?=form_close();?>
        </div>
    </div>
</div>