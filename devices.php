<?php
session_start();
if (!isset($_SESSION['Admin-name'])) {
  header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Buses</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--   	<link rel="icon" type="image/png" href="images/favicon.png"> -->
	<link rel="stylesheet" type="text/css" href="css/devices.css"/>

	<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.js"
	        integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
	        crossorigin="anonymous">
	</script>
    <script type="text/javascript" src="js/bootbox.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script src="js/dev_config.js"></script>
	<script>
	  	$(window).on("load resize ", function() {
		    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
		    $('.tbl-header').css({'padding-right':scrollWidth});
		}).resize();
	</script>
	<script>
		$(document).ready(function(){
		    $.ajax({
		      	url: "dev_up.php",
		      	type: 'POST',
		      	data: {
		        'dev_up': 1,
		  		}
	      	}).done(function(data) {
	  			$('#devices').html(data);
    		});
		});
	</script>
</head>
<body>
<?php include'header.php';?>
<main>
	<h1 class="slideInDown animated" style="color: #fff;">Add/Remove a bus</h1>

	<section class="container py-lg-5">
		<div class="alert_dev"></div>
		<!-- devices -->
		<div class="row">
			<div class="col-lg-12 mt-4">
				<div class="panel">
			      <div class="panel-heading" style="font-size: 19px; color:#fff">Your Buses:
			      	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#new-device" style="font-size: 18px; float: right; margin-top: -6px;">New Bus</button>
			      </div>
			      <div class="panel-body">
			      		<div id="devices"></div>
			      </div>
			    </div>
			</div>
		</div>
		<!-- \\devices -->
		<!-- New Devices -->
		<div class="modal fade" id="new-device" tabindex="-1" role="dialog" aria-labelledby="New Device" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h3 class="modal-title" id="exampleModalLongTitle">Add new device:</h3>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <form action="" method="POST" enctype="multipart/form-data">
			      <div class="modal-body">
			      	<label for="User-mail"><b>Bus_ID:</b></label>
			      	<input type="text" name="bus_id" id="bus_id" placeholder="Bus ID..." required/><br>
			      	<label for="User-mail"><b>Serving Area:</b></label>
			      	<input type="text" name="serv_area" id="serv_area" placeholder="Serving Area..." required/><br>
			      </div>
			      <div class="modal-footer">
			        <button type="button" name="dev_add" id="dev_add" class="btn btn-success">Create new Bus</button>
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			      </div>
			  </form>
		    </div>
		  </div>
		</div>
		<!-- //New Devices -->
	</section>
</main>
</body>
</html>