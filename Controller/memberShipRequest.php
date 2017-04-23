<?php
/*
** 1.Checks the wordpess database whether the user name is available.
** 2.Membership details store to the database.
** 3.A unique token is generated and present to the person who request which he could users
**  to pay the membership fee to the degree cordinator
*/

/*
** error = 1 => user name already exists
*/

require 'config.php';
require '../classes/AddRequest.php';

$username = $_POST["user_name"];
$email = $_POST["email"];
$displayName = $_POST["display_name"];
$year = $_POST["year"];
$degree = $_POST["degree"];

$test = new AddRequest();
print_r($test->addMember($username,$email,$displayName,$year,$degree));