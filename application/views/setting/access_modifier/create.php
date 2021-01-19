<div class="row row-xs">
  <div class="col-md-12">
    <?= form_open(base_url('setting/access_modifier/save')); ?>
    <div class="card">
      <div class="card-body">
        <label>Level</label>
        <select name="level" data-href="<?= base_url('/setting/access_modifier/create/') ?>" class="goToSelectedUrl custom-select select2">
          <option></option>
          <?php foreach ($level as $k => $v): ?>
            <option <?= $v['selected'] ?> value="<?= $v['id'] ?>"><?= $v['name'] ?></option>

          <?php endforeach; ?>
        </select>

        <hr>

        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Menu</th>
              <th>Create</th>
              <th>Read</th>
              <th>Update</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; ?>
            <?php foreach ($data as $k => $v): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $v['name'] ?></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>

              <?php if (isset($v['submenu'])): ?>
                <?php foreach ($v['submenu'] as $k1 => $v1): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>--- <?= $v1['name'] ?></td>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input name="xcreate-<?= $v1['id'] ?>" type="checkbox" class="custom-control-input" id="c-<?= $v1['id'] ?>" <?= $checked = ($v1['xcreate'] == 1) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="c-<?= $v1['id'] ?>"></label>
                      </div>
                    </td>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input name="xread-<?= $v1['id'] ?>" type="checkbox" class="custom-control-input" id="i-<?= $v1['id'] ?>" <?= $checked = ($v1['xread'] == 1) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="i-<?= $v1['id'] ?>"></label>
                      </div>
                    </td>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input name="xupdate-<?= $v1['id'] ?>" type="checkbox" class="custom-control-input" id="u-<?= $v1['id'] ?>" <?= $checked = ($v1['xupdate'] == 1) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="u-<?= $v1['id'] ?>"></label>
                      </div>
                    </td>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input name="xdelete-<?= $v1['id'] ?>" type="checkbox" class="custom-control-input" id="d-<?= $v1['id'] ?>" <?= $checked = ($v1['xdelete'] == 1) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="d-<?= $v1['id'] ?>"></label>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>

            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <a href="<?= base_url('setting/access_modifier/create') ?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
        <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
      </div>
    </div>
    <?= form_close(); ?>
  </div>
</div><!-- row -->
</div><!-- container -->
