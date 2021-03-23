<?php

 ob_start();
 session_start();
 $pageTitle="FCI-Assistant | Admin";
 include 'init.php'; 

 if(isset($_SESSION['adminuser'])){
    header('location:dashboard.php');
}
  
 if($_SERVER['REQUEST_METHOD'] == 'POST'){

  $adminname=filter_var($_POST['adminname'],FILTER_SANITIZE_STRING);
  $adminpass=$_POST['adminpass'];
  $hashedpass=sha1($adminpass);

  $stmt=$con->prepare('SELECT * FROM users WHERE username=? AND userpass=? AND `admin`= 1 ');
  $stmt->execute(array($adminname,$hashedpass));
  $get=$stmt->fetch();
  $result=$stmt->rowCount();
  $formError=array();

  if($result > 0){
      $_SESSION['adminuser']=$adminname;
      $_SESSION['adminid']=$get['id'];
      $successMsg="Good, Your Data Is Valid";
      header('location:dashboard.php');
      exit();
   }

  else
  { $formError[]="There Is Something Wrong With This Data";  }


 }// request=post
 
   
 






?>

<div class="container">

<!-- Start login form   -->
<form id="login" class="myform" action="<?php $_SERVER['PHP_SELF']  ?>" method="POST"> 

    <h3 class="log-h"><i class="fas fa-user-tie"> </i> Admin Login</h3>
    <input class="form-control" type="text" name="adminname" placeholder="Type Your Username Here" required autocomplete="off">   
    <input class="form-control" type="password" name="adminpass" placeholder="Type Your Password Here" required>    
   

    <input type="submit" name="login" class="btn btn-primary btn-block" value="Login">

      
    <div class="errors">
         <?php  
        if(!empty($formErrors)){
          foreach($formErrors as $error){
              echo "<div class='custom-error'>";
                echo $error;
              echo"</div>";
          }
        }

        if(isset($successMsg)){
            echo "<div class='custom-suc'>". $successMsg ."</div>";
        } 

        ?>
    </div> 


</form>

<!-- End Login form   -->

</div>



<?php
 include 'includes/footer.php' ;
ob_end_flush();
?>
