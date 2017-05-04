<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo editUserDetails($_POST['uname'], $_POST['old_password'], $_POST['new_password'], $_POST['user_bio']) ? 'true' : 'false';
?>