<?php
// Kết nối cơ sở dữ liệu
require('connectDB.php');

// Lấy dữ liệu từ request (POST hoặc PUT)
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem các trường cần thiết có tồn tại hay không
if (isset($data->id) && isset($data->device_name) && isset($data->device_dep) && isset($data->device_uid) && isset($data->device_date) && isset($data->device_mode)) {

    $id = $data->id;
    $device_name = $data->device_name;
    $device_dep = $data->device_dep;
    $device_uid = $data->device_uid;
    $device_date = $data->device_date;
    $device_mode = $data->device_mode;

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $sql = "UPDATE devices SET device_name = ?, device_dep = ?, device_uid = ?, device_date = ?, device_mode = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $device_name, $device_dep, $device_uid, $device_date, $device_mode, $id);
        
        // Thực thi câu lệnh
        if ($stmt->execute()) {
            echo json_encode(["message" => "Device updated successfully"]);
        } else {
            echo json_encode(["message" => "Error updating device: " . $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["message" => "Error preparing query: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid input data"]);
}

// Đóng kết nối
$conn->close();
?>
