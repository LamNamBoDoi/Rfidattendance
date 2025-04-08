<?php
  include 'connectDB.php';
  // Lấy id người dùng từ tham số GET (đảm bảo việc kiểm tra và làm sạch đầu vào)
  $user_id = $_GET['id']; // Giả sử bạn truyền id qua tham số GET

  // Chuẩn bị câu lệnh SQL DELETE
  $sql = "DELETE FROM users WHERE id = ?";

  // Chuẩn bị câu lệnh
  $stmt = $conn->prepare($sql);

  // Gán giá trị id vào tham số (sử dụng "s" vì id là chuỗi)
  $stmt->bind_param("s", $user_id);

  // Thực thi câu lệnh
  if ($stmt->execute()) {
      // Nếu xóa thành công, trả về JSON thành công
      echo json_encode(['status' => 'success']);
  } else {
      // Nếu có lỗi khi xóa, trả về JSON lỗi
      echo json_encode(['status' => 'error', 'message' => $conn->error]);
  }

  // Đóng câu lệnh và kết nối
  $stmt->close();
?>
