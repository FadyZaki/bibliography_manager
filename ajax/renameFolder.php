<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo renameFolder($_POST['renamed-folder'], $_POST['new-name']) ? 'true' : 'false';
?>