<?php 
require './parts/connect_db.php';

$tc1_id = isset($_GET['tc1_id']) ? intval($_GET['tc1_id']) : 0;
if(! empty($tc1_id)){
  $sql = "DELETE FROM ticketcategory1 WHERE tc1_id={$tc1_id}";
  $pdo->query($sql);
}

$come_from = 'ticketList_cate1.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");