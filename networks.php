<?php
include_once('conn.php');
include_once('libs/common.php');
include_once('libs/userAccount.php');

$pageTitle="Networks";
$success = null;
$error = null;

//Check login status, auto redirect to login if not logged in, and update session information.
loginCheck($mysql, true);
timeoutCheck();
refreshSessionInfo($mysql); //Might only be necessary after user has modified their profile -- Increases MySQL server load
#print_r($_SESSION);

$acc = new userAccount($mysql);

//Create a new network
if(isset($_POST['netname']) && !empty($_POST['netname'])){
    if($acc->createNetwork($_SESSION['id'], $_POST['netname'])){
        $success = "A new network was successfully created!";
    } else {
        $error = "An unknown error occured when creating a new network!";
    }
}

if(isset($_POST['deleteNetwork']) && !empty($_POST['deleteNetwork'])){
    if($acc->deleteNetwork($_SESSION['id'], $_POST['deleteNetwork'])){
        $success = "The network was successfully deleted!";
    } else {
        $error = "An unknown error occured when deleting the network";
    }
}

//Fetch list of networks the user owns and print out each network
$netlist = $acc->getNetworks($_SESSION['id']);
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require('libs/head.php'); ?>
        <title>TELUS: Private Networks - Networks</title>
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
            <p class="xs-10 offset-xs-1">You are connected to <span class="alttext">&nbsp;<?php echo count($netlist) ?>&nbsp;</span> <?php
            if(count($netlist) == 1){ echo 'network';}else{echo 'networks';} ?></p>
        </div>

        <div class="grid-row" align="center">
            <?php throwSuccess($success); ?>
            <?php throwError($error); ?>
        </div>

        <!--NETWORK LIST-->
        <div class="grid-row networkList">
            <!--
            <div class="xs-12 col-md-3">
              <i class="icon icon-core-hamburger icon--fw"></i>
              <img class="mx-3" src="images/icons/network/1.png" alt="Home"/>
              Home Network <br/>
              <p><span> 10 </span> Devices </p>
              <p><span>&nbsp;3&nbsp;</span> Users</p>
              <p>Online since October 13, 2017</p>
            </div>

            <div class="xs-12  col-md-3">
              <i class="icon icon-core-hamburger icon--fw"></i>
              <img class="mx-3"src="images/icons/PC.png" alt="Kim's PC"/>
                  Kim's PC<br />
              <p><span> &nbsp;5&nbsp; </span> Devices</p>
              <p><span> &nbsp;1&nbsp; </span> Users</p>
              <p>Online since August 10, 2017</p>
            </div>

            <div class="xs-12  col-md-3">
              <i class="icon icon-core-hamburger icon--fw"></i>
              <img class="mx-3"src="images/icons/work.png" alt="Work"/>
              Work <br/>
              <p><span> 16 </span> Devices</p>
              <p><span> 54 </span> Users</p>
              <p>Online since September 1, 2017</p>
            </div>

            <div class="xs-12  col-md-3">
              <i class="icon icon-core-hamburger icon--fw"></i>
              <img class="mx-3"src="images/icons/security.png" alt="Security System"/>
              Security System<br/>
              <p><span> 10 </span> Devices</p>
              <p><span>&nbsp;5&nbsp; </span> Users</p>
              <p>Online since September 1, 2017</p>
            </div>
            -->
            <form id="recaptcha-form" action="networks.php" method="post">
                <div class="xs-12  col-md-4 addNetwork">
                    <fieldset class="field" style="text-align: left;">
                        <label for="netname">Network Name</label>
                        <input id="netname" name="netname" type="text" required>
                    </fieldset>
                    <button style="width: 100%" class="button button--primary" type="submit">Create Network</button>
                </div>
            </form>
            <?php
            //print_r($netlist);
            foreach($netlist as $network){
                #Get number of devices for this network
                $deviceCount = $acc->getDeviceCount($network[4]);
                #Display network
                echo '
                <a href="devices.php?network='.$network[4].'">
                    <div class="xs-12  col-md-4">
                        <form action="networks.php" method="post">
                            <i class="icon icon-core-hamburger icon--fw"></i>
                            <img class="mx-3"src="images/icons/network/'.$network[11].'.png" alt="' .$network[6].'"/>
                            '.$network[6].'<br />
                            <p style="margin-left: 0">Devices: <span class="alttext">&nbsp;'.$deviceCount.'&nbsp; </span><br />
                            Online since: <span class="alttext">'.date("F jS, Y", strtotime($network[10])).'</span></p>
                            <button style="margin: 2px; width: 100%;" class="button button--primary" type="button" name="changeNetwork">Change Network</button>
                            <button style="margin: 2px; width: 100%;" class="button button--secondary" type="submit" name="deleteNetwork" value="'.$network[4].'">Delete Network</button>
                        </form>
                    </div>
                </a>
              ';
            }
            unset($network)
            ?>
        </div>

        <!--STATIC NAV-->
        <?php require('libs/navbar.php'); ?>

        </div>

        <!--JS FILES-->
        <?php require('libs/jsscripts.php'); ?>

    </body>
</html>
