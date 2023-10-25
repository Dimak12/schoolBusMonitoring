<?php 
session_start();
?>
<div class="table-responsive">          
	<table class="table">
		<thead>
	      <tr>
	        <th>Bus ID</th>
	        <th>Serving Area</th>
	        <th>Config</th>
	      </tr>
    	</thead>
    	<tbody>
			<?php  
		    	require'connectDB.php';
		    	$sql = "SELECT * FROM bus;";
				$result = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($result, $sql)) {
				    echo '<p class="error">SQL Error</p>';
				} 
				else{
				    mysqli_stmt_execute($result);
				    $resultl = mysqli_stmt_get_result($result);
				    echo '<form action="" method="POST" enctype="multipart/form-data">';
					    while ($row = mysqli_fetch_assoc($resultl)){
					    	echo '<tr>
							        <td>'.$row["bus_id"].'</td>
							        <td>'.$row["serving_area"].'</td>
							        <td>
								    	<button type="button" class="dev_del btn btn-danger" id="del_'.$row["bus_id"].'" data-id="'.$row["bus_id"].'" title="Delete this device"><span class="glyphicon glyphicon-trash"></span></button>
								    </td>
							      </tr>';
					    }
				    echo '</form>';
				}
		    ?>
    	</tbody>
	</table>
</div>
<!-- <button type="button" class="dev_pro_up btn btn-info" id="del_'.$row["id"].'" data-id="'.$row["id"].'"  title="Change this device Project"><span class="glyphicon glyphicon-cog"> </span></button> -->