<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo addNewFolder($_POST['folder_name']) ? 'true' : 'false';
?>