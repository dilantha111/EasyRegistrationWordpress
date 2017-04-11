<?php if (isset($_GET['success'])) {
  echo "success" ;
} ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>test</title>
  </head>
  <body>
    <form class="" action="../Controller/addUser.php" method="post">
      <input type="text" name="name" value="">
      <input type="password" name="password" value="">
      <input type="email" name="email" value="">
      <input type="text" name="display_name" value="">
      <input type="submit" value="Add user">
    </form>
  </body>
</html>
