<?php
require './parts/connect_db.php';
$pageName = 'list';
$title = '商品管理';

$perPage = 10; //一頁最多有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); #頁面轉向
  exit; #直接結束這支php
}

$t_sql = "SELECT COUNT(*) FROM productlist";
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
    "SELECT * FROM productlist ORDER BY sid DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './parts/html_head.php' ?>
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

      <button class="btn btn-primary" type="submit"><a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="add.php">新增票券</a></button>

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">#</th>
            <th scope="col">票券名稱</th>
            <th scope="col">票券類型</th>
            <th scope="col">售價金額</th>
            <th scope="col">開始時間</th>
            <th scope="col">結束時間</th>
            <th scope="col">描述</th>
            <th scope="col">
              <i class="fa-solid fa-file-pen">
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><a href="javascript: deleteItem(<?= $r['sid'] ?>)">
                  <i class="fa-solid fa-trash-can"></i>
                </a></td>
              <td><?= $r['sid'] ?></td>
              <td><?= $r['t_name'] ?></td>
              <td><?= $r['t_category'] ?></td>
              <td><?= $r['amount'] ?></td>
              <td><?= $r['beginTime'] ?></td>
              <td><?= $r['endTime'] ?></td>
              <td><?= htmlentities($r['description']) ?>
                <!--<?= strip_tags($r['description']) ?> -->
              </td>
              <td><a href="edit.php?sid=<?= $r['sid'] ?>">
                  <i class="fa-solid fa-file-pen">
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
  function deleteItem(sid) {
    if (confirm(`確定刪除編號 ${sid} 資料嗎?`)) {
      location.href = 'delete.php?sid=' + sid;
    }
  }
</script>
<?php include './parts/html_foot.php' ?>