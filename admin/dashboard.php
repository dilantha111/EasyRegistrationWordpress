<?php
session_start();

require("../classes/MembershipRequest.php");

if(!isset($_SESSION['username'])){
    header("Location: index.php");
}
$degree = $_SESSION["degree"];
$year = $_SESSION["year"];
$ID = $_SESSION['ID'];
$username = $_SESSION['username'];

$_memberShip = new MembershipRequest($ID,$degree,$year);
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
    <?php if (!isset($_POST['check']) && !isset($_POST['approve'])):?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <h1>Add Token to approve Membership Request</h1>
            <input type="text" name="token">
            <input type="submit" name="check">
        </form>
    <?php endif;?>
    <?php if(isset($_POST['check'])):?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <h1>Confirm Membership approval </h1>
            <?php
                $token = $_POST['token'];
                $details = $_memberShip->check($token);
            ?>
            <ul>
                <?php foreach ($details as $key => $value):?>
                    <li><span><?=$key?></span> <?=$value?></li>
                <?php endforeach;?>
            </ul>
            <input type="text" name="token" value="<?=$token?>" hidden>
            <input type="submit" name="approve">
        </form>
<!--        else if below is not working quite as expected. When the user refresh the browser window -->
<!--        it breaks. -->
    <?php elseif (isset($_POST['approve'])):?>
        <?php
            $token = $_POST['token'];
            $result = $_memberShip->ApproveRequest($token);
        ?>
        <?php if($result):?>
            <p><?=$result['username']?> has been successfully registered ...</p>
            <?php unset($_POST['approve']);?>
        <?php else:?>
            <p>Something went wrong registration failed ...</p>
        <?php endif;?>
    <?php endif;?>

</body>
</html>