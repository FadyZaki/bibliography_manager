<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo moveRefs($_POST['newFolder'], $_POST['ids']) ? 'true' : 'false';
?>