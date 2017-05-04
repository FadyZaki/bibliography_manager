<?php
	include("connect.php");
	error_reporting(-1);
	ini_set('display_errors', 'On');
  createUserTable();



  //Create user table in the database
	function createUserTable() {
	global $db;
	    $creatUserTableSql = "CREATE TABLE IF NOT EXISTS user (
		id int(6) NOT NULL AUTO_INCREMENT,
		uname varchar(30) NOT NULL,
		hash varchar(100) NOT NULL,
		user_bio varchar(100) NOT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY uname (uname)
		);";

		if($db->createTable($creatUserTableSql))
			echo "<h3>Table User created.</h3>";
	}


  function createFoldersTable() {
      global $db;
      $creatFolderTableSql = "CREATE TABLE IF NOT EXISTS folders(
          id INT(6) NOT NULL AUTO_INCREMENT,
          name VARCHAR(45) DEFAULT NULL,
          user_id INT(6) NOT NULL,
          PRIMARY KEY (id),
          FOREIGN KEY (user_id) REFERENCES user(id)
          );";

      if($db->createTable($creatFolderTableSql))
      	echo "<h3>Table Folder created.</h3>";

  }

  function createReferencesTable() {
      global $db;
      $createReferencesTableSql = "CREATE TABLE IF NOT EXISTS refs(
          id INT(6) NOT NULL AUTO_INCREMENT,
          pdf_url VARCHAR(50) NOT NULL,
          title VARCHAR(100) NOT NULL,
          author VARCHAR(100) NOT NULL,
          year_published INT(4) NOT NULL,
          date_added DATE NOT NULL,
          pages INT(10) DEFAULT NULL,
          abstract VARCHAR(45) DEFAULT NULL,
          volume INT(3) DEFAULT NULL,
          folder_id INT(6) NOT NULL,
          annote VARCHAR(500) DEFAULT NULL,
          book_title VARCHAR(100) DEFAULT NULL,
          chapter VARCHAR(100) DEFAULT NULL,
          how_published VARCHAR(500) DEFAULT NULL,
          institution VARCHAR(100) DEFAULT NULL,
          journal VARCHAR(100) DEFAULT NULL,
          note VARCHAR(500) DEFAULT NULL,
          issue_number INT(10) DEFAULT NULL,
          organization VARCHAR(100) DEFAULT NULL,
          publisher VARCHAR(100) DEFAULT NULL,
          PRIMARY KEY (id),
          UNIQUE KEY pdf_url_unique (pdf_url),
          FOREIGN KEY (folder_id) REFERENCES folders(id)
          );";

      if($db->createTable($createReferencesTableSql))
        echo "<h3>Table References created.</h3>";

  }


?>

