<?php 
function OpenLamuseeDB()
 {
	$dbhost = "";
	$dbuser = "";
	$dbpass = "";
	$db = "";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
	return $conn;
 }
 
 
 function OpenOldLamusee()
 {
	$dbhost = "";
	$dbuser = "";
	$dbpass = "";
	$db = "";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
	return $conn;
 }
 
function CloseLamuseeDB($conn)
 {
	$conn -> close();
 }
 
 ?>