<?php
include("../server_side/functions.php");
//echo $_POST['email'];

register($_POST['email'], $_POST['password'], $_POST['user_bio']);

$user_id = getUserId($_POST['email']);

echo create_trash_and_unfiled($user_id)? 'true' : 'false';
