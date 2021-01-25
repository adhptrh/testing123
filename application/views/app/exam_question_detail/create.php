<div class="card-header">
    <h6 class="mb-0">Tambah Butir Soal</h6>
</div>
<?= form_open('#') ?>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> Gunakan ekstensi
                <strong>*.png</strong> jika ingin menyertakan gambar pada soal atau jawaban.</div>
        </div>
        <div class="col-md-12 form-group d-none">
            <h6 class="mg-b-10">Master Soal ID</h6>
            <input class="form-control" name="master_soal_id" value="<?= $master_soal_id; ?>" readonly>
        </div>
        <div class="col-md-12">
            <h6 class="mg-b-10">Soal</h6>
            <div class="editor" id="soal"></div>
        </div>
        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Opsi A</h6>
            <div class="editor" id="opsi_a"></div>
        </div>
        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Opsi B</h6>
            <div class="editor" id="opsi_b"></div>
        </div>
        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Opsi C</h6>
            <div class="editor" id="opsi_c"></div>
        </div>
        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Opsi D</h6>
            <div class="editor" id="opsi_d"></div>
        </div>
        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Opsi E</h6>
            <div class="editor" id="opsi_e"></div>
        </div>
        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Kunci Jawaban</h6>
            <button data-opsi="opsi_a" type="button" class="btn btn-outline-success btn-sm opsi">Opsi A</button>
            <button data-opsi="opsi_b" type="button" class="btn btn-outline-success btn-sm opsi">Opsi B</button>
            <button data-opsi="opsi_c" type="button" class="btn btn-outline-success btn-sm opsi">Opsi C</button>
            <button data-opsi="opsi_d" type="button" class="btn btn-outline-success btn-sm opsi">Opsi D</button>
            <button data-opsi="opsi_e" type="button" class="btn btn-outline-success btn-sm opsi">Opsi E</button>
        </div>

    </div>
</div>
</div>
<div class="card-footer">
    <a href="#" class="btn btn-sm btn-danger batal" type="button" name="">Batal</a>
    <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
</div>
<?=form_close();?>