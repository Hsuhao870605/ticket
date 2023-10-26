<?php
if (!isset($pageName)) {
  $pageName = '';
}
?>
<style>
  nav.navbar ul.navbar-nav .nav-link.active {
    background-color: blue;
    color: white;
    border-radius: 6px;
    font-weight: 600;
  }
</style>
<div class="container">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?= $pageName == 'ticketList' ? 'active' : '' ?>" href="ticketList.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              商品管理
            </a>
            <ul class="dropdown-menu">
              <li class="nav-item"><a class="dropdown-item <?= $pageName == 'ticketList' ? 'active' : '' ?>" href="ticketList.php">商品管理列表</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li class="nav-item"><a class="nav-link dropdown-item <?= $pageName == 'ticketAdd' ? 'active' : '' ?>" href="ticketAdd.php">商品種類新增</a></li>
              <li class="nav-item"><a class="nav-link dropdown-item <?= $pageName == 'ticketList_cate1' ? 'active' : '' ?>" href="ticketList_cate1.php">票券種類列表</a></li>
              <li class="nav-item"><a class="nav-link dropdown-item <?= $pageName == 'ticketList_cate2' ? 'active' : '' ?>" href="ticketList_cate2.php">票券名稱列表</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?= $pageName == 'orderList' ? 'active' : '' ?>" href="orderList.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              訂單管理
            </a>
            <ul class="dropdown-menu">
              <li class="nav-item"><a class="dropdown-item <?= $pageName == 'orderList' ? 'active' : '' ?>" href="orderList.php">訂單管理列表</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li class="nav-item"><a class="nav-link dropdown-item <?= $pageName == 'orderAdd' ? 'active' : '' ?>" href="orderAdd.php">訂單新增</a></li>
            </ul>
          </li>
        </ul>
        <ul class="navbar-nav mb-2 mb-lg-0">
          <?php if (isset($_SESSION['admin'])) : ?>
            <li class="nav-item">
              <a class="nav-link"><?= $_SESSION['admin']['nickname'] ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $pageName == 'login' ? 'active' : '' ?>" href="logout.php">登出</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link <?= $pageName == 'login' ? 'active' : '' ?>" href="login.php">登入</a>
            </li>
          <?php endif ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>