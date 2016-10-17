<?php
	session_start();
	session_destroy();
	include("./common/functions.php");
	redirect("login.php");
?>
