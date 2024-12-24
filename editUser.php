<?php
  include 'connectDB.php';

  // Lấy dữ liệu từ POST
  $user_id = $_POST['user_id'];
  $Uname = $_POST['name'];
  $Number = $_POST['number'];
  $Email = $_POST['email'];
  $dev_uid = $_POST['dev_uid'];
  $Gender = $_POST['gender'];
  $dev_dep = $_POST['dev_dep']; // Giả sử bạn muốn dùng 'device_dep' từ form

  // Câu lệnh SQL để cập nhật
  $sql = "UPDATE users SET username=?, serialnumber=?, gender=?, email=?, user_date=CURDATE(), device_uid=?, device_dep=?, add_card=1 WHERE id=?";
  
  // Khởi tạo câu lệnh SQL
  $result = mysqli_stmt_init($conn);

  // Kiểm tra xem câu lệnh có được chuẩn bị đúng không
  if (!mysqli_stmt_prepare($result, $sql)) {
      echo "SQL_Error_select_Fingerprint";
      exit();
  } else {
      // Liên kết các tham số với kiểu dữ liệu phù hợp
      mysqli_stmt_bind_param($result, "sdsssssi", $Uname, $Number, $Gender, $Email, $dev_uid, $dev_dep, $user_id);
      
      // Thực thi câu lệnh
      if (mysqli_stmt_execute($result)) {
          echo 1; // Nếu thực thi thành công
      } else {
          echo "Error executing query: " . mysqli_error($conn); // Nếu có lỗi trong khi thực thi câu lệnh
      }
      exit();
  }

  // Đóng kết nối
  mysqli_stmt_close($result);
  mysqli_close($conn);
?>
