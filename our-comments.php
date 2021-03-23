<?php
 ob_start();
 session_start();


 if(isset($_SESSION['username'])){  $pageTitle="FCI-ASSISTANT | Our-Tracks"?>
   
   <div id="page-tracks">

   <?php 
   include "init.php";
   //include navbar in profile page
   include "includes/templates/navbar.php";


    $action='';
    if(isset($_GET['action'])){
    $action = $_GET['action'];
    }else{
    $action='Manage';
    }

    if($action == 'insertc'){
        echo "insert comment";
    }











    echo "</div>";


  }//session

include 'includes/templates/footer.php' ;
ob_end_flush();

?>