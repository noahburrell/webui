<?php
include_once('conn.php');
include_once('libs/common.php');
include_once ('libs/userAccount.php');

$pageTitle="Devices";
$success = null;
$error = null;

//Check login status, auto redirect to login if not logged in, and update session information.
loginCheck($mysql,true);
timeoutCheck();
refreshSessionInfo($mysql); //Might only be necessary after user has modified their profile -- Increases MySQL server load

//Make sure a network to lookup devices in is defined
if(!isset($_GET['network'])){
    header('Location: networks.php');
    die();
}

$acc = new userAccount($mysql);

//Verify the specified network is owned by the currently signed in user
if(!$acc->verifyNetworkOwnership($_GET['network'], $_SESSION['id']))
    die();

//Check if new device is to be created
if(isset($_POST['devname']) && !empty($_POST['devname'])){
    $password = null;
    if(isset($_POST['devpass']) && strlen($_POST['devpass']) >= 8 && strlen($_POST['devpass']) < 64){
        $password = $_POST['devpass'];
    }
    //Create a new device
    if($acc->addDevice($_GET['network'], $_POST['devname'], $_POST['devpass'])){
        $success = "A new device was successfully added!";
    } else {
        $error = "An unknown error occured when adding the device! Ensure the device does not use a shared password!";
    }
}

//Check if a device is to be deleted
if(isset($_POST['deleteDevice']) && !empty($_POST['deleteDevice'])){
    //delete device
    if($acc->deleteDevice($_GET['network'], $_POST['deleteDevice'])){
        $success = "Device was successfully removed!";
    } else {
        $error = "Device could not be deleted!";
    }
}

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
        <p class="xs-10 offset-xs-1">You have <span class="alttext">&nbsp;<?php print $acc->getDeviceCount($_GET['network']); ?>&nbsp;</span> devices connected to <?php print $acc->getNetName($_GET['network']); ?>.</p>
    </div>

    <div class="grid-row" align="center">
    <?php throwSuccess($success); ?>
    <?php throwError($error); ?>
    </div>

    <!--NETWORK LIST-->
    <div class="grid-row deviceList">
        <form action="devices.php?network=<?php echo $_GET['network']; ?>" method="post">
            <div class="xs-12  col-md-4">
                <fieldset class="field" style="text-align: left;">
                    <label for="devname">Device Name</label>
                    <input id="devname" name="devname" type="text" required>
                    <label for="devpass">Device Password</label>
                    <input id="devpass" name="devpass" type="text" minlength="8" placeholder="(Optional)">
                </fieldset>
                <button style="width: 100%; margin-top: 0;" class="button button--primary" type="submit">Add Device</button>
            </div>
        </form>

        <?php
        $devlist = $acc->getDevices($_GET['network']);
        //print_r($devlist);
        foreach($devlist as $device){
            #Display network
            echo '
                    <div class="xs-12  col-md-4">
                        <form action="devices.php?network='.$_GET['network'].'" method="post">
                            <i class="icon icon-core-hamburger icon--fw"></i>
                            <img class="mx-3"src="images/icons/network/2.png" alt="' .$device[4].'"/>
                            '.$device[4].'<br />
                            <p style="margin-left: 0;">
                            MAC:&nbsp;<span class="alttext">'.$device[3].'</span><br />                
                            Password:&nbsp;<span id="password_'.$device[1].'" class="alttext" id="password">'.$device[6].'</span> 
                            </p>
                        
                            <button style="margin: 2px; width: 100%;" class="button button--primary" type="submit" name="changeDevice" value="'.$device[0].'">Change Device</button>
                            <button style="margin: 2px; width: 100%;" class="button button--secondary" type="submit" name="deleteDevice" value="'.$device[0].'">Delete Device</button>
                        </form>
                    </div>
              ';
        }
        ?>

    </div>


    <!--STATIC NAV-->

    <?php require('libs/navbar.php'); ?>

</div>

<?php require('libs/jsscripts.php'); ?>

</body>
</html>
