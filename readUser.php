<?php
  include 'connectDB.php';
  $query = $conn->query("SELECT * FROM users");
  $result = array();

  while($rowData = $query->fetch_assoc()){
    $result[]=$rowData;
  }

  echo json_encode($result);
?>