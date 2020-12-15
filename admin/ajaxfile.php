<?php

include 'main.php';

if(isset($_POST['device_id'])){
   $device_id = $_POST['device_id'];

   // Check username
   $stmt = $pdo->prepare("SELECT count(*) as cntUser FROM devices WHERE device_id=:device_id");
   $stmt->bindValue(':device_id', $device_id, PDO::PARAM_STR);
   $stmt->execute(); 
   $count = $stmt->fetchColumn();

   $response = "<span style='color: green;'>Device ID Available.</span>";
   if($count > 0){
      $response = "<span style='color: red;'>Device ID Not Available.</span>";
   }

   echo $response;
   exit;
}