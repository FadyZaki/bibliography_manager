<?php
include("../server_side/functions.php");
//echo $_POST['email'];

echo updateRef($_POST['id'], $_POST['title'], $_POST['author'], $_POST['pdf_url'], $_POST['date_added'], $_POST['year_published'], $_POST['pages'], $_POST['volume'], $_POST['abstract']) ? 'true' : 'false';
?>