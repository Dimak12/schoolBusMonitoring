<?php
session_start();
if (!isset($_SESSION['Admin-name'])) {
  header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bus Location Tracking</title>
    <meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="icon" type="image/png" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/bus_location.css">
    <script type="text/javascript" src="js/bootbox.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/bus_location.js"></script>
     <!-- Call the initMap function when the page loads -->
     <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATl5l7QdgyvCWfHpi_P3T2m_x4GbH2FcI&callback=initMap"></script>
    <style>
        /* Specify a height for the map container */
        
    </style>
</head>
<body>
    <?php include'header.php';?>
    <main>
    <h1 class="slideInDown animated" style="color: #fff;">Bus Location</h1>
    <div class="form-style-5 slideInDown animated">
        <label for="busSelect"><b>Select Bus:</b></label>
        <select class="dev_sel" id="busSelect">
            <option value="">Select a Bus</option>
            <?php
            require'connectDB.php';
            $sql = "SELECT * FROM bus";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo '<p class="error">SQL Error</p>';
            } 
            else{
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                while ($row = mysqli_fetch_assoc($resultl)){
            ?>
            
            <option value="<?php echo $row['bus_id'];?>"><?php echo $row['bus_id']; echo" | "; echo $row['serving_area'];?></option>
                    
            <?php
                }
            }
            ?>
            <!-- Add more options for other buses -->
        </select>
    </div>

    
    <!-- Create a div to hold the map -->
    <div class="section">
        <div class="slideInRight animated">
            <div id="map"></div>
        </div>
    </div>
    
    </main>
</body>
</html>
