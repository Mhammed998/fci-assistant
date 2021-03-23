<?php
 ob_start();
 session_start();


 if(isset($_SESSION['username'])){  $pageTitle="FCI-ASSISTANT | Our-Tracks"?>

   <div id="page-tracks">

   <?php
   include "init.php";
   //include navbar in profile page
   include "includes/templates/navbar.php";

    $trackid="";
    if(isset($_GET['trackid']) && is_numeric($_GET['trackid']) )
         {
          $trackid=$_GET['trackid'];
         }else{
            echo 0;
         }


               $action='';
             if(isset($_GET['action'])){
              $action = $_GET['action'];
              }else{
              $action='Manage';
              }


       $stmtx=$con->prepare(" SELECT
            tracks.*,
            users.fullname  as fullname ,
            elements.element_heading as heading ,
            elements.element_link as linking
            FROM
            tracks
            INNER JOIN
                users ON users.id = tracks.admin_id
            INNER JOIN
            elements ON elements.trackID = tracks.track_id WHERE track_id=?
                 ");

       $stmtx->execute(array($trackid));
       $tracks=$stmtx->fetch();

     echo "<div class='track-intro'>";
              echo "<h1 class='track-title'>" . $tracks['track_name'] ." Path</h1>";
      echo"</div>";

      echo "<div class='container'>";

       echo "<div class='row'>";

        echo "<div class='col-md-12'>";
                echo "<div class='intro-desc'>";
                echo"<h3>About</h3>";
             echo "<div class='description'>";
               echo"<p>". $tracks['track_desc'] ."</p>";
             echo "</div>";
            echo "</div>";
                   echo "<hr class='sep'>";
         echo "</div>";



      echo "<div class='col-md-7'>";
        $stmt1=$con->prepare("SELECT * FROM `elements` WHERE trackID=? ORDER BY `order` ");
         $stmt1->execute(array($trackid));
         $lists=$stmt1->fetchAll();


        echo"<h3 class='steps'>Course Steps</h3>";
         foreach($lists as $list){
           echo "<div class='list'>";
              echo "<h3>". $list['element_heading'] ."</h3>";
              echo "<a target='_blank' href='" . $list['element_link'] . "'> Go to the course  &rarr; </a>";
           echo"</div>";

         }

          echo"</div>";



       echo "<div class='col-md-5'>";
           echo"<div class='laptop-img hidden-xs'>";
               $urlvideo= $tracks['urlvideo'];
             echo'<iframe class="laptop-video" width="430" height="270" src="' . $urlvideo . '"
              frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen></iframe>';
           echo"</div>";
         echo "</div>";

         echo"</div>";
         echo "<hr class='sep'>";
         echo "<div class='row'>";

              echo "<div class='col-md-8'>";

                  echo "<div class='comment-area'>";

                  $stmt=$con->prepare(" SELECT
                  comments.*,
                  tracks.*,
                  users.*
                  FROM
                  comments
                  INNER JOIN
                      tracks ON tracks.track_id = comments.TRACKID
                  INNER JOIN
                      users ON users.id = comments.USERID WHERE track_id=?  ");

            $stmt->execute(array($trackid));
            $comments=$stmt->fetchAll();
            $countc=$stmt->rowCount();

            echo "<h2><i class='far fa-comments'></i> Comments <span class='count-comment'>".$countc."</span></h2>";

            if($countc > 0){

            foreach($comments as $comment){


              echo "<div class='comment'>";
                echo"<img  height='50' width='100' class='img-thumbnail img-comment ' src='admin/uploads/avatars/" . $comment['avatar'] . "'>";
                echo "<h3>".$comment['fullname']."</h3>";
                if($comment['admin'] == 1){
                echo"<span><i class='fas fa-check admin'></i></span>";
                }
                echo"<small class='date-of-comment'><i class='fas fa-calander'></i> ". $comment['comment_date']."</small>";
                echo "<p>".$comment['comment_body']."</p>";
                if($_SESSION['userid'] == $comment['USERID']){
      echo "<div class='options'>";
        echo " <a  class='label label-danger' title='Delete' href='?trackid=" . $trackid . "&action=deletec&cid=".$comment['comment_id']."'>Delete</a>";
      echo"</div>";
                }
                echo"<hr class='sep'>";

              echo"</div>";

            }
          }else{
            echo "<div class='alert alert-warning'>No Comments Added Yet</div>";
          }
         echo"</div>";
       echo "</div>";

        echo "<div class='col-md-4'>";
            echo "<div class='add-comment'>";
                echo "<h4>Add Comment!</h4>";
        ?>

              <form action="<?php echo $_SERVER['PHP_SELF'] . '?trackid=' .$trackid ?>"  method="POST">
                   <div class='form-group hidden'>
                    <input name='tid' class='form-control' type='text' value='<?php echo $trackid ?>'>
                  </div>

                  <div class='form-group hidden'>
                    <input name='uid' class='form-control' type='text' value='<?php echo $_SESSION['userid'] ?>'>
                  </div>

                    <div class='form-group'>
                     <textarea name='comment' class='form-control' placeholder='Add New Comment'rows='5' required></textarea>
                    </div>

                   <div class='form-group'>
            <input type='submit' name='add-comment'  class='btn btn-default' value='Add Comment'>
                    </div>

              </form>




<?php




                if($_SERVER['REQUEST_METHOD'] == 'POST'){

                   if(isset($_POST['add-comment'])){ //query to insert comment in db
                   $tid=$_POST['tid'];
                   $comment=filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                   $userid=$_POST['uid'];

                    $stmti=$con->prepare(" INSERT INTO
                        comments
                        (comment_body, comment_stat , comment_date, USERID, TRACKID)
                          VALUES(:zcomment,0,NOW(),:zuser,:ztrack)

                      ");

                    $stmti->execute(array(

                          'zcomment'  => $comment ,
                          'zuser'     => $userid ,
                          'ztrack'      => $tid

                    ));

                   $counti=$stmti->rowCount();

                   if($counti > 0){
                     $msg="<div class='alert alert-success'>Comment Added Successfuly</div>";
                     echo $msg;
                     header("refresh:0;url=our-tracks.php?trackid=$trackid");
                   }else{
                      $msg="<div class='alert alert-danger'>Comment Added Failed</div>";
                     echo $msg;
                                   }

                }

              }
                     elseif($action == 'deletec'){

                      $cid="";
                      if(isset($_GET['cid']) && is_numeric($_GET['cid']) )
                          {
                            $cid=intval($_GET['cid']);
                          }else{
                              echo 0;
                          }


                            $stmtc=$con->prepare(" SELECT *
                            from comments
                            WHERE comment_id=?  LIMIT 1 ");

                            $stmtc->execute(array($cid));
                            $countc= $stmtc->rowCount();
                      if($countc > 0){
                      $stmtx=$con->prepare("DELETE FROM comments WHERE comment_id=?");
                      $stmtx->execute(array($cid));
                      header("refresh:0;url=our-tracks.php?trackid=$trackid");


                      }

         }




           echo "</div>";
        echo "</div>";
       echo "</div>";


     echo "</div>";
  echo "</div>";


  }//session
  else{
    header("refresh:0;url=login.php");
  }

include 'includes/templates/footer.php' ;
ob_end_flush();

?>
