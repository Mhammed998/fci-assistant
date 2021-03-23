<?php

ob_start();
session_start();

if(isset($_SESSION['adminuser'])){
    $pageTitle="Dashboard | Admin-Profile";
    include 'init.php';

    $action="";
    if (isset($_GET['action'])) {
      $action=$_GET['action'];
    }else{
      $action="nothing";
    }

        /* get all admin info   */
        $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
        $stmt->execute(array($_SESSION['adminid']));
        $get=$stmt->fetch();

        include "includes/side-nav.php"; ?>

      <div class="admin-profile" id="profile">

        <?php if($action == 'nothing'){ ?>

        <div class="img-head">
              <div class="left-info text-center">

              <img class="img-thumbnail" src="uploads/avatars/<?php echo  $get['avatar'] ?>" height="130px" width="130px"><br>
            <h3 class="memberName"><?php echo $get['fullname'] ?> <span><i class='fas fa-check admin'></i></span> </h3>

              </div>

        </div>

        <div class="row">



            <div class="col-md-12">
                <div id="admin-info " style="margin-top:120px;">

                <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Main Info:</h3>
                </div>
                <div class="panel-body" style="padding:5px 15px">
                <h4 class="labl">#ID:</h4> <span><?php echo $get['id'] ?></span> <hr>
                <h4 class="labl">Username:</h4><span><?php echo $get['username'] ?></span> <hr>

                <h4 class="labl">Email: </h4><span><?php echo $get['usermail'] ?></span> <hr>
                <h4 class="labl">Join in: </h4><span><?php echo $get['Date'] ?></span> <hr>
                <h4 class="labl">Acad-year:</h4> <span><?php echo $get['class'] ?>th</span>

                </div>
                </div>




         </div>
      </div>

      <?php
        if($get['approve'] == 1){
       echo "<a style='margin:5px 25px 25px;padding: 7px 10px;width:62px;float:right;'  href='users.php?do=Edit&userid="  . $get['id'] . "' class='btn btn-primary'>
       <i  class=' mr-5 far fa-edit'></i>Edit</a><br><br><br>";
        }
       ?>


      <div class="col-md-12">
         <div  id="comments">
           <?php

              $comments=$con->prepare(" SELECT * FROM comments WHERE USERID=?");
              $comments->execute(array($get['id']));
              $getcomment=$comments->fetchAll();
              $countcomments=$comments->rowCount(); ?>

              <div class="panel panel-default">
              <div class="panel-heading">
                 <h3 class="panel-title"><i class="fas fa-comments"></i> Comments</h3>
              </div>

            <?php  if($countcomments > 0){  ?>


                <div class="panel-body">


            <?php    foreach ($getcomment as $comment) {


                echo "<div class='comment'>";
                  echo"<small class='date-of-comment'><i class='fas fa-calander'></i> ". $comment['comment_date']."</small>";
                  echo "<p>".$comment['comment_body']."</p>";
                  echo "<div class='options'>";
                   echo " <a  class='label label-danger' title='Delete' href='?action=deletecomment&cid=".$comment['comment_id']."'>Delete</a>";
                 echo"</div>";

                  echo"<hr class='sep'>";

                echo"</div>";

                  }



              }
              else{
                echo "<div style='margin-bottom:0;' class='alert alert-info '>There Is No Comment To Show</div>";
              }

              echo "</div>";
              echo "</div>";


           ?>

          </div>
         </div>
       </div>
     <?php }elseif($action == 'deletecomment'){


       $cid="";
       if (isset($_GET['cid']) && is_numeric($_GET['cid']) ) {
         $cid=$_GET['cid'];
       }else{
         $cid=0;
       }

       echo "<h1 class='big-title text-center'>Delete Comment </h1>";

       $check=$con->prepare("SELECT * FROM comments WHERE comment_id=?");
       $check->execute(array($cid));
       $checkcount=$check->rowCount();

       if($checkcount > 0){
         $deleteCom=$con->prepare("DELETE FROM comments WHERE comment_id=? ");
         $deleteCom->execute(array($cid));
         $delcount=$deleteCom->rowCount();

         if($delcount > 0){
           echo "<div class='alert alert-danger'>".$delcount." Record Deleted</div>";
           header('Location: ' . $_SERVER['HTTP_REFERER']);

         }

       }


     } ?>
   </div>

  <?php } else{
          header("refresh:2;url=admin-login.php");

  }








include 'includes/footer.php' ;
ob_end_flush();
?>
