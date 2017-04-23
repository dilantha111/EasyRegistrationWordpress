<?php
$message_content = "";
$message_head = "";
if(isset($_GET['login_error'])){
    $message_content = "Error Login";
    $message_head = "Error";
}
else if(isset($_GET['info_logout'])){
    $message_content = "Successfully Logged out ...";
    $message_head = "Logout";
}
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Messages</title>
  </head>
  <body>
    <h1><?=$message_head?></h1>
    <p><?=$message_content?></p>
  </body>
</html>
