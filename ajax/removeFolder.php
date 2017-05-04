<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo removeFolder($_POST['deleted-folder'],$_POST['refs-destination-folder']) ? 'true' : 'false';
?>