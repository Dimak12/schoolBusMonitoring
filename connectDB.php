<?php
/* Database connection settings */
	/*$servername = "school-bus-monitoring.mysql.database.azure.com";
    $username = "plandi";		//put your phpmyadmin username.(default is "root")
    $password = "Bus@1205";			//if your phpmyadmin has a password put it here.(default is "root")
    $dbname = "busmonitoring";*/

    $servername = "localhost";
    $username = "root";		//put your phpmyadmin username.(default is "root")
    $password = "";			//if your phpmyadmin has a password put it here.(default is "root")
    $dbname = "bus_monitoring";
    
    
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }
?>