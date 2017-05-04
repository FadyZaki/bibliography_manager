<?php
include("connect.php");

function isLoggedIn() {
	return (isset($_SESSION['user_id']) && $_SESSION['logged_in'] == true);
}

function login($uname, $pass) {
	session_start();
	global $db;

	$query = "SELECT u.id, u.hash FROM user u WHERE u.uname = ? ";
	$queryParams = array($uname);
	$result = $db -> executeSelectOne($query, $queryParams);

	if(validate($pass, $result['hash'])) {
		$_SESSION['logged_in'] = true;
		$_SESSION['user_id'] = $result['id'];
		return true;
	}
	else {
		return false;
	}
	return $result;

}

function logout() {

	$_SESSION['logged_in'] = false;

	$_SESSION = array();
	session_destroy();

	return true;
}

function validate($plain, $hash) {

	$thisHash = hash('md5', $plain);
	//echo "this ".$thisHash;
	//echo "<br>that ".$hash;
	return $thisHash === $hash;
}

function register($uname, $pass, $user_bio) {
	global $db;

	$hash = hash('md5', $pass);

	$query = "INSERT INTO user (uname, hash, user_bio) VALUES(?,?,?)";
	$queryParams = array($uname, $hash, $user_bio);
	$insert = $db -> executeInsert($query, $queryParams);

	if($insert)
		return true;
	else
		return false;
}

function getUserId($uname) {
	global $db;

	$query = "SELECT u.id FROM user u WHERE u.uname = ? ";
	$queryParams = array($uname);
	$result = $db -> executeSelectOne($query, $queryParams);

	return $result['id'];
}


function create_trash_and_unfiled($user_id) {
	global $db;
   
   	$query = "INSERT INTO folders(name, user_id) VALUES (?, ?)";
	$queryParams = array('trash', $user_id);
    $trash_folder_created = $db -> executeInsert($query, $queryParams);


    $query = "INSERT INTO folders(name, user_id) VALUES (?, ?)";
	$queryParams = array('unfiled', $user_id);
    $unfiled_folder_created = $db -> executeInsert($query, $queryParams);

	return $trash_folder_created && $unfiled_folder_created;

}

