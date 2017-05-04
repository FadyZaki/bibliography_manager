<?php
require_once "database.php";
try {
	$db = new Db();
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>