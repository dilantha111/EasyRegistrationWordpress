<?php
require 'config.php';

if(isset($_POST['name']) && isset($_POST['password'])){
  $name = $_POST['name'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $display_name = $_POST['display_name'];
  $nice_name = strtolower(str_replace(" ","",$display_name));

  /*
  ** Adding users to the wordpress table.
  */

  try {
    $conn = new PDO("mysql:host=".wp_host.";dbname=".wp_database,wp_db_username,wp_db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO `wp_users`(`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_status`, `display_name`)
    VALUES ('".$name."', MD5('".$password."'), '".$nice_name."', '".$email."', '0', '".$display_name."');";

    $ID = $conn->lastInsertId();

    $sql1 = "INSERT INTO `wp_usermeta` (`user_id`, `meta_key`, `meta_value`)
    VALUES ($ID, 'wp_capabilities', 'a:1:{s:10:\"subscriber\";b:1;}');";

    $sql2 = "INSERT INTO `wp_usermeta` (`user_id`, `meta_key`, `meta_value`)
    VALUES ($ID, 'wp_user_level', '0');";


    $conn->exec($sql0);
    $conn->exec($sql1);
    $conn->exec($sql2);

    header("Location: ../admin/addUser.php?success=1");

  } catch (PDOException $e) {
    echo "Connection failed ".$e->getMessage();
  }

}
