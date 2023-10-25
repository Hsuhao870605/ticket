<?php 
require './parts/connect_db.php';

$tc2_id = isset($_GET['tc2_id']) ? intval($_GET['tc2_id']) : 0;
if(! empty($tc2_id)){
  $sql = "DELETE FROM ticketcategory2 WHERE tc2_id={$tc2_id}";
  $pdo->query($sql);
}

$come_from = 'ticketList_cate2.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");