function getRefs() {
    global $db;
    if(isset($_GET['folder']) && $_GET['folder'] != 'all') {
        $query = "SELECT r.* FROM refs r INNER JOIN folders f ON r.folder_id = f.id 
            WHERE f.user_id = ? AND f.name = ?";
        $queryParams = array($_SESSION['user_id'], $_GET['folder']);
    }
    else {
        $query = "SELECT r.* FROM refs r INNER JOIN folders f ON r.folder_id = f.id 
            WHERE f.user_id = ?";
        $queryParams = array($_SESSION['user_id']);
    }

    $results = $db -> executeSelectAll($query, $queryParams);

    $arr = array();
    $count = 0;
    foreach($results as $row) {
        $count++;                       
        $row_values = array(
                'id' => $row['id'],
                'title'=> $row['title'],
                'author'=> $row['author'],
                'date_added'=> $row['date_added'],
                'year_published'=> $row['year_published'],
                'pdf_url'=> $row['pdf_url'],
                'volume'=> $row['volume'],
                'abstract'=> $row['abstract'],
                'pages'=> $row['pages']
        );  


        array_push($arr, $row_values);

    }


 	return json_encode($arr);
 	//file_put_contents('ref_data.json', json_encode($arr), LOCK_EX);
 }

 function moveRefs($newFolder, $ids) {
	session_start();
	global $db;

	$query = "SELECT f.id FROM folders f 
        WHERE f.user_id = ? AND f.name = ?";
    $queryParams = array($_SESSION['user_id'], $newFolder);
    $executedQuery = $db -> executeSelectOne($query, $queryParams);
	$newFolderId = $executedQuery['id'];

	$query = "SELECT f.id FROM folders f 
        WHERE f.user_id = ? AND f.name = 'trash'";
    $queryParams = array($_SESSION['user_id']);
    $executedQuery = $db -> executeSelectOne($query, $queryParams);
	$trashFolderId = $executedQuery['id'];

	foreach ($ids as $id) {
    	$query = "SELECT r.folder_id FROM refs r WHERE id = ?";
    	$queryParams = array($id);
    	$executedQuery = $db -> executeSelectOne($query, $queryParams);
    	$refFolderId = $executedQuery['folder_id'];

    	if($newFolderId === $trashFolderId && $refFolderId === $trashFolderId){
    		$query = "DELETE FROM refs WHERE id = ?";
    		$queryParams = array($id);
    		$delete = $db -> executeDelete($query, $queryParams);

    		if (!$delete)
    			return false;
    	}
    	else {
			$query = "UPDATE refs r
			    SET r.folder_id = ?
			    WHERE id = ?" ;
			$queryParams = array($newFolderId, $id);
			$update = $db -> executeUpdate($query, $queryParams);

			if (!$update)
				return false;
    	}
	}

	//$in = join(',', array_fill(0, count($ids), '?'));
	//$query = "UPDATE refs r
    //    SET r.folder_id = ?
    //    WHERE id IN ($in)" ;
    


	return true;


}


 function addNewRef($folder, $title, $author, $url, $date_added, $year_published, $pages, $volume, $abstract) {
	session_start();
	global $db;

    $query = "SELECT f.id FROM folders f 
        WHERE f.user_id = ? AND f.name = ?";
    $queryParams = array($_SESSION['user_id'], $folder);
	$executedQuery = $db -> executeSelectOne($query, $queryParams);
	$folderId = $executedQuery['id'];

	$query = "INSERT INTO refs(folder_id, title, author, pdf_url, date_added, year_published, pages, volume, abstract) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$queryParams = array($folderId, $title, $author, $url, $date_added, $year_published, $pages, $volume,$abstract);
    $insert = $db -> executeInsert($query, $queryParams);
    
	if($insert)
		return true;
	else
		return false;

}

 function addNewFolder($folder_name) {
	session_start();
	global $db;

	$query = "INSERT INTO folders(name, user_id) VALUES (?,?) ";
	$queryParams = array($folder_name, $_SESSION['user_id']);
    $insert = $db -> executeInsert($query, $queryParams);
    
	if($insert)
		return true;
	else
		return false;

}

 
 function removeFolder($deleted_folder, $destination_folder) {
	session_start();
	global $db;

	$query = "SELECT r.id FROM refs r INNER JOIN folders f ON r.folder_id = f.id 
            WHERE f.user_id = ? AND f.name = ?";
    $queryParams = array($_SESSION['user_id'], $deleted_folder);
    $results = $db -> executeSelectAll($query, $queryParams);

    $query = "SELECT f.id FROM folders f 
        WHERE f.user_id = ? AND f.name = ?";
    $queryParams = array($_SESSION['user_id'], $destination_folder);
    $executedQuery = $db -> executeSelectOne($query, $queryParams);
	$destinationFolderId = $executedQuery['id'];

	foreach($results as $row) {
		$query = "UPDATE refs r SET r.folder_id = ? WHERE r.id = ?";
    	$queryParams = array($destinationFolderId, $row['id']);
    	$update = $db -> executeUpdate($query, $queryParams);
	}

	if (!$update) {
		return false;
	}


	$query = "DELETE FROM folders WHERE name = ?";
	$queryParams = array($deleted_folder);
	$delete = $db -> executeDelete($query, $queryParams);

	if (!$delete) {
		return false;
	}

	return true;
}


 function renameFolder($renamed_folder, $new_name) {
	session_start();
	global $db;


	$query = "UPDATE folders f SET f.name = ? WHERE f.name = ?";
	$queryParams = array($new_name, $renamed_folder);
	$update = $db -> executeUpdate($query, $queryParams);

	if (!$update) {
		return false;
	}

	return true;
}


 function updateRef($id, $title, $author, $url, $date_added, $year_published, $pages, $volume, $abstract) {
	session_start();
	global $db;

	$query = "UPDATE refs
	SET title = ? , author = ? , pdf_url = ? , date_added = ?, year_published = ?, pages = ?, volume = ?, abstract = ? WHERE id = ?";
	$queryParams = array($title, $author, $url, $date_added, $year_published, $pages, $volume,$abstract, $id);
    $update = $db -> executeUpdate($query, $queryParams);
    
	if($update)
		return true;
	else
		return false;

}

function editUserDetails($uname, $old_password, $new_password, $user_bio) {
	session_start();
	global $db;
	$query = "SELECT u.hash FROM user u WHERE u.id = ?";
	$queryParams = array($_SESSION['user_id']);
	$result = $db -> executeSelectOne($query, $queryParams);
	if(validate($old_password, $result['hash'])) {
		$query = "UPDATE user u SET u.hash = ?, u.uname = ?, u.user_bio = ? WHERE u.id = ?";
		$queryParams = array(hash('md5', $new_password), $uname, $user_bio, $_SESSION['user_id']);
		$update = $db -> executeUpdate($query, $queryParams);
		return $update;
	} else {
		return false;
	}
}