<?php
include_once('conn.php');
include_once('libs/common.php');

$pageTitle="Profile Settings";

//Check login status, auto redirect to login if not logged in, and update session information.
loginCheck($mysql, true);
timeoutCheck();
refreshSessionInfo($mysql); //Might only be necessary after user has modified their profile -- Increases MySQL server load
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require('libs/head.php'); ?>
    </head>
    <body>

        <!--HEADER-->
        <?php require('libs/header.php'); ?>

        <div class="container-fluid">

            <!--STATIC NAV-->
            <?php require('libs/navbar.php'); ?>

        </div>

        <!--JS FILES-->
        <?php require('libs/jsscripts.php'); ?>

    </body>
</html