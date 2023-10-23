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
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

if (empty($order_id)) {
  $output['errors']['order_id'] = "沒有PK";
  echo json_encode($output);
  exit; //結束程式
}

$user_name = $_POST['user_name'] ?? '';
$t_name = $_POST['t_name'] ?? '';
$amount = $_POST['amount'] ?? '';
$orderTime = $_POST['orderTime'] ?? '';
$orderState_id = $_POST['orderState_id'] ?? '';



// TODO: 資料在寫入之前, 要檢查格式

// trim(): 去除頭尾的空白
// strlen(): 查看字串的長度
// mb_strlen(): 查看中文字串的長度

$isPass = true;
if (empty($user_name)) {
  $isPass = false;
  $output['errors']['user_name'] = '請填寫正確的姓名';
}
if (empty($t_name)) {
  $isPass = false;
  $output['errors']['t_name'] = '請填寫正確的票券名稱';
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

$sql = "UPDATE `orderlist` SET 
  `user_name`=?,
  `t_name`=?,
  `amount`=?,
  `orderTime`=?,
  `orderState_id`=?
WHERE `order_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $user_name,
  $t_name,
  $amount,
  $orderTime,
  $orderState_id,
  $order_id
]);

$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
