<?php

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

    <title>TELUS: Private Networks - Work Computer</title>
  </head>
  <body>


<!--NETWORK LIST PAGE-->
  <div class="container-fluid">

      <div class="grid-row ">
        <div class="xs-12 header">

          <div class="grid-row">
            <h1 class="xs-6">Device</h1>
            <div class="loggedIn xs-5 offset-xs-1">
              <img src="images/people/kimberly.png" alt="Kim"/><br/>Kim
            </div>
          </div>

        </div>
      </div>

      <div class="grid-row">
          <div class="xs-12 deviceInfo">

            <div class="grid-row">
              <img class="my-3" src="images/icons/PC.png" alt="Work Computer"/>
              <h2>Work Computer</h2>
              <div class="xs-10 offset-xs-1">
                <p>MAC Address: 00-14-22-01-23-45  </p>
                <p>IPv4 address: 192.168.0.88  |  IPv4 DNS Servers: 192.168.0.1</p>
                <p>Manufacturer: ASUS</p>
                <p>Description: ASUS Notebook PC X555L</p>
              </div>
            </div>

            <div class="grid-row">
              <div class="permissions">
                <a href="#" class="my-3 text--medium chevron-link chevron-link--inverted">
                  Set Permissions
                </a>
              </div>
            </div>

            <!--Toggle if anchor above is clicked-->
           <div class="grid-row">
             <div class="permissionList">
              <div class="xs-10 offset-xs-1">
                  <ul class="list xs-hidden">
                    <li class="list__item py-3"></li>
                  </ul>
              </div>
            </div>
          </div>

        </div>
    </div>

      <div class="grid-row">

        <div class="staticNavBar fixed-bottom">
          <div class="xs-2 offset-xs-1 active">
            <a href="networks.php"><img class="mx-2 my-1" src="images/icons/networks.png" alt="Networks"/><br/>Networks</a>
          </div>
          <div class="xs-2">
            <a href="devices.php"><img  class="mx-2 my-1" src="images/icons/devices.png" alt="Devices"/><br/>Devices</a>
          </div>
          <div class="xs-2">
            <a href="users.html"><img  class="mx-2 my-1" src="images/icons/users.png" alt="Users"/><br/>Users</a>
          </div>
          <div class="xs-2">
            <a href="settings.html"><img  class="mx-2 my-1" src="images/icons/settings.png" alt="Settings"/><br/>Settings</a>
          </div>
          <div class="xs-2">
            <a href="#"><img  class="mx-2 my-1"src="images/icons/logOut.png" alt="Log Out"/><br/>LogOut</a>
          </div>
        </div>

      </div>

</div>




<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script
    src="https://cdn.telus.digital/thorium/enriched/v0-latest/tds-enriched.min.js"></script>
<script
    src="https://unpkg.com/react@15.3.2/dist/react.js"></script>
<script
    src="https://unpkg.com/react-dom@15.3.2/dist/react-dom.js"></script>
<script
    src="js/scripts.js"></script>

</body>
</html>
