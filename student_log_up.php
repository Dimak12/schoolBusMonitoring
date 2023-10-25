<?php
require 'connectDB.php';
date_default_timezone_set('Africa/Johannesburg');
$d = date("Y-m-d");
$t = date("H:i:sa");

if (isset($_GET['card_uid']) && isset($_GET['bus_id'])){

    $card_uid = $_GET['card_uid'];
    $bus_id = $_GET['bus_id'];
    $data = array();
    
    $sql = "SELECT  sb.*, s.student_num, s.name, s.surname,  s.relative_phone FROM student_bus sb JOIN students s ON sb.card_uid = s.card_uid WHERE sb.card_uid=? AND sb.bus_id=?";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error";
        exit();
    }
    else{
        mysqli_stmt_bind_param($result, "si", $card_uid,$bus_id);
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)){
            $student_num = $row['student_num'];
            $name = $row['name'];
            $surname = $row['surname'];
            $full_name = "$name $surname";
            $phone = $row['relative_phone'];
            $response = "Student approved";
            $json = "";
            $sms = "";
            

            $sql = "SELECT * from attendance_logs WHERE card_uid = ? AND log_date = ?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error";
                exit();
            }
            else{
                mysqli_stmt_bind_param($result, "ss", $card_uid,$d);
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                if (!$row = mysqli_fetch_assoc($resultl)){
                    $sql = "INSERT INTO attendance_logs(student_num, card_uid, full_name, bus_id, log_date, time_in) VALUES(?,?,?,?,?,?)";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($result, "ississ", $student_num, $card_uid, $full_name, $bus_id, $d, $t);
                        mysqli_stmt_execute($result);
                        $sms = "$name $surname has boarded the bus";
                        $data [] = array('phone' => $phone, 'response' => $response, 'sms' => $sms);
                        $json = json_encode($data);
                        echo $json;
                        exit();
                    }
                }
                else{
                    $sql = "UPDATE attendance_logs SET time_out = ? WHERE card_uid = ? AND log_date = ?";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($result, "sss", $t, $card_uid, $d);
                        mysqli_stmt_execute($result);
                        $sms = "$name $surname has left the bus";
                        $data [] = array('phone' => $phone, 'response' => $response, 'sms' => $sms);
                        $json = json_encode($data);
                        echo $json;
                        exit();
                    }
                }
            }
        }
        else{
            $data ['response'] = "Student denied";
            //$json = 
            echo json_encode($data);
        }
    }
}
else if(isset($_GET['lat']) && isset($_GET['lng']) && isset($_GET['bus_id'])){
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $bus_id = $_GET['bus_id'];
    $sql = "UPDATE bus_location SET latitude = ? , longitude = ? WHERE bus_id = ?";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error";
        exit();
    }
    else{
        mysqli_stmt_bind_param($result, "ssi", $lat, $lng, $bus_id);
        mysqli_stmt_execute($result);
        echo "Location updated";
        exit();
    }

}
?>