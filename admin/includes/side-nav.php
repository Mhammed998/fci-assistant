<?php
  /* get all admin info   */

    $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
    $stmt->execute(array($_SESSION['adminid']));
    $get=$stmt->fetch();
?>

<div class="side-nav">
<div style="position:relative;" class="top text-center">

       <img  src="uploads/avatars/<?php echo $get['avatar'] ?>">

       <h4 id="names"> <?php echo $get['fullname']  ?></h4>

       
</div>

<div class="middle">
    <ul class="list-unstyled">
        <li><a href="dashboard.php"><i class="fas fa-tv"></i> <span id="names"> Main Panel </span></a></li>
        <li><a href="members.php"><i class="fas fa-users-cog"></i> <span id="names"> Members </span></a></li>
        <li><a href="tracks.php"><i class="fas fa-chalkboard-teacher"></i> <span id="names"> Tracks </span></a></li>
        <li><a href="admin-events.php"><i class="far fa-newspaper"></i> <span id="names"> Events </span></a></li>
        <li><a href="comments.php"><i class="far fa-comments"></i> <span id="names"> Comments </span></a></li>
        <li><a href="admin-profile.php"><i class="fas fa-cog"></i> <span id="names"> My Profile </span></a></li>
        <li><a href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> <span id="names"> Logout </span></a></li>

    </ul>

</div>

</div>
