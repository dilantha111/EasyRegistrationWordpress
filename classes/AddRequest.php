<?php

/**
 * Created by PhpStorm.
 * User: Dilantha
 * Date: 4/21/2017
 * Time: 12:08 AM
 * Description : use this class to check whether a user name available and add a request for a membership.
 */
class AddRequest
{
    private $wp_host = wp_host;
    private $wp_db_username = wp_db_username;
    private $wp_db_password = wp_db_password;
    private $wp_db = wp_database;

    private $sr_host = sr_host;
    private $sr_db_username = sr_db_username;
    private $sr_db_password = sr_db_password;
    private $sr_db = sr_database;

    private $wp_conn;
    private $sr_conn;

    public function __construct()
    {
        $this->wp_conn = new PDO("mysql:host=" .
            $this->wp_host . ";dbname=" .
            $this->wp_db, $this->wp_db_username, $this->wp_db_password);

        $this->wp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->sr_conn = new PDO("mysql:host=" .
            $this->sr_host . ";dbname=" .
            $this->sr_db, $this->sr_db_username, $this->sr_db_password);

        $this->sr_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /*
     * 1 => username available, 0 => username not available, 2 => error
     * */
    private function checkAvailable($username){
        try{
            $conn = $this->wp_conn;
            $sql = "SELECT * FROM `wp_users` WHERE `user_login` = '" . $username . "'";
            $st = $conn->prepare($sql);
            $st->execute();
            $result = $st->fetchAll();

            $conn = $this->sr_conn;
            $sql1 = "SELECT * FROM `registration` WHERE `username` = '" . $username . "'";
            $st1 = $conn->prepare($sql1);
            $st1->execute();
            $result1 = $st1->fetchAll();

            if (count($result) > 0 || count($result1) > 0){
                return 0;
            }else{
                return 1;
            }
        }catch (Exception $ex){
            return 2;
        }
    }

    public function addMember($username,$email,$displayName,$year,$degree){
        if($this->checkAvailable($username) == 1){
            try{
                $conn = $this->sr_conn;

                $sql0 = "INSERT INTO `registration` (`username`, `displayName`".
                    ", `email`, `year`, `degree`, `registered`)".
                    "VALUES ('" . $username . "', '" . $displayName . "', '"
                    . $email . "',$year,'" . $degree . "',0);";

                $conn->exec($sql0);

                $ID = $conn->lastInsertId();

                $token = uniqid($ID);

                $sql1 = "INSERT INTO `tokens` (`ID`, `token`) VALUES ($ID, '" . $token . "');";

                $conn->exec($sql1);

                return $token;
            }catch (Exception $ex){
                return false;
            }
        }else{
            return false;
        }
    }
}