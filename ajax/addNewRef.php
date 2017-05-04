<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo addNewRef($_POST['folder'], $_POST['title'], $_POST['author'], $_POST['url'], $_POST['date_added'], $_POST['year_published'], $_POST['pages'], $_POST['volume'], $_POST['abstract']) ? 'true' : 'false';
?>