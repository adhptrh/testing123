<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="DashForge">
    <meta name="twitter:description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="twitter:image" content="http://themepixels.me/dashforge/img/dashforge-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/dashforge">
    <meta property="og:title" content="DashForge">
    <meta property="og:description" content="Responsive Bootstrap 4 Dashboard Template">

    <meta property="og:image" content="http://themepixels.me/dashforge/img/dashforge-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/dashforge/img/dashforge-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="author" content="ThemePixels">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url('template/');?>assets/img/favicon.png">

    <title>Login | CBT</title>

    <!-- vendor css -->
    <link href="<?=base_url('template/');?>lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?=base_url('template/');?>lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="<?=base_url('template/');?>assets/css/dashforge.css">
    <link id="dfMode" rel="stylesheet" href="<?=base_url('template/');?>assets/css/skin.light.css">
    <link id="dfMode" rel="stylesheet" href="<?=base_url('template/');?>assets/css/skin.deepblue.css">
    <link rel="stylesheet" href="<?=base_url('template/');?>assets/css/dashforge.auth.css">
</head>

<body>

    <header class="navbar navbar-header navbar-header-fixed">
        <!-- <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a> -->
        <div class="navbar-brand">
            <!-- <a href="#" class="df-logo">C<span>BT</span>Labschool Pekanbaru</a> -->
            <a href="#" class="df-logo">Labschool Exam</a>
        </div><!-- navbar-brand -->
        <div id="navbarMenu" class="navbar-menu-wrapper">
            <div class="navbar-menu-header">
                <a href="#" class="df-logo">X<span>Panel</span></a>
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

    <div class="content content-fixed content-auth">
        <div class="container">
        <?=form_open(base_url('login/authorization'), ['class' => 'form-signin']);?>
            <div class="media align-items-stretch justify-content-center ht-100p pos-relative">
                <div class="media-body align-items-center d-none d-lg-flex">
                    <div class="mx-wd-300">
                        <img src="<?=base_url('upload/logo.png')?>" class="img-fluid" alt="">
                    </div>
                    <!-- <div class="pos-absolute b-0 l-0 tx-12 tx-center">
                        Workspace design vector is created by <a href="https://www.freepik.com/pikisuperstar"
                            target="_blank">pikisuperstar (freepik.com)</a>
                    </div> -->
                </div><!-- media-body -->
                <div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
                    <div class="wd-100p">
                        <h3 class="tx-color-01 mg-b-5">Log In</h3>
                        <p class="tx-color-03 tx-16 mg-b-40">Selamat datang! Silahkan login untuk masuk.</p>

                        <div
                            class="align-items-center alert alert-warning <?=$hide = ($this->session->flashdata('message')) ? '' : 'd-none'?>">
                            <i data-feather="alert-circle"
                                class="mg-r-10"></i><?=$this->session->flashdata('message');?>
                        </div>

                        <div class="form-group">
                                <label>Email address</label>
                                <input name="username" type="text"
                                    class="form-control <?=$invalid = (form_error('username')) ? 'is-invalid' : ''?>"
                                    placeholder="nama@email.com" value="<?=$this->input->post('username');?>">
                                <div class="invalid-feedback">
                                    <?=form_error('username')?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex justify-content-between mg-b-5">
                                    <label class="mg-b-0-f">Password</label>
                                    <!-- <a href="" class="tx-13">Forgot password?</a> -->
                                </div>
                                <input name="password" value="<?=$this->input->post('password');?>" type="password"
                                    class="form-control <?=$invalid = (form_error('password')) ? 'is-invalid' : ''?>"
                                    placeholder="Masukkan password">
                                <div class="invalid-feedback">
                                    <?=form_error('password')?>
                                </div>
                            </div>
                            <button class="btn btn-brand-02 btn-block">Log In</button>
                    </div>
                </div><!-- sign-wrapper -->
            </div><!-- media -->
        </div><!-- container -->
        <?=form_close();?>
    </div>

    <footer class="footer">
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

    <script src="<?=base_url('template/');?>lib/jquery/jquery.min.js"></script>
    <script src="<?=base_url('template/');?>lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url('template/');?>lib/feather-icons/feather.min.js"></script>
    <script src="<?=base_url('template/');?>lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script src="<?=base_url('template/');?>assets/js/dashforge.js"></script>

    <!-- append theme customizer -->
    <script src="<?=base_url('template/');?>lib/js-cookie/js.cookie.js"></script>
</body>

</html>