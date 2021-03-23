<?php
 ob_start();
 session_start();
 $pageTitle="FCI-Assistant | Register";
 if(isset($_SESSION['username'])){
    header('location:home.php');
}
  include 'init.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(isset($_POST['newUser'])){

        $formErrors= array();
        $username=$_POST['username'];
        $fname=$_POST['fname'];
        $password1=$_POST['pass1'];
        $password2=$_POST['pass2'];
        $email=$_POST['usermail'];
        $acdyear=$_POST['year'];
        $avatarName=$_FILES['avatar']['name'];
        $avatarSize=$_FILES['avatar']['size'];
        $avatarTmp=$_FILES['avatar']['tmp_name'];
        $avatarType=$_FILES['avatar']['type'];
        $avatarAllowedExten=array("jpeg","jpg","png","gif");
        $avatarexten1=explode('.',$avatarName);
        $avatarexten2= end($avatarexten1);
        $avatarExtension=strtolower($avatarexten2);


        $stmtx=$con->prepare("SELECT * from users where username=? ");
        $stmtx->execute(array($username));
        $count=$stmtx->rowCount();
        if($count > 0){
             $formErrors[] = "This Username Is Already Exists Before!";
        }

         if(isset($username)){

        $filterUsername = filter_var($username,FILTER_SANITIZE_STRING);

        if(strlen($filterUsername) < 3 ){
            $formErrors[] = "Username Must Be More Than 4 Characters!";
        }

      }

      if(isset($fname)){

        $filterUsername = filter_var($fname,FILTER_SANITIZE_STRING);

        if(strlen($filterUsername) < 3 ){
            $formErrors[] = "Username Must Be More Than 4 Characters!";
        }

      }






      if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExten)){
        $formErrors[]=" This extension is not allowed ";
        }

        if($avatarSize > 4194304){
            $formErrors[]=" Avatar cant be more than 4MB";
        }




    if(isset($password1) && isset($password2)){
        if(empty($password1)){
            $formErrors[]='Sorry, Password Cant Be Empty';
         }

       if($password1 !== $password2){
          $formErrors[]='Sorry, Password Is Not Matched';
       }
       else{
           $hashpass=sha1($password1);
       }

    }


    if(isset($email)){

        $emailUser = filter_var($email,FILTER_SANITIZE_EMAIL);

        if(filter_var($emailUser,FILTER_VALIDATE_EMAIL) != true){
            $formErrors[] = "This Email Is Not Valid";
        }

    }

        if(empty($formErrors)){
       if(!empty($_FILES['avatar']['name'])){
        $avatar= rand(0,1000000) . "_" . $avatarName;
            }else{
                $avatar="user.png";
            }

        move_uploaded_file($avatarTmp,"admin/uploads/avatars/". $avatar);

         $stmt=$con->prepare("INSERT INTO
        users(username,`userpass`,fullname,usermail,class,`admin`,approve,`Date`,avatar)
        VALUES(:zname,:zpass,:fname,:zemail,:zyear,0,0,NOW(),:zavatar)
        ");
        $stmt->execute(array(

            'zname' => $username,
            'zpass' => $hashpass ,
            'zemail' => $email,
            'fname' => $fname,
            'zyear' => $acdyear,
            'zavatar' => $avatar

        ));


        header("refresh:0;url=login.php");


      }








      }
    }//post-request
    else{
      
    }

  ?>


  <div id="register">
    <div  class="container">

            <!-- Start Signup form   -->
 <form id="signup"  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <h3 class="log-h"><i class="fas fa-registered"></i> Registeration</h3>
        <div class="inside">
            <div class="row">

                <div class="col-md-6">
                    <label>Username:</label>
                    <input  pattern="[a-zA-Z0-9]+"  class="form-control" type="text" maxlength="40" title="Only English & Max Length is 40 chars" name="username"
                    placeholder="Type Your Username Here"  autocomplete="off" required>
                    <label>FullName:</label>
                    <input class="form-control" type="text" maxlength="25" title="Max Length is 25 char" name="fname"
                    placeholder="Type Your Fullname Here" required autocomplete="off">
                    <label>Email:</label>
                    <input  class="form-control" maxlength="25" title="Max Length is 25 char" type="email" name="usermail"
                    placeholder="Type Your Valid Email Here" required>
                     <label>Academic year:</label>
                        <select  class="select-from" name="year" required="required">
                            <option  value="1">1th year</option>
                            <option  value="2">2th year</option>
                            <option  value="3">3th year</option>
                            <option  value="4">4th year</option>
                        </select>
                </div>

             <div class="col-md-6">
                    <label>password:</label>
                    <i id="showpass2" class="far fa-eye"></i>
                    <input id="secret2" maxlength="25" title="Max Length is 25 char" class="form-control" type="password"
                    name="pass1" placeholder="Type Your Password Here" required>
                    <label>Confirm password:</label>
                    <i id="showpass3" class="far fa-eye"></i>
                    <input id="secret3" maxlength="25" title="Max Length is 25 char" class="form-control" type="password"
                    name="pass2" placeholder="Confirm Your Password" required>
                    <label>Image:</label>
                        <input class="form-control" type="file" name="avatar">

              </div>

          </div>

          <div class="text-center">

           <input name="newUser" type="submit" class="btn btn-primary" value="Submit"> <br>
            <a href="login.php" class="not">Already Have An Account ?</a><br>
                <div class="errors">
                    <?php
                        if(!empty($formErrors)){
                        foreach($formErrors as $e){
                            echo "<div class='custom-error alert alert-danger'>";
                            echo "<i id='close' class='fas fa-times'> </i>" ." ". $e;
                            echo"</div>";
                        }
                        }
                   ?>
                    </div>



          </div>


       </div>

     </form>



                <!-- End Signup form   -->

       </div>
    </div>













<?php
 include 'includes/templates/footer.php' ;
ob_end_flush();
?>
