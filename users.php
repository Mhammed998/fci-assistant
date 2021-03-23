<?php
ob_start();
session_start();
$pageTitle="FCI-ASSISTANT | Profile";


if(isset($_SESSION['username'])){
include "init.php"; 
include "includes/templates/navbar.php";

$stmt=$con->prepare('select * from users where id=?');
$stmt->execute(array($_SESSION['userid']));
$get=$stmt->fetch();


$do='';
if(isset($_GET['do'])){
   $do = $_GET['do'];
}else{
    $do='Edit';
}


if($do == 'Edit'){
    
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
         <div id="users">
            <div class="container">
            <a href="javascript:history.back()">&larr; back</a>
         <h1 class="text-center user-title ">Edit Profile</h1>

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

         <!--start fullname-->
                  <div class="form-group">
              
              <label class="col-sm-2 control-label">Fullname</label>
              <div class="col-sm-10 col-md-5">
                 <input class="form-control" type="text" name="fname" value="<?php echo $row['fullname']  ?>" autocomplete="off">
              </div>
            
            </div>
         <!--end fullname-->

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

         <!--start year-->
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
         <!--end year-->



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
echo "<div class='container'>";

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

if(strlen($uname) < 4){
 $formERS[]="<div class='alert alert-danger'> Username cant be less than 4 characters ! </div>";

}


if(empty($uname)){
 $formERS[]="<div class='alert alert-danger'> Username cant be empty ! </div>";
}

if(strlen($fname) < 4){
  $formERS[]="<div class='alert alert-danger'> Username cant be less than 4 characters ! </div>";
 
 }
 
 
 if(empty($fname)){
  $formERS[]="<div class='alert alert-danger'> Username cant be empty ! </div>";
 }

if(empty($email)){
 $formERS[]="<div class='alert alert-danger'> Email cant be empty ! </div>";
 }
 

   



  if(empty($formERS)){
     $stmt=$con->prepare('UPDATE users SET username=?, userpass= ?,fullname=?,usermail=?,class=? WHERE id=?');
     $stmt->execute(array($uname,$pass,$fname,$email,$year,$id));

    $theMsg ='<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated . </div>';
    echo $theMsg;
    header("refresh:1;url=profile.php");

  } else{
   echo "<div class='container'>";
    foreach($formERS as $err){
        echo $err;
    }
   echo "</div>"; 

    header("refresh:4;url=profile");
    
  }


}  
else {

$theMsg= "<div class='alert alert-danger'>you cant browse this page directly</div>";
echo $theMsg;
header("refresh:5;url=admin-profile.php");

}
echo "</div>"; 
echo '</div>';
echo '</div>';
}

}//if session exists 
else{
    echo "You cant browse this page directly!";
    header('location:login.php');
    exit();
  }
  
  ob_end_flush();  
  include "includes/templates/footer.php";
  
  ?>


