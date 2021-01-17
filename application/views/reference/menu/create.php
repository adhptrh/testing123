<div class="row row-xs">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <?= form_open(base_url('reference/menu/save')); ?>
        <div class="align-items-center alert alert-warning <?= $hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none' ?>">
          <i data-feather="alert-circle" class="mg-r-10"></i><?= $this->session->flashdata('create_info_message');  ?>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                  <label>Parent</label>
                  <select name="parent" class="form-control select2  <?= $has_error = (form_error('parent')) ? 'is-invalid' : '' ?>" style="width: 100%;">
                    <!-- <option value=""></option> -->
                    <option value="<?= enc(0) ?>" selected>Umum</option>
                    <?php
                      if($parent_selected){
                        $xdata = $parent_selected;
                      }else{
                        $xdata = $data;
                      }
                      foreach ($xdata as $k => $v):
                    ?>
                      <option <?= $selected = ($v['selected'] == 'selected') ? 'selected' : ''; ?> value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?= $has_error = (form_error('parent')) ? '<div class="invalid-feedback">' . form_error('parent') . '</div>' : '' ?>
                </div>
                <!-- /.form-group -->

            <div class="form-group">
              <label>Nama</label>
              <input name="name" type="text" class="form-control <?= $has_error = (form_error('name')) ? 'is-invalid' : '' ?>" value="<?= $isi = (isset($old['name'])) ? $old['name'] : ''; ?>" >
              <?= $has_error = (form_error('name')) ? '<div class="invalid-feedback">' . form_error('name') . '</div>' : '' ?>
            </div>

            <div class="form-group">
              <label>Prefix</label>
              <input name="prefix" type="text" class="form-control <?= $has_error = (form_error('prefix')) ? 'is-invalid' : '' ?>" value="<?= $isi = (isset($old['prefix'])) ? $old['prefix'] : ''; ?>">
              <?= $has_error = (form_error('prefix')) ? '<div class="invalid-feedback">' . form_error('prefix') . '</div>' : '' ?>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label>Icon</label>
              <div class="row">
                <div class="col-md-6">
                  <input name="icon" type="text" class="form-control <?= $has_error = (form_error('icon')) ? 'is-invalid' : 'wew' ?>" value="<?= $isi = (isset($old['icon'])) ? $old['icon'] : ''; ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>No. Urutan</label>
              <div class="row">
                <div class="col-md-6">
                  <input name="sort" type="text" class="form-control <?= $has_error = (form_error('sort')) ? 'is-invalid' : '' ?>" value="<?= $isi = (isset($old['sort'])) ? $old['sort'] : ''; ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input name="is_hide" type="checkbox" <?= $isi = (isset($old['is_hide'])) ? 'checked' : '' ?>>
                  Sembunyikan menu ini dari bilah navigasi ?
                </label>
              </div>
            </div>
        </div>
      </div>
      </div>
      <div class="card-footer">
        <a href="<?= base_url('reference/menu') ?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
        <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
      </div>
    <?= form_close(); ?>
    </div>
  </div>
</div>
