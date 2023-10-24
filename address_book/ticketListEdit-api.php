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
$tc2_id = isset($_POST['tc2_id']) ? intval($_POST['tc2_id']) : 0;

if (empty($tc2_id)) {
  $output['errors']['tc2_id'] = "沒有PK";
  echo json_encode($output);
  exit; //結束程式
}

$tc2_name = $_POST['tc2_name'] ?? '';
$tc_amount = $_POST['tc_amount'] ?? '';




// TODO: 資料在寫入之前, 要檢查格式

// trim(): 去除頭尾的空白
// strlen(): 查看字串的長度
// mb_strlen(): 查看中文字串的長度

$isPass = true;
if (empty($tc2_name)) {
  $isPass = false;
  $output['errors']['tc2_name'] = '請填寫正確的名稱';
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

$sql = "UPDATE `ticketcategory2` SET 
  `tc2_name`=?,
  `tc_amount`=?
WHERE `tc2_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $tc2_name,
  $tc_amount,
  $tc2_id
]);

$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
