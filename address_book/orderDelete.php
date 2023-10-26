<?php 
require './parts/connect_db.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if(! empty($order_id)){
  $sql = "DELETE FROM orderList WHERE order_id={$order_id}";
  $pdo->query($sql);
}

$come_from = 'orderList.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");