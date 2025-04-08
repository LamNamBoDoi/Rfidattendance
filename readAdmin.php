<?php
  include 'connectDB.php';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ yêu cầu JSON
    $data = json_decode(file_get_contents("php://input"));

    $admin_email = $data->admin_email;
    $admin_pwd = $data->admin_pwd;

    if (empty($admin_email) || empty($admin_pwd)) {
        echo json_encode(["status" => "error", "message" => "Email hoặc mật khẩu không được để trống."]);
        exit();
    }

    // Truy vấn cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($admin_pwd, $row['admin_pwd'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Đăng nhập thành công.",
                "admin" => [
                    "id" => $row['id'],
                    "admin_name" => $row['admin_name'],
                    "admin_email" => $row['admin_email']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Mật khẩu không đúng."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Tài khoản không tồn tại."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ."]);
}

$conn->close();
?>