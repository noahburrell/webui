<?php
include_once('conn.php');
include_once('libs/common.php');

$pageTitle="Settings";

//Check login status, auto redirect to login if not logged in, and update session information.
loginCheck($mysql, true);
timeoutCheck();
refreshSessionInfo($mysql); //Might only be necessary after user has modified their profile -- Increases MySQL server load
?>

<!DOCTYPE html>
<html>
<head>
    <?php require('libs/head.php'); ?>
    <title>TELUS: Private Networks - Settings</title>
</head>
<body>

<!--HEADER-->
<?php require('libs/header.php'); ?>

<div class="container-fluid">

    <!--STATIC NAV-->
    <?php require('libs/navbar.php'); ?>

    <!-- SEARCH -->
    <div class="grid-row search">
        <a class="xs-3 my-2 offset-xs-2 col-md-3 offset-md-3" href="#">Filter <i class="icon icon-core-caret-down"></i></a>
        <a class="xs-3 my-2 col-md-3 " href="#">Sort <i class="icon icon-core-caret-down"></i></a>
        <a class="xs-4 my-2 col-md-3 "><!--onClick: open search modal--><i class="icon icon-core-spyglass"></i>Search</a>
    </div>

</div>

<!--JS FILES-->
<?php require('libs/jsscripts.php'); ?>

</body>
</html