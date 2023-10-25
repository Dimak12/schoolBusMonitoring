<?php
//Connect to database
require'connectDB.php';
date_default_timezone_set('Africa/Johannesburg');

$output = '';

if(isset($_POST["To_Excel"])){
  
    $searchQuery = "";
    $Start_date ="";
    $End_date = "";
    $Start_time = "";
    $End_time = "";
    $card_sel = "";

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
        $card_sel = $_POST['card_sel'];
        $_SESSION['searchQuery'] .= " AND card_uid='".$card_sel."'";
    }
    //Department filter
    if ($_POST['dev_sel'] != 0) {
        $dev_uid = $_POST['dev_sel'];
        $_SESSION['searchQuery'] .= " AND bus_id='".$dev_uid."'";
    }

    $sql = "SELECT * FROM attendance_logs WHERE ".$_SESSION['searchQuery']."";
    //$sql = "SELECT * FROM attendance_logs WHERE log_date = CURDATE()";
    /*echo $sql;
    die;*/
    $result = mysqli_query($conn, $sql);
    if($result->num_rows > 0){
      $output .= '
                  <table class="table" bordered="1">  
                    <TR>
                        <th>Student Number</th>
                        <th>Card UID</th>
                        <th>Full Name</th>
                        <th>Bus ID</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </TR>';
        while($row=$result->fetch_assoc()) {
            $output .= '
                        <TR> 
                            <TD> '.$row['student_num'].'</TD>
                            <TD> '.$row['card_uid'].'</TD>
                            <TD> '.$row['full_name'].'</TD>
                            <TD> '.$row['bus_id'].'</TD>
                            <TD> '.$row['log_date'].'</TD>
                            <TD> '.$row['time_in'].'</TD>
                            <TD> '.$row['time_out'].'</TD>
                        </TR>';
        }
        $output .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename=student_Log'.$Start_date.'.xls');
        
        echo $output;
        exit();
    }
    else{
      header( "location: UsersLog.php" );
      exit();
    }
}
?>