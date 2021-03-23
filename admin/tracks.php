<?php
ob_start();
session_start();
if(isset($_SESSION['adminuser'])){
    $pageTitle='Dashboard | Tracks';
    include 'init.php';
    include "includes/side-nav.php";

      /* get all admin info   */

    $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
    $stmt->execute(array($_SESSION['adminid']));
    $get=$stmt->fetch();

    $do='';
    if(isset($_GET['do'])){
       $do = $_GET['do'];
    }else{
        $do='display';
    }

           $stmtx=$con->prepare(" SELECT
            tracks.*,
            users.fullname  as fullname
            FROM
            tracks
            INNER JOIN
                users ON users.id = tracks.admin_id ");

       $stmtx->execute();
       $tracks=$stmtx->fetchAll();


    if($do == 'display'){

         if(isset($_GET['trackid']) && is_numeric($_GET['trackid'])){
            $trackid= intval($_GET['trackid']);
        }
          echo'<a href="javascript:history.back()">&larr; back</a>';

    echo "<h1 class='text-center big-title'>Manage Tracks</h1>";


     if(!empty($tracks)){
         echo "<ul>";
     foreach($tracks as $track){

          $desc=$track['track_desc'];
          $p=substr($desc,0,100) . "...";

       echo"<li class='track list-unstyled'>";
          echo "<a class='title' href='tracks.php?do=details&trackid=".$track['track_id']." '>";
         echo $track['track_name'] ;
          echo "</a>";
          echo"<p>". $p."</p>";
          echo"<h6>By/ ". $track['fullname']."</h6>";
          echo "<div class='control'>";

           echo "<a style='margin-right:5px' class='btn btn-primary' href='tracks.php?do=edit&trackid=" . $track['track_id'] ."'>Edit";
           echo"</a>";

           echo "<a class=' confirm btn btn-danger' href='tracks.php?do=delete&trackid=" . $track['track_id'] . "'>Delete";
           echo"</a> ";



          echo"</div>";
          echo "</li>";
          echo"<hr>";
  }
        echo "</ul>";

         }else{
           echo"<div class='alert alert-info'>There Is No Track Yet</div>";
         }

   echo "<a style='margin: 10px 5px 50px 50px;' class='btn btn-primary' href='tracks.php?do=add'>New Track</a>";
             echo " <a style='margin: 10px 5px 50px 3px;' class=' btn btn-success' href='tracks.php?do=add-elements&trackid=" . $track['track_id'] . "'>New Elements";
           echo"</a>";


   }elseif($do == 'add-elements'){ ?>

       <a href="javascript:history.back()">&larr; back</a>

     <h1 class='text-center big-title'>Add New List</h1>

    <form class="add-track " action="?do=insert-elements" method="POST" >


         <div class="form-group">
          <label>List-heading:</label>
         <input class="form-control" type="text" name="heading" placeholder="Type heading of list" required>
        </div>

        <div class="form-group">
          <label>List-link:</label>
         <textarea rows="6" class="form-control" type="text" name="linking" placeholder="Type link of list" required></textarea>
        </div>

        <div class="form-group">
          <label>List-order:</label>
          <input class="form-control" type="text" name="order" placeholder="Type number " required>
        </div>

        <div class="form-group">
          <label>Selected Track:</label>
                     <select  name="track">

                        <?php

                          $stmt=$con->prepare('SELECT * FROM tracks');
                          $stmt->execute();
                          $tracks=$stmt->fetchAll();

                          foreach($tracks as $track){
                              echo"<option value='" .$track['track_id'] . "'>" .  $track['track_name'] ."</option>";
                          }

                        ?>

                    </select>
                    </div>



       <div class="form-group">
         <input class="btn btn-success"  type="submit" name="newelement" value="Add">
       </div>

    </form>





   <?php }elseif($do == 'insert-elements'){

      if($_SERVER['REQUEST_METHOD'] == 'POST'){

           echo'<a href="javascript:history.back()">&larr; back</a>';

            echo "<h1 class='text-center big-title'>Insert New List</h1>";

        $heading=filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
        $linking=filter_var($_POST['linking'],FILTER_SANITIZE_STRING);
        $track=$_POST['track'];
        $order=$_POST['order'];

       $stmtel=$con->prepare("INSERT INTO
        elements(element_heading,element_link,`order`,trackID)
        VALUES(:zheading,:zlinking,:zorder,:ztrack); ");
        $stmtel->execute(array(

            'zheading' => $heading,
            'zlinking' => $linking,
            'zorder' => $order,
            'ztrack' => $track

        ));

        $countel=$stmtel->rowCount();
         echo "<div class='alert alert-success'>".$stmt->rowCount()." Record Added</div>";
        header('refresh:2;url=tracks.php');




      } //POST

   } elseif($do == 'details'){

        if(isset($_GET['trackid']) && is_numeric($_GET['trackid'])){
            $trackid= intval($_GET['trackid']);
        }



      $stmtt=$con->prepare(" SELECT
            tracks.*,
            users.username  as username
            FROM
            tracks
            INNER JOIN
                users ON users.id = tracks.admin_id  where track_id=?");
            $stmtt->execute(array($trackid));
            $track=$stmtt->fetchAll();
            foreach($track as $trac){
            echo'<a href="javascript:history.back()">&larr; back</a>';

            echo "<h1 class='text-center big-title'>". $trac['track_name'] ."</h1>";

            $stmt4=$con->prepare("SELECT * FROM elements WHERE trackID=? ORDER BY `order` ");
            $stmt4->execute(array($trackid));
            $elements=$stmt4->fetchAll();
            $count4=$stmt4->rowCount();

            if($count4 > 0){

              echo"<h3 class='steps'>Course Steps</h3>";
               foreach($elements as $element){
                 echo "<div class='list'>";
                    echo "<h3>". $element['element_heading'] ."</h3>";
                    echo "<a target='_blank' href='" . $element['element_link'] . "'> Go to the course  &rarr; </a>";
                    echo "<div class='controls'>";
                    echo "<a href='?do=del&nid=" . $element['element_id'] . "' class='btn btn-danger'>Delete</a>";
                    echo "<a href='?do=edt&track=" .$trackid. "&nid=" . $element['element_id'] . "'  class='btn btn-primary'>Edit</a>";
                    echo "</div>";
                 echo"</div>";

               }


            }else{
              echo "<div class='alert alert-info'> There Is No Element Yet</div>";
            }




        }//foreach


    }elseif($do == 'del'){


         if(isset($_GET['nid']) && is_numeric($_GET['nid'])){
           $nid=intval($_GET['nid']);
         }

         echo "<h1 class='main-title text-center'>Delete Element </h1>";

         $del=$con->prepare("DELETE FROM elements WHERE element_id=?");
         $del->execute(array($nid));
         $delcount=$del->rowCount();

         if($delcount > 0){
           echo "<div class='alert alert-danger'>" . $delcount. " Record Deleted</div>";
           header("refresh:1;url=tracks.php");
           exit();
         }

    } // start edit query
    elseif($do == 'edt'){

          $nid="";
         if(isset($_GET['nid']) && is_numeric($_GET['nid'])){
           $nid=intval($_GET['nid']);
         }

         if(isset($_GET['track']) && is_numeric($_GET['track'])){
           $track=intval($_GET['track']);
         }

         echo "<h1 class='main-title text-center'>Edit Element </h1>";

         $edt=$con->prepare("SELECT * FROM elements WHERE element_id=?");
         $edt->execute(array($nid));
         $elm=$edt->fetch();
         $edtcount=$edt->rowCount();
         if($edtcount > 0){ ?>


           <form class="add-track " action="?do=upd" method="POST" >

                 <div class="form-group hidden">
                  <label>id</label>
                 <input value="<?php echo $elm['element_id'] ?>" class="form-control" type="text" name="elementid">
                </div>

                <div class="form-group">
                 <label>List-heading:</label>
                <input value="<?php echo $elm['element_heading'] ?>" class="form-control" type="text" name="heading" placeholder="Type heading of list" required>
               </div>

               <div class="form-group">
                 <label>List-link:</label>
                <textarea rows="6" class="form-control" type="text" name="linking" placeholder="Type link of list" required><?php echo $elm['element_link'] ?></textarea>
               </div>

               <div class="form-group">
                 <label>List-order:</label>
                 <input value="<?php echo $elm['order'] ?>" class="form-control" type="text" name="orders" placeholder="Type number " required>
               </div>

               <div class="form-group">
                 <label>Selected Track:</label>
                            <select  name="track">

                               <?php

                                 $stmt=$con->prepare('SELECT * FROM tracks where track_id=?');
                                 $stmt->execute(array($track));
                                 $tracks=$stmt->fetchAll();

                                 foreach($tracks as $track){
                                     echo"<option value='" .$track['track_id'] . "'>" .  $track['track_name'] ."</option>";
                                 }

                               ?>

                           </select>
                           </div>



              <div class="form-group">
                <input class="btn btn-success"  type="submit" name="newelement" value="Add">
              </div>

           </form>




         <?php }

    } //start udate query
    elseif($do == 'upd'){

        if($_SERVER['REQUEST_METHOD'] == "POST"){

          $elementid=$_POST['elementid'];
          $elementhead=$_POST['heading'];
          $elementlink=$_POST['linking'];
          $orders=$_POST['orders'];

         echo "<h1 class='main-title text-center'>Update Element </h1>";

         $upd=$con->prepare(" UPDATE elements SET element_heading=? , element_link=? , `order`=? WHERE element_id=? ");
         $upd->execute(array($elementhead,$elementlink,$orders,$elementid));
         $updcount=$upd->rowCount();

         if($updcount > 0){
           echo "<div class='alert alert-success'>" . $updcount. " Record Updated</div>";
           header("refresh:1;url=tracks.php");
           exit();
         } else{
           echo "<div class='alert alert-success'>" . $updcount. " Record Updated</div>";
           header("refresh:1;url=tracks.php");
           exit();
         }

    }

    }

    elseif($do == 'add'){ //Form to add new track ?>

    <a href="javascript:history.back()">&larr; back</a>


     <h1 class='text-center big-title'>Create New Track</h1>

    <form class="add-track " action="?do=insert" method="POST" enctype="multipart/form-data">
       <div class="form-group">
          <label>Track Name:</label>
         <input class="form-control" type="text" name="trackname" placeholder="Type name of track" required>
       </div>

        <div class="form-group">
          <label>Track Description:</label>
         <textarea rows="6" class="form-control" type="text" name="trackdesc" placeholder="Type description of track" required></textarea>
       </div>


      <div class="form-group">
          <label>Url Video:</label>
         <input class="form-control" type="text" name="trackvideo" placeholder="Type url of video" required>
       </div>

      <div class="form-group">
          <label>Track Image:</label>
         <input class="form-control" type="file" name="trackimg" placeholder="Type image of track" required>
       </div>


        <div class="form-group hidden">
                     <select  name="member">

                        <?php

                          $stmt=$con->prepare('SELECT * FROM users where admin=1 and id=?');
                          $stmt->execute(array($_SESSION['adminid']));
                          $members=$stmt->fetchAll();

                          foreach($members as $member){
                              echo"<option value='" .$member['id'] . "'>" .  $member['fullname'] ."</option>";
                          }

                        ?>

                    </select>
                    </div>

         <div class="form-group">
         <input class="btn btn-primary"  type="submit" name="newtrack" value="Save">
       </div>


    </form>




   <?php }elseif($do == 'insert'){


   if($_SERVER['REQUEST_METHOD'] == 'POST'){

     $tname=filter_var($_POST['trackname'],FILTER_SANITIZE_STRING);
     $tdesc=filter_var($_POST['trackdesc'],FILTER_SANITIZE_STRING);
     $trackvideo=filter_var($_POST['trackvideo'],FILTER_SANITIZE_URL);
     $admin=$_POST['member'];
        $avatarName=$_FILES['trackimg']['name'];
        $avatarSize=$_FILES['trackimg']['size'];
        $avatarTmp=$_FILES['trackimg']['tmp_name'];
        $avatarType=$_FILES['trackimg']['type'];
        $avatarAllowedExten=array("jpeg","jpg","png","gif");
        $avatarexten1=explode('.',$avatarName);
        $avatarexten2= end($avatarexten1);
        $avatarExtension=strtolower($avatarexten2);


          if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExten)){
        $formErrors[]=" This extension is not allowed ";
        }

        if($avatarSize > 4194304){
            $formErrors[]=" Avatar cant be more than 4MB";
        }

           if(empty($formErrors)){
             if(!empty($_FILES['trackimg']['name'])){
             $avatar= rand(0,10000) . "_" . $avatarName;
            }else{
                $avatar="track.jpg";
            }



     if(empty($errors)){


        move_uploaded_file($avatarTmp,"uploads/tracks/". $avatar);

         $stmt=$con->prepare("INSERT INTO
        tracks(track_name,track_desc,urlvideo,track_avatar,admin_id)
        VALUES(:zname,:zdesc,:zvideo,:zavatar,:zadmin);
        ");
        $stmt->execute(array(

            'zname' => $tname,
            'zdesc' => $tdesc,
            'zvideo' => $trackvideo,
            'zavatar' => $avatar,
            'zadmin' => $admin


        ));
        $count=$stmt->rowCount();

        echo "<div class='alert alert-success'>".$stmt->rowCount()." Recorded Added</div>";
        header('refresh:2;url=tracks.php');
     }
     else{
         foreach($errors as $e){
             echo "<div class='alert alert-danger'>".$e."</div>";
         }
        }
      }

   }//POST



    }elseif($do == 'delete'){

      echo" <h1 class='text-center big-title'>Delete Track</h1>";

        if(isset($_GET['trackid']) && is_numeric($_GET['trackid'])){
            $trackid= intval($_GET['trackid']);
        }

        $stmt2=$con->prepare("SELECT * FROM tracks WHERE track_id=?");
        $stmt2->execute(array($trackid));
        $count2=$stmt2->rowCount();

        if($count2 > 0){
          $stmt2=$con->prepare("DELETE FROM tracks WHERE track_id=?");
          $stmt2->execute(array($trackid));
          echo "<div class='alert alert-danger'>". $stmt2->rowCount() ." Record Deleted </div>";
          header("refresh:2;url=tracks.php");
        }else{
                    echo "<div class='alert alert-danger'>There is No such id !! </div>";

        }

    }elseif($do == 'edit'){


        $trackid=isset($_GET['trackid']) && is_numeric($_GET['trackid']) ? intval($_GET['trackid']) : 0;

        $stmt=$con->prepare(" SELECT *
            from tracks
            WHERE track_id=? ");

            $stmt->execute(array($trackid));
            $get=$stmt->fetch();
            $count= $stmt->rowCount();

            if($count > 0){  ?>

      <a href="javascript:history.back()">&larr; back</a>
      <h1 class='text-center big-title'>Edit Track</h1>

    <form class="add-track " action="tracks.php?do=update" method="POST">

    <input value="<?php echo $get['track_id'] ?>" class="form-control hidden" type="text" name="trackid" placeholder="Type name of track" required>

       <div class="form-group">
          <label>Track Name</label>
         <input value="<?php echo $get['track_name'] ?>" class="form-control" type="text" name="traname" placeholder="Type name of track" required>
       </div>

        <div class="form-group">
          <label>Track Description</label>
         <textarea rows="6" cols="10" class="form-control" type="text" name="tradesc" placeholder="Type description of track" required><?php echo $get['track_desc'] ?>
         </textarea>

       </div>



      <div class="form-group">
          <label>Url Video:</label>
         <input value="<?php echo $get['urlvideo'] ?>" class="form-control" type="text" name="trackvideo" placeholder="Type url of video" required>
       </div>


        <div class="form-group hidden">
                     <select value="<?php echo $get['fullname'] ?>"  name="mber">

                        <?php
                          $stmt=$con->prepare('SELECT * FROM users where admin=1');
                          $stmt->execute();
                          $members=$stmt->fetchAll();

                          foreach($members as $member){
                              echo"<option  value='" .$member['id'] . "'";
                                if($member['id'] == $get['admin_id']){echo 'selected' ;}
                              echo ">" .  $member['fullname'] ."</option>";
                          }

                        ?>

                    </select>
                    </div>

         <div class="form-group">
         <input class="btn btn-primary"  type="submit"  value="Save">
       </div>


    </form>
   <?php }

 }elseif($do == 'update'){

     $trackid=isset($_GET['trackid']) && is_numeric($_GET['trackid']) ? intval($_GET['trackid']) : 0;

           if($_SERVER['REQUEST_METHOD'] == 'POST'){
             $trackid=$_POST['trackid'];
              $trackN=$_POST['traname'];
              $trackD=$_POST['tradesc'];
              $trackV=$_POST['trackvideo'];
              $tid=$trackid;

              $stmt3=$con->prepare(" UPDATE tracks SET track_name=? , track_desc=? , urlvideo=? WHERE track_id=? ");
              $stmt3->execute(array($trackN,$trackD,$trackV,$trackid));
              $count3=$stmt3->rowCount();

                  echo"<h1 class='text-center big-title'>Update Track</h1>";


              echo "<div class='alert alert-success'>".$count3." Recored Updated</div>";
              header('refresh:1;url=tracks.php');
           }//post
           else{
             echo "You Cant Browse This Page Directly";
           }



    }










  } else{
    header("refresh:2;url=admin-login.php");
  }


include 'includes/footer.php' ;
ob_end_flush();

?>
