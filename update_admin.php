<?php
require('connectDB.php');

// Set header cho JSON response
header('Content-Type: application/json');

// Lấy dữ liệu JSON từ client
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra nếu dữ liệu không được gửi đúng
if ($data === null) {
    echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ."]);
    exit();
}

// Lấy dữ liệu từ POST (json đã decode)
$up_name = isset($data['up_name']) ? $data['up_name'] : '';
$up_email = isset($data['up_email']) ? $data['up_email'] : '';
$up_password = isset($data['up_pwd']) ? $data['up_pwd'] : '';
$up_new_password = isset($data['up_pwd_new']) ? $data['up_pwd_new'] : '';
$user_id = isset($data['id']) ? $data['id'] : '';  // Lấy id từ dữ liệu JSON

// Kiểm tra các trường thông tin
if (empty($up_name) || empty($up_email) || empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "Các trường thông tin không thể để trống!"]);
    exit();
} elseif (!filter_var($up_email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $up_name)) {
    echo json_encode(["status" => "error", "message" => "Email hoặc tên không hợp lệ."]);
    exit();
} elseif (!filter_var($up_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Email không hợp lệ."]);
    exit();
} elseif (!preg_match("/^[a-zA-Z0-9]*$/", $up_name)) {
    echo json_encode(["status" => "error", "message" => "Tên không hợp lệ."]);
    exit();
} else {
    // Thực hiện truy vấn kiểm tra admin trong database
    $sql = "SELECT * FROM admin WHERE id = ?";  
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)){
        echo json_encode(["status" => "error", "message" => "Lỗi cơ sở dữ liệu."]);
        exit();
    } else {
        mysqli_stmt_bind_param($result, "i", $user_id); // Thay vì id cố định, sử dụng id từ client
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);

        if ($row = mysqli_fetch_assoc($resultl)) {
            // Kiểm tra mật khẩu cũ
            $pwdCheck = password_verify($up_password, $row['admin_pwd']);
            if ($pwdCheck == false) {
                echo json_encode(["status" => "error", "message" => "Mật khẩu cũ không đúng."]);
                exit();
            } elseif ($pwdCheck == true) {
                // Nếu người dùng muốn thay đổi mật khẩu
                if (!empty($up_new_password)) {
                    $hashed_new_pwd = password_hash($up_new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE admin SET admin_pwd=? WHERE id=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo json_encode(["status" => "error", "message" => "Lỗi cơ sở dữ liệu."]);
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "si", $hashed_new_pwd, $user_id); // Sử dụng id động
                        mysqli_stmt_execute($stmt);
                    }
                }

                // Cập nhật tên và email
                $sql = "UPDATE admin SET admin_name=?, admin_email=? WHERE id=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(["status" => "error", "message" => "Lỗi cơ sở dữ liệu."]);
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "ssi", $up_name, $up_email, $user_id); // Sử dụng id động
                    mysqli_stmt_execute($stmt);
                    echo json_encode(["status" => "success", "message" => "Cập nhật thông tin thành công."]);
                    exit();
                }
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Không tìm thấy người dùng."]);
            exit();
        }
    }
}

include('ac_update.php');
?>
