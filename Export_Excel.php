<?php
//Connect to database
require'connectDB.php';

$output = '';

if(isset($_POST["To_Excel"])){
    
    $searchQuery = " ";
    $Start_date = " ";
    $End_date = " ";
    $Start_time = " ";
    $End_time = " ";
    $card_sel = " ";

    // Lọc theo ngày bắt đầu
    if (!empty($_POST['date_sel_start'])) {
        $Start_date = $_POST['date_sel_start'];
        $_SESSION['searchQuery'] = "checkindate='".$Start_date."'";
    } else {
        $Start_date = date("Y-m-d");
        $_SESSION['searchQuery'] = "checkindate='".date("Y-m-d")."'";
    }
    // Lọc theo ngày kết thúc
    if (!empty($_POST['date_sel_end'])) {
        $End_date = $_POST['date_sel_end'];
        $_SESSION['searchQuery'] = "checkindate BETWEEN '".$Start_date."' AND '".$End_date."'";
    }
    // Kiểm tra thời gian vào
    if ($_POST['time_sel'] == "Time_in") {
        if (!empty($_POST['time_sel_start']) && !empty($_POST['time_sel_end'])) {
            $Start_time = $_POST['time_sel_start'].":00";
            $End_time = $_POST['time_sel_end'].":00";

            // Nếu thời gian bắt đầu lớn hơn hoặc bằng thời gian kết thúc
            if ($Start_time >= $End_time) {
                echo '<p class="error">Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc.</p>';
            } else {
                $_SESSION['searchQuery'] .= " AND timein BETWEEN '".$Start_time."' AND '".$End_time."'";
            }
        } elseif (!empty($_POST['time_sel_start'])) {
            $Start_time = $_POST['time_sel_start'].":00";
            $_SESSION['searchQuery'] .= " AND timein='".$Start_time."'";
        }
      }
    // Kiểm tra thời gian ra
    if ($_POST['time_sel'] == "Time_out") {
        if (!empty($_POST['time_sel_start']) && !empty($_POST['time_sel_end'])) {
            $Start_time = $_POST['time_sel_start'].":00";
            $End_time = $_POST['time_sel_end'].":00";

            // Nếu thời gian bắt đầu lớn hơn hoặc bằng thời gian kết thúc
            if ($Start_time >= $End_time) {
                echo '<p class="error">Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc.</p>';
            } else {
                $_SESSION['searchQuery'] .= " AND timeout BETWEEN '".$Start_time."' AND '".$End_time."'";
            }
        } elseif (!empty($_POST['time_sel_start'])) {
            $Start_time = $_POST['time_sel_start'].":00";
            $_SESSION['searchQuery'] .= " AND timeout='".$Start_time."'";
        }
      }
    // Lọc theo thẻ
    if (!empty($_POST['card_sel']) && $_POST['card_sel'] != 0) {
        $Card_sel = $_POST['card_sel'];
        $_SESSION['searchQuery'] .= " AND card_uid='".$Card_sel."'";
    }

    // Lọc theo phòng ban
    if (!empty($_POST['dev_uid']) && $_POST['dev_uid'] != 0) {
        $dev_uid = $_POST['dev_uid'];
        $_SESSION['searchQuery'] .= " AND device_uid='".$dev_uid."'";
    }

    $sql = "SELECT * FROM users_logs WHERE ".$_SESSION['searchQuery']." ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    if($result->num_rows > 0){
      $output .= '
                  <table class="table" bordered="1">  
                    <TR>
                      <TH>ID</TH>
                      <TH>Name</TH>
                      <TH>Serial Number</TH>
                      <TH>Card UID</TH>
                      <TH>Device ID</TH>
                      <TH>Device Dep</TH>
                      <TH>Date log</TH>
                      <TH>Time In</TH>
                      <TH>Time Out</TH>
                    </TR>';
        while($row=$result->fetch_assoc()) {
            $output .= '
                        <TR> 
                            <TD> '.$row['id'].'</TD>
                            <TD> '.$row['username'].'</TD>
                            <TD> '.$row['serialnumber'].'</TD>
                            <TD> '.$row['card_uid'].'</TD>
                            <TD> '.$row['device_uid'].'</TD>
                            <TD> '.$row['device_dep'].'</TD>
                            <TD> '.$row['checkindate'].'</TD>
                            <TD> '.$row['timein'].'</TD>
                            <TD> '.$row['timeout'].'</TD>
                        </TR>';
        }
        $output .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename=User_Log'.$Start_date.'.xls');
        
        echo $output;
        exit();
    }
    else{
      header( "location: UsersLog.php" );
      exit();
    }
}
?>