<div class="row row-xs">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <?= form_open(base_url('data/study/update')); ?>
        <div class="align-items-center alert alert-warning <?= $hide = ($this->session->flashdata('update_info_message')) ? '' : 'd-none' ?>">
          <i data-feather="alert-circle" class="mg-r-10"></i><?= $this->session->flashdata('update_info_message');  ?>
        </div>

        <div class="form-group d-none">
          <label>ID</label>
          <input name="id" type="text" class="form-control" value="<?= $isi = (isset($old['id'])) ? $old['id'] : $data['id']; ?>" readonly>
        </div>

        <div class="form-group">
          <label>Nama</label>
          <input name="name" type="text" class="form-control <?= $has_error = (form_error('name')) ? 'is-invalid' : '' ?>" value="<?= $isi = (isset($old['name'])) ? $old['name'] : $data['name']; ?>" >
          <?= $has_error = (form_error('name')) ? '<div class="invalid-feedback">' . form_error('name') . '</div>' : '' ?>
        </div>
      </div>
      <div class="card-footer">
        <a href="<?= base_url('data/study') ?>" class="btn btn-sm btn-danger" type="button" name="">Batal</a>
        <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
      </div>
    <?= form_close(); ?>
    </div>
  </div>
</div>
