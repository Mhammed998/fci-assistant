<?php
ob_start();
session_start();
if(isset($_SESSION['adminuser'])){
    $pageTitle='Dashboard | Members';
    include 'init.php';

    $do='';
    if(isset($_GET['do'])){
       $do = $_GET['do'];
    }else{
        $do='Manage';
    }


    $bigtitle='Manage Members';
    $query='';
    if(isset($_GET['page']) && $_GET['page'] == 'pending' ){

        $query='AND approve=0';
        $bigtitle="Pending Members";

    }


    /* get all admin info   */
    $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
    $stmt->execute(array($_SESSION['adminid']));
    $get=$stmt->fetch();


?>

<div id="members">
<?php include "includes/side-nav.php"; ?>
<?php

if($do == 'Manage'){ /* Manage members page */ ?>

           <a href="javascript:history.back()">&larr; back</a>

          <h1 class="text-center big-title"><?php echo $bigtitle  ?></h1>


     <div class="table-responsive" style="margin-top:50px">
        <table class="table table-bordered text-center">

           <tr>
             <td>#ID</td>
             <td style="text-align:center;">Username</td>
             <td>Fullname</td>
             <td>Email</td>
             <td>Academic year</td>
             <td>Join at</td>
             <td>Controls</td>
           </tr>

           <?php

             /* function to get all users data */
               $all=getAllThings('*','users','WHERE admin !=1', $query ,'id','DESC','');
               if(!empty($all)) {
              foreach($all as $get){

                echo "<tr>";

                echo"<td>". $get['id'] ."</td>";
                    echo "<td>"; ?>
          <img class="img-circle" src="uploads/avatars/<?php echo $get['avatar'] ?>" height="40px" width="40px">
                   <?php echo"<span><a href='users-profile.php?memberid=" . $get['id'] . "'>". $get['username'] ."</a></span>";
                    echo"</td>";
                    echo"<td>". $get['fullname'] ."</td>";
                     echo"<td>". $get['usermail'] ."</td>";
                     echo"<td>". $get['class'] ."th</td>";
                     echo"<td>". $get['Date'] ."</td>";
                     echo"<td>";
                       echo "<a title='Delete' class='btn btn-danger confirm' href='members.php?do=Delete&userid=" . $get['id'] . "'><i class='fas fa-trash-alt'></i></a>";
                       if($get['approve'] == 0){
    echo "<a title='Approve' class='btn btn-info' href='members.php?do=Activate&userid=" . $get['id'] . "'><i class='fas fa-check'></i></a>";
                          }
                       echo"</td>";

               echo "</tr>";

                        }

                    }else{
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<td>-</td>";
                        echo"<div class='alert alert-info'>there is no data to display</div>";
                    }



            ?>



        </table>
            <?php
            if(!empty($all) && isset($_GET['page']) && $_GET['page'] == 'pending'){
                    echo "<a class='approve-all btn btn-info' href='members.php?do=approveall'><i class='fas fa-check'></i> Approve All</a>";
                }
            ?>
    </div>


 <?php } elseif($do == 'Activate'){ /* Manage active page */ ?>

    <h1 class="text-center big-title">Activate Members</h1>
     <?php
              if(isset($_GET['userid']) && is_numeric($_GET['userid'])){

                $userid= intval($_GET['userid']);

            }
            else{

                echo 0;
            }

            $stmt1=$con->prepare('select * from users where id=? LIMIT 1');
            $stmt1->execute(array($userid));
            $count=$stmt1->rowCount();

        if($count > 0){
            $stmt1=$con->prepare('UPDATE users SET approve=1 WHERE id=?');
            $stmt1->execute(array($userid));
            $count=$stmt1->rowCount();

            echo "<div class='alert alert-success'>" .$count ."Record Updated </div>";
            header('refresh:1;url=members.php');

        }else{

            echo "<div class='alert alert-danger'>There Is No Such Id</div>";
            header('refresh:1;url=members.php');
        }

        ?>


<?php } elseif($do == 'Delete'){ /* Manage delete page */ ?>

  <h1 class="text-center big-title">Delete Member</h1>

      <?php
            if(isset($_GET['userid']) && is_numeric($_GET['userid'])){

                $userid= intval($_GET['userid']);

            }
            else{

                echo 0;
            }

            //check if user is exist in database

            $stmt2=$con->prepare('SELECT * from users WHERE id=?');
            $stmt2->execute(array($userid));
            $count2=$stmt2->rowCount();

            if($count2 > 0){

                $stmt2=$con->prepare("DELETE FROM users WHERE id=? ");
                $stmt2->execute(array($userid));

                echo "<div class='alert alert-warning'>" .$count2 ." Record Deleted </div>";
                header('refresh:0;url=members.php');

            }else{
                echo "<div class='alert alert-danger'>There Is No Such Id</div>";
                header('refresh:1;url=members.php');
            }

           ?>




<?php } elseif($do == 'approveall'){

     echo ' <h1 class="text-center big-title">Approve All Users</h1>';

    $stmt5=$con->prepare(" SELECT * FROM users WHERE admin=0 AND approve =0 ");
    $stmt5->execute();
    $allusers=$stmt5->rowCount();

   if($allusers > 0){
       $stmtupdate=$con->prepare('UPDATE users SET approve=1 ');
       $stmtupdate->execute();
      $countx= $stmtupdate->rowCount();
       echo "<div class='alert alert-warning'>" .$countx ." Record Approved </div>";
       header('refresh:1;url=members.php');
   }else{
       echo "<div class='alert alert-danger'>There is no update</div>";
       header('refresh:2;url=members.php');
   }
?>

<?php } elseif($do == 'Edit'){

    if(isset($_GET['userid']) && is_numeric($_GET['userid'])){

        $userid= intval($_GET['userid']);

    }
    else{

        echo 0;
    }

    $stmt2=$con->prepare(" SELECT *
        from users
        WHERE  id=?  LIMIT 1 ");

        $stmt2->execute(array($userid));
        $row=$stmt2->fetch();
        $count2= $stmt2->rowCount();
        if($stmt2->rowCount() > 0){ ?>

          <a href="javascript:history.back()">&larr; back</a>

         <h1 class="text-center big-title">Edit Profile</h1>

         <form id="members-form" class="form-horizontal " action="?do=Update" method="POST">
             <input type="hidden" name="userid" value="<?php echo $userid  ?>">

         <!--start username-->
            <div class="form-group">

              <label class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10 col-md-5">
                 <input class="form-control" type="text" name="username" value="<?php echo $row['username']  ?>" autocomplete="off">
              </div>

            </div>
         <!--end username-->

        <!--start username-->
            <div class="form-group">

              <label class="col-sm-2 control-label">Fullname</label>
              <div class="col-sm-10 col-md-5">
                 <input class="form-control" type="text" name="fname" value="<?php echo $row['fullname']  ?>" autocomplete="off">
              </div>

            </div>
         <!--end username-->


        <!--start password-->
                  <div class="form-group ">

              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10 col-md-5">
              <input class="form-control" type="hidden" name="oldpassword"  value="<?php echo $row['userpass']  ?>" >
                 <input class="form-control" type="password" name="newpassword"  autocomplete="new-password" placeholder="Leave Blank if you dont want to change">
              </div>

            </div>
         <!--end password-->


          <!--start email-->
                  <div class="form-group ">

              <label class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10 col-md-5">
                 <input class="form-control" type="email" name="email" value="<?php echo $row['usermail']  ?>" autocomplete="off">
              </div>

            </div>
         <!--end email-->

         <!--start email-->
                   <div class="form-group ">

              <label class="col-sm-2 control-label">Academic year</label>
              <div class="col-sm-10 col-md-5">
                 <select name="year">
                     <option <?php if($row['class']==1){echo "selected";} ?>  value="1">1</option>
                     <option  <?php if($row['class']==2){echo "selected";} ?> value="2">2</option>
                     <option  <?php if($row['class']==3){echo "selected";} ?> value="3">3</option>
                     <option  <?php if($row['class']==4){echo "selected";} ?> value="4">4</option>
              </select>
              </div>

            </div>
         <!--end email-->



         <!--start submit-->
             <div class="form-group">

               <div class=" col-sm-offset-2  col-sm-10">
                 <input class="btn btn-primary " type="submit"  value="Save">
               </div>

            </div>
         <!--start submit-->




         </form>

        <?php }

    ?>


<?php }elseif($do == 'Update'){

echo '<h1 class="text-center big-title">Update Profile</h1>';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){

$id=$_POST['userid'];
$uname=$_POST['username'];
$fname=$_POST['fname'];
$email=$_POST['email'];
$year=$_POST['year'];
$pass='';
if(empty($_POST['newpassword'])){
    $pass=$_POST['oldpassword'];
}else{
    $pass=sha1($_POST['newpassword']);
}

$formERS=array();




if(empty($uname)){
 $formERS[]="<div class='alert alert-danger'> Fullname cant be empty ! </div>";
}

if(empty($fname)){
    $formERS[]="<div class='alert alert-danger'> Username cant be empty ! </div>";
   }

if(empty($email)){
 $formERS[]="<div class='alert alert-danger'> Email cant be empty ! </div>";
 }



 foreach($formERS as $err){
     echo $err;
 }


  if(empty($formERS)){
     $stmt=$con->prepare('UPDATE users SET username=?,fullname=?, userpass= ?,usermail=?,class=? WHERE id=?');
     $stmt->execute(array($uname,$fname,$pass,$email,$year,$id));

    $theMsg ='<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated . </div>';
    echo $theMsg;
    header("refresh:1;url=admin-profile.php");

  }


}
else {

$theMsg= "<div class='alert alert-danger'>you cant browse this page directly</div>";
echo $theMsg;
header("refresh:5;url=admin-profile.php");

}

echo '</div>';



 }



else{
    echo "There is no such directory";
    header('location:members.php');
}

  echo "</div>";



}else{
        header("refresh:2;url=admin-login.php");

}

include 'includes/footer.php' ;
ob_end_flush();

?>
