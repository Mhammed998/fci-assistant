<?php

function getTitle(){
  
    global $pageTitle;

    if(isset($pageTitle)){
        echo $pageTitle;
    }
    else{
        echo 'Default';
    }
  

 }


  //function to count all users info - v1.0 

 function numThings($field,$tablex,$cond1=null,$cond2=null,$orderby='id',$orderway='ASEC',$n=null){
     global $con;
    $stmtx=$con->prepare(" SELECT $field FROM $tablex  $cond1  $cond2   ORDER BY $orderby $orderway $n ");
    $stmtx->execute();
    $get=$stmtx->fetchAll();   
    $count=$stmtx->rowCount();  
    

    return $count;

 }

  //function to fetch all users info - v1.1 

  function getAllThings($field,$tablex,$cond1=null,$cond2=null,$orderby=null,$orderway=null,$n=null){
    global $con;
   $stmty=$con->prepare(" SELECT $field FROM $tablex  $cond1  $cond2   ORDER BY $orderby $orderway $n ");
   $stmty->execute();
   $gety=$stmty->fetchAll();   
   $count=$stmty->rowCount();  
   

   return $gety;

}