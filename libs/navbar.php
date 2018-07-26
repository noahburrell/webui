<?php
    include_once('common.php');
?>

<div class="grid-row">
    <div class="staticNavBar fixed-bottom">
        <div class="xs-3">
            <a href="<?php getURL("networks.php"); ?>"><img class="mx-2 my-1" src="images/icons/networks.png" alt="Networks"/><br/>Networks</a>
        </div>
        <!-- <div class="xs-2">
            <a href="<?php getURL("devices.php"); ?>"><img class="mx-2 my-1" src="images/icons/devices.png" alt="Devices"/><br/>Devices</a>
        </div>-->
        <div class="xs-3">
            <a href="<?php getURL("users.php"); ?>"><img  class="mx-2 my-1" src="images/icons/users.png" alt="Users"/><br/>Users</a>
        </div>
        <div class="xs-3">
            <a href="<?php getURL("settings.php"); ?>"><img  class="mx-2 my-1" src="images/icons/settings.png" alt="Settings"/><br/>Settings</a>
        </div>
        <div class="xs-3">
            <a href="<?php getURL("logout.php"); ?>"><img  class="mx-2 my-1" src="images/icons/logOut.png" alt="Log Out"/><br/>LogOut</a>
        </div>
    </div>
</div>