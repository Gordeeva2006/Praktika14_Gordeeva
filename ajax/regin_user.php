<?php
	session_start();
	include("../settings/connect_datebase.php");

	$NowDate = date("Y-m-d H:i:s");
	$Sql = "SELECT * FROM `access_ip` WHERE `ip` = '$user_ip'";
	$QueryAccess = $mysqli->query($Sql);
	if ($QueryAccess->num_rows > 0) {
		$ReadAccess = $QueryAccess->fetch_assoc();
		$EndDate = $ReadAccess['endDate'];
		$StartDate = $ReadAccess['startDate'];

		if ($StartDate == $EndDate) {
			echo md5(md5(-1));
			exit;
		} else {
			$Sql = "UPDATE `access_ip` SET `startDate` = '$EndDate', `endDate` = '$NowDate' WHERE `ip` = '$user_ip'";
			$mysqli->query($Sql);
		}
	} else {
		$Sql = "INSERT INTO `access_ip`(`ip`, `startDate`, `endDate`) VALUES ('$user_ip', NULL, '$NowDate')";
		$mysqli->query($Sql);
	}
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
		echo $id;
	} else {
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");
		
		$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
			
		if($id != -1) $_SESSION['user'] = $id; // запоминаем пользователя
		echo $id;
	}
?>