<div class="table-responsive-sm" style="max-height: 870px;"> 
  <table class="table">
    <thead class="table-primary">
      <tr>
        <th>Card UID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Gender</th>
        <th>Rel. Phone num</th>
        <th>Bus ID</th>
        
      </tr>
    </thead>
    <tbody class="table-secondary">
    <?php
      //Connect to database
      require'connectDB.php';

        $sql = "SELECT * FROM students";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo '<p class="error">SQL Error</p>';
        }
        else{
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
          if (mysqli_num_rows($resultl) > 0){
              while ($row = mysqli_fetch_assoc($resultl)){
      ?>
                  <TR>
                  	<TD><?php echo $row['card_uid'];?></TD>
                    <TD><?php echo $row['name'];?></TD>
                    <TD><?php echo $row['surname'];?></TD>
                    <TD><?php echo $row['gender'];?></TD>
                    <TD><?php echo $row['relative_phone'];?></TD>
                    <TD><?php echo $row['bus_id'];?></TD>
                  </TR>
    <?php
            }   
        }
      }
    ?>
    </tbody>
  </table>
</div>