<?php

$stmt=$con->prepare('select * from users where id=?');
$stmt->execute(array($_SESSION['userid']));
$get=$stmt->fetch();

?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">

      <a class="navbar-brand" href="home.php">
        <img alt="Brand" src="logo3.png">
      </a>

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>


    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

        <ul class="nav navbar-nav">
        <li><a href="home.php#about-us">About Us</a></li>
        <li><a href="home.php#our-features">Features</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Courses <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php

               $courses=getAllThings("*","tracks","","","track_id","DESC","");
               foreach($courses as $course){
      echo"<li><a href='our-tracks.php?trackid=" . $course['track_id'] . " '>" . $course['track_name'] . "</a></li>";
               }

            ?>
          </ul>
        </li>

        <li><a href="home.php#posts">Latest Posts</a></li>


      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">

<img class=" img-circle" src="admin\uploads\avatars\<?php echo $get['avatar'] ?>" height="35px" width="35px">


          <?php echo $get['username'] ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="profile.php"><i class="fas fa-cog"></i> My Profile</a></li>
            <li><a href="home.php#contact"><i class="fas fa-headphones-alt"></i> Need A Help?</a></li>
            <li style="margin:2px 0;" role="separator" class="divider"></li>
            <?php
             if($get['admin']==1){
               echo '<li><a href="admin\dashboard.php?userid=' . $get['id'] . '">Dashboard</a></li>';
             }
            ?>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
          <?php
              if($get['approve'] == 0){

                echo "<div class='alert-approve'>";

                    echo  "<i id='close' class='fas fa-times close'></i> Your membership still waiting for approval !";
                echo "</div>";

              }
          ?>

        </li>
      </ul>
    </div>
  </div>
</nav>
