<?php
  //DEBUG
  // echo form_error('password');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/">

    <!-- Bootstrap core CSS -->
<link href="<?= base_url('template_login/dist/css/bootstrap.min.css') ?>" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Favicons -->
<link rel="apple-touch-icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
<link rel="icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/favicon.ico">
<meta name="msapplication-config" content="https://getbootstrap.com/docs/4.5/assets/img/favicons/browserconfig.xml">
<meta name="theme-color" content="#563d7c">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="<?= base_url('template_login/signin.css') ?>" rel="stylesheet">
  </head>
  <body class="text-center">
    <?=
      form_open(base_url('login/authorization'), [
        'class' => 'form-signin'
      ]);
    ?>
  <img class="mb-4" src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

  <?php if($this->session->flashdata('message')):  ?>
    <div class="alert alert-danger" role="alert">
      <?= $this->session->flashdata('message');  ?>
    </div>
  <?php endif; ?>

  <div class="form-group">
    <label for="inputEmail" class="sr-only">Email address</label>
    <input name="username" value="<?= $this->input->post('username'); ?>" type="email" id="inputEmail" class="form-control <?= $invalid = (form_error('username')) ? 'is-invalid' : ''  ?>" placeholder="Email address" autofocus autocomplete="off">
    <div class="invalid-feedback">
      <?= form_error('username') ?>
    </div>
  </div>

  <div class="form-group">
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" value="<?= $this->input->post('password'); ?>" type="password" id="inputPassword" class="form-control <?= $invalid = (form_error('password')) ? 'is-invalid' : ''  ?>" placeholder="Password">
    <div class="invalid-feedback">
        <?= form_error('password') ?>
    </div>
  </div>

  <div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Sign in</button>
  <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p>
<?= form_close(); ?>
</body>
</html>
