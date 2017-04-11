<?php if (isset($_GET['error'])) {
  if($_GET['error'] == 1){
    echo "Username already exists";
  }elseif($_GET['success'] == 1){
    echo "Successfully added to the system...";
  }

} ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Student Registration System</title>
  </head>
  <body>
    <form class="" action="Controller/membershipRequest.php" method="post">
      <input type="text" name="user_name" value="" required>
      <input type="email" name="email" value="" required>
      <input type="text" name="display_name" value="" required>
      <select class="" name="year" required>
        <option value="4">Fourth Year</option>
        <option value="3">Third Year</option>
        <option value="2">Second Year</option>
        <option value="1">First Year</option>
      </select>
      <select class="" name="degree" required>
        <option value="cst">CST</option>
        <option value="iit">IIT</option>
        <option value="sct">SCT</option>
        <option value="mrt">MRT</option>
      </select>
      <input type="submit" value="submit">
    </form>
  </body>
</html>
