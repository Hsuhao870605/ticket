<?php 
require './parts/connect_db.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
if(! empty($sid)){
  $sql = "DELETE FROM productlist WHERE sid={$sid}";
  $pdo->query($sql);
}

$come_from = 'ticketList.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");