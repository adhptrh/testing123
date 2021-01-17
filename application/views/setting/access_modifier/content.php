<div class="row row-xs">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="align-items-center alert alert-warning <?= $hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none' ?>">
          <i data-feather="alert-circle" class="mg-r-10"></i><?= $this->session->flashdata('create_info_message');  ?>
        </div>
        <label>Level</label>
        <select name="level" data-href="<?= base_url('/setting/access_modifier/create/') ?>" class="goToSelectedUrl custom-select select2">
          <option></option>
          <?php foreach ($level as $k => $v): ?>

            <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>

          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
</div><!-- row -->
</div><!-- container -->
