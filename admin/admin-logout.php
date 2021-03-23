
<?php

session_start();

unset($_SESSION['adminuser']);




header('location:admin-login.php');
exit();


?>