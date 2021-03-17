<body class="page-profile">

    <header class="navbar navbar-header navbar-header-fixed">
        <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
        <div class="navbar-brand">
            <a href="#" class="df-logo">CBT <?= $header['school_name']; ?><span></span></a>
        </div>
        
        <div class="navbar-right">
            <!-- <a href="#" class="btn btn-social"><i class="fab fa-dribbble"></i></a>
            <a href="#" class="btn btn-social"><i class="fab fa-github"></i></a>
            <a href="#" class="btn btn-social"><i class="fab fa-twitter"></i></a> -->
            <!-- <a href="#" class="btn btn-buy"><i data-feather="shopping-bag"></i> <span>Versi 1.0</span></a> -->
        <h6 class="tx-white mg-b-0 "><?= $profile->name ?></h6>
            <nav class="nav">
                <a href="<?= base_url('login') ?>" class="nav-link"><i data-feather="power"></i></a>
            </nav>
        </div><!-- navbar-right -->
    </header><!-- navbar -->

    <div class="content content-fixed">
        <div class="container">