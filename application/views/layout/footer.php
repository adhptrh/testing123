</div>
</div>

<script src="<?= base_url('template/'); ?>lib/jquery/jquery.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/jqueryui/jquery-ui.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/feather-icons/feather.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?= base_url('template/'); ?>assets/js/dashforge.js"></script>
<script src="<?= base_url('template/'); ?>assets/js/dashforge.aside.js"></script>
<script src="<?= base_url('template/'); ?>lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/select2/js/select2.min.js"></script>
<script src="<?= base_url('template/'); ?>lib/cleave.js/cleave.min.js"></script>
<script src="<?= base_url('template/'); ?>assets/js/quill.js"></script>
<script src="<?= base_url('template/'); ?>assets/js/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<?= $js = (isset($header['js_file']) ? '<script src="' . base_url('template/assets/js/cbt/' . $header['js_file'] . '.js') . '"></script>' : '') ?>

<script type="text/javascript">
function tInit() {
    var t = $('.dtable').DataTable({
        responsive: false,
        "bLengthChange": false,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });

    $('.dtp_cari').on('keyup', function() {
        t.search(this.value).draw();
    });
}

tInit();

$('.hapus').click(function() {
    Swal.fire({
        title: 'Peringatan',
        text: "Apakah Anda yakin akan menghapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = $(this).data('href');
        }
    })
})

$('.select2').select2({
    placeholder: 'Pilih',
    widht: '100%',
    allowClear: true
});

$('.goToSelectedUrl').on('change', function() {
    href = $(this).data('href') + this.value
    window.location.href = href;
});

$('.date').datepicker({
    dateFormat: "dd-mm-yy"
});

$('#customFile').on('change', function() {
    // Ambil nama file 
    let fileName = $(this).val().split('\\').pop();
    // Ubah "Choose a file" label sesuai dengan nama file yag akan diupload
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});

function setButtonOptionAllClear() {
    document.querySelectorAll('.bSelect').forEach(item => {
        item.classList.remove('btn-success');
        item.classList.add('btn-outline-success');
    })
}

document.querySelectorAll('.bSelect').forEach(item => {
    item.addEventListener('click', event => {
        setButtonOptionAllClear();
        item.classList.add('btn-success')
        item.classList.remove('btn-outline-success')
    })
});
</script>
</body>

</html>