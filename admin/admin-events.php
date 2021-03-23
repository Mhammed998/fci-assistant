<?php 

ob_start();
session_start();

if(isset($_SESSION['adminuser'])){ 
    $pageTitle="Dashboard | Events ";
    include 'init.php';

    /* get all admin info   */

    $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
    $stmt->execute(array($_SESSION['adminid']));
    $get=$stmt->fetch();

   /* Fetch al events */

   $stmtv=$con->prepare(" SELECT
    *
    FROM
    events

   ");
   $stmtv->execute();
   $getv=$stmtv->fetchAll();
   $countv=$stmtv->rowCount();
  
   include "includes/side-nav.php"; 

    $do="";
   if(isset($_GET['do'])){
     $do= $_GET['do'];
   }else{
          $do="manage";
   }

   $eventID="";
   if(isset($_GET['vid']) && is_numeric($_GET['vid'])){
     $eventID= intval($_GET['vid']);
   }

  if($do == 'manage'){?>
    <a href="javascript:history.back()">&larr; back</a>
  <h1 class="big-title text-center">Manage Events</h1>
      <div class="row">

   <?php  
    
     foreach($getv as $event){

        echo "<div class='col-sm-6 col-md-4'>";
           echo "<div class='thumbnail event'>";
            echo "<img class='img-event'  src='uploads/events/" .$event['event_avatar'] ."' alt='event-img' >";
              echo "<div class='caption'>";
               echo "<h3><a href='".$event['link']."' target='_blank'>" . $event['event_title']."</a></h3>";
                echo "<span>".$event['event_date']."</span>";
                echo "<p>";
                   echo "<a href='?do=edit&vid=" . $event['event_id'] . "' class='btn btn-primary' role='button'>Edit</a>" ; 
                   echo " <a href='?do=delete&vid=" . $event['event_id'] ."' class='btn btn-danger' role='button'>Delete</a>"; 
                echo"</p>";
            echo "</div>";
            echo"</div>";
        echo"</div>";
      
     }


    ?>
    </div>

    <a href="?do=add" class="btn btn-info addevent">Add Event</a>

    <?php }elseif($do == 'delete'){

      echo "<h1 class='big-title text-center'>Delete Event</h1>";

      $stmts=$con->prepare("SELECT * FROM events WHERE event_id=?");
      $stmts->execute(array($eventID));
      $counts=$stmts->rowCount();

      if($counts > 0){
        $stmtx=$con->prepare("DELETE from events WHERE event_id=?");
        $stmtx->execute(array($eventID));
        $countx=$stmtx->rowCount();
            if($countx > 0){
              echo "<div class='alert alert-danger confirm'>".$countx." Record Deleted!</div>";
              header("refresh:2;url=admin-events.php");

            }
      }else{
          echo "<div class='alert alert-danger'> There is no such id !</div>";
          header("refresh:2;url=admin-events.php");
      }



    }elseif($do == 'add'){ ?>

      <a href="javascript:history.back()">&larr; back</a>

      <h1 class="text-center big-title">Add New Event</h1>
       
       <form id="addEvent" action="?do=insert" method="POST" enctype="multipart/form-data">
        
         <div class="form-group">
            <label>Event Name:</label>
            <input required type="text" class="form-control" name="eventn" placeholder="type the title of event">
         </div>

         <div class="form-group">
            <label>Event Body:</label>
            <textarea required rows="5" class="form-control" name="eventb" placeholder="type the topic of event"></textarea>
         </div>

         <div class="form-group">
            <label>Event Image:</label>
            <input type="file" class="form-control" name="eventavatar">
         </div>

         <div class="form-group">
            <label>Link:</label>
            <input type="text" class="form-control" name="eventlink">
         </div>

     <div class="form-group hidden">
      <label>Added By:</label>
       <select name='admin'>
         <?php
         $mine="AND id=". $_SESSION['adminid'];
         $admins= getAllThings('*','users','WHERE admin =1',$mine,'id','','');
           foreach($admins as $admin){
            echo "<option>".$admin['fullname']."</option>";
          }
         ?>
       </select>
    </div>

            <input type="submit" class="btn btn-primary" value="save">

       </form>    


   <?php }elseif($do == 'insert'){

     if($_SERVER['REQUEST_METHOD'] == "POST"){

       echo '<h1 class="text-center big-title">Insert New Event</h1>';

        $formErrors= array();
        $eventname=filter_var($_POST['eventn'],FILTER_SANITIZE_STRING);
        $eventbody=filter_var($_POST['eventb'],FILTER_SANITIZE_STRING);
        $link=$_POST['eventlink'];
        $avatarName=$_FILES['eventavatar']['name']; 
        $avatarSize=$_FILES['eventavatar']['size']; 
        $avatarTmp=$_FILES['eventavatar']['tmp_name']; 
        $avatarType=$_FILES['eventavatar']['type'];  
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
             if(!empty($_FILES['eventavatar']['name'])){
             $avatar= rand(0,10000) . "_" . $avatarName;
            }else{
                $avatar="event.jpg";
            }
        
        move_uploaded_file($avatarTmp,"uploads/events/". $avatar);

         $stmt=$con->prepare("INSERT INTO 
        events(event_title,event_body,event_avatar,event_date,link) 
        VALUES(:ztitle,:zbody,:zavatar,NOW(),:zlink);
        ");
        $stmt->execute(array(
         
            'ztitle' => $eventname,
            'zbody' => $eventbody,
            'zavatar' => $avatar,
            'zlink' => $link,

        ));
        $count=$stmt->rowCount();


        echo "<div class='alert alert-success'>".$count." New Event Added successfully</div>";
        header("refresh:2;url=admin-events.php");
        exit();

      }

     }

    }elseif($do == 'edit'){
      $stmtE=$con->prepare("SELECT * FROM events WHERE event_id=?");
      $stmtE->execute(array($eventID));
      $countE=$stmtE->rowCount();
      $events=$stmtE->fetch();

      if($countE > 0){ ?>
        <a href="javascript:history.back()">&larr; back</a>
            <h1 class='big-title text-center'>Edit Event</h1>
       <form id="addEvent" action="?do=update" method="POST">

     <input required value="<?php echo $events['event_id'] ?>" type="text" class="form-control hidden" name="eventid" placeholder="type the title of event">
         <div class="form-group">
            <label>Event Name:</label>
            <input required value="<?php echo $events['event_title'] ?>" type="text" class="form-control" name="eventn" placeholder="type the title of event">
         </div>
         <div class="form-group">
            <label>Event Body:</label>
            <textarea required v rows="5" class="form-control" name="eventb" placeholder="type the topic of event"><?php echo $events['event_body'] ?>
            </textarea>
         </div>
         <div class="form-group">
            <label>Link:</label>
            <input value="<?php echo $events['link'] ?>" type="text" class="form-control" name="eventlink">
         </div>
         
            <input type="submit" class="btn btn-primary" value="update">

       </form>    


      

    <?php }else{header("refresh:0;url=admin-events.php");} 
    
  }elseif($do == 'update'){

    
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
       echo"<h1 class='big-title text-center'>Update Event</h1>";
        $eventid=$_POST['eventid'];
        $ename=filter_var($_POST['eventn'],FILTER_SANITIZE_STRING);
        $ebody=filter_var($_POST['eventb'],FILTER_SANITIZE_STRING);
        $elink=filter_var($_POST['eventlink'],FILTER_SANITIZE_URL);
        $stmtu=$con->prepare(' UPDATE events SET event_title=?,event_body=?,link=? WHERE event_id=?');
        $stmtu->execute(array($ename,$ebody,$elink,$eventid));
       
        echo "<div class='alert alert-info'>".$stmtu->rowCount()." Record Updated !</div>";
     
       header("refresh:2;url=admin-events.php");
      


        
      }
    }





    
 }else{
    header("refresh:2;url=admin-login.php");
   
}


 include 'includes/footer.php' ;
ob_end_flush();

?>