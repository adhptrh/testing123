<div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info <?=$hide = ($this->session->flashdata('message')) ? '' : 'd-none'?>">
                    <?=$pesan = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''?>
                </div>
                <?= form_open_multipart('app/exam_question_detail/save_excel/' . $exam_question_id_enc) ?>
                <div
                    class="alert alert-warning align-items-center <?= $hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none' ?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?= $this->session->flashdata('create_info_message');  ?>
                </div>
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Pilih file Excel</label>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('app/exam_question_detail/list/' . $exam_question_id_enc) ?>"
                    class="btn btn-sm btn-danger" type="button" name="">Batal</a>
                <button class="btn btn-sm btn-primary float-right" type="submit" name="">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>