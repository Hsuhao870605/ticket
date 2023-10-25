<?php
require './parts/connect_db.php';

/* *****************
# 會有 SQL injection
# 值如果包含單引號就會出錯
$sql = sprintf("INSERT INTO `address_book`(
  `name`, `email`, `mobile`, `birthday`, `address`, `created_at`
  ) VALUES (
    '%s', '%s', '%s', '%s', '%s', NOW()
  )", 
    $_POST['name'], 
    $_POST['email'],
    $_POST['mobile'],
    $_POST['birthday'],
    $_POST['address']
);
$stmt = $pdo->query($sql);
*/

$output = [
  'postData' => $_POST,
  'success' => false,
  // 'error' => '',
  'errors' => [],
];

# 告訴用戶端, 資料格式為 JSON
header('Content-Type: application/json');
/*
if(empty($_POST['name']) or empty($_POST['email'])){
  $output['errors']['form'] = '缺少欄位資料';
  echo json_encode($output);
  exit;
}
*/
$tc1_name = $_POST['tc1_name'] ?? '';



// TODO: 資料在寫入之前, 要檢查格式

// trim(): 去除頭尾的空白
// strlen(): 查看字串的長度
// mb_strlen(): 查看中文字串的長度

$isPass = true;
/*if (empty($tc1_name)) {
  $isPass = false;
  $output['errors']['tc1_name'] = '請填寫正確的種類';
} */

/*if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $isPass = false;
  $output['errors']['email'] = 'email 格式錯誤';
}*/

# 如果沒有通過檢查
if (!$isPass) {
  echo json_encode($output);
  exit;
} 


$sql = "INSERT INTO `ticketcategory1`(
  `tc1_name`
  ) VALUES (
    ?
  )";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $tc1_name,
]);

$output['lastInserId'] = $pdo->lastInsertId(); #取得最新資料的PK
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
