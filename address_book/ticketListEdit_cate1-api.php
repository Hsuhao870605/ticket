<?php
require './parts/connect_db.php';


# 告訴用戶端, 資料格式為 JSON
header('Content-Type: application/json');
# echo json_encode($_POST);
# exit; //結束程式


$output = [
  'postData' => $_POST,
  'success' => false,
  // 'error' => '',
  'errors' => [],
];

// 取得資料的primary key
$tc1_id = isset($_POST['tc1_id']) ? intval($_POST['tc1_id']) : 0;

if (empty($tc1_id)) {
  $output['errors']['tc1_id'] = "沒有PK";
  echo json_encode($output);
  exit; //結束程式
}

$tc1_name = $_POST['tc1_name'] ?? '';




// TODO: 資料在寫入之前, 要檢查格式

// trim(): 去除頭尾的空白
// strlen(): 查看字串的長度
// mb_strlen(): 查看中文字串的長度

$isPass = true;
if (empty($tc1_name)) {
  $isPass = false;
  $output['errors']['tc1_name'] = '請填寫正確的種類';
}

/*if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $isPass = false;
  $output['errors']['email'] = 'email 格式錯誤';
} */

# 如果沒有通過檢查
if (!$isPass) {
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `ticketcategory1` SET 
  `tc1_name`=?
WHERE `tc1_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $tc1_name,
  $tc1_id
]);

$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
