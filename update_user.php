<?php
// Kết nối cơ sở dữ liệu
require('connectDB.php');

// Lấy dữ liệu từ request (POST hoặc PUT)
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem các trường cần thiết có tồn tại hay không
if (isset($data->id) && isset($data->username) && isset($data->serialnumber) && isset($data->gender) && isset($data->email) && isset($data->card_uid) && isset($data->card_select) && isset($data->user_date) && isset($data->device_uid) && isset($data->device_dep) && isset($data->add_card)) {

    // Lấy dữ liệu từ request
    $id = $data->id;
    $username = $data->username;
    $serialnumber = $data->serialnumber;
    $gender = $data->gender;
    $email = $data->email;
    $card_uid = $data->card_uid;
    $card_select = $data->card_select;
    $user_date = $data->user_date;
    $device_uid = $data->device_uid;
    $device_dep = $data->device_dep;
    $add_card = $data->add_card;

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $sql = "UPDATE users SET username = ?, serialnumber = ?, gender = ?, email = ?, card_uid = ?, card_select = ?, user_date = ?, device_uid = ?, device_dep = ?, add_card = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Gắn các tham số vào câu lệnh SQL
        $stmt->bind_param("sssssssssss", $username, $serialnumber, $gender, $email, $card_uid, $card_select, $user_date, $device_uid, $device_dep, $add_card, $id);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            echo json_encode(["message" => "Error updating user: " . $stmt->error]);
        }

        // Đóng câu lệnh chuẩn bị
        $stmt->close();
    } else {
        echo json_encode(["message" => "Error preparing query: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid input data"]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
