<?php
include_once('conn.php');
include_once('libs/common.php');
include_once('libs/userAccount.php');



if(!isset($_GET['email']) || !isset($_GET['token']))
    die();

$a = new userAccount($mysql);

$status = $a->emailConf($_GET['email'], $_GET['token']);
if($status == 0){
    //Do something else eventually
    echo "Failed to confirm email! Redirecting to login...";
} else if($status == 1){
    //Do something else eventually - maybe redirect to login
    echo "Email successfully confirmed! Redirecting to login...";
} else if($status == 2 ){
    //Do something else eventually - maybe redirect to login
    echo "Email already confirmed! Redirecting to login...";
}

$url = getURL("login.php", true);
header( "Refresh:3; url=$url", true, 303);