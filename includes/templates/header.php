<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?php getTitle() ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.css" /> <!-- bootstrap v 3.3.7 used-->
        <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>frontend.css" /><!-- bootstrap v 3.3.7 used-->
    </head>
    <body>  
        <div class='container upper-nav'>
        <?php
          if (isset($_SESSION['frontUser']) && isset($_SESSION['uID'])) {
            $ID = $_SESSION['uID'];
            $stmt = $con->prepare("SELECT img FROM users WHERE userID = $ID");
            $stmt->execute();
            $count = $stmt->fetchAll();
            ?>
                  <img 
                  class='img-circle img-thumbnail img-responsive img-sm' 
                  src='admin/<?php if ($count > 0) {
                                 foreach($count as $img) {
                                     echo $img['img'];
                                 }
                              }?>'>
                  <div class="dropdown">
                  <button class="btn btn-sm btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?php echo $sessionUser;  ?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="profile.php">My Profile </a></li>
                    <li><a href="profile.php#my-items"> My Items</a></li>
                    <li><a href="newadd.php"> New Add</a></li>
                    <li><a href="logout.php"> log out</a></li>
                  </ul>
                  </div>
           </div>

        <?php
          } else {
              echo "<a class='pull-right' href='login.php'>
                    <span>Login/Signup</span>
                    </a>";
            }
      ?>
        
    </div>
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
          <a class="navbar-brand" href="homepage.php"><?php echo 'Home'; ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php
                $where = 'where parent = 0';
                $categories = countCats($where);
                foreach ($categories as $cat) {
                  echo "<li class='show-menue' data-class='." .  $cat['name']   . "'><a href='categories.php?id=" . $cat['ID']  . "&pageName=" . $cat['name']  . "'>";
                  echo $cat['name'];
                  $where = 'where parent =' . $cat['ID'];
                  $childCats = countCats($where);
                  foreach($childCats as $c) {
                  if ($c['parent'] == $cat['ID']) {
                    
                
                   echo '<span class="caret"></span>';
                   echo "<div class='down " . $cat['name']  . "'>";
                      echo "<ul>";
                        echo "<li><a href='categories.php?id=" . $c['ID']  . "&pageName=" . $c['name']  . "'>";
                        echo $c['name'] . "</a> </li>";
                      echo "</ul>";
                    echo "</div>";
                  } 
                }
                  echo "</a> </li>";
                }
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>