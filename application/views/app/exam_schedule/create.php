<div class="row row-xs">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?=form_open(base_url('app/exam_schedule/save'), ['id' => 'xform']);?>
                <div
                    class="align-items-center alert alert-warning <?=$hide = ($this->session->flashdata('create_info_message')) ? '' : 'd-none'?>">
                    <i data-feather="alert-circle"
                        class="mg-r-10"></i><?=$this->session->flashdata('create_info_message');?>
                </div>
                <div class="row">
                    <div class="col-md-5">

                        <div class="form-group d-none">
                            <label>ID</label>
                            <input id="iId" name="id" type="text" class="form-control" value="0" readonly>
                        </div>

                        <div class="form-group d-none">
                            <label>Intime</label>
                            <input id="iIntime" name="id" type="text" class="form-control" value="0" readonly>
                        </div>

                        <div class="form-group">
                            <?php $var = 'period';?>
                            <label>Periode</label>
                            <select id="bperiod" name="<?=$var;?>"
                                class="custom-select select2 <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>">
                                <option></option>
                                <?php foreach ($period as $k => $v): ?>

                                <option value="<?=$v['id']?>"><?=$v['name']?></option>

                                <?php endforeach;?>
                            </select>
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>

                        <div class="form-group">
                            <?php $var = 'exam';?>
                            <label>Mata Uji</label>
                            <select id="bexam" name="<?=$var;?>"
                                class="custom-select select2 <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>">
                                <option data-jsoal="0"></option>
                            </select>
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>

                        <div class="form-group">
                            <?php $var = 'order';?>
                            <label>Sesi</label>
                            <select name="<?=$var;?>"
                                class="custom-select select2 <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>">
                                <option></option>
                                <?php foreach ($order as $k => $v): ?>
                                <option value="<?=$v['id']?>"><?=$v['name']?></option>
                                <?php endforeach;?>
                            </select>
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <?php $var = 'date';?>
                            <label>Tanggal</label>
                            <input autocomplete="off" name="<?=$var;?>" type="text"
                                class="date form-control <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>"
                                value="<?=$isi = (isset($old[$var])) ? $old[$var] : '';?>">
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>

                        <div class="form-group">
                            <?php $var = 'start';?>
                            <label>Waktu Mulai</label>
                            <input id="start" autocomplete="off" name="<?=$var;?>" type="text"
                                class="form-control <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>"
                                value="<?=$isi = (isset($old[$var])) ? $old[$var] : '';?>">
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>

                        <div class="form-group">
                            <?php $var = 'finish';?>
                            <label>Waktu Selesai</label>
                            <input autocomplete="off" id="finish" name="<?=$var;?>" type="text"
                                class="form-control <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>"
                                value="<?=$isi = (isset($old[$var])) ? $old[$var] : '';?>">
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <?php $var = 'number_of_exam';?>
                            <label>Jumlah soal yang akan diujiankan</label>
                            <input id="numbersToExam" autocomplete="off" name="<?=$var;?>" type="text"
                                class="form-control <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>"
                                value="<?=$isi = (isset($old[$var])) ? $old[$var] : 0;?>">
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                            <small id="warningNumberExam" class="d-none tx-danger">Jumlah soal yang diujiankan harus
                                lebih kecil atau sama dengan jumlah soal tersedia</small>
                        </div>
                        <div class="form-group">
                            <?php $var = 'number_of_all_exam';?>
                            <label>Jumlah soal tersedia</label>
                            <input id="iJSoal" disabled autocomplete="off" name="" type="text"
                                class="form-control <?=$has_error = (form_error($var)) ? 'is-invalid' : ''?>"
                                value="<?=$isi = (isset($old[$var])) ? $old[$var] : '';?>">
                            <?=$has_error = (form_error($var)) ? '<div class="invalid-feedback">' . form_error($var) . '</div>' : ''?>
                        </div>
                        <div class="form-group">
                            <label>Tampilkan soal dengan</label><br/>
                            <button type='button' data-value="1" class='btn btn-sm btn-outline-success bSelect'>Acak</button>
                            <button type='button' data-value="0" class='btn btn-sm btn-outline-success bSelect'>Tidak Acak</button>
                            <input id="isRandom" type="text" name="is_random" class="d-none form-control" value=" ">
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="<?=base_url('app/exam_schedule')?>" class="btn btn-sm btn-danger" type="button"
                    name="">Batal</a>
                <button id="bSave" class="btn btn-sm btn-primary float-right" type="button" name="">Simpan</button>
            </div>
            <?=form_close();?>
        </div>
    </div>
</div>