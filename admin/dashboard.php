<?php
session_start();

require("../classes/MembershipRequest.php");

if(!isset($_SESSION['username'])){
    header("Location: index.php");
}
$degree = $_SESSION["degree"];
$year = $_SESSION["year"];

$_memberShip = new MembershipRequest();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    You are logged in as <?php echo $_SESSION['username']?>
    <form action="../Controller/adminLogout.php">
        <input type="submit" value="Logout">
    </form>
    <?php
        $test = new MembershipRequest(3,"CST",4);
        print_r($test->ApproveRequest("758f8f0f941003"));
    ?>
</body>
</html>