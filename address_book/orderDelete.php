<?php 
require './parts/connect_db.php';

$sid = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if(! empty($sid)){
  $sql = "DELETE FROM orderList WHERE sid={$sid}";
  $pdo->query($sql);
}

$come_from = 'orderList.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");