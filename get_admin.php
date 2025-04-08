<?php
  include 'connectDB.php';
  $query = $conn->query("SELECT * FROM admin WHERE id=1");
  $result = array();

  while($rowData = $query->fetch_assoc()){
    $result[]=$rowData;
  }

  echo json_encode($result);
?>