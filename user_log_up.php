<?php  
session_start();
?>
<div class="table-responsive" style="max-height: 500px;"> 
  <table class="table">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Serial Number</th>
        <th>Card UID</th>
        <th>Device Dep</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
      </tr>
    </thead>
    <tbody class="table-secondary">
      <?php
        // Kết nối đến cơ sở dữ liệu
        require 'connectDB.php';
        $searchQuery = "";

        if (isset($_POST['log_date'])) {
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
        }

        if ($_POST['select_date'] == 1) {
            $Start_date = date("Y-m-d");
            $_SESSION['searchQuery'] = "checkindate='".$Start_date."'";
        }

        $sql = "SELECT * FROM users_logs WHERE ".$_SESSION['searchQuery']." ORDER BY id DESC";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo '<p class="error">SQL Error</p>';
        } else {
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if (mysqli_num_rows($resultl) > 0) {
                while ($row = mysqli_fetch_assoc($resultl)) {
      ?>
                  <tr>
                      <td><?php echo $row['id'];?></td>
                      <td><?php echo $row['username'];?></td>
                      <td><?php echo $row['serialnumber'];?></td>
                      <td><?php echo $row['card_uid'];?></td>
                      <td><?php echo $row['device_dep'];?></td>
                      <td><?php echo $row['checkindate'];?></td>
                      <td><?php echo $row['timein'];?></td>
                      <td><?php echo $row['timeout'];?></td>
                  </tr>
      <?php
                }
            } else {
                echo '<tr><td colspan="8" class="text-center">Không có dữ liệu nào.</td></tr>';
            }
        }
        ?>
    </tbody>
  </table>
</div>

