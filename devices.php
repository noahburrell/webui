<?php
include_once('conn.php');
include_once('libs/common.php');

$pageTitle="Devices";

//Check login status, auto redirect to login if not logged in, and update session information.
loginCheck($mysql,true);
timeoutCheck();
refreshSessionInfo($mysql); //Might only be necessary after user has modified their profile -- Increases MySQL server load
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/tds-lite.css">
    <link rel="stylesheet" type="text/css" href="css/tds.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <title>TELUS: Private Networks - Devices</title>
</head>
<body>


<!--HEADER-->
<?php require('libs/header.php'); ?>

<div class="container-fluid">

    <!-- SEARCH -->
    <div class="grid-row search">
        <a class="xs-3 my-2 offset-xs-2 col-md-3 offset-md-3" href="#">Filter <i class="icon icon-core-caret-down"></i></a>
        <a class="xs-3 my-2 col-md-3 " href="#">Sort <i class="icon icon-core-caret-down"></i></a>
        <a class="xs-4 my-2 col-md-3 "><!--onClick: open search modal--><i class="icon icon-core-spyglass"></i>Search</a>
    </div>

    <!-- NETWORK INFO -->
    <div class="grid-row networkInfo">
        <p class="xs-10 offset-xs-1">You have <span>&nbsp;0&nbsp;</span> devices connected</p>
    </div>

    <!--NETWORK LIST-->
    <div class="grid-row networkList">



        <div class="xs-12  col-md-3 addNetwork">
            <button type="submit" id="toLogin" name="toLogin" onclick="window.location.href='newNetwork.php';"><img src="images/icons/add.png" alt="Add a Device"</button>

            Add a Device
        </div>

    </div>


    <!--STATIC NAV-->

    <?php require('libs/navbar.php'); ?>

</div>

<?php require('libs/jsscripts.php'); ?>

</body>
</html>
