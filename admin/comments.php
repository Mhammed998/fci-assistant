<?php
ob_start();
session_start();
if(isset($_SESSION['adminuser'])){
    $pageTitle='Dashboard | Comments';
    include 'init.php';
        /* get all admin info   */
        $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
        $stmt->execute(array($_SESSION['adminid']));
        $get=$stmt->fetch();

    include "includes/side-nav.php"; ?>

    <div id="comments">



    <?php

      $do='';
    if(isset($_GET['do'])){
       $do = $_GET['do'];
    }else{
        $do='Manage';
    }


        $stmt=$con->prepare(" SELECT
            comments.*,
            tracks.*,
            users.*
            FROM
            comments
            INNER JOIN
                tracks ON tracks.track_id = comments.TRACKID
            INNER JOIN
                users ON users.id = comments.USERID   ");

        $stmt->execute();
        $comments=$stmt->fetchAll();



        if($do == 'Manage'){ /* Manage members page */ ?>

           <a href="javascript:history.back()">&larr; back</a>


    <h1 class="text-center big-title">Manage Comments</h1>


     <div class="table-responsive" style="margin-top:50px">
        <table class="table table-bordered text-center">

           <tr>
             <td>Username</td>
             <td style="text-align:center;">Fullname</td>
             <td>Comment</td>
             <td>Track</td>
             <td>Date</td>
             <td>Controls</td>
           </tr>

           <?php

             /* function to get all users data */

               if(!empty($comments)) {
              foreach($comments as $comment){

                echo "<tr>";

                   echo"<td>". $comment['username'] ."</td>";
                    echo"<td>". $comment['fullname'] ."</td>";
                     echo"<td>". $comment['comment_body'] ."</td>";
                     echo"<td>". $comment['track_name'] ."</td>";
                     echo"<td>". $comment['comment_date'] ."</td>";

                     echo"<td>";
   echo "<a title='Delete' class='btn btn-danger confirm' href='comments.php?do=Delete&comid=" . $comment['comment_id'] . "'><i class='fas fa-trash-alt'></i></a>";
                     echo "</td>";

               echo "</tr>";

                        }

                    }else{
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                  
                        echo"<div class='alert alert-info'>there is no data to display</div>";
                    }

       echo "</table>";

   echo "</div>";

     }elseif($do = 'Delete'){


        if(isset($_GET['comid']) && is_numeric($_GET['comid'])){
            $cid=intval($_GET['comid']);
        }else{echo 0; }

        echo ' <h1 class="text-center big-title">Delete Comment</h1>';

      $stmt1=$con->prepare(" SELECT *
        from comments
        WHERE comment_id=?  LIMIT 1 ");

        $stmt1->execute(array($cid));
        $count= $stmt1->rowCount();

        // check if a person is exist in database

        if($stmt1->rowCount() > 0){

            $stmt=$con->prepare("DELETE FROM comments WHERE comment_id=? ");
            $stmt->execute(array($cid));

            $theMsg ="<div class='alert alert-success'>". $stmt1->rowCount() ." Record Deleted </div>";
            echo $theMsg;
            header("location:comments.php");

        }  else{
         $theMsg ="<div class='alert alert-success'> This Id Is Not Exist </div>";
         echo $theMsg;
      header("location:comments.php");

        }


     }elseif($do == 'add-comment'){
         echo "add comment";
     }















    echo"</div>";

 } else{
       header("refresh:0;url=login.php");
 }

include 'includes/footer.php' ;
ob_end_flush();

?>
