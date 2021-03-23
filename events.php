<?php
 ob_start();
 session_start();


 if(isset($_SESSION['username'])){  $pageTitle="FCI-ASSISTANT | Events"?>
   
   <div class="profile-body">
   <?php 
   include "init.php";
   //include navbar in profile page
   include "includes/templates/navbar.php";

   //fetch all events data to show
   
      $stmtv=$con->prepare(" SELECT
         *
         FROM
         events

         ");
         $stmtv->execute();
         $events=$stmtv->fetchAll();
         $countv=$stmtv->rowCount();




   ?>
    
   <h1 class="main-title text-center">Latest Events</h1> 

  <div class="container"> 
  <?php if($countv > 0) {?> 
    <div class="row">

     <?php  

       foreach($events as $event){
           echo "<div class='col-md-9'>";
            echo "<div class='thumbnail event'>";
            echo "<img class='img-event'  src='admin/uploads/events/" .$event['event_avatar'] ."' alt='event-img' >";
              echo "<div class='caption'>";
                 echo "<h3><a href='".$event['link']."' target='_blank'>" . $event['event_title']."</a></h3>";
                 echo "<p>".$event['event_body']."</p>";
                echo "<span class='date-tap'>".$event['event_date']."</span>";
             echo "</div>";
            echo"</div>";
           echo"</div>";  
       }
     ?>


    <div class="col-md-2 col-md-offset-1">
      <div class="ads">
        <h5>Ads Section</h5>
      </div>
    </div>


    </div>
    </div>

      <?php } else{
         echo "<div class='alert alert-info'>There Is No Event Yet!</div>";
      } ?>











 </div>
    <?php
 }//isset session?? 
 else{
    echo "you cant browse this page directly!";
    header('location:login.php');
    exit();
 }


 include "includes/templates/footer.php";

 ob_end_flush();

 ?>