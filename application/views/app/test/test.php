<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url('template/');?>assets/img/favicon.png">

    <title>Test - CBT</title>

    <!-- vendor css -->
    <link href="<?=base_url('template/');?>lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?=base_url('template/');?>lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <link id="dfMode" rel="stylesheet" href="<?=base_url('template/');?>lib/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?=base_url('template/');?>assets/css/dashforge.css">
    <!-- <link rel="stylesheet" href="<?=base_url('template/');?>assets/css/dashforge.dashboard.css"> -->
    <link id="dfMode" rel="stylesheet" href="<?=base_url('template/');?>assets/css/skin.light.css">
    <link id="dfMode" rel="stylesheet" href="<?=base_url('template/');?>assets/css/skin.deepblue.css">
    <link id="dfMode" rel="stylesheet"
        href="<?=base_url('template/');?>lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

</head>

<body class="page-profile">

    <header class="navbar navbar-header navbar-header-fixed">
        <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
        <div class="navbar-brand">
            <a href="<?=base_url('template/');?>index.html" class="df-logo">C<span>BT</span></a>
        </div><!-- navbar-brand -->
        <div id="navbarMenu" class="navbar-menu-wrapper">
            <div class="navbar-menu-header">
                <a href="<?=base_url('template/');?>index.html" class="df-logo">X<span>Panel</span></a>
                <a id="mainMenuClose" href=""><i data-feather="x"></i></a>
            </div><!-- navbar-menu-header -->
            <ul class="nav navbar-menu">
                <li class="nav-label pd-l-20 pd-lg-l-25 d-lg-none">Main Navigation</li>
                <!-- <li class="nav-item"><a href="#" class="nav-link"><i data-feather="box"></i> Components</a></li> -->
                <!-- <li class="nav-item"><a href="#" class="nav-link"><i data-feather="archive"></i> Collections</a></li> -->
            </ul>
        </div><!-- navbar-menu-wrapper -->
        <div class="navbar-right">
            <a href="#" class="btn btn-social"><i class="fab fa-dribbble"></i></a>
            <a href="#" class="btn btn-social"><i class="fab fa-github"></i></a>
            <a href="#" class="btn btn-social"><i class="fab fa-twitter"></i></a>
            <a href="#" class="btn btn-buy"><i data-feather="shopping-bag"></i> <span>Versi 1.0</span></a>
        </div><!-- navbar-right -->
    </header><!-- navbar -->

    <div class="content content-fixed">
        <div class="container">
        </div><!-- container -->
    </div><!-- content -->

    <footer class="footer fixed-bottom">
        <div>
            <span>&copy; 2020 - 2021 XPanel v4.0.0. </span>
            <span>Created by <a href="#">Labschool Software Engineering</a></span>
        </div>
        <div>
            <nav class="nav">
                <a href="#" class="nav-link">Licenses</a>
                <a href="#" class="nav-link">Change Log</a>
                <a href="#" class="nav-link">Get Help</a>
            </nav>
        </div>
    </footer>

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
    <script src="<?= base_url('template/'); ?>lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js">
    </script>
    <script src="<?= base_url('template/'); ?>lib/select2/js/select2.min.js"></script>
    <script src="<?= base_url('template/'); ?>lib/cleave.js/cleave.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
        allowClear: true
    });

    $('.goToSelectedUrl').on('change', function() {
        href = $(this).data('href') + this.value
        window.location.href = href;
    });

    $('.date').datepicker({
        dateFormat: "dd-mm-yy"
    });
    </script>
</body>

</html>