<div class="row row-xs">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mg-b-10">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control dtp_cari" placeholder="Cari di sini"
                                aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <a href="<?= base_url('setting/profile/edit'); ?>"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i
                                class="fa fa-edit"></i> Ubah</a>
                    </div>
                </div>

                <div class="alert alert-info <?= $hide = ($this->session->flashdata('message')) ? '' : 'd-none' ?>">
                    <?= $pesan = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td class="wd-200">Nama Sekolah</td>
                                <td><?= $data['name'] ?></td>
                            </tr>
                            <tr>
                                <td class="wd-200">Nama Sekolah Panjang</td>
                                <td><?= $data['long_name'] ?></td>
                            </tr>
                            <tr>
                                <td>Kepala Sekolah</td>
                                <td><?= $data['headmaster'] ?></td>
                            </tr>
                            <tr>
                                <td>NIP Kepala Sekolah</td>
                                <td><?= $data['nip'] ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td><?= $data['address'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>