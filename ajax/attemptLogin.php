<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo login($_POST['email'], $_POST['login-password']) ? 'true' : 'false';
?>