<?php
    include_once('common.php');
?>
<table class="header">
    <tr>
        <td>
            <h1><?php echo $pageTitle; ?></h1></td>
        <td class="loggedIn">
            <a href="<?php getURL("profilesettings.php"); ?>"><img src="<?php getURL($_SESSION['profile_img']); ?>" alt="Avatar"/></a><br/><?php echo $_SESSION["fname"]." ".$_SESSION['lname']; ?>
        </td>
    </tr>
</table>

<!--
<div class="grid-row header">
  <div class="xs-6">
    <h1><?php echo $pageTitle; ?></h1>
  </div>
  <div class="loggedIn xs-6">
    <a href="<?php getURL("profilesettings.php"); ?>"><img src="<?php getURL($_SESSION['profile_img']); ?>" alt="Avatar"/></a><br/><?php echo $_SESSION["fname"]." ".$_SESSION['lname']; ?>
  </div>
</div>
-->