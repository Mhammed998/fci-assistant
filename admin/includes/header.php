<?php

 $class="";
 $id="";

    if( $_SERVER['REQUEST_URI'] == '/newphp/admin/admin-login.php'){
       $class="admin-log";
       $id="particle-id";
    }else{
        $class="dashboard";
    }





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php getTitle()?></title>
    <link href="layouts/css/bootstrap.min.css"rel="stylesheet">
    <link href="layouts/css/admin.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

</head>

<body id="<?php echo $id ?>" class="<?php echo $class ?>">
