<?php

ob_start();
session_start();

if(isset($_SESSION['adminuser'])){
    $pageTitle="FCI-Assistant | Dashboard";
    include 'init.php';

    /* get all admin info   */
$userid="";
   if(isset($_GET['userid']) && is_numeric($_GET['userid'])){
     $userid= intval($_GET['userid']);
   }
    $stmt=$con->prepare('SELECT * FROM users WHERE  id=?');
    $stmt->execute(array($userid));
    $get=$stmt->fetch();

   /* get numbers of boxes */

   $numUser= numThings('*','users','WHERE admin !=1','','id','','');
    $numAdmin= numThings('*','users','WHERE admin =1','','id','','');
   $numPending= numThings('*','users','WHERE admin !=1','AND approve= 0','id','Desc','');
   $lastUsers= getAllThings('username','users','WHERE admin !=1','AND approve= 1','Date','DESC','LIMIT 6');
   $TheAdmins= getAllThings('username','users','WHERE admin =1','AND approve= 1','Date','DESC','LIMIT 6');
   $coms= getAllThings('comment_body','comments','','','comment_date','DESC','LIMIT 6');
   $tracks= numThings('*','tracks','','','track_id','Desc','');
   $numEvent= numThings('*','events','','','event_id','','');
   $numCom= numThings('*','comments','','','comment_id','','');


 ?>

    <?php include "includes/side-nav.php"; ?>

    <!-- start dashboard page -->
        <div id="dashboard">

        <h1 class="text-center big-title">Dashboard</h1>

        <div class="row">

           <div class="col-md-3 col-sm-6">
               <div id="users" class="dash-box">
                   <div class="info">
                      <h5><a href="members.php?do=Manage">Members</a></h5>
                      <p><?php echo  $numUser ?></p>
                   </div>

                   <div class="icon">
                       <i class="fas fa-users"></i>
                   </div>
               </div>
           </div>

           <div class="col-md-3 col-sm-6">
               <div id="pending-users" class="dash-box">
               <div class="info">
                      <h5><a href="members.php?do=Manage&page=pending">Pending</a></h5>
                      <p><?php echo $numPending  ?></p>
                   </div>

                   <div class="icon">
                       <i class="fas fa-check"></i>
                   </div>
               </div>
           </div>

           <div class="col-md-3 col-sm-6">
               <div id="views" class="dash-box">
               <div class="info">
                      <h5><a href="tracks.php">Tracks</a></h5>
                      <p><?php echo $tracks ?></p>
                   </div>

                   <div class="icon">
                       <i class="fas fa-chalkboard-teacher"></i>
                   </div>
               </div>
           </div>




             <div class="col-md-3 col-sm-6">
               <div id="comments" class="dash-box">
               <div class="info">
                      <h5><a href="comments.php">Comments</a></h5>
                      <p><?php echo $numCom ?></p>
                </div>

                   <div class="icon">
                       <i class="fas fa-comments"></i>
                   </div>
               </div>
           </div>


        </div>


      </div>
      <!-- end dashboard page -->

      <div class="row">
            <div class="col-md-6">
              <div class="latest-users" style="margin-top:40px">
              <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fas fa-users"></i> Latest Users</h3>
                    </div>
                    <div class="panel-body">
                       <ul class="list-unstyled">
                       <?php
                        if(!empty($lastUsers)){
                         foreach($lastUsers as $name){
                          echo "<li>".$name['username']."</li>";
                           }
                        }else{
                          echo "<div style='margin-bottom:0px;margin-top:6px;' class='alert alert-info'>There Is No Users Yet</div>";
                        }
                        ?>
                       </ul>
                    </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="latest-comment" style="margin-top:40px">
              <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-user-tie"></i> The Admins</h3>
                </div>
                <div class="panel-body">
                 <ul class="list-unstyled">
                        <?php
                         foreach($TheAdmins as $admin)
                          echo "<li>".$admin['username']."</li>";
                        ?>
                       </ul>
                </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="latest-comment" style="margin-top:40px">
              <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-comments"></i> Comments</h3>
                </div>
                <div style="padding-bottom:0px;" class="panel-body">
                 <ul class="list-unstyled">
                        <?php
                         if(!empty($coms)){
                         foreach($coms as $com){
                          echo "<li>". $com['comment_body'] ."</li>";
                        }
                      }
                        else{
                          echo "<div style='margin-bottom:0px;margin-top:6px;' class='alert alert-info'>There Is No Comment Yet</div>";
                        }
                        ?>
                       </ul>
                </div>
                </div>
              </div>
            </div>

      </div>




















<?php }else{
    header('refresh:0;url=admin-login.php');
}


 include 'includes/footer.php' ;
ob_end_flush();

?>
