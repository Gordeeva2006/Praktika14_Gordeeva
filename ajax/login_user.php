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

	$CountAttempt = 0;
	$Sql = "SELECT `attempt` FROM `users` WHERE `login` = '$login'";
	$QueryAttempt = $mysqli->query($Sql);
	if ($QueryAttempt->num_rows > 0) {
		$ReadAttempt = $QueryAttempt->fetch_assoc();
		$CountAttempt = $ReadAttempt['attempt'];
	}
	if ($CountAttempt >= 5) {
		echo md5(md5(-1));
		exit;
	}
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
	
	$id = -1;
	while($user_read = $query_user->fetch_row()) {
		$id = $user_read[0];
	}
	
	if($id != -1) {
		$_SESSION['user'] = $id;
		$CountAttempt = 0;
	} else {
		$CountAttempt += 1;
	}
	$Sql = "UPDATE `users` SET `attempt` = $CountAttempt WHERE `login` = '$login'";
	$mysqli->query($Sql);
	echo md5(md5($id));
?>