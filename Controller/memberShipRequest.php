<?php
/*
** 1. Checks the wordpess database whether the user name is available.
** 2.Membership details store to the database.
** 3.A unique token is generated and present to the person who request which he could users
**  to pay the membership fee to the degree cordinator
*/

/*
** error = 1 => user name already exists
*/

require 'config.php';

$username = $_POST["user_name"];
$email = $_POST["email"];
$displayName = $_POST["display_name"];
$year = $_POST["year"];
$degree = $_POST["degree"];

try {
  $conn = new PDO("mysql:host=".wp_host.";dbname=".wp_database,wp_db_username,wp_db_password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM `wp_users` WHERE `user_login` = '".$username."'";
  $st = $conn->prepare($sql);
  $st->execute();
  $result = $st->fetchAll();

  $conn = new PDO("mysql:host=".sr_host.";dbname=".sr_database,sr_db_username,sr_db_password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql1 = "SELECT * FROM `registration` WHERE `username` = '".$username."'";
  $st1 = $conn->prepare($sql1);
  $st1->execute();
  $result1 = $st1->fetchAll();


  if(count($result) > 0 || count($result1) > 0){
    header("Location: ../index.php?error=1");
  }else{
    try {
      $conn = new PDO("mysql:host=".sr_host.";dbname=".sr_database,sr_db_username,sr_db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql0 = "INSERT INTO `registration` (`username`, `displayName`, `email`, `year`, `degree`, `registered`)
      VALUES ('".$username."', '".$displayName."', '".$email."',$year,'".$degree."',0);";

      $ID = $conn->lastInsertId();

      $token = uniqid($ID);

      $sql1 = "INSERT INTO `tokens` (`ID`, `token`) VALUES ($ID, '".$token."');";

      $conn->exec($sql0);
      $conn->exec($sql1);

      header("Location: ../index.php?success=1");

    } catch (PDOException $e) {
      echo "Connection failed ".$e->getMessage();
    }
  }

} catch (PDOException $e) {
  echo "Connection failed ".$e->getMessage();
}
