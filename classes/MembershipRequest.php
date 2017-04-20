<?php
require("../Controller/config.php");

class MembershipRequest
{
    private $wp_host = wp_host;
    private $wp_db_username = wp_db_username;
    private $wp_db_password = wp_db_password;
    private $wp_db = wp_database;

    private $sr_host = sr_host;
    private $sr_db_username = sr_db_username;
    private $sr_db_password = sr_db_password;
    private $sr_db = sr_database;

    private $degree;
    private $year;
    private $coordinatorID;

    private $wp_conn;
    private $sr_conn;

    private function genPass()
    {
        return "temp";
    }

    public function __construct($coordinatorID, $degree, $year)
    {
        $this->coordinatorID = $coordinatorID;
        $this->degree = $degree;
        $this->year = $year;

        $this->wp_conn = new PDO("mysql:host=" .
            $this->wp_host . ";dbname=" .
            $this->wp_db, $this->wp_db_username, $this->wp_db_password);

        $this->wp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->sr_conn = new PDO("mysql:host=" .
            $this->sr_host . ";dbname=" .
            $this->sr_db, $this->sr_db_username, $this->sr_db_password);

        $this->sr_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getRequests()
    {
        $sql = "SELECT * FROM `registration` WHERE `degree` = '" . $this->degree . "'" .
            "AND `year` = '" . $this->year . "' AND `registered` = 0";
        $st = $this->sr_conn->prepare($sql);
        $st->execute();
        return $st->fetchAll();
    }

    private function addToWordPress($name,$password,$email,$display_name,$nice_name){
        try {
            $conn = $this->wp_conn;

            $sql0 = "INSERT INTO `wp_users`(`user_login`, `user_pass`, `user_nicename`,".
                " `user_email`, `user_status`, `display_name`)".
                "VALUES ('" . $name . "', MD5('" . $password . "'), '" . $nice_name .
                "', '" . $email . "', '0', '" . $display_name . "');";

            $conn->exec($sql0);
            $ID = $conn->lastInsertId();

            $sql1 = "INSERT INTO `wp_usermeta` (`user_id`, `meta_key`, `meta_value`)".
                "VALUES ($ID, 'wp_capabilities', 'a:1:{s:10:\"subscriber\";b:1;}');";

            $sql2 = "INSERT INTO `wp_usermeta` (`user_id`, `meta_key`, `meta_value`)".
                "VALUES ($ID, 'wp_user_level', '0');";

            $conn->exec($sql1);
            $conn->exec($sql2);

            return true;

        } catch (PDOException $e) {
            // revert back everything.
            echo "Connection failed " . $e->getMessage();
        }
    }

    private function revertBackAproveRequest(){
        $sql0 = "UPDATE `registration` SET `registered` = 0,`cordID` = '' WHERE `ID` =".
        "(SELECT ID FROM `tokens` WHERE `token` = '" . $token . "')";
        $st = $this->sr_conn->prepare($sql0);
        $st->execute();
    }

    public function ApproveRequest($token)
    {
        try {
            $sql0 = "UPDATE `registration` SET `registered` = 1,`cordID` = '"
                . $this->coordinatorID . "' WHERE `ID` =" .
                "(SELECT ID FROM `tokens` WHERE `token` = '" . $token . "')";

            $sql1 = "SELECT * FROM `registration` WHERE `ID` =" .
                " (SELECT ID FROM `tokens` WHERE `token` = '" . $token . "')";

            $st = $this->sr_conn->prepare($sql0);

            if ($st->execute()) {
                $st1 = $this->sr_conn->prepare($sql1);
                $st1->execute();
                $result = $st1->fetchAll();

                if (count($result) > 0) {
                    $result = $result[0];
                    $name = $result["username"];
                    $password = $this->genPass();
                    $email = $result["email"];
                    $display_name = $result["displayName"];
                    $nice_name = strtolower(str_replace(" ", "", $display_name));

                    if($this->addToWordPress($name,$password,$email,$display_name,$nice_name)){
                        return true;
                    }else{
                        $this->revertBackAproveRequest();
                    }
                } else {
                    $this->revertBackAproveRequest();
                }

            } else {
                $this->revertBackAproveRequest();
            }

        } catch (Exception $ex) {
            $this->revertBackAproveRequest();
            return false;
        }

    }
}