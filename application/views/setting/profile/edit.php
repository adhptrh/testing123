<div class="row row-xs">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?= form_open(base_url('setting/profile/update')); ?>
                <div
                    class="align-items-center alert alert-warning <?= $hide = ($this->session->flashdata('update_info_message')) ? '' : 'd-none' ?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?= $this->session->flashdata('update_info_message');  ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td class="wd-200">Nama Sekolah</td>
                                <td>
                                    <input name="name" type="text"
                                        class="form-control <?= $has_error = (form_error('name')) ? 'is-invalid' : '' ?>"
                                        value="<?= $isi = (isset($old['name'])) ? $old['name'] : $data['name']; ?>">
                                    <?= $has_error = (form_error('name')) ? '<div class="invalid-feedback">' . form_error('name') . '</div>' : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="wd-200">Nama Sekolah Panjang</td>
                                <td>
                                    <input name="long_name" type="text"
                                        class="form-control <?= $has_error = (form_error('long_name')) ? 'is-invalid' : '' ?>"
                                        value="<?= $isi = (isset($old['long_name'])) ? $old['long_name'] : $data['long_name']; ?>">
                                    <?= $has_error = (form_error('long_name')) ? '<div class="invalid-feedback">' . form_error('long_name') . '</div>' : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Kepala Sekolah</td>
                                <td>
                                    <select name="headmaster" class="custom-select select2">
                                        <option></option>
                                        <?php foreach ($hrd as $k => $v): ?>
                                        <option <?=$v['selected']?> value="<?=$v['id']?>"><?=$v['name']?></option>

                                        <?php endforeach;?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>
                                    <input name="address" type="text"
                                        class="form-control <?= $has_error = (form_error('address')) ? 'is-invalid' : '' ?>"
                                        value="<?= $isi = (isset($old['address'])) ? $old['address'] : $data['address']; ?>">
                                    <?= $has_error = (form_error('address')) ? '<div class="invalid-feedback">' . form_error('address') . '</div>' : '' ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="card-footer">
                <a href="<?= base_url('setting/profile') ?>" class="btn btn-sm btn-danger" type="button"
                    name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>