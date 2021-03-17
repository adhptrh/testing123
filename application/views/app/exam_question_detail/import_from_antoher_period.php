<div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div
                    class="align-items-center alert alert-warning <?= $hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none' ?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?= $this->session->flashdata('create_info_message');  ?>
                </div>

                <div class="form-group d-none">
                    <label>ID</label>
                    <input name="id" type="text" class="form-control" value="0" readonly>
                </div>

                <div class="form-group">
                    <?php $var = 'period'; ?>
                    <label>Periode</label>
                    <select id="bPeriod" name="<?= $var; ?>"
                        class="custom-select select2 <?= $has_error = (form_error($var)) ? 'is-invalid' : '' ?>">
                        <option></option>
                        <?php foreach ($period as $k => $v): ?>

                        <option <?= $selected = ($v['selected'] == 'selected') ? $v['selected'] : ''; ?>
                            value="<?= base_url('/app/exam_question_detail/create_from_another_period/' . $exam_question_id . '/' . $study_id . '/' . $v['id']) ?>">
                            <?= $v['name'] ?></option>

                        <?php endforeach; ?>
                    </select>
                    <?= $has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : '' ?>
                </div>

                <?php if ($data): ?>
                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Soal</th>
                        <th>Jumlah Soal</th>
                        <th>Aksi</th>
                    </tr>
                    <?php foreach ($data as $k => $v): $no = 1; ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $v['exam']; ?></td>
                        <td><?= $v['jsoal']; ?></td>
                        <td>
                            <button data-href="<?= base_url('/app/exam_question_detail/import_from_another_period/'. $v['id'] . '/' . $exam_question_id) ?>" class='btn btn-sm btn-warning bImport'>Import</button>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                </table>
                <?php endif; ?>

            </div>
            <div class="card-footer">
                <a href="<?= base_url('app/exam_question_detail/list/' . $exam_question_id) ?>"
                    class="btn btn-sm btn-danger" type="button" name="">Batal</a>
                <!-- <button class="btn btn-sm btn-primary float-right mg-l-10" type="submit" name="">Import Semua Butir Soal</button> -->
                <!-- <button class="btn btn-sm btn-primary float-right" type="button" name="">Import Soal Tertentu</button> -->
            </div>
        </div>
    </div>
</div>
</div>