
<?php
include("header.php");

if(isset($_POST['logout'])) {
	logout();
}


if(!isLoggedIn()) {
	$redirect = 'login.php';
} else {
	$redirect = 'main_page.php';
}

header('Location: ' . $redirect);


?>