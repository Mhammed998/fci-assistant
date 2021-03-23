<?php
 ob_start();
 session_start();




 if(isset($_SESSION['username'])){  $pageTitle="FCI-ASSISTANT | Home"?>

   <div class="profile-body">

   <?php
   include "init.php";


   // to logout when delete user


   $check = $con->prepare("SELECT *  FROM users  WHERE id=?");
   $check->execute(array( $_SESSION['userid'] ));
   $countCheck=$check->rowCount();

   if($countCheck = 0){
     session_start();
     unset($_SESSION['username']);
     header('Location:login.php');
     exit();
   }

   //include navbar in profile page
   include "includes/templates/navbar.php";
   ?>

    <?php

     $members= numThings('*','users','','','id','','');
     $events=  numThings('*','events','','','event_id','','');
     $tracks=  numThings('*','tracks','','','track_id','','');

     $stmt=$con->prepare('select * from users where id=?');
     $stmt->execute(array($_SESSION['userid']));
     $get=$stmt->fetch();
     $count=$stmt->rowCount();

     if($count == 0){
      echo "you cant browse this page directly!";
      unset($_SESSION['username']);
      header('location:login.php');
      exit();
     }

   /* start page content */ ?>

   <!-- start intro section -->

   <div id="slides" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <!-- <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
     <li data-target="#carousel-example-generic" data-slide-to="3"></li>
  </ol> -->

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">

    <div class="item active">
      <img src="layouts/images/slide1.png" alt="...">
      <div class="carousel-caption">
      <h3>Whatever You Want To Learn..This Is The Right Place!</h3>
         <p class='hidden-xs'> We are provide you with awesome materials that can help you to choice your track & begin your career.</p>

      </div>
    </div>

    <div class="item">
      <img src="layouts/images/slide2.jpg" alt="...">
      <div class="carousel-caption">
         <h3>Faculty Of Computer sciences And Informatics</h3>
         <p class=''>Zagazig university</p>

      </div>
    </div>

    <div class="item">
      <img src="layouts/images/slide3.jpg" alt="...">
      <div class="carousel-caption">
     <h3>Choose Your Track & Start Your Career </h3>
         <p class='hidden-xs'> We Provide You With All Materials And </p>

      </div>
    </div>

    <div class="item">
      <img src="layouts/images/slide4.jpg" alt="...">
      <div class="carousel-caption">
        <h3>Whatever You Want To Learn..This Is The Right Place!</h3>
         <p class='hidden-xs'> We are provide you with awesome materials that can help you to choice your track & begin your career.</p>
      </div>
    </div>


  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#sildes" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#slides" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


   <!-- end intro section -->

   <!-- start section statis  -->
     <div class="container">
        <section id="statis">
       <div class="row">

          <div class="col-md-4 col-xs-6">
            <div class="stat">
              <i class="fas fa-users"></i>
              <h1> <?php echo $members?>  </h1>
              <span>Members</span>
            </div>
          </div>

          <div class="col-md-4 col-xs-6">
            <div class="stat">
              <i class="fas fa-book"></i>
              <h1> <?php echo $tracks?> </h1>
              <span>Tracks</span>
            </div>
          </div>

         <div class="col-md-4 col-xs-6">
            <div class="stat">
              <i class="fas fa-newspaper"></i>
              <h1> <?php echo $events  ?> </h1>
              <span>Posts</span>
            </div>
          </div>

       </div>
     </div>
   </section>

   <!-- End section statis -->

   <!-- start section about us -->

    <section id="about-us" class="text-center">
       <div class="container">
          <h1 class="main-title">About Me</h1>
             <div class="row">

               <div class="col-md-3">
                   <div class="my-corner">

                      <div class="my-img">
                         <img src="layouts/images/me.jpg" alt="Admin Image" class="">
                      </div>

                        <h4 style="margin-top:50px;">Muhammed Hashim </h4>

                      <div class="socials">

                      </div>

                   </div>
               </div>

               <div class="col-md-9">
                   <div class="my-info">

                      <div class="p-content">
                      <p>
                        Professional Summary 2 year of experience as a front-end and back end developer
            						Strong skills creating static and responsive cross-browser websites with a lot of CSS3 and Javascript animations
            						Rich experience in a client-side optimization - fixing bugs, code refactoring, in-creasing the load speed
            						Project managing and teaching skills, remote work with customers and other developers
                      <p>
                    </div>

                        <div class="p-content">


                            <ul class="list-unstyled under">

                                 <li>
                                     <strong><i class="fas fa-university fa-fw m-r-8 colo"></i>Zagazig university  </strong>
                                     <span class="answer">, faculty of computer sciences (software engineering) </span>
                                 </li>


                                 <li>
                                     <strong><i class="far fa-calendar-alt m-r-8"></i> BIRTH DATE: </strong>
                                     <span class="answer">Novamber 1, 1998 </span>
                                 </li>


                                  <li>
                                     <strong><i class="fas fa-briefcase m-r-8"></i> JOB: </strong>
                                      <span class="answer">Freelancer,Full Stack Developer </span>
                                 </li>


                                 <li>

                                 <strong><i class="fas fa-user-tie m-r-8"></i> Phones: </strong>

                                 <span class="answer">+02 01115435882</span>


                                 </li>

                    </ul>
           </div>

                    <div class="p-content">
                      <ul class="list-unstyled">
                          <li class="contact"> You can contact me with: </li>
                          <li class="social"><i class="fab fa-facebook"></i><a target="_blank" href="https://www.facebook.com/mohamed.hashem.904750">Facebook</a> </li>
                          <li class="social"><i class="fab fa-google-plus"></i> <a target="_blank" href="#">Gmail </a> </li>
                      </ul>


                  </div>

                   </div>
               </div>


           </div>
       </div>
    </section>

   <!-- end section about us -->

   <!-- start section features -->

      <section id="our-features">
       <div class="container">
          <h1 class="main-title"> Our Features</h1>
           <p class="p">
            We Are Juniors At Faculty Of Computers And Informatics At Zagazig Universty , Hope To
            Help Everyone Who Wants To Start A New Career.
           </p>
           <div class="row">

             <div class="col-md-3">
               <div class="feature">
                  <i class="far fa-smile"></i>
                  <div class="desc">

                    <h5>Always Available</h5>
                    <span>
                    	I am avaliable always for my mates who want anything
                  </span>

                  </div>
               </div>
             </div>

             <div class="col-md-3">
               <div class="feature">
                  <i class="fas fa-users"></i>
                  <div class="desc">

                    <h5>Active Members</h5>
                    <span>
                      Our group has many active members who can help & motivate
                    </span>

                  </div>
               </div>
             </div>

             <div class="col-md-3">
               <div class="feature">
                   <i class="fas fa-laptop-code"></i>
                  <div class="desc">

                    <h5>Free Materials</h5>
                    <span>
                      We recommend our materials to you which are free and awesome
                   </span>

                  </div>
               </div>
             </div>


           <div class="col-md-3 ">
               <div class="feature">
                   <i class="far fa-bell"></i>
                  <div class="desc">

                    <h5>Know what's new</h5>
                    <span>
                    Keep in touch with all updates happens daily in our fields
                   </span>

                  </div>
               </div>
             </div>




           </div>
       </div>
    </section>

   <!-- end section features -->

   <!-- Start section courses -->

   <section id="courses">
     <div class="container">
     <h1 class="main-title text-center">Our Awesome Courses</h1>
     <?php  if($get['approve'] == 1) { ?>
    <p class="p">
      We Are Juniors At Faculty Of Computers And Informatics At Zagazig Universty , Hope To
      Help Everyone Who Wants To Start A New Career.
    </p>

      <div class="row">
        <?php
         $courses=getAllThings('*','tracks','','','track_id','','');
         foreach ($courses as $course){

          $thedesc=$course['track_desc'];
          $para=substr($thedesc,0,60) . "...";


           echo "<div class='col-md-4'>";
              echo"<div class='thumbnail'>";
               echo "<img class='img-thumbnail' src='admin/uploads/tracks/" . $course['track_avatar'] . "' alt='track-image'>";
                echo "<div class='caption'>";
                  echo "<h3><a href='our-tracks.php?trackid=" . $course['track_id'] . " '>" .$course['track_name'] . " Path</a></h3>";
                  echo "<p>";
                 echo" </p>";
               echo "</div>";
              echo "</div>";
           echo "</div>";

         }


        ?>

        </div>

                <?php }else{
          echo "<div class='not-approval'>Hello, " . $get['fullname'] . " Your Membership needs Approval From Admin To Show This Section </div>";
        } ?>
      </div>
   </section>

   <!-- End section courses -->


   <!-- start section latest posts -->
     <section id="posts">
       <div class="container">
        <div class="post-content">

        <h1 class="main-title text-center">Latest Posts</h1>
        <p class="p">
        We Are Juniors At Faculty Of Computers And Informatics At Zagazig Universty , Hope To
         Help Everyone Who Wants To Start A New Career.
        </p>

        <div class="row">
   <?php

       $stmtp=$con->prepare(" SELECT  * FROM events");
       $stmtp->execute();
       $posts=$stmtp->fetchAll();


           foreach($posts as $post) {

             $substring=substr($post['event_body'],0,200);
            echo '<div class="col-md-4">';
              echo'<div class="post">';


              echo '<div class="thumbnail">';
                echo '<a target="_blank" href="' . $post['link'] . '">';
                 echo '<img  src="admin/uploads/events/' . $post['event_avatar'] . '" alt="post-image">';
               echo '</a>';
                echo '<div class="caption">';
                  echo '<h4 class="media-heading"><a target="_blank" href="' . $post['link']  .'">' . $post['event_title'] .'</a></h4>';
                  echo  '<p>'  . $substring . '...</p>';
                   echo "<div class='date'>";
                    echo '<i class="far fa-calendar-alt"></i>';
                    echo '<span>' . $post['event_date']  . ' </span>';
                   echo "</div>";
                echo '</div>';
                echo '</div>';





           echo '</div>';
        echo '</div>';

           }

    ?>



        </div>

        </div>
       </div>
     </section>

   <!-- end section latest posts -->



   <!-- start section testimonials -->

    <section id="testimonials">
      <div class="container">

        <h1 class="main-title text-center">Testimonials</h1>
        <p class="p">
        We Are Juniors At Faculty Of Computers And Informatics At Zagazig Universty , Hope To
         Help Everyone Who Wants To Start A New Career.
        </p>

<div id="testimonial" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#testimonial" data-slide-to="0" class="active"></li>
    <li data-target="#testimonial" data-slide-to="1"></li>
    <li data-target="#testimonial" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">

    <div class="item active">
      <div class="img-area">
         <img class="img-circle" src="layouts/images/main.jpg" alt="img-user">
      </div>
      <div class="opinion">
        <a href="#"><h3>Medo</h3></a>
        <p>" Medo medoMedo medoMedo medoMedo medoMedo medoMedo medoMedo medo "</p>
      </div>
    </div>

    <div class="item ">
      <div class="img-area">
         <img class="img-circle" src="layouts/images/main.jpg" alt="img-user">
      </div>
      <div class="opinion">
        <a href="#"><h3>Medo</h3></a>
        <p>" Medo medoMedo medoMedo medoMedo medoMedo medoMedo medoMedo medo "</p>
      </div>
    </div>

    <div class="item">
      <div class="img-area">
         <img class="img-circle" src="layouts/images/main.jpg" alt="img-user">
      </div>
      <div class="opinion">
        <a href="#"><h3>Medo</h3></a>
        <p>" Medo medoMedo medoMedo medoMedo medoMedo medoMedo medoMedo medo "</p>
      </div>
    </div>

  </div>

       </div>
     </div>
    </section>


   <!--end section testimonials -- >


   <!-- start section contact -->

   <section id="contact">
     <div class="container">
       <div class="contain">
       <h1 class=" text-center main-title">Contact Us</h1>
        <p class="p">
          We Would Love To Contact With You .. Begin Your Future With Us

        </p>

       <div class="row">

             <div class="col-md-5">
                <div class="left">
                  <h4>About us</h4>
                  <p>
                    This Site Was Designed For Helping People To Find Their Track And Begin Their Career
                    With Free & Awesome Materials
                  </p>

                  <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> Egypt,elsharkia,Zagazig</li>
                    <li><i class="fas fa-university"></i> Faculty of computer sciences and informatics</li>
                    <li> #Facebook Group:</li>
                  </ul>


                </div>
             </div>



               <div class="col-md-7">
                 <div class="right" style="overflow:hidden">

                </div>
              </div>



       </div>



        </div>
     </div>
   </section>



   <!-- end section contact -->



  <div class="myfooter">

       &copy; 2019 - FCI-Assistant Made With <i class="far fa-heart"></i>  By <a href="#">MH</a>

  </div>





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
