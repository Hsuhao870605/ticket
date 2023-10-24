<?php
if(!isset($partName)){
$partName='';
}
?>
<style>
  nav.navbar ul.navbar-nav .nav-link.active {
    background-color: skyblue;
    font-weight: 900;
    color: blue;
    border-radius: 30px 5px 30px 5px;
  }
</style>
<div class="container">
    <nav class="navbar navbar-expand-lg text-bg-info p-3">
      <div class="container-fluid">
        <a class="navbar-brand fs-3" href="#">遊樂園設施維護系統</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link fs-5 ms-3 <?= $partName=='user'? 'badge text-bg-primary mt-1':'' ?>" href="">會員中心</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fs-5 ms-3 <?= $partName=='ticket'? 'badge text-bg-primary mt-1':'' ?>" href="./list.php">購票系統</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fs-5 ms-3 <?= $partName=='ride'? 'badge text-bg-primary mt-1':'' ?>" href="./ride_list.php">遊樂設施</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fs-5 ms-3 <?= $partName=='product'? 'badge text-bg-primary mt-1':'' ?>" href="">商品</a>
            </li>
            
          </ul>
          
        </div>
      </div>
    </nav>

  </div>