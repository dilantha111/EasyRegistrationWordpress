<?php
session_start();

require 'config.php';

if(
    isset($_POST['email']) &&
    isset($_POST['password'])
){
    $email = $_POST['email'];
    $password = $_POST['password'];

    try{
        $conn = new PDO("mysql:host=" . sr_host . ";dbname=" . sr_database, sr_db_username, sr_db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql1 = "SELECT * FROM `cordinators` WHERE `email` = '" . $email . "' AND `password`='".$password."'";
        $st1 = $conn->prepare($sql1);
        $st1->execute();
        $result1 = $st1->fetchAll();
        if(count($result1) > 0){
            $result1 = $result1[0];
            $_SESSION['degree'] = $result1['degree'];
            $_SESSION['year'] = $result1['year'];
            $_SESSION['email'] = $result1['email'];
            $_SESSION['username'] = $result1['username'];
            $_SESSION['ID'] = $result1['ID'];
            header("Location: ../admin/dashboard.php");
        }else{
            header("Location: ../message.php?login_error=1");
        }
    }catch (PDOException $e) {
        echo "Connection failed " . $e->getMessage();
    }
}