<?php  
//Connect to database
require'connectDB.php';

//Add user
if (isset($_POST['Add'])) {
    $student_num = $_POST['student_num'];
    $card_uid = $_POST['card_uid'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $Number = $_POST['number'];
    $bus_id = $_POST['bus_id'];
    $Gender = $_POST['gender'];
    
    //check if there any selected user
    
    if(!empty($name) && !empty($Number) && !empty($surname) && !empty($card_uid) && !empty($bus_id) && !empty($student_num)){
        //check if the user already exists
        $sql = "SELECT student_num FROM students WHERE student_num=?";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error";
            exit();
        }
        else{
            mysqli_stmt_bind_param($result, "i", $student_num);
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if (!$row = mysqli_fetch_assoc($resultl)){
                //check if the card UID is already taken
                $sql = "SELECT card_uid FROM students WHERE card_uid=?";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error";
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($result, "s", $card_uid);
                    mysqli_stmt_execute($result);
                    $resultl = mysqli_stmt_get_result($result);
                    if (!$row = mysqli_fetch_assoc($resultl)){
                        $sql = "INSERT INTO students VALUES(?, ?, ?, ?, ?, ?, ?)";
                        $result = mysqli_stmt_init($conn);
                        if ( !mysqli_stmt_prepare($result, $sql)){
                            echo '<p class="alert alert-danger">SQL Error</p>';
                        }
                        else{
                            mysqli_stmt_bind_param($result, "isssssi", $student_num, $card_uid, $name, $surname, $Gender, $Number, $bus_id);
                            mysqli_stmt_execute($result);
                            $sql = "INSERT INTO student_bus(card_uid, bus_id) VALUES(?, ?)";
                            $result = mysqli_stmt_init($conn);
                            if ( !mysqli_stmt_prepare($result, $sql)){
                                echo '<p class="alert alert-danger">SQL Error</p>';
                            }
                            else{
                                mysqli_stmt_bind_param($result, "si", $card_uid, $bus_id);
                                mysqli_stmt_execute($result);
                                echo 1;
                                exit();
                            }

                        }
                    }
                    else{
                        echo "This card UID is already taken";
                        exit();
                    } 
                }
            }
            else{
                echo "This student already exist";
                exit();
            }
        }
    }
    else{
        echo "Empty Fields";
        exit();
    }
}
// Update an existant student
if (isset($_POST['Update'])) {
    $student_num = $_POST['student_num'];
    $card_uid = $_POST['card_uid'];
    $name = $_POST['name'];
    $Number = $_POST['number'];
    $surname = $_POST['surname'];
    $bus_id = $_POST['bus_id'];
    $Gender = $_POST['gender'];

    if(!empty($name) && !empty($Number) && !empty($surname) && !empty($card_uid) && !empty($bus_id) && !empty($student_num)){
        //check if the student exists
        $sql = "SELECT student_num FROM students WHERE student_num=?";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error";
            exit();
        }
        else{
            mysqli_stmt_bind_param($result, "i", $student_num);
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if ($row = mysqli_fetch_assoc($resultl)){
                //check if the card uid is taken
                $sql = "SELECT card_uid FROM students WHERE card_uid=? AND student_num NOT like ?";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error";
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($result, "si", $card_uid, $student_num);
                    mysqli_stmt_execute($result);
                    $resultl = mysqli_stmt_get_result($result);
                    if (!$row = mysqli_fetch_assoc($resultl)){
                        $sql = "UPDATE students SET card_uid=?, name=?, surname=?, gender=?, relative_phone=?, bus_id=? WHERE student_num=? ";
                        $result = mysqli_stmt_init($conn);
                        if ( !mysqli_stmt_prepare($result, $sql)){
                            echo '<p class="alert alert-danger">SQL Error</p>';
                        }
                        else{
                            mysqli_stmt_bind_param($result, "sssssii", $card_uid, $name, $surname, $Gender, $Number, $bus_id,$student_num);
                            mysqli_stmt_execute($result);
                            echo 1;
                            exit();
                        }
                    }
                    else{
                        echo "This card UID is already taken";
                        exit();
                    } 
                }
            }
            else{
                echo "This student doesn't exist";
                exit();
            }
        }
    }
    else{
        echo "Empty Fields";
        exit();
    }
}
// select fingerprint 
if (isset($_GET['select'])) {

    $card_uid = $_GET['card_uid'];

    $sql = "SELECT * FROM users WHERE card_uid=?";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    }
    else{
        mysqli_stmt_bind_param($result, "s", $card_uid);
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        // echo "User Fingerprint selected";
        // exit();
        header('Content-Type: application/json');
        $data = array();
        if ($row = mysqli_fetch_assoc($resultl)) {
            foreach ($resultl as $row) {
                $data[] = $row;
            }
        }
        $resultl->close();
        $conn->close();
        print json_encode($data);
    } 
}
// delete user 
if (isset($_POST['delete'])) {

    $student_num = $_POST['student_num'];

    if (empty($student_num)) {
        echo "There no selected student to remove";
        exit();
    } else {
        $sql = "DELETE FROM students WHERE student_num=?";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_delete";
            exit();
        }
        else{
            mysqli_stmt_bind_param($result, "i", $student_num);
            mysqli_stmt_execute($result);
            echo 1;
            exit();
        }
    }
}

if (isset($_POST['studentNum'])) {
    

    $studentNum = $_POST['studentNum'];

    $sql = "SELECT * FROM students WHERE student_num = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['error' => 'SQL Error']);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $studentNum);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        echo json_encode($data);
    }
}
?>