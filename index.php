<?php
include_once('libs/common.php');

//If loginCheck fails, function automatically returns false and redirects to login
//otherwise, if loginCheck succeeds, returns true then redirect to networks.php
if(loginCheck($mysql, true)){
    header("Location: networks.php");
//test
}
