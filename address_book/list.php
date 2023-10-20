<?php
require './parts/connect_db.php';
$pageName = 'list';
$title = '列表';

$perPage = 10; //一頁最多有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); #頁面轉向
  exit; #直接結束這支php
}

$t_sql = "SELECT COUNT(*) FROM user_table";
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
    "SELECT * FROM user_table ORDER BY sid DESC LIMIT %s, %s",
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
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">#</th>
            <th scope="col">姓名</th>
            <th scope="col">account</th>
            <th scope="col">Birthday</th>
            <th scope="col">Email</th>
            <th scope="col">Password</th>
            <th scope="col">telephone</th>
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
              <td><?= $r['u_name'] ?></td>
              <td><?= $r['u_acco'] ?></td>
              <td><?= $r['u_birth'] ?></td>
              <td><?= $r['u_email'] ?></td>
              <td><?= htmlentities($r['u_pw']) ?>
              <!--<?= strip_tags($r['u_pw']) ?></td> -->
              <td><?= $r['u_tel'] ?></td>
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