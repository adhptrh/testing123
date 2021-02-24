<aside class="aside aside-fixed">
  <div class="aside-header">
    <a href="#" class="aside-logo">C<span>BT</span></a>
    <a href="" class="aside-menu-link">
      <i data-feather="menu"></i>
      <i data-feather="x"></i>
    </a>
  </div>
  <div class="aside-body">
    <div class="aside-loggedin">
      <div class="d-flex align-items-center justify-content-start">
        <a href="" class="avatar"><img src="<?= base_url('/template/assets/img/user.png'); ?>" class="rounded-circle" alt=""></a>
        <div class="aside-alert-link">
          <a href="" class="new" data-toggle="tooltip" title="You have 2 unread messages"><i data-feather="message-square"></i></a>
          <a href="" class="new" data-toggle="tooltip" title="You have 4 new notifications"><i data-feather="bell"></i></a>
          <a href="" data-toggle="tooltip" title="Sign out"><i data-feather="log-out"></i></a>
        </div>
      </div>
      <div class="aside-loggedin-user">
        <a href="#loggedinMenu" class="d-flex align-items-center justify-content-between mg-b-2" data-toggle="collapse">
          <h6 class="tx-semibold mg-b-0"><?= $profile->name; ?></h6>
          <!-- <i data-feather="chevron-down"></i> -->
        </a>
        <p class="tx-color-03 tx-12 mg-b-0"><?= $profile->level; ?></p>
      </div>
      <!-- <div class="collapse" id="loggedinMenu">
        <ul class="nav nav-aside mg-b-0">
          <li class="nav-item"><a href="" class="nav-link"><i data-feather="edit"></i> <span>Edit Profile</span></a></li>
          <li class="nav-item"><a href="" class="nav-link"><i data-feather="user"></i> <span>View Profile</span></a></li>
          <li class="nav-item"><a href="" class="nav-link"><i data-feather="settings"></i> <span>Account Settings</span></a></li>
          <li class="nav-item"><a href="" class="nav-link"><i data-feather="help-circle"></i> <span>Help Center</span></a></li>
          <li class="nav-item"><a href="<?= base_url('login'); ?>" class="nav-link"><i data-feather="log-out"></i> <span>Sign Out</span></a></li>
        </ul>
      </div> -->
    </div><!-- aside-loggedin -->
    <ul class="nav nav-aside">

      <?php
        foreach ($menu as $k => $v) {
          if(isset($v['submenu'])){
            $mg = ($k > 0) ? 'mg-t-25' : '';
            echo '<li class="nav-label '. $mg .'">'.$v['name'].'</li>';

            foreach ($v['submenu'] as $k1 => $v1) {
              if($header['nav_active'] == $v1['prefix']){
                $active = 'active';
              }else{
                $active = '';
              }
              echo '<li class="nav-item '.$active.'"><a href="' . base_url($v1["prefix"]) . '" class="nav-link"><i data-feather="' . $v1['icon'] . '"></i> <span>' . $v1['name'] . '</span></a></li>';
            }

          }
        }
      ?>
    </ul>
  </div>
</aside>

<div class="content ht-100v pd-0">
  <div class="content-header">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-0">
          <!-- <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Sales Monitoring</li> -->
          <?php
            foreach ($header['breadcrumb'] as $k => $v) {
              if(end($header['breadcrumb'])['label'] == $v['label']){
                echo '<li class="breadcrumb-item active" aria-current="page">'.$v['label'].'</li>';
              }else{
                echo '<li class="breadcrumb-item"><a href="'.$v['href'].'">'.$v['label'].'</a></li>';
              }
            }
          ?>
        </ol>
      </nav>
    </div>
    <nav class="nav">
      <a href="<?= base_url('login') ?>" class="nav-link"><i data-feather="power"></i></a>
    </nav>
  </div><!-- content-header -->
<div class="content-body">

  <div class="container pd-x-0">
