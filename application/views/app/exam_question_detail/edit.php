<div class="card-header">
    <h6 class="mb-0">Edit Butir Soal</h6>
</div>
<?= form_open('#') ?>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> Gunakan ekstensi
                <strong>*.png</strong> jika ingin menyertakan gambar pada soal atau jawaban.
            </div>
        </div>
        <div class="col-md-12 form-group d-none">
            <h6 class="mg-b-10">ID</h6>
            <input id="id_butir_soal" class="form-control" name="id" value="<?= $data['id'] ?>" readonly>
        </div>

        <div class="col-md-12 form-group d-none">
            <h6 class="mg-b-10">Master Soal ID</h6>
            <input class="form-control" name="master_soal_id" value="<?= $data['exam_question_id'] ?>" readonly>
        </div>

        <div class="col-md-12">
            <h6 class="mg-b-10">Soal</h6>
            <div class="editor" id="soal"><?= $data['question'] ?></div>
            <!-- <div class="editor" id="soal"></div> -->
        </div>

        <?php foreach ($options as $option): ?>

        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10"><?= $option['label']; ?></h6>
            <div class="editor" id="<?= $option['prefix']; ?>"><?= $data[$option['prefix']] ?></div>
            <!-- <div class="editor" id="<?= $option['prefix']; ?>"></div> -->
        </div>

        <?php endforeach; ?>

        <div class="col-md-12 mg-t-25">
            <h6 class="mg-b-10">Kunci Jawaban</h6>
            <?php foreach ($options as $option): ?>
                <button data-opsi="<?= $option['prefix']; ?>" type="button"
                class="btn <?= $btn = ($data['keyword'] == $option['prefix']) ? 'btn-success btn-keyword' : 'btn-outline-success'; ?> btn-sm opsi"><?= $option['label']; ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="card-footer">
    <a href="#" class="btn btn-sm btn-danger batal" type="button" name="">Batal</a>
    <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
</div>
<?= form_close() ?>