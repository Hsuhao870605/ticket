<?php
require './parts/connect_db.php';
$pageName = 'orderList';
$title = '訂單管理';
$partName = 'ticket';


$perPage = 10; //一頁最多有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); #頁面轉向
  exit; #直接結束這支php
}

$t_sql = "SELECT COUNT(*) FROM orderlist";
#y總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$totalPages = 0;
$rows = [];

//有資料時
if ($totalRows > 0) {
  #總頁數
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages); #頁面轉向最後一頁
    exit; #直接結束這支php
  }
  $sql = sprintf(
    "SELECT * FROM orderlist JOIN orderstate on orderlist.orderState_id = orderState.orderState_id JOIN ticketcategory2 t2 ON orderlist.tc2_id = t2.tc2_id
    ORDER BY order_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );

  $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './parts/html_head.php' ?>
<?php include './parts/main_part.php' ?>
<?php include './parts/navbar.php' ?>

<div class="container">
  <div class="row">
    <div class="col">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=1">
              <i class="fa-solid fa-angles-left"></i></a>
          </li>
          <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
            if ($i >= 1 and $i <= $totalPages) : ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?><a>
              </li>
          <?php
            endif;
          endfor; ?>
          <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>">
              <i class="fa-solid fa-angles-right"></i></a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <div><?= "$totalRows / $totalPages" ?></div>
  <div class="row">
    <div class="col">
      <button class="btn btn-primary" type="submit"><a class="nav-link <?= $pageName == 'orderAdd' ? 'active' : '' ?>" href="orderAdd.php">新增訂單票券</a></button>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">訂單號碼</th>
            <th scope="col">使用者名稱</th>
            <th scope="col">票券名稱</th>
            <th scope="col">金額</th>
            <th scope="col">票券日期</th>
            <th scope="col">付款狀態</th>
            <th scope="col">
              編輯訂單<i class="fa-solid fa-file-pen">
            </th>
            <th scope="col">
              刪除訂單<i class="fa-solid fa-trash-can"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><?= $r['order_id'] ?></td>
              <td><?= $r['user_name'] ?></td>
              <td><?= $r['tc2_name'] ?></td>
              <td><?= $r['tc_amount'] ?></td>
              <td><?= $r['orderTime'] ?></td>
              <td><?= $r['stateName'] ?></td>
              <td><a href="orderEdit.php?order_id=<?= $r['order_id'] ?>">
                  <i class="fa-solid fa-file-pen">
                </a></td>
              <td><a href="javascript: deleteItem(<?= $r['order_id'] ?>)">
                  <i class="fa-solid fa-trash-can"></i>
                </a></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>




<?php include './parts/scripts.php' ?>
<script>
  function deleteItem(order_id) {
    if (confirm(`確定刪除編號 ${order_id} 資料嗎?`)) {
      location.href = 'orderDelete.php?order_id=' + order_id;
    }
  }
</script>
<?php include './parts/html_foot.php' ?>