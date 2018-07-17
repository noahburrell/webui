<?php
include_once('libs/common.php');
include_once('conn.php');
include_once('libs/userAccount.php');
//DEBUGGING - PRINT SESSION ARRAY
printf("SessionID => ".session_id()." ");
print_r($_SESSION);
echo '<br /><br />';
$acc = new userAccount($mysql);
print_r($_COOKIE['remember']);