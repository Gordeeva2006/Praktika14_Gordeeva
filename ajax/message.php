<?
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

    $IdUser = $_SESSION['user'];
    $Message = $_POST["Message"];
    $IdPost = $_POST["IdPost"];

    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
?>