<nav class="navbar navbar-default navbar-inverse">
<div class="container">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?php echo 'dashboard.php'; ?>"><?php echo lang('brand'); ?></a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav navbar-left">
      <li><a href="categories.php"><?php echo lang('Categories'); ?> <span class="sr-only">(current)</span></a></li>
      <li><a href="items.php"><?php echo lang('items'); ?></a></li>
      <li><a href="members.php?do=Manage"><?php echo lang('members'); ?></a></li>
      <li><a href="comments.php?do=Manage"><?php echo lang('comments'); ?></a></li>
      <li><a href="#"><?php echo lang('statstics'); ?></a></li>
      <li><a href="#"><?php echo lang('logs'); ?></a></li>
    </ul>

    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="members.php?do=Edit&id=<?php echo $_SESSION['ID'] ?>"><?php echo lang('edit profile'); ?></a></li>
          <li><a href="#"><?php echo lang('setting'); ?></a></li>
          <li role="separator" class="divider"></li>
          <li><a href="logout.php"><?php echo lang('log out'); ?></a></li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>