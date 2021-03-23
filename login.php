<?php
 ob_start();
 session_start();
 $pageTitle="FCI-Assistant | Signin";


 if(isset($_SESSION['username'])){
    header('location:home.php');
}
  include 'init.php';




  if($_SERVER['REQUEST_METHOD'] == 'POST'){

     if(isset($_POST['login'])){

        $name=filter_var($_POST['username'],FILTER_SANITIZE_STRING);
        $password=$_POST['password'];
        $hpass=sha1($password);

        $stmt=$con->prepare('select * from users where username=? and userpass=?   ');
        $stmt->execute(array($name,$hpass));
        $get=$stmt->fetch();
        $cout=$stmt->rowCount();

        $formErrors=array();

        if(empty($name)){
            $formErrors[]="Username Is Required";
        }

            if(empty($password)){
            $formErrors[]="Password Is Required";
        }

        if(empty($formErrors)){
        if($cout > 0){
            $_SESSION['username']=$name;
            $_SESSION['userid']=$get['id'];
            header('location:home.php');

        }
        else{
            $formErrors[]="Username Or Password Must Be Wrong";
        }
    }


     }//login


  }//SERVER
  else{
      echo "";
  }


?>


<div class="container">
    <div class="row">


     <div class="col-md-7">
         <div class="content sm-content">
             <h1 class="h-intro">FCI-Assistant </h1>
             <p class="p-intro">Your Assistant Helps You To Find Your Track And Start Your Career With Awesome Materials & Arranged Steps ,, Learn With Your Mates :)  </p>
             <img class="img-responsive"  src="layouts/images/intro.png" alt="image-people">
             <ul class="list-unstyled">
                 <li class="contact"> You can contact me with: </li>
                 <li class="social"><i class="fab fa-facebook"></i><a href="#">Facebook</a> </li>
                 <li class="social"><i class="fab fa-google-plus"></i> <a href="#">Gmail </a> </li>
             </ul>

         </div>
     </div>


      <div class="col-md-5">


            <form id="login" class="myform" action="<?php $_SERVER['PHP_SELF']  ?>" method="POST" >
                <i class="far fa-user user-login"></i>
                <h3 class="log-h"> Sign In</h3>
                <input required class="form-control" type="text" name="username" placeholder="Type Your Username Here"  autocomplete="off">
                <div class="contain">
                <input required id="secret" class="form-control" type="password" name="password" placeholder="Type Your Password Here" >
                <i id="showpass" class="fas fa-eye"></i>
                </div>

                <a href="register.php" id="sign-up" class="not">Create A New Account?</a><br>

                <input type="submit" name="login" class="btn btn-primary btn-block" value="Login">

                <div class="errors">
                    <?php
                    if(!empty($formErrors)){
                      foreach($formErrors as $error){
                          echo "<div class='custom-error alert alert-danger'>";
                          echo "<i id='close' class='fas fa-times'> </i>" ." ". $error;
                          echo"</div>";
                      }
                    }

                    if(isset($successMsg)){
                        echo "<div class='custom-suc alert alert-success'>"."<i class='fas fa-check'></i>"." ". $successMsg ."</div>";
                    }
                    ?>
                </div>

            </form>



            <!-- End Login form   -->
      </div>



  </div>
 </div>











<?php
 include 'includes/templates/footer.php' ;
ob_end_flush();
?>
