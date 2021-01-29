<?php 
function OpenLamuseeDB()
 {
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "freakazoid2016!";
	$db = "db_lamusee";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
	return $conn;
 }
 
 
 function OpenOldLamusee()
 {
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "freakazoid2016!";
	$db = "lamusee_wp";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
	return $conn;
 }
 
function CloseLamuseeDB($conn)
 {
	$conn -> close();
 }
 
 ?>