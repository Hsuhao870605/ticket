<?php

require './parts/connect_db.php';
$pageName = 'expressCate';
$title = '快速通關';

$sql = "SELECT * FROM expresspasstoamuse";

$rows = $pdo->query($sql)->fetchAll();

#echo json_encode($rows, JSON_UNESCAPED_UNICODE);
?>
<?php include './parts/html_head.php' ?>
<?php include './parts/navbar.php' ?>

<div class="container">
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">快速通關票券</h5>
          <form name="form1" onsubmit="return false">
            <div class="input-group mb-3">
              <span class="input-group-text">ExpressPass</span>
              <select class="form-select" name="cate1" id="cate1" onchange="generateCate2List()">
                <?php foreach ($rows as $r) :
                  if ($r['parent_id'] == '0') : ?>
                    <option value="<?= $r['sid'] ?>"><?= $r['name'] ?></option>
                <?php
                  endif;
                endforeach ?>
              </select>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">設施名稱</span>
              <select class="form-select" name="cate2" id="cate2"></select>
            </div>
            <button type="submit" class="btn btn-primary">送出</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include './parts/scripts.php' ?>
<script>
  const initVals = {
    cate1: 2,
    cate2: 12
  };
  const cates = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;

  const cate1 = document.querySelector('#cate1')
  const cate2 = document.querySelector('#cate2')


  function generateCate2List() {
    const cate1Val = cate1.value; // 主分類的值

    let str = "";
    for (let item of cates) {
      if (+item.parent_id === +cate1Val) {
        str += `<option value="${item.sid}">${item.name}</option>`;
      }
    }

    cate2.innerHTML = str;
  }
  cate1.value = initVals.cate1; //設定第一層的初始值
  generateCate2List(); //生第二層
  cate2.value = initVals.cate2; //設定第二層通道
</script>
<?php include './parts/html_foot.php' ?>