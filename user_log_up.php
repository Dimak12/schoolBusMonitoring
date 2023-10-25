<?php  
session_start();
?>
<div class="table-responsive" style="max-height: 500px;"> 
  <table class="table">
    <thead class="table-primary">
      <tr>
        <th>Student Number</th>
        <th>Card UID</th>
        <th>Full Name</th>
        <th>Bus ID</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
      </tr>
    </thead>
    <tbody class="table-secondary">
      <?php

        //Connect to database
        require'connectDB.php';
        $searchQuery = " ";
        $Start_date = " ";
        $End_date = " ";
        $Start_time = " ";
        $End_time = " ";
        $Card_sel = " ";
        $bus_id = " ";

        if (isset($_POST['log_date'])) {
          //Start date filter
          if ($_POST['date_sel_start'] != '') {
              $Start_date = $_POST['date_sel_start'];
              $_SESSION['searchQuery'] = "log_date='".$Start_date."'";
          }
          else{
              $Start_date = date("Y-m-d");
              $_SESSION['searchQuery'] = "log_date='".date("Y-m-d")."'";
          }
          //End date filter
          if ($_POST['date_sel_end'] != '') {
              $End_date = $_POST['date_sel_end'];
              $_SESSION['searchQuery'] = "log_date BETWEEN '".$Start_date."' AND '".$End_date."'";
          }
          //Time-In filter
          if ($_POST['time_sel'] == "Time_in") {
            //Start time filter
            if ($_POST['time_sel_start'] != '' && $_POST['time_sel_end'] == '') {
                $Start_time = $_POST['time_sel_start'];
                $_SESSION['searchQuery'] .= " AND time_in='".$Start_time."'";
            }
            elseif ($_POST['time_sel_start'] != '' && $_POST['time_sel_end'] != '') {
                $Start_time = $_POST['time_sel_start'];
            }
            //End time filter
            if ($_POST['time_sel_end'] != '') {
                $End_time = $_POST['time_sel_end'];
                $_SESSION['searchQuery'] .= " AND time_in BETWEEN '".$Start_time."' AND '".$End_time."'";
            }
          }
          //Time-out filter
          if ($_POST['time_sel'] == "Time_out") {
            //Start time filter
            if ($_POST['time_sel_start'] != '' && $_POST['time_sel_end'] == '') {
                $Start_time = $_POST['time_sel_start'];
                $_SESSION['searchQuery'] .= " AND time_out='".$Start_time."'";
            }
            elseif ($_POST['time_sel_start'] != '' && $_POST['time_sel_end'] != '') {
                $Start_time = $_POST['time_sel_start'];
            }
            //End time filter
            if ($_POST['time_sel_end'] != '') {
                $End_time = $_POST['time_sel_end'];
                $_SESSION['searchQuery'] .= " AND time_out BETWEEN '".$Start_time."' AND '".$End_time."'";
            }
          }
          //Card filter
          if ($_POST['card_sel'] != 0) {
              $Card_sel = $_POST['card_sel'];
              $_SESSION['searchQuery'] .= " AND card_uid='".$Card_sel."'";
          }
          //Department filter
          if ($_POST['dev_uid'] != 0) {
              $bus_id = $_POST['dev_uid'];
              $_SESSION['searchQuery'] .= " AND bus_id='".$bus_id."'";
          }
          
        }
        
        if ($_POST['select_date'] == 1) {
            $Start_date = date("Y-m-d");
            $_SESSION['searchQuery'] = "log_date='".$Start_date."'";
        }

        // $sql = "SELECT * FROM users_logs WHERE log_date=? AND pic_date BETWEEN ? AND ? ORDER BY id ASC";
        $sql = "SELECT * FROM attendance_logs WHERE ".$_SESSION['searchQuery']."";
        /*echo $sql;
        die;*/
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
                  <TD><?php echo $row['student_num'];?></TD>
                  <TD><?php echo $row['card_uid'];?></TD>
                  <TD><?php echo $row['full_name'];?></TD>
                  <TD><?php echo $row['bus_id'];?></TD>
                  <TD><?php echo $row['log_date'];?></TD>
                  <TD><?php echo $row['time_in'];?></TD>
                  <TD><?php echo $row['time_out'];?></TD>
                  </TR>
      <?php
                }
            }
        }
        // echo $sql;
      ?>
    </tbody>
  </table>
</div>