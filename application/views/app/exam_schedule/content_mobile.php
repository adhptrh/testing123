<div class="row row-xs">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <div class="alert alert-info <?=$hide = ($this->session->flashdata('message')) ? '' : 'd-none'?>">
                    <?=$pesan = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''?>
                </div>

                <div class="media-list">
                    <?php foreach ($data['exam_schedule'] as $k => $v): ?>
                    <?php if ( (date("d-m-Y", $v['time_server_now']) == $v['date'] && date("H:i:s", ($v['time_server_now'] + 900)) >= $v['start'] && date("H:i:s", $v['time_server_now']) <= $v['finish']) || !$data['student'] ): ?>
                    <div class="d-sm-flex mg-b-20">
                        <div class="media-body mg-sm-t-40 mg-t-0">
                            <a href="" class="d-block tx-uppercase tx-11 tx-medium mg-b-5"><?=$v['period_name'];?></a>
                            <h6><a href="" class="link-01"><?=$v['study'];?></a></h6>
                            <p class="tx-13 mg-b-0">Soal ini tersedia dalam rentang pukul <?=$v['start'];?> s.d
                                <?=$v['finish'];?>, dengan <?=$v['stock_of_exams'];?> butir soal </p>
                            <a class="mg-t-10 btn btn-xs btn-success"
                                href="<?php echo base_url('app/test/confirm/' . $v['id']); ?>"><i
                                    class="fas fa-edit"></i> Ikuti
                                Ujian</a>
                        </div><!-- media-body -->
                    </div>
                    <hr class="mg-t-20">
                    <?php endif;?>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>