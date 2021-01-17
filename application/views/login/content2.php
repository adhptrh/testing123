<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">
    <link rel="canonical" href="">

    <title>Sticky Footer Navbar Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('template/dist/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?= base_url('template/assets/css/ie10-viewport-bug-workaround.css') ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('template/sticky-footer-navbar.css') ?>" rel="stylesheet">

    <script src="<?= base_url('template/assets/js/ie-emulation-modes-warning.js') ?>"></script>
  </head>

  <body>

    <!-- Begin page content -->
    <div class="container">
      <!-- <form class="form-inline"> -->
      <?=
        form_open(base_url('login/authorization'), [
          'class' => 'form-inline'
        ]);
      ?>
        <div class="form-group">
          <label class="sr-only" for="exampleInputEmail3">Email address</label>
          <input type="email" class="form-control" name="username" placeholder="Email" value="<?= $this->input->post('username'); ?>">
        </div>
        <div class="form-group">
          <label class="sr-only" for="exampleInputPassword3">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-default">Sign in</button>
      <?= form_close(); ?>
    </div>

    <footer class="footer">
      <div class="container">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="<?= base_url('template/dist/js/bootstrap.min.js') ?>"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= base_url('template/assets/js/ie10-viewport-bug-workaround.js') ?>"></script>
  </body>
</html>
